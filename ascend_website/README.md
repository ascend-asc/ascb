# ASCB Website and Content Management System

This repository contains the public website and administration panel for **Andres Soriano Colleges of Bislig (ASCB)**. It is a small, framework-free PHP application backed by MySQL/MariaDB. Public pages present the school, programs, admissions information, scholarships, student life, policies, news, and inquiry forms; authenticated staff can maintain the database-driven content through the CMS.

## Features

### Public website

- Responsive Bootstrap-based pages
- Database-managed home-page hero slides
- School identity, program, staff, and news content
- Individual news articles addressed by slug
- Published custom pages addressed by slug
- Contact/inquiry submission
- Static admissions, scholarships, student-life, policy, and alumni information

### Administration panel

- Dashboard counts for inquiries, published news, and active slides
- Hero slide management
- News and events management
- Custom page management
- Academic program and brochure management
- Staff directory and photo management
- Media library
- Inquiry review
- Site identity and settings screens
- Administrator account management
- Session authentication, password hashing, prepared SQL statements, and CSRF tokens on administrative forms

## Technology

- PHP 8.x with PDO and the `pdo_mysql` extension
- MySQL or MariaDB
- Apache (the included configuration and paths are intended for a Laragon/XAMPP-style installation)
- Bootstrap 5.3, Swiper 10, Lucide icons, and Google Fonts loaded from CDNs
- Plain PHP, HTML, CSS, and JavaScript; Composer and npm are not required

The supplied database dump was generated with PHP 8.2.12 and MariaDB 10.4.32.

## Project structure

```text
app/core/                  Database, authentication, CSRF, and router classes
config/config.php          Database credentials and application URL
public_html/index.php      Public front controller
public_html/views/         Public page templates and shared partials
public_html/admin/         Login, dashboard, and CMS modules
public_html/assets/        Site styles
public_html/uploads/       Uploaded slide, news, and staff assets
schema.sql                 Empty schema without default credentials
ascend_db.sql              Full sample database dump and content
```

`app/core/Router.php` is currently not used by the public entry point. Routing is performed directly by `public_html/index.php` using the `url` query parameter.

## Local installation

### 1. Requirements

Enable Apache, MySQL/MariaDB, PHP 8.x, and these PHP extensions:

- `pdo`
- `pdo_mysql`
- `fileinfo` (used to validate uploads)
- `session`

The web-server user must be able to write to `public_html/uploads/` and its subdirectories.

### 2. Place and expose the application

Clone or copy the repository into the web root. For the configuration committed here, expose `public_html` at:

```text
http://localhost/ascend_website
```

For Apache, this can be done with an alias whose target is the repository's `public_html` directory. Alternatively, create a virtual host with `public_html` as its document root, then update `URLROOT` in `config/config.php` to that virtual-host URL.

### 3. Create the database

Choose one of the two SQL files:

- Import `ascend_db.sql` for the included demonstration content and uploaded-asset references.
- Import `schema.sql` for table structure only, then provision an administrator securely.

Example using the MySQL CLI from the repository root:

```bash
mysql -u root -p < ascend_db.sql
```

Do not import both: the dump contains existing records and imports into the
database selected by the client, while `schema.sql` creates/uses `ascend_db`
and provides empty tables without an administrator.

### 4. Configure the application

Edit `config/config.php`:

```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ascend_db');
define('URLROOT', 'http://localhost/ascend_website');
```

Use credentials appropriate to your environment. `URLROOT` must not have a trailing slash.

### 5. Configure clean URLs

Public links use paths such as `/about` and `/news/article-slug`. The web server must internally route requests that are not real files or directories to `index.php?url=<path>`.

An Apache configuration equivalent to the following is required in the `public_html` document root (and `mod_rewrite` must be enabled):

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+?)/?$ index.php?url=$1 [QSA,L]
```

There is currently no `.htaccess` file in this repository, so this rule must be supplied by the host configuration unless requests are made explicitly as `index.php?url=about`.

### 6. Open the site

- Public site: `http://localhost/ascend_website/`
- Admin login: `http://localhost/ascend_website/admin/login.php`

No default administrator is created by `schema.sql`. Provision a unique
superadmin account with a strong password hash for each environment. The full
`ascend_db.sql` dump contains demonstration data and must not be treated as a
source of production credentials.

## Public routes

| Path | Purpose |
| --- | --- |
| `/` or `/home` | Home page |
| `/about` | Identity and staff directory |
| `/academics` | Active academic programs |
| `/admissions` | Admissions information |
| `/scholarships` | Scholarship information |
| `/student-life` | Student-life information |
| `/policies` | School policies |
| `/alumni` | Alumni page |
| `/news` | Published news listing |
| `/news/{slug}` | Published news article |
| `/inquire` | Inquiry form |
| `/{slug}` | Published custom CMS page, when present |

## Content and uploads

Uploaded files are stored below `public_html/uploads/` and their relative paths are saved in the database. Back up both the database and this directory together. The current CMS accepts:

- Hero and news images
- Staff photographs
- Program brochure PDFs
- General media-library files

Limits and accepted MIME types are enforced separately by each module. Production PHP settings such as `upload_max_filesize` and `post_max_size` must be large enough for the module limits.

## Current implementation notes

This is an evaluation of the repository as currently committed:

- There is no automated test suite or dependency manifest.
- `config/config.php` contains environment-specific credentials and URL values. Moving these to environment variables is recommended.
- CMS page and news bodies support a sanitized HTML allowlist. Administrative content access should still remain limited to trusted staff.
- Some image references begin at `/images/...`, but no `public_html/images/` directory is included. Supply the referenced logo assets or update those paths.
- The sample dump contains absolute localhost links in some content. Update them when deploying under another hostname or base path.

## Production checklist

- Replace all sample administrator accounts and passwords.
- Keep PHP error display disabled and server-side error logging enabled.
- Store database secrets outside source control.
- Serve the application over HTTPS and set secure session-cookie options.
- Review administrator roles regularly and disable accounts that are no longer needed.
- Reconcile the `settings`/`site_settings` table name.
- Add CSRF protection and rate limiting to the public inquiry form.
- Review upload validation and prevent script execution in upload directories.
- Back up the database and uploaded files regularly.

## Security controls

The application applies secure, HTTP-only session cookies on HTTPS, strict
session handling, CSRF validation, login throttling, superadmin-only user
management, generic database error responses, MIME-validated uploads, and
script-execution blocking below `public_html/uploads/`. Public inquiries use a
CSRF token, a honeypot, length validation, and a short submission cooldown.

Production environments default to hidden PHP errors with server-side logging.
Set `APP_ENV` to `development` only in a local, ignored `config.local.php`.
There are no default administrator credentials in `schema.sql`; every
environment must provision unique administrator accounts and strong passwords.

## Staging deployment

Pushes to `main` that change files under `ascend_website/` are deployed by
`.github/workflows/deploy-staging.yml` to `staging.asc-bislig.com`. The workflow
first lints every PHP file with PHP 8.2, then synchronizes this application to
the staging host over SSH.

The following GitHub Actions secrets must be configured in the `staging`
environment:

- `STAGING_SSH_PRIVATE_KEY` — the private half of a dedicated deployment key
- `STAGING_SSH_KNOWN_HOSTS` — a verified `known_hosts` entry for the hosting server

The deployment intentionally preserves these server-managed paths:

```text
config/config.local.php
public_html/uploads/
```

The staging database is not migrated or replaced by the workflow. Create it
through cPanel and maintain its credentials only in the server's ignored
`config/config.local.php` file.

## Production preparation

`.github/workflows/deploy-production.yml` is a manual-only workflow that
prepares the isolated `/home/ascblzri/ascb-production` directory. It does not
modify the main domain's live WordPress document root. Running it requires the
`production` GitHub environment, its approval rules, and these environment
secrets:

- `PRODUCTION_SSH_PRIVATE_KEY`
- `PRODUCTION_SSH_KNOWN_HOSTS`

The operator must enter `prepare-production` when manually starting the
workflow. Production database migration, upload migration, and the main-domain
cutover are intentionally separate operations.

## License

No license file is currently included. Unless the project owner specifies otherwise, the source should be treated as proprietary.
