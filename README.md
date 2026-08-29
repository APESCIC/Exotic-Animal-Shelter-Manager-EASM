# Exotic Animal Shelter Manager (EASM)

Self-hosted software for one exotic-animal shelter per install. Stack: **Laravel + PHP 8 + MySQL/MariaDB** on ordinary web hosting. One `composer install` at setup, not on every request. It is a new application, not a fork of Animal Shelter Manager / sheltermanager.com. Species stay free-text — there is no dog/cat vocabulary.

UK-first defaults: locale `en_GB`, timezone `Europe/London`. Display dates as `dd/mm/yyyy` land with settings (#15).

See [AGENTS.md](AGENTS.md) for product constraints and implement order (v0.1.0 through v1.0.0).

Tracked against [issue #12](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/12) under [epic #3](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/3) / [plan #1](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/1).

## Hosting matrix

| Host type | PHP | Database | Document root | Notes |
|-----------|-----|----------|---------------|--------|
| cPanel shared | 8.3+ (select in MultiPHP) | MySQL 8 or MariaDB 10.6+ | `public/` | One `composer install`. Do not point the site at the repo root. |
| Plesk | 8.3+ | MySQL or MariaDB | `public/` | Same document-root and Composer step. |
| VPS (Apache or nginx) | 8.3+ | MySQL or MariaDB | `public/` | HTTPS recommended. No Cloudron packaging or OIDC. |

Laravel 13 needs PHP 8.3 or newer. Composer runs at install time only — not on every HTTP request. One shelter per install; this is not multi-tenant SaaS. No Cloudron OIDC or staff login in v0.1.

### PHP extensions

`bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `zip`.

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
4. Run `php artisan storage:link` so primary animal photos are publicly reachable.
5. Open the site in a browser. You are redirected to `/install`.
6. Enter database credentials, organisation name, timezone, and the first admin user.

The wizard writes `.env`, runs migrations, creates that admin (role `admin`), and writes `storage/app/installed`. After that, `/install` will not run again.

Sign in at `/login` with the admin email and password. Roles are admin, staff, volunteer, and readonly. Only admins can open `/admin` and change organisation settings (name, locale, timezone). Dates display as `dd/mm/yyyy`. Successful logins are written to `login_events`.

Admin and staff can manage animal records at `/animals` (free-text species, primary photo, enclosure/CITES/DWA, find/filter, shelter view by location). Run `php artisan storage:link` once so primary photos are served.

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

Open http://127.0.0.1:8000/install and complete the wizard against a local MySQL/MariaDB database, then sign in at `/login`. Node and Vite are optional and are not required to boot, install, or hit `/health`.

If `php artisan serve` reloads when the wizard writes `.env`, refresh the home page — install has already finished.

```bash
composer test
composer lint
```

PHPUnit treats the app as already installed (`APP_INSTALLED=true`) except in installer tests.

## Releases

There is no CI or agent deploy to customer hosting. Shelters install from a clone or zip (Composer + `/install`) as above.

GitHub Releases are optional and operator-gated: after a merge to `main`, Cursor agents ask before creating a release (see [AGENTS.md](AGENTS.md) ship-gate). CI (`.github/workflows/ci.yml`) runs tests only.

## What this does not include

- People/contacts (#14), movements (#13), lost/found (#28), public site, Cloudron OIDC / MyAPES-Account auth copy

The repository is maintained by [APES CIC](https://github.com/APESCIC).
