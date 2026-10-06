<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;
use Config\Services;

class EmailService
{
    // Throttle verification email resends to avoid spamming.
    protected const VERIFICATION_THROTTLE_SECONDS = 600; // 10 minutes

    protected $email;
    protected $userModel;

    protected function getClientBaseUrl(): string
    {
        $clientUrl = trim((string) env('APP_CLIENT_URL', ''));
        if ($clientUrl !== '') {
            return rtrim($clientUrl, '/');
        }

        return rtrim(base_url(), '/');
    }

    public function __construct()
    {
        $this->email = Services::email();
        $this->userModel = new UserModel();
    }

    /**
     * Send Email Verification (immediate).
     */
    public function sendVerificationEmail(array $user): bool
    {
        if ($this->getVerificationEmailCooldown($user) > 0) {
            log_message('info', 'Verification email throttled for user ID: ' . (string) ($user['id'] ?? ''));
            return false;
        }

        $emailData = $this->buildVerificationData($user);

        $status = $this->sendTemplatedEmail(
            (string) $user['email'],
            'Verify Your Email - Yooo.App',
            'emails/email-verification',
            $emailData
        );

        if ($status) {
            $this->markVerificationEmailSent($user);
        }

        return $status;
    }

    /**
     * Send Welcome Email After Verification.
     */
    public function sendWelcomeEmail(array $user): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
            'login_link' => $this->getClientBaseUrl() . '/signin',
            'email' => $user['email'] ?? '',
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Welcome to Yooo.App - Your Account is Ready',
            'emails/signup-complete',
            $emailData
        );
    }

    /**
     * Send Onboarding Process Email After Verification.
     */
    public function sendOnboardingProcessEmail(array $user): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
            'dashboard_link' => $this->getClientBaseUrl() . '/dashboard',
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Instructions to Make Your Profile - Yooo.App',
            'emails/onbording-process',
            $emailData
        );
    }

    /**
     * Send Profile Pending Email.
     */
    public function sendProfilePendingEmail(array $user, string $pendingFields, string $language = 'en'): bool
    {
        $language = strtolower(trim($language));
        if (!preg_match('/^[a-z]{2}$/', $language)) {
            $language = 'en';
        }

        $emailData = [
            'name' => $user['name'] ?? '',
            'pending_fields' => $pendingFields,
            'dashboard_link' => $this->getClientBaseUrl() . '/dashboard?lang=' . $language,
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Complete Your Profile - Yooo.App',
            'emails/profile-pending',
            $emailData
        );
    }

    /**
     * Send Profile Active Email.
     */
    public function sendProfileActiveEmail(array $user, string $profileUrl, string $cityUrl): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
            'profile_url' => $profileUrl,
            'city_url' => $cityUrl,
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Your Profile is Now Active - Yooo.App',
            'emails/profile-active',
            $emailData
        );
    }

    /**
     * Send Profile Upgraded Email.
     */
    public function sendProfileUpgradedEmail(array $user, string $profileUrl, string $cityUrl): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
            'profile_url' => $profileUrl,
            'city_url' => $cityUrl,
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Your Profile Has Been Upgraded - Yooo.App',
            'emails/profile-upgraded',
            $emailData
        );
    }

    /**
     * Send Profile Verified Email.
     */
    public function sendProfileVerifiedEmail(array $user, string $profileUrl): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
            'profile_url' => $profileUrl,
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Profile Verified - Yooo.App',
            'emails/profile-verified',
            $emailData
        );
    }

    /**
     * Send Upgrade Membership Email.
     */
    public function sendUpgradeMembershipEmail(array $user): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Upgrade Your Membership - Yooo.App',
            'emails/upgrade-membership',
            $emailData
        );
    }

    /**
     * Send Membership Packages Email.
     */
    public function sendMembershipPackagesEmail(array $user): bool
    {
        $emailData = [
            'name' => $user['name'] ?? '',
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Membership Packages - Yooo.App',
            'emails/membership-packages',
            $emailData
        );
    }

    /**
     * Send Password Reset Email.
     */
    public function sendPasswordResetEmail(array $user, string $token, string $language = 'en'): bool
    {
        $language = strtolower(trim($language));
        if (!preg_match('/^[a-z]{2}$/', $language)) {
            $language = 'en';
        }
        // Point reset link to the API reset-password page so the API can render the HTML form.
        $resetLink = rtrim(base_url(), '/') . '/api/v1/auth/reset-password?token=' . urlencode($token) . '&lang=' . urlencode($language);

        $emailData = [
            'name'       => $user['name'] ?? '',
            'reset_link' => $resetLink,
        ];

        return $this->sendTemplatedEmail(
            (string) $user['email'],
            'Reset Your Password - Yooo.App',
            'emails/forgot-password',
            $emailData
        );
    }

    protected function buildVerificationData(array $user): array
    {
        $token = bin2hex(random_bytes(32));

        $this->userModel->update($user['id'], [
            'email_verification_token' => $token,
        ]);

        $verificationLink = rtrim(base_url(), '/') . '/api/v1/auth/verify-email?token=' . urlencode($token);

        return [
            'name' => $user['name'] ?? '',
            'verification_link' => $verificationLink,
        ];
    }

    public function getVerificationEmailCooldown(array $user): int
    {
        $cache = cache();
        $key = $this->getVerificationThrottleKey($user);
        $lastSent = $cache->get($key);

        if (!is_int($lastSent)) {
            if (is_numeric($lastSent)) {
                $lastSent = (int) $lastSent;
            } else {
                return 0;
            }
        }

        $remaining = ($lastSent + self::VERIFICATION_THROTTLE_SECONDS) - time();
        if ($remaining <= 0) {
            $cache->delete($key);
            return 0;
        }

        return $remaining;
    }

    protected function markVerificationEmailSent(array $user): void
    {
        $key = $this->getVerificationThrottleKey($user);
        cache()->save($key, time(), self::VERIFICATION_THROTTLE_SECONDS);
    }

    protected function getVerificationThrottleKey(array $user): string
    {
        $userId = (int) ($user['id'] ?? 0);
        if ($userId > 0) {
            return 'email_verification_last_sent_' . $userId;
        }

        $email = strtolower(trim((string) ($user['email'] ?? '')));
        return 'email_verification_last_sent_' . md5($email);
    }

    protected function sendTemplatedEmail(string $to, string $subject, string $view, array $data): bool
    {
        $message = view($view, $data);
        $config = config('Email');

        $this->email->clear(true);
        $this->email->setFrom($config->fromEmail, $config->fromName);
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        $status = $this->email->send();
        $this->logSentEmail(
            $data['name'] ?? '',
            $to,
            $subject,
            $status
        );

        return $status;
    }

    protected function logSentEmail(string $name, string $email, string $subject, bool $status): void
    {
        $path = WRITEPATH . 'logs/sent-emails.log';
        $directory = dirname($path);

        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        $entry = [
            'time' => date('Y-m-d H:i:s'),
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'status' => $status ? 'success' : 'failed',
        ];

        $line = json_encode($entry, JSON_UNESCAPED_SLASHES);
        if ($line !== false) {
            @file_put_contents($path, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($lines) && count($lines) > 100) {
            $tail = array_slice($lines, -100);
            @file_put_contents($path, implode(PHP_EOL, $tail) . PHP_EOL, LOCK_EX);
        }
    }
}
