# VK / OK / RUR removal

This bundle removes VK and OK integration points from the project overlay and shared frontend handlers.

Completed:
- OAuth provider config for VK and OK removed.
- Frontend share handlers for VK and OK removed.
- Project social settings for `VK_PAGE` and `OK_PAGE` removed.
- Social links panel now renders only `FB`, `IG`, `TW`.
- RUR/ruble runtime setting removed from project settings and calculator config.
- Legacy VK/OK icon assets marked for removal via root manifest.

Fallback behavior:
- `FEED_LIMIT` and unrelated runtime behavior were not touched.
- Generic social login support for remaining providers stays intact.
