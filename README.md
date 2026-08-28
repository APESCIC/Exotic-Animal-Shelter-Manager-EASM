# Exotic Animal Shelter Manager (EASM)

Self-hosted software for one exotic-animal shelter per install. Stack: **Laravel + PHP 8 + MySQL/MariaDB** on ordinary web hosting. One `composer install` at setup, not on every request. It is a new application, not a fork of Animal Shelter Manager / sheltermanager.com. Species stay free-text — there is no dog/cat vocabulary.

UK-first defaults: locale `en_GB`, timezone `Europe/London`. Display dates as `dd/mm/yyyy` land with settings (#15), not this scaffold.

See [AGENTS.md](AGENTS.md) for product constraints and implement order (v0.1.0 through v1.0.0). This PR is **#9 only**. v0.1.0 continues as #10 installer, then #11 auth/roles, then #15 settings — do not start the next milestone until this foundation sequence is done.

Tracked against [issue #9](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/9) under [epic #2](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/2) / [plan #1](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/1).

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
- Animal records (v0.2 #12), public site, Cloudron OIDC / MyAPES-Account auth copy

The repository is maintained by [APES CIC](https://github.com/APESCIC).
