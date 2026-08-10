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
schema.sql                 Empty schema plus a development administrator
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
- Import `schema.sql` for the table structure and a minimal development administrator.

Example using the MySQL CLI from the repository root:

```bash
mysql -u root -p < ascend_db.sql
```

Both scripts create/use a database named `ascend_db`. Do not import both: the dump contains existing records, while `schema.sql` inserts its own default administrator.

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

When `schema.sql` is used, the seeded development credentials are:

```text
Email:    admin@ascb.edu.ph
Password: password123
```

Change this password immediately. The full `ascend_db.sql` dump contains a different sample account and should not be treated as a known-login source; create or reset an administrator record before relying on it.

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
- Database connection failures are printed to the response by `Database.php`; production deployments should log generic errors instead of exposing connection details.
- `admin/login.php` contains a development-only fallback login when a database operation throws. Remove it before production use.
- `config/config.php` contains environment-specific credentials and URL values. Moving these to environment variables is recommended.
- The public contact form does not currently use a CSRF token, although administrative forms do.
- CMS page bodies are deliberately rendered as HTML. Access to page editing must therefore remain trusted and tightly controlled.
- Role values are stored, but the modules generally require only a logged-in session rather than enforcing per-role permissions.
- The settings module queries `site_settings`. The full dump defines that table, but `schema.sql` defines only `settings`; the settings screen therefore fails after a schema-only installation until the schema or query is reconciled.
- The user-management module checks `$_SESSION['user']`, while authentication stores individual `admin_*` session keys. Its self-edit/self-delete protection therefore does not currently identify the signed-in user correctly.
- Some image references begin at `/images/...`, but no `public_html/images/` directory is included. Supply the referenced logo assets or update those paths.
- The sample dump contains absolute localhost links in some content. Update them when deploying under another hostname or base path.

## Production checklist

- Replace all sample administrator accounts and passwords.
- Remove the login fallback and disable PHP error display.
- Store database secrets outside source control.
- Serve the application over HTTPS and set secure session-cookie options.
- Add authorization checks for privileged administrator actions.
- Reconcile the `settings`/`site_settings` table name.
- Add CSRF protection and rate limiting to the public inquiry form.
- Review upload validation and prevent script execution in upload directories.
- Back up the database and uploaded files regularly.

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

## License

No license file is currently included. Unless the project owner specifies otherwise, the source should be treated as proprietary.
