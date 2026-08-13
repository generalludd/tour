# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

"Tour" — a tour-management system for a baseball tours operator (address book, tours, payers/tourists, hotels, rooming lists, letter merge, payment tracking). Built on **CodeIgniter 3**, vendored in `web/system`; all application code is in `web/application`. Docroot is `web/`.

## Commands

DDEV provides the local environment (project name `tours`, MariaDB 10.6, nginx-fpm):

```bash
ddev start          # boot containers
ddev launch         # open the site
ddev ssh            # shell in the web container (project mounts at /var/www/html)
ddev phpmyadmin     # phpMyAdmin (addon; its generated files are gitignored)
ddev import-db --file=db-backups/<file>.sql.gz
```

CSS build — Node lives in the web container, so run it through DDEV (or from `web/` if you have Node on the host):

```bash
ddev exec -d /var/www/html/web npm run build       # webpack, development mode + source maps
ddev exec -d /var/www/html/web npm run build:prod  # production
ddev exec -d /var/www/html/web npm run watch
```

Sass emits "legacy JS API is deprecated" warnings on every entry point; those are expected, not a broken build.

Webpack compiles **each** `web/source/scss/*.scss` into `web/css/<name>.css` — one entry point per file, generated dynamically. It does **not** touch `web/js/*`. `web/css/` and `web/node_modules/` are gitignored, so CSS must be rebuilt after pulling SCSS changes.

**There is no automated test suite.** `composer.json`'s `test:coverage` script points at `tests/travis/sqlite.phpunit.xml`, which does not exist in this repo. Verification is manual — follow the checklist in `web/testing-releases.md`.

## Required local config (gitignored)

- `web/application/config/database.local.php` — `include`d by `config/database.php`; must define the `$db['default']` array (`hostname`, `username`, `password`, `database`, …).
- `web/application/config/email.local.php` — SMTP settings for the `email` library used by `Auth` (password reset).

`web/tour.sql` is the reference schema dump.

## Deployment

Push to `main` triggers `.github/workflows/deploy.yml`: SSH to `db.ballparktours.net`, `git pull`, `npm ci`, `npm run build`. Note what deploy does **not** do — no `composer install`, and no schema migration step. Database changes reach production only through the update mechanism below.

## Architecture

### Request flow and the two view wrappers

Standard CI 3 routing (`config/routes.php` has no custom routes; `default_controller` is `index`, `index_page` is empty so URLs are `/<controller>/<method>/<args>`).

Controllers build a `$data` array and load one of two wrappers:

- `page/index` — full page (header, navigation, utility bar, flashdata messages, footer)
- `page/modal` — bare modal markup

Both then `$this->load->view($target)`, where `$data['target']` is the inner view path (e.g. `tour/list`). The near-universal idiom:

```php
if ($this->input->get('ajax')) { $this->load->view('page/modal', $data); }
else { $this->load->view('page/index', $data); }
```

Other `$data` keys read by the wrappers: `title` (page/browser title), `styles` (array of extra CSS bundle names, e.g. `['table']` → `css/table.css`), `scripts` (extra script URLs), `print` (suppresses chrome for print views). `page/head.php` always loads `main`, `popup`, `messages`, `buttons`, `elements` plus `print.css`; jQuery/jQuery UI come from the Google CDN.

Client side (`web/js/`, hand-written, not bundled). `general.js` has two halves: a modern `DOMContentLoaded` block using vanilla JS with delegated listeners bound to `#page`, and a legacy `$(document).ready` jQuery block below it (`payer.js` and `payment.js` are also jQuery). New JS should go in the vanilla block. Delegated link conventions:
- link `class="dialog"` → refetches the same href with `ajax=1` and injects the response into a popup
- link `class="inline"` → refetches with `ajax=1` expecting JSON `{id, value}` and replaces that element's HTML
- input `class="update-value"` with `data-url` → on `change`, POSTs `field`/`value` to that URL

So a controller method usually needs to serve three shapes: full page, modal fragment, and (for `update_value`/`get_value`-style methods) a bare echo or JSON.

### Base classes

- `core/MY_Controller.php` — every controller extends this **except `Auth`** (which extends `CI_Controller` so login is reachable). Its constructor enforces auth via `is_logged_in($this->session->all_userdata())`, bakes a `uri` cookie so login can return the user to where they were, loads `variable_model` as `$this->variable`, and defines the `BACKUP_STATUS` constant (seconds since the last logged backup, compared against `BACKUP_THRESHOLD` = 2 weeks) which drives the "back up the database" nag.
- `core/MY_Model.php` — thin: `_get()`, `_get_value()`, `_get_all()`, `_log()`, and `keyed()` (re-index a result set by a column). Most query logic lives in the concrete models.

Models are deliberately fat and web-aware: they declare public properties mirroring DB columns and a `prepare_variables()` method that reads `$this->input->post()` directly, so controllers often just call `$this->model->insert()` / `update($id)` with no explicit data passing. Models are loaded with short aliases — `$this->load->model('tour_model', 'tour')` → `$this->tour`.

Read-heavy getters compose aggregates: `Tour_model::get()` eager-loads payers (via `Payer_model::getPayers()`) and hotels, and sums `total_paid`, `total_due`, `total_discount`, `total_surcharge`, `total_payers`, `total_tourists`, `total_cancels` onto the returned tour object. Prefer these over recomputing in a view.

### Helpers do the real work — check before writing new code

Autoloaded helpers: `form`, `url`, `file`, `date`, `auth`, `general`, `interface`, `cookie`.

- `helpers/general_helper.php` — formatters (`format_date`, `format_timestamp`, `format_money`, `format_address`, `format_person`, `format_salutation`, `grammatical_implode`, `person_link`, `create_link`) **and the pricing rules**: `get_tour_price()`, `get_room_rate()`, `get_room_size()`, `get_amount_due()`, `get_payment_due()`. Money math belongs here, not in models or views — reuse these so totals stay consistent with `Tour_model`'s aggregates.
- `helpers/interface_helper.php` — HTML generation: `create_button()` / `create_button_bar()` (button bars are declared as arrays of `selection`/`text`/`href`/`class`/`title` — see `views/page/utility.php`), `create_field()`, `edit_field()`, `create_input()`, `create_dropdown()`, `create_checkbox()`, `get_page_title()`.
- `helpers/tour_helper.php` — shirt-count tallies; `helpers/auth_helper.php` — `is_logged_in()`; `helpers/message_helper.php` — letter-merge token replacement.

### Field rendering — three generations coexist

When touching a detail/edit view, match what that view already uses; when adding a new view, prefer the newest.

1. **Newest — view partials.** `views/elements/field-item.php` (display) and `input-field.php` / `select-field.php` (forms), loaded with a data array (`id`, `value`, `label`, `wrapper`, `classes`, `wrapper_classes`). Used by `person/view.php`, `tour/view.php`, `hotel/view.php`, `address/view.php`, `contact/list.php`. **Use these for new work.**
2. `create_field()` in `interface_helper.php` — display-only field with optional format handling (`url`, `email`). Only `views/user/view.php` uses it.
3. **Legacy — jQuery click-to-edit.** `edit_field($field_name, $value, $label, $table, $id)` emits an envelope with id `"{table}__{field}__{id}"` (double-underscore delimiter, split in `general.js`) wrapping a `<span class="edit-field field">`. Clicking swaps in an input; saving POSTs to `<table>/update_value`. Only `views/room/edit.php` and `views/merge/edit.php` remain.

Whichever front end is used, the server side converges on a controller `update_value()` method (present in 11 controllers): read `id`/`field`/`value` from `$this->input->post()`, coerce by field-name convention (fields containing `price`/`rate` → int, containing `date` → `format_date(..., 'mysql')`), save, and echo the value back for the JS to reinsert.

### Schema changes: `Update.php`, not migrations

CI migrations are disabled (`config/migration.php`). Instead, `controllers/Update.php::index()` holds an ordered array of `['id' => n, 'query' => '…', 'description' => '…']`. `Update_model::run_updates()` creates an `updates` table if needed and applies only ids not already recorded, then the controller redirects to `person`.

To change the schema: **append a new entry with the next unused id** — never edit or renumber existing entries, since applied ids are keyed by number. `Index::index()` redirects `/` to `/update`, so pending updates run on the next visit to the site root, which is how they land in production.

### Auth and roles

Homegrown, not ion_auth. `Auth` controller + `Auth_model`; sessions use the `database` driver in table `user_sessions`. A session is considered logged in when userdata has `username`, `role`, and `user_id`. `role == 'admin'` gates user creation in `User.php`. Note the config posture: `csrf_protection`, `global_xss_filtering`, `cookie_secure`, and `cookie_httponly` are all off, and `enable_hooks` is off. Login/logout attempts are recorded via `Auth_model::log()` / `Logging_model`.

### Backup

`controllers/Backup.php` streams `mysqldump | gzip` to a temp file via `exec()` using a 0600 `--defaults-file` (deliberately not `--defaults-extra-file`, so a host `~/.my.cnf` can't override the credentials), then `force_download()`s it and records success/failure via `Logging_model`. It has extensive comments explaining why — read them before changing it. Downloaded backups are kept in `db-backups/` (gitignored).

### Domain model

`person` is the hub. Key relationships:

- `person.address_id` → `address`; an address can be shared by several people ("housemates"). Deleting an address only removes the row if no one else references it, otherwise it just detaches — see `Person::remove_address()`.
- `phone` ↔ `person` via the `phone_person` join table.
- `payer` — composite PK `(payer_id, tour_id)`: a person who pays for a tour, carrying `payment_type`, `room_size`, `discount`, `surcharge`, `amt_paid`, `is_comp`, `is_cancelled`.
- `tourist` — composite PK `(tour_id, person_id)` plus `payer_id`: a guest travelling on a payer's ticket. A payer normally also has a `tourist` row for themself.
- `hotel` rows belong to a tour ("stays"); `room` + `roommate` build the printable rooming list, which can be duplicated from the previous stay.
- `letter` / `message` — per-tour letter templates merged against a payer's data.
- `variable` — generic key/value lookup table read through `Variable_model` (e.g. `user_role`, dropdown option sets); pair with `get_keyed_pairs()` for select lists.
- `user`, `user_log`, `user_sessions`, `receipt`.

Because payer/tourist use composite keys, "delete a person" is usually a soft disable: `person.status`/`tour.status` are flipped when history exists, and hard deletes are only allowed for records never attached to a tour.

### Conventions and gotchas

- The tour detail page is `tourist/view_all/{tour_id}`. `Tours::view()` is marked `@deprecated` and just redirects there.
- `APP_VERSION` lives in `config/constants.php` — bump on release.
- Views are `views/<entity>/<action>.php`; small reusable fragments live in `views/elements/` and `views/dialogs/`.
- Tabs for indentation in PHP; user feedback goes through `$this->session->set_flashdata('notice'|'alert'|'warning', …)`, rendered by `views/page/messages.php`.
- Commit messages typically reference the GitHub issue: `Issue #170 fix bug to allow bus driver to be searched.`
- Whenever jquery is encountered, it should be updated to use plain javascript
- Javascript should only use data attributes for modal and other tasks. The old use of added url query parameters is no longer correct.
- Any javascript that uses parsed urls or query parameters should be switched to use data attributes. If the view does not contain the needed data attributes thy should be added. 
- Whenever possible all javascript should be reusable as much as possible. 
- As much as possible make the site PHP 8.3 compatible. This includes changing the code-igniter core files. 