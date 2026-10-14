# Lumora

PHP MVC storefront for acrylic artwork, with customer accounts, carts, cash-on-delivery checkout, wishlists, custom design requests, and an administration dashboard.

## Using a fresh GitHub clone

Follow **Set up elsewhere** below to configure PHP, a database, and your private `config/local.php`. The GitHub repository contains the active application, schema, scripts, and tests. Portable runtimes, local databases, archived originals, credentials, and session/log files are excluded from Git.

## Local preview in the original prepared folder

The original prepared folder includes private portable PHP and MariaDB runtimes in `.tools/`. These are not included in GitHub. A separate local database, `lumora_local`, has been initialized with one clearly labelled sample product. The original hosted database has not been modified.

From PowerShell in this folder:

```powershell
./scripts/start-local.ps1
```

Open [the local store](http://127.0.0.1:8000). PHP listens on `127.0.0.1:8000`; the private database listens on `127.0.0.1:3307`. Settings and the generated database password are in `config/local.php`.

No default administrator password is installed. Create your own administrator using environment variables, then sign in through `/login`:

```powershell
$env:ADMIN_EMAIL = 'your-email@example.com'
$securePassword = Read-Host 'Administrator password (at least 12 characters)' -AsSecureString
$env:ADMIN_PASSWORD = [System.Net.NetworkCredential]::new('', $securePassword).Password
./.tools/php/php.exe scripts/create-admin.php
Remove-Item Env:ADMIN_PASSWORD
Remove-Item Env:ADMIN_EMAIL
```

## Folder structure

| Path | Purpose |
| --- | --- |
| `app/Controllers/` | Public and admin request handlers |
| `app/Core/` | Router, requests, sessions, database, view rendering |
| `app/Repositories/` | Database queries |
| `app/Services/` | Login, cart, orders, image uploads |
| `app/Helpers/` | Cached CMS settings |
| `app/Views/` | Templates, shared layouts, error pages |
| `config/` | Environment-aware configuration and private local overrides |
| `database/schema.sql` | Complete schema for the current application |
| `database/legacy/` | Local-only preserved old dump and SQL patches; excluded from Git |
| `public/` | Styles, images, and upload destination |
| `scripts/` | Local startup, database setup, administrator creation |
| `tests/` | Isolated end-to-end checks and disposable test fixtures |
| `storage/` | Private logs and sessions |
| `archive/` | Local-only preserved legacy files and original configuration; excluded from Git |
| `.tools/` | Private local runtimes and database files; never deploy |

## Set up elsewhere

Use PHP 8.0 or newer with `pdo_mysql`, `fileinfo`, and `mbstring`, plus MySQL 8 or MariaDB 10.4 or newer. A PHP 8.3 runtime was used for verification. Node.js 18 or newer is needed only for the smoke tests. There are no Composer or npm application dependencies.

1. Copy `config/local.example.php` to `config/local.php` and set database credentials, or supply the equivalent environment variables: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `APP_ENV`, `APP_URL`.
2. Choose a **new database** for this application. The SQL in `database/legacy/` describes an older `items` / `users_items` application and is not a migration for the current code. Existing customer passwords and orders need a separate, reviewed migration.
3. Run `php scripts/setup.php`. The database user needs permission to create the database and tables. On shared hosting, create a fresh database through the host's control panel, import `database/schema.sql`, and configure its credentials instead.
4. Optionally run `php scripts/setup.php --demo` to insert the sample product. This is idempotent and never resets existing products.
5. Create an administrator with `scripts/create-admin.php` as shown above.
6. Run `php -S 127.0.0.1:8000 router.php` for development.

The site expects deployment at the domain root. Apache requires `mod_rewrite`, `AllowOverride All`, and the supplied `.htaccess`. Give PHP write access to `storage/` and `public/img/`. Set `APP_ENV=production` on your host and use HTTPS.

Deploy the active application files, public assets, configuration, and `.htaccess`. Exclude `archive/`, `database/legacy/`, `.tools/`, tests, local credentials, logs, and session files. For Nginx or IIS, configure equivalent front-controller routing and private-directory restrictions; the Apache rules do not apply to those servers.

In the original local folder, production configuration is preserved in `archive/legacy/config.php` for reference; it is excluded from GitHub. Replace its old embedded database password before using that account for deployment. Product image files referenced by the legacy dump were mostly absent from the supplied folder; the current app has a local placeholder image.

## Verify

With your configured database server running:

```powershell
node tests/smoke.mjs
```

The test creates a unique `lumora_test_<random>` database, starts a temporary server on port 8011, exercises customer/admin flows, verifies database totals and stock, then removes its own test database and uploaded test image. It does not use the local store's customer or order records. Test database creation/removal requires appropriate privileges. Use `PHP_BINARY` to select PHP, or `TEST_PORT` if 8011 is occupied.

For a syntax check:

```powershell
Get-ChildItem app,config,scripts,tests -Recurse -Filter '*.php' | ForEach-Object { ./.tools/php/php.exe -l $_.FullName }
./.tools/php/php.exe -l index.php
./.tools/php/php.exe -l bootstrap.php
./.tools/php/php.exe -l router.php
```

See `VERIFICATION.md` for the checks and remaining deployment limits.

## Current functional limits

Checkout supports cash on delivery. Payment gateways, email delivery, and live shipping integrations are not implemented. The supplied admin product interface supports inventory listing, duplication, deletion, and bulk prices; it does not include a full product creation/gallery editor. Bootstrap 5, icons, fonts, animations, and the homepage video use third-party CDNs, so the full visual experience requires internet access.
