# Lumora verification

Verified locally on 14 October 2026 using PHP 8.3.35, MariaDB 11.4.5, and Node.js 24.19.0.

## Result

- **67 active PHP files passed syntax checks**, including application code, configuration, scripts, tests, and entry points.
- **195 automated checks passed** against a uniquely named disposable database. The complete output is saved in [docs/smoke-results.txt](docs/smoke-results.txt).
- The homepage, catalog, product details, and cart were also inspected in the browser. Adding a product, changing its quantity through the Update button, and removing it were verified. The catalog screenshot is saved in [docs/preview.png](docs/preview.png).
- Both local servers were stopped and restarted through `scripts/start-local.ps1`; `/products` returned HTTP 200 afterward. Re-running the startup script correctly detects running servers.

## Repairs

- Restored the `app/` and `config/` folders expected by the autoloader and database connection.
- Organized templates, shared layouts, assets, setup scripts, logs, and legacy files.
- Added the complete current database schema, rather than importing the incompatible legacy store dump.
- Added Apache routing/private-file restrictions and a PHP development router, plus missing error templates.
- Repaired stylesheet URLs, gallery image queries, placeholder images, and missing shared gold theme variables.
- Preserved raw passwords and FAQ arrays when reading forms, escaping text at rendering time.
- Applied CSRF validation to every POST route, including add/remove cart and logout. Malformed tokens and unexpected array inputs are rejected.
- Added cart ownership checks, product/variant matching, and quantity/stock validation.
- Made checkout re-read current prices and cart contents under a transaction, lock inventory, decrement stock, and roll back unavailable-stock orders.
- Rotated session IDs and CSRF tokens on authentication while retaining the guest-cart key for transfer.
- Fixed the CMS logo file field check and rendered uploaded logos in the storefront header.
- Fixed queries under `ONLY_FULL_GROUP_BY`, repeated product-copy SKU collisions, negative bulk prices, duplicate category handling, and invalid order statuses.
- Moved production credentials out of active source defaults and kept the original configuration private in the archive.

## Automated coverage

The checks cover public page responses, search suggestions, missing pages/products, private-file access restrictions, authentication gates, registration, special-character passwords, session rotation, guest-cart transfer, cart editing/removal, cross-cart access rejection, variants, stock limits, profile escaping, wishlists, custom requests, checkout totals, stock decrement, cart clearing, rollback, admin pages, CMS FAQ arrays, actual logo uploads/header rendering, category creation/deletion, product duplication/deletion, customer soft deletion, bulk prices, and order/custom-request status updates.

Test data and uploads are removed after the run. The intentional insufficient-stock checkout is recorded in the private application log; that entry is expected.

## Limits

This verifies the local application and its current feature set. The hosted database and website were not changed or tested. Apache rewrite rules still require verification on the deployment host; local HTTP checks used the PHP router. No production load/concurrency or external CDN outage testing was performed.

The supplied SQL dump and old standalone scripts belong to a different application model. They remain archived. Existing production customer data needs a separate migration, including a password-reset strategy for the legacy hashes. Missing legacy product photographs cannot be recovered from the supplied files.

The application has cash-on-delivery checkout and the admin functions supplied in the project. It does not include payment gateways, transactional email, courier integrations, or a full product/gallery creation editor. See [README.md](README.md) for setup and deployment instructions.
