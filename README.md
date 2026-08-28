# Exotic Animal Shelter Manager (EASM)

Self-hosted software for one exotic-animal shelter per install. Stack: **Laravel + PHP 8 + MySQL/MariaDB** on ordinary web hosting. One `composer install` at setup, not on every request. It is a new application, not a fork of Animal Shelter Manager / sheltermanager.com. Species stay free-text — there is no dog/cat vocabulary.

UK-first defaults: locale `en_GB`, timezone `Europe/London`. Display dates as `dd/mm/yyyy` land with settings (#15).

See [AGENTS.md](AGENTS.md) for product constraints and implement order (v0.1.0 through v1.0.0). v0.1.0 continues as #11 auth/roles, then #15 settings — do not start those until this installer is done.

Tracked against [issue #10](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/10) under [epic #2](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/2) / [plan #1](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/1).

## Hosting matrix

| Host type | PHP | Database | Document root | Notes |
|-----------|-----|----------|---------------|--------|
| cPanel shared | 8.3+ (select in MultiPHP) | MySQL 8 or MariaDB 10.6+ | `public/` | One `composer install`. Do not point the site at the repo root. |
| Plesk | 8.3+ | MySQL or MariaDB | `public/` | Same document-root and Composer step. |
| VPS (Apache or nginx) | 8.3+ | MySQL or MariaDB | `public/` | HTTPS recommended. No Cloudron packaging or OIDC. |

Laravel 13 needs PHP 8.3 or newer. Composer runs at install time only — not on every HTTP request. One shelter per install; this is not multi-tenant SaaS. No Cloudron OIDC or staff login in v0.1.

### PHP extensions

`bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `zip`.

## Install

Unzip a release or clone the repository, then run Composer **once**. The web wizard collects database credentials, the first admin user, organisation name, and timezone (default Europe/London).

```bash
git clone https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM.git
cd Exotic-Animal-Shelter-Manager-EASM

composer install --no-dev --optimize-autoloader
```

From a zip:

```bash
unzip easm.zip
cd easm   # directory name may match the release archive
composer install --no-dev --optimize-autoloader
```

`composer install` copies `.env.example` to `.env` when needed and fills `APP_KEY` if it is empty. It does not migrate and does not mark the app installed.

Then:

1. Create an empty MySQL or MariaDB database in the host panel.
2. Point the vhost document root at `public/`.
3. Make `storage/` and `bootstrap/cache/` writable by the web server.
4. Open the site in a browser. You are redirected to `/install`.
5. Enter database credentials, organisation name, timezone, and the first admin user.

The wizard writes `.env`, runs migrations, creates that admin, and writes `storage/app/installed`. After that, `/install` will not run again.

Open `/health`. You should see JSON with `"status":"ok"` and a `version` field.

Development install uses `composer install` (with dev packages) instead of `--no-dev`.

## Health and version

`GET /health` returns application status, name, and version. It checks that PHP can open the configured database and does not include secrets, `.env` values, or credentials. It stays available before and after the installer.

Laravel's built-in `GET /up` probe remains available.

## Local development

```bash
composer install
php artisan serve
```

Open http://127.0.0.1:8000/install and complete the wizard against a local MySQL/MariaDB database. Node and Vite are optional and are not required to boot, install, or hit `/health`.

If `php artisan serve` reloads when the wizard writes `.env`, refresh the home page — install has already finished.

```bash
composer test
composer lint
```

PHPUnit treats the app as already installed (`APP_INSTALLED=true`) except in installer tests.

## What this does not include

- Auth and roles ([#11](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/11)) — the installer only creates the first admin user
- Settings UI for org name and date formats ([#15](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/15))
- Animal records (v0.2 #12), public site, Cloudron OIDC / MyAPES-Account auth copy

The repository is maintained by [APES CIC](https://github.com/APESCIC).
