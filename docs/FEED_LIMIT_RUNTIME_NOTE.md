# FEED LIMIT runtime note

## M0 / P10 feed limit performance hotfix

- Global runtime constant `FEED_LIMIT` was reduced from `10000` to `100`.
- Reason: legacy `itFeed` SQL still uses global `FEED_LIMIT` in `LIMIT offset,count` and ignores the per-feed `$this->limit` value in the main query path.
- Impact: pages with multiple feeds, including mailing history, stop trying to pull excessively large batches from MySQL on initial load.
- Current scope: only the global batch cap was reduced.
- Follow-up candidate: wire `itFeed::$limit` into SQL so each feed can enforce its own DB-level batch size.
