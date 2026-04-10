# Feed total count runtime note

## M0 / P16 real feed total count bundle

This patch fixes the semantic mismatch in `SKEL80/classes/blocks/itFeed.class.php` where:
- the feed data query was loaded with `LIMIT`
- `count_all()` returned `mysqli_num_rows()` from that limited result set

After this patch:
- SQL feeds still load the current page/block with `LIMIT`
- total count for `count_all()` is calculated separately without `LIMIT`
- explicit feed `limit` has priority
- global `FEED_LIMIT` is used only as fallback when a feed does not define `limit`

For the mail history panel:
- `public/engine/core/engine_mails.php` now passes explicit `limit` from `FEED_NUMBER['mailing_history']`
- this prevents oversized DB loading while preserving correct totals
