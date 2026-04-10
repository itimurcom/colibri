# Feed limit runtime note

## M0 / P11 feed query limit explicit override

- `itFeed` now uses an explicitly passed `limit` value in SQL `LIMIT offset,count`.
- Global `FEED_LIMIT` remains the fallback only when a feed does not provide its own `limit`.
- `mailing_history_panel()` now passes an explicit DB limit derived from `FEED_NUMBER['mailing_history']`, so the mails section does not over-fetch rows from the database.
- No feed rendering logic was removed; this patch only fixes SQL limit resolution and mailing-history feed wiring.
