# Runtime stabilization stage 2

## Scope
- bootstrap hardening
- config layering with local/secret overlays
- deterministic autoload and discovery order
- runtime error handling and file logging
- PHP 8 compatibility hot spots
- short tag removal in runtime-delivery PHP files

## Notes
- `FEED_LIMIT` behavior from the earlier feed fix is preserved.
- The new runtime baseline keeps shared-core / project-overlay architecture intact.
- `public/config.php` now supports optional array-based overlays:
  - `config.local.php`
  - `config.secrets.php`
  - `config.secrets.local.php`
- runtime log default: `public/logs/php-runtime.log`
