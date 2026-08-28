# Exotic Animal Shelter Manager (EASM)

Self-hosted software for one exotic-animal shelter per install. It runs on ordinary web hosting (PHP 8.3+ and MySQL or MariaDB). It is a new application, not a fork of Animal Shelter Manager / sheltermanager.com. Species stay free-text — there is no dog/cat vocabulary.

UK-first defaults: locale `en_GB`, timezone `Europe/London`. Display dates as `dd/mm/yyyy` land with settings (#15), not this scaffold.

Tracked against [issue #9](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/9) under [epic #2](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/2) / [plan #1](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/1).

## Hosting matrix

| Host type | PHP | Database | Document root | Notes |
|-----------|-----|----------|---------------|--------|
| cPanel shared | 8.3+ (select in MultiPHP) | MySQL 8 or MariaDB 10.6+ | `public/` | One `composer install`. Do not point the site at the repo root. |
| Plesk | 8.3+ | MySQL or MariaDB | `public/` | Same document-root and Composer step. |
| VPS (Apache or nginx) | 8.3+ | MySQL or MariaDB | `public/` | HTTPS recommended. No Cloudron, OIDC, or extra platform files required. |

This release does **not** target Cloudron, Laravel Cloud, or multi-tenant SaaS. Composer runs at install time only — not on every HTTP request.

### PHP extensions

`bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `zip`.

## Install

Unzip a release or clone the repository, then run Composer **once**:

```bash
git clone https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM.git
cd Exotic-Animal-Shelter-Manager-EASM

composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

- `APP_URL` — public HTTPS URL
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — empty MySQL/MariaDB database created in the host panel
- Keep `APP_LOCALE=en_GB` and `APP_TIMEZONE=Europe/London` unless you have a reason to change them

Then:

```bash
php artisan migrate --force
```

Point the vhost document root at `public/`. Make `storage/` and `bootstrap/cache/` writable by the web server.

Open `/health`. You should see JSON with `"status":"ok"` and a `version` field. The home page also boots without a Node/npm step.

Development install uses `composer install` (with dev packages) instead of `--no-dev`.

The web installer wizard is [#10](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/10) and is not in this scaffold.

## Health and version

`GET /health` returns application status, name, and version. It checks that PHP can open the configured database and does not include secrets, `.env` values, or credentials.

Laravel's built-in `GET /up` probe remains available.

## Local development

```bash
composer install
cp .env.example .env
php artisan key:generate
# Create an empty MySQL/MariaDB database named easm, then:
php artisan migrate
php artisan serve
```

`php artisan serve` listens on http://127.0.0.1:8000/. Node and Vite are optional and are not required to boot or to hit `/health`.

```bash
composer test
composer lint
```

## What this scaffold does not include

- Installer wizard ([#10](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/10))
- Auth and roles ([#11](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/11))
- Settings UI for org name and date formats ([#15](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/15))
- Animal records, public site, Cloudron SSO

The repository is maintained by [APES CIC](https://github.com/APESCIC).
