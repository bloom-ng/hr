# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Bloom HR — an internal HR/payroll system built on **CodeIgniter 3.1.11** (PHP, MySQL, server-rendered views) with a small **JWT-authenticated JSON API** used by a mobile app. Server-side assets are plain PHP views styled with Bootstrap plus a Tailwind layer.

## Setup & commands

```bash
composer install                 # PHP deps (firebase/php-jwt, phpoffice/phpword, smalot/pdfparser, expo-server-sdk)
npm install                      # tailwindcss only
cp application/config/config.dist.php application/config/config.php
cp application/config/database.dist.php application/config/database.php
```

`application/config/config.php` and `database.php` are gitignored — always edit the real files locally, and mirror any *new* config key into the `.dist.php` versions so other developers get it. Keys the app depends on beyond stock CI: `base_url`, `encryption_key`, `jwt_secret_key`, `jwt_token_header`.

```bash
# Tailwind (watch mode)
npx tailwindcss -i ./assets/input.css -o ./assets/output.css --watch

# Serve locally (base_url defaults to http://localhost:9000)
php -S localhost:9000 index.php

# Tests
vendor/bin/phpunit                                  # whole suite
vendor/bin/phpunit tests/Performance_model_test.php # one file
vendor/bin/phpunit --filter testRatingBandOutstanding

# Migrations: set $config['migration_enabled'] = TRUE in application/config/migration.php,
# then hit /run_migration in the browser (application/controllers/Run_migration.php).

# CLI controllers (cron only, blocked in the browser)
php index.php cli/EventReminder send
```

Environment comes from the `CI_ENV` server var, defaulting to `development` (see `index.php`).

## Architecture

**Request flow.** `.htaccess` → `index.php` → CI router. `application/config/routes.php` holds ~160 explicit routes; most are hyphenated URL aliases onto `Controller/method` (e.g. `$route['manage-anonymous'] = 'anonymous/manage'`). Add a route here whenever you add a controller method that users navigate to — the default `controller/method` fallback is only relied on in a few places.

**Controllers** live in `application/controllers/`, one per HR domain (Payroll, Payslip, Salary, Leave, Attendance, Appraisal/Appraisal_new, Performance, Vote, Budget, Equipment, TransactionJournals, …). Most extend `CI_Controller` directly; a few newer ones (`Projects`, `FundRequest`, `Equipment`) extend `MY_Controller`. `MY_Controller` (`application/core/MY_Controller.php`) is currently an empty shell whose real value is the `@property` docblocks and the `API_Controller` subclass defined in the same file.

**Auth is per-controller, not middleware.** Each web controller's `__construct()` does:

```php
if (!$this->session->userdata('logged_in')) { redirect(base_url() . 'login'); }
```

and role checks are inline against `$this->session->userdata('role')`. Any new controller must repeat this guard — there is no global hook doing it.

**Session shape** (set in `Home::login`): `logged_in`, `username`, `usertype`, `role`, `userid`, plus `staff_id` and `department_id` **only when `role === 'staff'`**. Non-staff roles have no `staff_id` in session, so code needing the staff record for e.g. an HRM user must look it up by `user_id` (see `Vote::_current_staff()` for the canonical pattern). Roles in use: `super`, `hrm`, `hod`, `staff`, `account`. Passwords are `password_verify`/`password_hash`.

**Views** in `application/views/` split by audience: `admin/` (management screens, ~70 files), `staff/` (self-service), plus `vote/`, `events/`, `errors/`. Every page is rendered as a three-call sandwich:

```php
$this->load->view('admin/header');
$this->load->view('admin/manage-thing', $data);
$this->load->view('admin/footer');
```

`admin/header` + `admin/footer` are used even for `staff/` pages — the header renders the role-aware sidebar, so new menu entries go there.

**Models** in `application/models/`, one per table/domain, thin wrappers over CI's query builder. Conventions: a `public $table` property, `insert_x`/`update_x`/`delete_x`/`select_x_byID` method names, `result_array()` / `row_array()` returns, status values as class constants (`Vote_model::STATUS_OPEN`, `Appraisal_new_model::APPRAISAL_HR_APPROVED`), and `$this->db->trans_start()/trans_complete()` for multi-table writes. 25 models are autoloaded in `application/config/autoload.php`; anything else is `$this->load->model(...)` inside the controller. When you add a model that most pages need, add it to the autoload list — otherwise load it locally.

**Pure business logic is testable.** `Performance_model` deliberately keeps scoring/derivation methods (`deriveYearAndQuarterFromMonthUnderReview`, `scoreFinalAppraisalRow`) free of `$this->db`, and `tests/Performance_model_test.php` instantiates the model directly by requiring `system/core/Model.php`. Follow that split — put new scoring/calculation rules in DB-free methods so they can be unit tested; DB-touching code has no test harness here.

**API.** `application/controllers/api/` (currently `Auth.php`) extends `API_Controller`, which sets the JSON content type and authenticates every method except `login` via the `jwt_token_header` request header. Tokens are minted/validated by `application/helpers/jwt_helper.php` (HS256, 30-day expiry, payload under `data`). Use `send_response($data, $code)` rather than echoing. API routes are method-scoped in `routes.php`, e.g. `$route['api/auth/login']['POST']`.

**Migrations** are timestamp-style in `application/migrations/` (`YYYYMMDDHHMMSS_Description.php`). They are not the source of truth for the whole schema — most tables predate them, so migrations only cover incremental changes. Check existing migration files before assuming a column exists.

**Push notifications** go through `ctwillie/expo-server-sdk-php` with tokens in `PushToken_model`; document generation uses `phpoffice/phpword` and `smalot/pdfparser`.

## Conventions

- `system/` is unmodified CodeIgniter core — do not edit it.
- Tailwind scans `application/views/**/*.{html,php}` only; `assets/output.css` is a build artifact that must be regenerated after adding classes. `assets/build/` and `assets/dist/` are the older Bootstrap/Grunt assets still in use by `admin/header`.
- Every PHP file under `application/` starts with `defined('BASEPATH') or exit('No direct script access allowed');`.
- User uploads land in `uploads/` (`profile-pic/`, `equipment/`).
- `payroll notes` at the repo root is an informal spec/checklist for the payroll schema and screens.
