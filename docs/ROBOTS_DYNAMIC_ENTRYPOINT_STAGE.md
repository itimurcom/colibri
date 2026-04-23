# M0 / P27 robots dynamic entrypoint

## Scope
- add `public/robots.php`
- route `robots.txt` to `robots.php` in `public/.htaccess`
- register `public/robots.txt` removal through a root remove manifest
- restore the root helper script `remove-paths-from-manifests.sh`

## Why
The project must not stay hard-bound to `atelier-colibri.com` inside `robots.txt`.
A dynamic entrypoint builds `Host` and `Sitemap` using the runtime host.

## Removal workflow
Run from project root:

```bash
./remove-paths-from-manifests.sh --project-root .
```

This removes `public/robots.txt` using `M0_P27_robots_dynamic_entrypoint_REMOVE_MANIFEST.txt`.
