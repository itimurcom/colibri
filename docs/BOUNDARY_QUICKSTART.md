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

## M0 / P56 runtime hotzone stabilization note
After editor/form/DB cleanup passes, stabilization patches may fix discovered regressions and add narrow guards inside the same runtime files. Do not use this stage to introduce new framework layers or move storage behavior.

## M0 / P57 catalog/feed note
`itFeed.class.php` and `engine_items.php` now keep catalog/feed callback resolution and item-card request guards local. Do not introduce a separate feed framework during M0; keep future catalog/feed cleanup inside the existing runtime files unless a real boundary is defined.

## M0 / P58 editor media/gallery second pass note
Gallery event cleanup must stay inside `SKEL80/events/editor/editor_events.func.php` and `SKEL80/classes/editor/itEditor.class.php` during M0. Keep the old public editor event names and gallery method names stable.

## M0 / P59 customer/auth runtime note
Customer/auth cleanup stays project-side under `public/engine/core/units/users/`. Keep public event function names stable and avoid creating a separate auth framework during M0.

## M0 / P60 user admin/social cleanup note
`public/engine/core/units/users/*` remains project-side runtime. Keep admin/customer/social helper consolidation local to these files and do not introduce a new auth/admin framework layer during M0.

## M0 / P61 order/contact controller note
Order/contact/measurement/register controllers are project-side MVC flow files. Keep cleanup local to controller runtime unless a later migration defines a real shared form-page boundary.

## M0 / P62 item filter cleanup note
`public/engine/core/units/items/engine_filters.php` is still project-side catalog runtime. Keep filter session keys and JS entrypoints stable; cleanup should stay local unless a later item/catalog boundary is explicitly defined.

## M0 / P63 controller cleanup note
Project-side controller cleanup may add local helpers inside the controller file when that reduces repeated rendering/flow code. Do not move these helpers into shared-core during M0.

## M0 / P64 recent controller stabilization note
After broad project-side controller cleanup, verify helper functions that use globals inside function scope. In PHP, controller-local helper functions must explicitly declare globals such as `$_USER` when they use them.

## M0 / P65 form page controller guard note
Contact, order, measurement, register and pin controllers should avoid direct optional `$_REQUEST[...]` reads on normal page loads. Keep request guards local to the controller and do not move them into shared-core during M0.

## M0 / P66 controller entrypoint note
Project-side MVC controllers may have local helper functions when that reduces repeated request guards or page assembly. Do not turn this into a new controller framework during M0.

## M0 / P67 controller residual request stabilization note
Project-side controllers should not read optional `$_REQUEST` keys directly when the route can be opened without those parameters. Keep controller request guards local and do not introduce a new controller framework during M0.

## M0 / P68 navigation/settings/session note
Project-side navigation/settings/session helpers may be cleaned up locally in `public/engine/core/engine_*.php`, but do not move this runtime into a new framework layer during M0.

## M0 / P69 wizard/object runtime note
`public/engine/core/classes/wizards/itWizard.class.php` and `itObject.class.php` remain project-side runtime classes. Keep cleanup local to these files unless a later migration defines a real wizard/object boundary.
