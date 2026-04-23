# M0 / P28 robots dynamic entrypoint and manifest restore

## Scope
- Add dynamic `public/robots.php`
- Route `robots.txt` to `robots.php` through `public/.htaccess`
- Restore root-level `remove-paths-from-manifests.sh`
- Add root-level remove manifest for `public/robots.txt`

## Why
The project should not keep a hardcoded old production domain inside `robots.txt`.
`robots.txt` must be served for the current runtime host.

## Notes
- `public/robots.txt` is kept only as a neutral fallback.
- The intended cleanup path is to remove `public/robots.txt` using the root manifest script.
