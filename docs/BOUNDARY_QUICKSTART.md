# Boundary quickstart for new developers

1. Open `SKEL80/kernel/runtime_contract.php` to understand lifecycle phases.
2. Open `SKEL80/kernel/runtime_boundaries.php` to see ownership zones and hotspot map.
3. Open `public/engine/overlay_contract.php` to understand what Colibri is allowed to override.
4. Open `public/engine/BOUNDARY.php`, `public/mvc/BOUNDARY.php`, `public/themes/BOUNDARY.php` to orient yourself quickly.
5. Treat `SKEL80/` as shared platform code unless the boundary docs explicitly say otherwise.
6. Treat `public/engine/` as the first legal place for Colibri-specific runtime behavior.
7. Treat `public/mvc/`, `public/themes/`, `public/languages/` as delivery/presentation territory.
8. Do not call shared runtime helpers from `public/engine/kernel.php` before `SKEL80/run.php` is required; the shared kernel owns runtime helper initialization.

9. For `public/ed_field.php`, reduce duplicate success tails and duplicate update+redirect branches before attempting broader flow cleanup.

11. Runtime URLs and host-dependent links must use `CMS_CURRENT_BASE_URL` or related constants from `public/config.php`; do not hardcode project domains in templates, mail markup, or redirects.
