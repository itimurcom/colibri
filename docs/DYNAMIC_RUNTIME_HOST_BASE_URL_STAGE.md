# M0 / P29 remaining runtime host dehardcoding bundle

## Goal
Remove remaining hardcoded `atelie-colibri.com` / `atelier-colibri.com` runtime references and replace them with the current runtime host address.

## Runtime constants
Added in `public/config.php`:
- `CMS_CURRENT_SCHEME`
- `CMS_CURRENT_HOST`
- `CMS_CURRENT_HOST_NO_PORT`
- `CMS_CURRENT_EMAIL_DOMAIN`
- `CMS_CURRENT_BASE_URL`
- `CMS_CURRENT_BASE_URL_SLASH`

## Scope
Patched files:
- `public/config.php`
- `public/languages/en.php`
- `public/languages/ru.php`
- `public/languages/ua.php`
- `public/languages/ua.bak.php`
- `public/engine/core/units/users/engine_customers.php`
- `public/engine/core/units/users/events/engine_customer_events.php`
- `public/engine/ini/const.oAuth.php`
- `public/mvc/controllers/controller.settings.php`
- `public/test_mail.php`
- `public/mvc/controllers/controller.test.php`
- `public/engine/core/units/items/engine_items.php`
- `public/.htaccess`
- docs

## What intentionally did not change
- `public/robots.txt` is not part of this bundle; that path is handled separately by the dynamic robots entrypoint work.
- No file removals are included in this patch.
