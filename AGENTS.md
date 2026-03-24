# AGENTS.md

This file gives coding agents a practical map of the `wordpress-plugins` repo.
It reflects the current repository, not generic WordPress advice.

## Scope

- Repo root contains two WordPress plugins plus release helpers.
- Main plugin paths:
  - `anytrack-affiliate-link-manager/trunk`
  - `anytrack-for-woocommerce/trunk`
- Each plugin uses WordPress.org SVN-style layout: `trunk/` for code, `assets/` for wp.org assets.
- There is no root package manager or PHP toolchain config: no `package.json`, `composer.json`, PHPUnit config, ESLint config, or PHPCS config in the repo.

## Rule Files

- No `.cursor/rules/` directory was found.
- No `.cursorrules` file was found.
- No `.github/copilot-instructions.md` file was found.
- There are no repo-specific Cursor or Copilot rule files to merge into agent behavior.
- `.github/workflows/opencode-review.yml` exists, but its prompt references an unrelated AnyTrack Nx monorepo; treat it as stale and not authoritative for this repo.

## Repository Layout

- `README.md` documents local development and release at a high level.
- `generate-zips.sh` packages both plugins into `releases/`.
- `release.sh <pluginName> <version>` publishes a plugin to WordPress SVN.
- `scripts/copy-to-trafficker.sh` and `scripts/copy-from-trafficker.sh` sync code to/from a local DevKinsta WordPress install.
- `svn/` is a temporary checkout target used by `release.sh`.

## Architecture

- Both plugins are classic procedural WordPress plugins with a small bootstrap class in the main plugin file.
- Most behavior lives in `modules/*.php` files included from the plugin entrypoint.
- WordPress hooks are the primary composition mechanism: `add_action`, `add_filter`, AJAX actions, and activation hooks.
- Admin UI is largely rendered as concatenated HTML strings in PHP helper classes.
- JavaScript is plain jQuery enqueued via `wp_register_script` / `wp_enqueue_script`.
- There are no namespaces, Composer autoloading, dependency injection containers, or modern typed PHP patterns.

## Build, Lint, And Test Commands

There is no compile step, dependency install step, or formal build pipeline.

- Package both plugins:
  - `bash generate-zips.sh`
- Release to WordPress SVN:
  - `bash release.sh anytrack-for-woocommerce 1.5.7`
  - `bash release.sh anytrack-affiliate-link-manager 1.5.5`
- Sync repo -> local DevKinsta install:
  - `bash scripts/copy-to-trafficker.sh`
- Sync local DevKinsta install -> repo:
  - `bash scripts/copy-from-trafficker.sh`

There is no configured lint runner. Use syntax checks instead:

- Lint one PHP file:
  - `php -l anytrack-for-woocommerce/trunk/modules/hooks.php`
- Lint all PHP files in one plugin:
  - `rg --files anytrack-for-woocommerce/trunk -g '*.php' | xargs -I{} php -l "{}"`
  - `rg --files anytrack-affiliate-link-manager/trunk -g '*.php' | xargs -I{} php -l "{}"`
- Optional manual style check if `phpcs` happens to be installed globally:
  - `phpcs --standard=WordPress anytrack-for-woocommerce/trunk`

There is no automated test suite checked in:

- No PHPUnit setup was found.
- No WordPress test scaffold was found.
- No JS unit test runner was found.

## Single-Test Guidance

There is no true "run one test" command in this repo.
Closest equivalents:

- Single-file syntax check:
  - `php -l anytrack-affiliate-link-manager/trunk/modules/ajax.php`
- Single behavior manual test in local WordPress:
  - redirect flow: create or edit one `custom_redirect` item and visit the source URL
  - WooCommerce flow: run one product view, add-to-cart, checkout, or thank-you scenario
- Single plugin smoke test:
  - activate only the touched plugin in a local WordPress instance and verify the affected admin/front flow
- Packaging smoke test:
  - `bash generate-zips.sh`

## Local Development Expectations

- The repo expects development against a real WordPress instance, not mocks.
- DevKinsta paths are hardcoded in the sync scripts.
- README mentions staging/production Kinsta sync as a possible workflow, but agents should avoid remote or destructive actions unless explicitly asked.
- There is no repo-local cache or asset build to clear; changed plugin files take effect immediately in the target WordPress install.

## PHP Style Guidelines

- Follow the style already used in the file you touch; do not normalize the whole plugin.
- New entry files should start with `<?php` and guard direct access with an `ABSPATH` check.
- Keep one top-level responsibility per module file: hooks, AJAX handlers, settings, helper functions, scripts, CPT definitions.
- Prefer WordPress APIs over raw PHP when an equivalent helper already exists.
- Avoid introducing namespaces, traits, autoloaders, Composer-only patterns, or inconsistent scalar type hints / return types.

## Includes, Naming, And Structure

- There are no modern imports; plugin files are wired together with `include` from the main bootstrap file.
- When adding a module, add it to the include list in the plugin entrypoint and keep paths relative to the plugin root.
- Match existing prefixes:
  - `anytrack_for_woocommerce_*` for WooCommerce plugin functions
  - `aalm_*` for Affiliate Link Manager functions
  - legacy class names like `aalmFormElementsClass` are normal here
- Keep option keys and meta keys in snake_case, matching existing names such as `waap_options`, `source_url`, and `_anytrack_processed`.
- Avoid generic global function names that could collide with WordPress or another plugin.

## Formatting Conventions

- Indentation is inconsistent across the repo; some files use tabs, some spaces.
- Preserve the dominant indentation and brace style of the file you edit.
- Keep array syntax aligned with the local file; both `[]` and `array()` exist.
- Avoid broad formatting-only diffs.
- Keep edits surgical; these plugins are heavily hook-driven and run in live WordPress flows.

## Types And Data Handling

- Expect loose arrays and scalar strings from WordPress APIs, `$_POST`, `$_GET`, options, post meta, cookies, and WooCommerce objects.
- Guard optional values with `isset` before use.
- Cast IDs and counters explicitly when needed with `(int)` or `intval()`.
- Prefer simple arrays and booleans over introducing DTOs or new abstraction layers.
- When building payloads, append keys incrementally in the same style as nearby code.

## Error Handling And Security

- Sanitize request data before saving or sending it.
- Escape output when inserting dynamic values into HTML, attributes, or URLs.
- For AJAX and forms, use `check_ajax_referer` or `wp_verify_nonce`.
- For privileged admin actions, check capabilities with `current_user_can`.
- Prefer early returns or `wp_die` for invalid state, failed auth, or bad input.
- Use `is_wp_error` when inspecting WordPress HTTP results.
- Preserve existing redirect semantics such as `wp_redirect(..., 302)` unless requirements change.
- Be careful with superglobals: many handlers read `$_POST` and `$_GET` directly, so tighten validation rather than expanding unsafe access.

## WordPress, WooCommerce, And JS Conventions

- Prefer hook-based extensions over editing unrelated control flow.
- Use `get_option`, `update_option`, `get_post_meta`, and `update_post_meta` for persistence.
- For WooCommerce data, prefer getters such as `$order->get_total()` and `wc_get_product()`.
- Keep plugin textdomain usage aligned with the plugin-local prefix already used in each file.
- Reuse localized script globals already present in the repo, especially `aalm_local_data` and `wpafw_local_data`.
- JavaScript should stay as plain jQuery wrapped in `jQuery(document).ready(function($){ ... })`.
- Do not introduce React, TypeScript, ESM modules, bundlers, or new frontend dependencies unless explicitly requested.

## HTML/Admin UI Conventions

- Many admin screens are intentionally built by concatenating HTML strings in PHP classes.
- Preserve that legacy approach unless the task explicitly calls for a UI rewrite.
- Escape dynamic visible text and attribute values.
- Reuse the existing Bootstrap-like utility classes already shipped with plugin assets instead of adding a new CSS framework.

## Agent Working Rules

- Before claiming a command exists, verify it from repository files.
- Do not invent Composer, NPM, PHPUnit, ESLint, or CI workflows that are not present.
- If asked to "run tests", explain that validation is manual/syntax-based unless you also add a test harness.
- Avoid broad modernization; prefer minimal changes that fit the current plugin architecture.
- Be especially careful in redirect, AJAX, order-hook, and admin-delete code paths; validate nonce, capabilities, sanitization, and redirect targets.

## Good Final Verification Checklist

- Run `php -l` on every changed PHP file.
- If packaging changed, run `bash generate-zips.sh`.
- If WordPress behavior changed, verify the exact affected admin/frontend flow in a local WordPress install.
- If WooCommerce behavior changed, test the narrowest affected scenario: product view, add to cart, checkout, thank-you page, or order update.
