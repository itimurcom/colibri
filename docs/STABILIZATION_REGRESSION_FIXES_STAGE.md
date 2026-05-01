# M0 / P33 stabilization regression fixes bundle

## Scope
This patch is a stabilization checkpoint after P31/P32/P32B.

## Runtime changes
- `public/.htaccess` now routes `robots.txt` and `sitemap.xml` before broader slash/item rewrites.
- The duplicate lower `RewriteEngine On` was removed; rewrite is already enabled at the top of the file.

## Why this patch exists
The special generated endpoints should be explicit and early in the rewrite file after the robots and sitemap work.

## Manual checks
- `/robots.txt`
- `/sitemap.xml`
- ordinary item URLs
- language root URLs
- HTTP to HTTPS redirect
