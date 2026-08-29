# AGENTS.md

Instructions for Cursor agents working on **Exotic Animal Shelter Manager (EASM)**. Do not invent extra product constraints. This repo may be README-only until the Laravel scaffold lands.

## Product constraints

- Self-hosted PHP/MySQL app for ordinary web hosting. One Composer install step. One shelter per install.
- Stack: **Laravel + PHP 8 + MySQL/MariaDB**.
- No Cloudron OIDC/staff login in v0.1. Local admin on generic hosting must work on day one. Do **not** copy MyAPES-Account Cloudron auth.
- Do **not** fork Animal Shelter Manager (ASM / [sheltermanager.com](https://sheltermanager.com)). Use it as a feature baseline only.
- Exotic species stay free-text. Animal records get a **primary photo** plus enclosure/CITES/DWA on #12 (do not wait for v0.3 media / #18 for the primary photo).
- Animal control (#24) is municipal ASM; it must **not** block v1.0.0.
- UK locale/timezone defaults.

## Implement order

Finish a milestone before starting the next.

**App Developer owns [#9](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/issues/9)** (Laravel + PHP 8 + MySQL scaffold). Other Cursor runs must not open a second scaffold PR, touch #9, or start a scaffold branch.

### v0.1.0 Foundation: generic hosting ([milestone/1](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/1), epic #2)

1. #9 scaffold — App Developer only
2. #10 installer wizard
3. #11 auth/roles (admin, staff, volunteer, readonly)
4. #15 settings (org, UK locale, timezone)

### v0.2.0 Animals, people and movements ([milestone/2](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/2), epic #3)

1. #12 animal records (photo, enclosure, CITES, DWA)
2. #14 people/contacts
3. #13 movements (intake, hold, quarantine, foster, trial adoption, adopt, reclaim, transfer, deceased — first movements; quarantine for exotic intake)
4. #28 lost/found matching

### v0.3.0 Medical, diary and care ([milestone/3](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/3), epic #4)

1. #16 vaccinations/tests/treatments/diets
2. #17 staff diary/tasks
3. #18 media attachments
4. #31 custom fields (after #12 enclosure/CITES/DWA; does not block first animal records)

### v0.4.0 Public adoption and applications ([milestone/4](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/4), epic #5)

1. #19 adoptable website and public API
2. #20 online applications

### v0.5.0 Documents, publishing and finance ([milestone/5](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/5), epic #6)

1. #21 document templates/e-sign
2. #22 publish to partner sites
3. #23 donations/fees

### v0.6.0 Animal control, reporting and mobile ([milestone/6](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/6), epic #8)

1. #24 animal control (does not block v1.0.0)
2. #25 reports/timeline
3. #26 touch-friendly tasks/rota

### v1.0.0 Production-ready self-host ([milestone/7](https://github.com/APESCIC/Exotic-Animal-Shelter-Manager-EASM/milestone/7), epic #7)

1. #27 backup/security/first production install pack

Plan epic is #1 (unmiled).

## Local development (Windows)

Prefer **Laragon** for preview and CLI. See README “Local development” and `.cursor/rules/local-dev.mdc`.

- Source `scripts/local/use-laragon.ps1` before `php` / `composer` / tests.
- Preview: **http://easm.test** (Apache document root `public/`). Do not start `php artisan serve` when Laragon can serve the app.
- Interactive DB: Laragon MySQL on `127.0.0.1`. Do not use Docker hostnames (e.g. `easm-mysql`) for local smoke.
- Do **not** run Pint / `composer format` / `composer lint` mid-work. Lint once only in the ship-gate pre-commit verification block.

## Ship-gate

Work starts and finishes from a Cursor agent request. Follow `.cursor/rules/ship-gate.mdc` and `.cursor/skills/ship-gate/SKILL.md`:

1. When a slice is ready, run the **pre-commit verification** (full `composer test`, one `composer lint`, Laragon UI smoke when HTTP was touched). On failure, fix or stop — do **not** present the commit gate.
2. Only after verification passes, present the **commit gate** (never commit silently).
3. Open/update the PR; watch CI until green; present the **merge gate**.
4. After merge on `main`, present the **GitHub Release gate** only as an opt-in — create a release when the operator decides, never silently.
5. **No deploy gate.** Shelters download/clone and install on their own hosting (Composer + `/install`). Do not invent Cloudron or CI deploy steps.

## GitHub issues

- Search **this repo** for duplicates. Never open issues in other repos.
- Prefer `area:` labels if they exist. Do not invent `type:` or `priority:` labels.
- Branch `cursor/{feature|fix|chore}/<issue-number>-<slug>`. Open PRs with `Fixes #<n>`.
- Assignee: `bmurphy-apescic` unless another owner is named.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.3. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Test every code change by adding or updating a test.
- Run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>
