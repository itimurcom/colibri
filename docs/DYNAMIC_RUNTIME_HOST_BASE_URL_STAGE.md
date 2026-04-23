# M0 / P26 runtime host/base URL de-hardcoding

## Goal
Remove hardcoded `atelie-colibri.com` / `atelier-colibri.com` site URLs from runtime code, templates, and rewrite rules.

## What was changed
- Added dynamic runtime URL constants in `public/config.php`:
  - `CMS_CURRENT_SCHEME`
  - `CMS_CURRENT_HOST`
  - `CMS_CURRENT_HOST_NO_PORT`
  - `CMS_CURRENT_EMAIL_DOMAIN`
  - `CMS_CURRENT_BASE_URL`
  - `CMS_CURRENT_BASE_URL_SLASH`
- Replaced hardcoded site URLs in page templates, item markup, user PIN flows, mail header templates, test controller code, and OAuth redirect configuration.
- Removed canonical host rewrites in `public/.htaccess` that forced requests to `atelier-colibri.com`.
- Removed hardcoded `Host` / `Sitemap` target from `public/robots.txt` to avoid binding robots output to a legacy domain.

## Intentionally not changed
- Existing email mailbox names such as `ateliecolibri@gmail.com` were left intact because they are mailbox identifiers, not site URLs.
- Existing asset file names like `ateliecolibri-ua.png` were left intact because they are file names, not site URLs.
