# YoooCMS

YoooCMS is a self-hosted directory and booking platform built with PHP and CodeIgniter 4. It provides public profile listings, account and profile management, booking workflows, an administration area, and a JSON API. Yooo.app is a live platform powered by YoooCMS.

Use this project as a starting point for a directory or service platform, and adapt its categories, languages, content, and settings to your deployment.

## Features

- Public, location-aware listings and individual profile pages
- Account registration, sign-in, password recovery, and user profile management
- Booking requests and booking management
- Admin tools for users, profiles, verification, bookings, locations, SEO, and site settings
- Localized routes and generated XML sitemaps
- JSON API under `/api/v1`
- Browser-based first-run installer

## Requirements

- PHP 8.2 or newer
- Composer
- MySQL or MariaDB, with PHP's `mysqli` extension
- PHP extensions `intl` and `mbstring` (required by the project)
- A web server configured to serve the `public/` directory and route requests to `public/index.php`

The application also uses cURL for some outbound API and upload operations. Configure it if you use those features.

## Install locally

1. Clone the repository and install dependencies:

   ```sh
   git clone <repository-url> yooocms
   cd yooocms
   composer install
   ```

2. Create the environment file expected by the installer. The file is intentionally ignored by Git:

   ```sh
   touch .env
   ```

   Ensure PHP can write to `.env` and to the `writable/` directory. The installer saves the site URL, database connection, and initial administrator credentials in `.env`.

3. Create an empty MySQL or MariaDB database and a database user with permission to create and update tables in it.

4. Start the development server:

   ```sh
   php spark serve
   ```

5. Open [http://localhost:8080/install](http://localhost:8080/install), enter your site, database, and administrator details, test the database connection, and complete installation.

After installation, visit the site root. The administration sign-in is at `/ci-admin`.

## Deployment

Point your web server's document root at the project's `public/` directory; do not expose the project root. Make sure URL rewriting/front-controller routing is enabled, `.env` is writable during installation, and `writable/` is writable by PHP. Use HTTPS and set the production URL in the installer. Restrict access to `.env` and keep it out of version control.

The installer creates `writable/installed.lock` when setup succeeds and prevents the setup page from running again. Keep that file in place after installation.

## Configuration

The installer writes the site URL, database connection, site name, and administrator account to `.env`. Further environment variables can be added there. The API routes are enabled by default and can be disabled with `API_ENABLED=false`.

Site categories and languages are configurable with `SITE_CATEGORIES` and `SITE_LANGUAGES`. Review the application configuration and admin settings before opening a production deployment to users.

## Tests

Install development dependencies and run PHPUnit:

```sh
composer install
composer test
```

Some database tests require a configured test database. See [tests/README.md](tests/README.md) for the testing setup.

## Live example

[Yooo.app](https://www.yooo.app) is a live platform powered by YoooCMS.

## License

This project is distributed under the [MIT License](LICENSE).
