# Boundary quickstart for new developers

1. Open `SKEL80/kernel/runtime_contract.php` to understand lifecycle phases.
2. Open `SKEL80/kernel/runtime_boundaries.php` to see ownership zones and hotspot map.
3. Open `public/engine/overlay_contract.php` to understand what Colibri is allowed to override.
4. Open `public/engine/BOUNDARY.php`, `public/mvc/BOUNDARY.php`, `public/themes/BOUNDARY.php` to orient yourself quickly.
5. Treat `SKEL80/` as shared platform code unless the boundary docs explicitly say otherwise.
6. Treat `public/engine/` as the first legal place for Colibri-specific runtime behavior.
7. Treat `public/mvc/`, `public/themes/`, `public/languages/` as delivery/presentation territory.
8. Do not call shared runtime helpers from `public/engine/kernel.php` before `SKEL80/run.php` is required; the shared kernel owns runtime helper initialization.
9. `public/engine/kernel.path.php` is an optional extension point. `SKEL80/run.php` loads it only when the file actually exists.
10. For `public/ed_field.php`, reduce duplicate success tails and duplicate update+redirect branches before attempting broader flow cleanup.
11. For runtime absolute links, prefer `CMS_CURRENT_BASE_URL`, `CMS_CURRENT_BASE_URL_SLASH`, `CMS_CURRENT_HOST`, and `CMS_CURRENT_EMAIL_DOMAIN` instead of hardcoded project domains or direct `$_SERVER['HTTP_HOST']` string assembly.
12. `public/robots.php` is the dynamic entrypoint for `robots.txt`; keep the `.htaccess` rewrite and runtime-host output aligned.
13. Keep generated endpoint rewrites for `robots.txt` and `sitemap.xml` before broader route rewrites in `public/.htaccess`.
14. In `SKEL80/kernel/engine_functions.php`, prefer local shared primitives for repeated string/date/random helper logic instead of adding broader runtime layers.
11. For `itForm2.class.php`, keep field builder cleanup local to the class and preserve all public `add_*` method names.

### itForm2 canonical method names
Use canonical `itForm2` add-method names only. Legacy synonym names were removed in `docs/ITFORM2_CANONICAL_METHOD_NAMES_STAGE.md`.

11. `M0 / P37 itForm2 field builder regression hotfix` keeps P36 canonical method names, but restores safe defaults for `SELECT` and `SET` editor field creation.
