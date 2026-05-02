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
14. In `SKEL80/kernel/engine_functions.php`, prefer local shared primitives for repeated string/date/random/json helper logic instead of adding broader runtime layers.
15. For `itForm2.class.php`, keep field builder cleanup local to the class and use canonical `add_*` method names only.

## Recent cleanup stages

- `M0 / P37 itForm2 field builder regression hotfix`: keeps canonical method names and restores safe defaults for list-like field creation.
- `M0 / P39 itFeed and engine_items safe cleanup bundle`: carries visual `itFeed` cleanup plus a small safe `engine_items` cleanup without route/feed behavior changes.
- `M0 / P40 engine_functions third cleanup pass`: removes stale visual noise and folds repeated random/json helper logic back into local shared primitives.

## Comment cleanup rule
Broad comment cleanup may remove generated CRC metadata and decorative separator lines, but it must not remove behavior notes, TODO/FIXME markers, license text, or comments that document runtime constraints. See `docs/PROJECT_COMMENT_NOISE_CLEANUP_STAGE.md`.

## M0 / P49 itForm2 controlled runtime consolidation note
`itForm2.class.php` now shares local field row/layout/render preparation between `_view_fields()` and `_edit_fields()`. Keep future form cleanup inside this class unless a later migration defines a real form boundary; do not reintroduce alias renames or field-storage changes during M0.

## M0 / P52 editor events note
`SKEL80/events/editor/editor_events.func.php` now reuses local event helpers for repeated editor creation and store+reload responses. Keep editor event cleanup local during M0; do not introduce a separate editor event dispatcher layer.

## M0 / P55 itMySQL strict-mode second pass note
`SKEL80/classes/system/itMySQL.class.php` now uses one local DB row normalization path for the main read methods. Keep DB compatibility cleanup inside this class unless a later migration explicitly defines a real DB boundary.
