# M0 / P1 platform contract and runtime baseline

## Architectural identity

Colibri is a project overlay running on top of the shared `SKEL80` platform kernel.

Historical deployment topology:

- `SKEL80/` — shared kernel reused by multiple projects on the same server
- `public/` — deployable surface of the Colibri project
- `public/engine/` — project bootstrap, overrides, functions, post-run customs
- `public/mvc/` — request delivery layer used by the project runtime
- `public/themes/` — project presentation skin
- `public/languages/` — project localization resources

This patch does **not** rewrite the model. It formalizes the existing runtime contract so later modernization can stay compatible with the original shared-kernel design.

## Shared kernel and overlay contract

### Shared kernel responsibilities (`SKEL80`)

- own the runtime lifecycle in `SKEL80/run.php`
- discover and register shared classes
- apply shared constants and ini defaults
- register shared functions/events
- build the router and user context
- provide fallback defaults only when the project has not defined them

### Project overlay responsibilities (`public` / `public/engine`)

- provide project config and host/database constants
- override path discovery before defaults are finalized
- register project engine bootstrap files
- define project pre-constants and post-ini overrides
- register project functions/hooks before core event functions
- apply project customs after router and user bootstrap
- apply final post-run kernel overlay before `itSite::compile()`

## Formal runtime phase order

The historical runtime is now named as a deterministic phase pipeline.

1. `core.primitives` — load `SKEL80/kernel/core.php`
2. `session.bootstrap` — start session
3. `constants.bootstrap` — bootstrap helper constants
4. `config.resolve` — locate and include `public/config.php`
5. `paths.user` — resolve project paths and optional `public/engine/kernel.path.php`
6. `paths.core` — resolve shared core paths
7. `paths.runtime_defaults` — apply runtime path defaults
8. `classes.register` — register project/core class folders and autoload
9. `engine.register` — include `public/engine/core/engine_*.php`
10. `events.core.pre` — register shared kernel events from `SKEL80/kernel/events/`
11. `const.user.pre` — apply `public/engine/ini/const.*.php`
12. `const.core.post` — apply `SKEL80/events/*/ini/const.php`
13. `defaults.core` — apply fallback defaults in `SKEL80/run.php`
14. `ini.core` — apply `SKEL80/events/*/ini/ini.php`
15. `ini.user.post` — apply `public/engine/ini/ini.*.php`
16. `functions.core.compat` — load `SKEL80/kernel/engine_functions.php`
17. `events.user` — register `public/engine/core/events/**/*.func.php`
18. `events.core.post` — register `SKEL80/events/**/*.func.php`
19. `router.bootstrap` — build `itRouter`
20. `common.core` — apply `SKEL80/events/*/ini/common.php`
21. `user.bootstrap` — build `itUser`
22. `custom.user.post` — apply `public/engine/ini/custom.*.php` and `public/engine/kernel.customs.php`
23. `prepared_arrays.finalize` — prepare moderator arrays when available
24. `kernel.postrun.overlay` — continue executing `public/engine/kernel.php` after shared runtime handoff
25. `site.compile` — `public/index.php` runs `itSite::compile()`

Runtime compatibility baseline additionally introduces:

- `runtime.compat.settings` — apply PHP 8+ runtime settings from config/env
- `runtime.compat.handlers` — install logging, deprecation and fatal-shutdown handlers

## Official overlay points

### Early base config
- File: `public/config.php`
- Phase: `config.resolve`
- Purpose: database, host, theme, base runtime constants

### Path override point
- File: `public/engine/kernel.path.php`
- Phase: `paths.user`
- Purpose: override user/core path discovery before defaults are locked in

### Project engine bootstrap
- Files: `public/engine/core/engine_*.php`
- Phase: `engine.register`
- Purpose: project feature bootstrap and boot-time glue

### Project pre-constants
- Files: `public/engine/ini/const.*.php`
- Phase: `const.user.pre`
- Purpose: constants that must exist before shared post-constants are applied

### Project post-ini overrides
- Files: `public/engine/ini/ini.*.php`
- Phase: `ini.user.post`
- Purpose: project ini/settings overrides after shared defaults

### Project functions and hooks
- Files: `public/engine/core/events/**/*.func.php`
- Phase: `events.user`
- Purpose: register project-first functions before shared event functions

### Project post-router custom layer
- Files: `public/engine/ini/custom.*.php`, `public/engine/kernel.customs.php`
- Phase: `custom.user.post`
- Purpose: customization that needs router/language/user context already available

### Final post-run overlay
- File: `public/engine/kernel.php`
- Phase: `kernel.postrun.overlay`
- Purpose: final project glue after the shared runner has completed

## Precedence model

### Config precedence
1. `public/config.php`
2. optional runtime-specific values computed during bootstrap

### Path precedence
1. explicit overrides from `public/engine/kernel.path.php`
2. default path discovery in `set_skeleton_user_ways()` and `set_skeleton_core_ways()`

### Constant precedence
1. `public/engine/ini/const.*.php`
2. `SKEL80/events/*/ini/const.php`
3. fallback `definition([...])` block inside `SKEL80/run.php`

### Ini/settings precedence
1. `SKEL80/events/*/ini/ini.php`
2. `public/engine/ini/ini.*.php`

### Function precedence
1. `public/engine/core/events/**/*.func.php`
2. `SKEL80/events/**/*.func.php`

Shared functions are skipped when a project function with the same name already exists. This is a key part of the historical project-first overlay contract.

### Custom/post-router precedence
1. `public/engine/kernel.customs.php` (via language bootstrap)
2. `public/engine/ini/custom.*.php`
3. remaining post-run code in `public/engine/kernel.php`

## What this patch changes in code

- adds a declarative runtime contract in `SKEL80/kernel/runtime_contract.php`
- names the shared lifecycle phases directly inside `SKEL80/run.php`
- marks the `public/engine/kernel.php` continuation as `kernel.postrun.overlay`
- documents the contract for further modernization without changing the historical deployment model

## What this patch intentionally does not change yet

- no behavioral rewrite of shared kernel logic
- no relocation of folders
- no controller/view renaming campaign
- no presentation cleanup yet
- no deprecation cleanup yet

Those belong to the next steps of `M0 / P1` after the contract is fixed and explicit.

## Runtime compatibility baseline (M0 / P2)

This stage keeps the historical shared-kernel model intact and hardens the runtime for modern PHP.

### Included in this baseline
- short open tags migrated from `<?` to `<?php` across the runtime PHP surface
- deprecated `each()` removed from legacy PHPMailer sources bundled in `SKEL80/classes/mailer/`
- `public/config.php` now supports optional `config.secrets.php` / `config.secrets.local.php` overlays and environment-variable overrides
- runtime error reporting, display/log policy and log file path are now driven from config/env instead of hard-coded suppression in `public/engine/kernel.php`
- runtime logging baseline added via `SKEL80/kernel/runtime_compat.php`
- shared-kernel handoff in `public/engine/kernel.php` now uses a normalized absolute path instead of a fragile relative include

### New config overlay files
- `public/config.secrets.php` — ignored, optional secret/project-local overrides
- `public/config.secrets.local.php` — ignored, optional machine-local overrides
- `public/config.secrets.example.php` — documented template with placeholders

### Default runtime behavior
- `CMS_ERROR_REPORTING` defaults to `E_ALL`
- `CMS_DISPLAY_ERRORS` defaults to `1` in `dev`, `0` in `prod`
- `CMS_LOG_ERRORS` defaults to `1`
- `CMS_RUNTIME_LOG_FILE` defaults to `public/logs/php-runtime.log`

These defaults can be overridden by config secrets files or environment variables without changing the shared-core contract.
