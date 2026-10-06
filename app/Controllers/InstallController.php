<?php

namespace App\Controllers;

use App\Services\InstallerSchema;
use CodeIgniter\Controller;
use CodeIgniter\Database\Config as DatabaseConfig;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class InstallController extends Controller
{
    public function index()
    {
        if (is_file(WRITEPATH . 'installed.lock')) {
            return redirect()->to('/');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->install();
        }

        return view('install/wizard', [
            'siteUrl' => ($this->request->getServer('HTTPS') === 'on' ? 'https://' : 'http://') . (string) $this->request->getServer('HTTP_HOST'),
            'errors' => [],
            'old' => [],
        ]);
    }

    public function testConnection(): ResponseInterface
    {
        if (is_file(WRITEPATH . 'installed.lock')) {
            return $this->response->setStatusCode(410)->setJSON(['ok' => false, 'message' => 'The site has already been installed.']);
        }
        session()->remove('installer_database_fingerprint');
        $input = $this->request->getPost();
        $dbConfig = $this->databaseSettings($input);
        $errors = $this->validateDatabase($dbConfig);
        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => implode(' ', $errors)]);
        }

        try {
            $connection = DatabaseConfig::connect($dbConfig, false);
            $connection->initialize();
            if (!$connection->connID) {
                throw new \RuntimeException('Could not establish the database connection.');
            }
            $connection->close();
            session()->set('installer_database_fingerprint', $this->databaseFingerprint($input));
            return $this->response->setJSON(['ok' => true, 'message' => 'Database connection successful.']);
        } catch (Throwable $e) {
            log_message('error', '[Installer] Database connection test failed: {error}', ['error' => $e->getMessage()]);
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Could not connect to that database. Check the host, database name, credentials, and port.']);
        }
    }

    private function install()
    {
        $input = $this->request->getPost();
        $errors = $this->validateInput($input);
        if ($errors !== []) {
            return view('install/wizard', ['errors' => $errors, 'old' => $input, 'siteUrl' => $input['site_url'] ?? '']);
        }

        $testedFingerprint = (string) session()->get('installer_database_fingerprint');
        if ($testedFingerprint === '' || !hash_equals($testedFingerprint, $this->databaseFingerprint($input))) {
            return view('install/wizard', [
                'errors' => ['Test the database connection before installing.'],
                'old' => $input,
                'siteUrl' => $input['site_url'],
            ]);
        }

        $dbConfig = $this->databaseSettings($input);
        try {
            $db = DatabaseConfig::connect($dbConfig, false);
            $db->initialize();
            if (!$db->connID) {
                throw new \RuntimeException('Could not establish the database connection.');
            }

            (new InstallerSchema())->createRequiredTables($db);

            $admin = [
                'username' => trim((string) $input['admin_username']),
                'email' => strtolower(trim((string) $input['admin_email'])),
                'password' => (string) $input['admin_password'],
            ];

            $settings = [
                'site_name' => trim((string) $input['site_name']),
                'site_url' => rtrim(trim((string) $input['site_url']), '/') . '/',
            ];
            $this->writeEnvironment($dbConfig, $settings, $admin);
            $this->writeLock();
            session()->remove('installer_database_fingerprint');
            $db->close();

            return view('install/complete', [
                'siteUrl' => $settings['site_url'],
                'adminUsername' => $admin['username'],
            ]);
        } catch (Throwable $e) {
            log_message('error', '[Installer] Installation failed: {error}', ['error' => $e->getMessage()]);
            return view('install/wizard', [
                'errors' => [$e->getMessage()],
                'old' => $input,
                'siteUrl' => $input['site_url'],
            ]);
        }
    }

    private function databaseSettings(array $input): array
    {
        return [
            'DSN' => '',
            'hostname' => trim((string) ($input['db_host'] ?? '')),
            'username' => trim((string) ($input['db_username'] ?? '')),
            'password' => (string) ($input['db_password'] ?? ''),
            'database' => trim((string) ($input['db_name'] ?? '')),
            'DBDriver' => 'MySQLi',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => false,
            'charset' => 'utf8mb4',
            'DBCollat' => 'utf8mb4_unicode_ci',
            'port' => (int) ($input['db_port'] ?? 3306),
        ];
    }

    private function validateDatabase(array $settings): array
    {
        $errors = [];
        if ($settings['hostname'] === '') $errors[] = 'Database host is required.';
        if ($settings['database'] === '') $errors[] = 'Database name is required.';
        if ($settings['username'] === '') $errors[] = 'Database username is required.';
        if ($settings['port'] < 1 || $settings['port'] > 65535) $errors[] = 'Enter a valid database port.';
        return $errors;
    }

    private function databaseFingerprint(array $input): string
    {
        return hash('sha256', implode("\0", [
            trim((string) ($input['db_host'] ?? '')),
            trim((string) ($input['db_name'] ?? '')),
            trim((string) ($input['db_username'] ?? '')),
            (string) ($input['db_password'] ?? ''),
            (string) ($input['db_port'] ?? ''),
        ]));
    }

    private function validateInput(array $input): array
    {
        $errors = $this->validateDatabase($this->databaseSettings($input));
        $siteName = trim((string) ($input['site_name'] ?? ''));
        $siteUrl = trim((string) ($input['site_url'] ?? ''));
        $adminUsername = trim((string) ($input['admin_username'] ?? ''));
        $adminEmail = strtolower(trim((string) ($input['admin_email'] ?? '')));
        $adminPassword = (string) ($input['admin_password'] ?? '');

        if (mb_strlen($siteName) < 2 || mb_strlen($siteName) > 100) $errors[] = 'Site name must be between 2 and 100 characters.';
        if (!filter_var($siteUrl, FILTER_VALIDATE_URL) || !in_array(strtolower((string) parse_url($siteUrl, PHP_URL_SCHEME)), ['http', 'https'], true)) $errors[] = 'Enter a valid website URL beginning with http:// or https://.';
        if (!preg_match('/^[A-Za-z0-9_.-]{3,32}$/', $adminUsername)) $errors[] = 'Admin username must be 3–32 characters using letters, numbers, dots, underscores, or hyphens.';
        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid admin email address.';
        if (strlen($adminPassword) < 8) $errors[] = 'Admin password must be at least 8 characters.';

        return $errors;
    }

    private function writeEnvironment(array $database, array $site, array $admin): void
    {
        $path = dirname(APPPATH) . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($path) || !is_readable($path) || !is_writable($path)) {
            throw new \RuntimeException('The project .env file must exist and be readable and writable by PHP.');
        }

        $values = [
            'app.baseURL' => $site['site_url'],
            'SITE_NAME' => $site['site_name'],
            'ADMIN_USERNAME' => $admin['username'],
            'ADMIN_EMAIL' => $admin['email'],
            'ADMIN_PASSWORD' => $admin['password'],
            'database.default.hostname' => $database['hostname'],
            'database.default.database' => $database['database'],
            'database.default.username' => $database['username'],
            'database.default.password' => $database['password'],
            'database.default.DBDriver' => $database['DBDriver'],
            'database.default.DBPrefix' => $database['DBPrefix'],
            'database.default.port' => (string) $database['port'],
            'database.default.charset' => $database['charset'],
            'database.default.DBCollat' => $database['DBCollat'],
        ];

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            throw new \RuntimeException('Could not read the project .env file.');
        }
        $found = [];
        foreach ($lines as $index => $line) {
            foreach ($values as $key => $value) {
                if (preg_match('/^\s*' . preg_quote($key, '/') . '\s*=/', $line) === 1) {
                    if (!isset($found[$key])) {
                        $lines[$index] = $key . ' = ' . $this->dotenvValue($value);
                        $found[$key] = true;
                    } else {
                        $lines[$index] = '# duplicate installer setting removed: ' . $key;
                    }
                    break;
                }
            }
        }
        foreach ($values as $key => $value) {
            if (!isset($found[$key])) {
                $lines[] = $key . ' = ' . $this->dotenvValue($value);
            }
        }

        $contents = implode(PHP_EOL, $lines) . PHP_EOL;
        $temporaryPath = $path . '.' . bin2hex(random_bytes(6)) . '.tmp';
        if (file_put_contents($temporaryPath, $contents, LOCK_EX) === false) {
            throw new \RuntimeException('Could not write the updated .env file.');
        }
        @chmod($temporaryPath, 0600);
        if (!rename($temporaryPath, $path)) {
            @unlink($temporaryPath);
            throw new \RuntimeException('Could not activate the updated .env file.');
        }
        @chmod($path, 0600);
    }

    private function dotenvValue(string $value): string
    {
        if (str_contains($value, "\n") || str_contains($value, "\r")) {
            throw new \RuntimeException('A configuration value contains an unsupported line break.');
        }

        return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
    }

    private function writeLock(): void
    {
        $path = WRITEPATH . 'installed.lock';
        $handle = @fopen($path, 'x');
        if ($handle === false) {
            throw new \RuntimeException('Could not create the installation lock file. Check writable/ permissions.');
        }
        fwrite($handle, 'Installed at ' . gmdate('c') . PHP_EOL);
        fclose($handle);
        @chmod($path, 0600);
    }
}
