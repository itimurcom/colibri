# Platform contract: SKEL80 + Colibri overlay

## Identity
- **SKEL80** is the shared platform kernel.
- **Colibri** is a project overlay that configures and extends the kernel.
- The system is not organized around MVC as a primary architectural truth.
- The real contract is **shared core + project overlay + delivery surface**.

## Runtime phases
1. `bootstrap.core`
2. `bootstrap.config`
3. `bootstrap.paths`
4. `bootstrap.overlay.contract`
5. `bootstrap.classes`
6. `bootstrap.engine`
7. `bootstrap.const`
8. `bootstrap.ini`
9. `bootstrap.functions`
10. `bootstrap.router`
11. `bootstrap.common`
12. `bootstrap.user`
13. `bootstrap.customs`
14. `delivery.controllers`
15. `delivery.views`
16. `delivery.theme`

## Ownership model
- `SKEL80/` => shared platform core
- `public/index.php`, `public/config*.php` => project bootstrap surface
- `public/engine/` => project runtime overlay
- `public/mvc/`, `public/themes/`, `public/languages/` => project delivery surface
- `public/ed_field.php`, `public/more.php`, `SKEL80/kernel/engine_functions.php` => transitional mixed hotspots

## Legal override points
- `public/config*.php`
- `public/engine/kernel.path.php`
- `public/engine/overlay_contract.php`
- `public/engine/ini/const.*.php`
- `public/engine/ini/ini.*.php`
- `public/engine/ini/custom.*.php`
- `public/engine/core/engine_*.php`
- `public/engine/core/events/**/*.func.php`
- `public/engine/kernel.customs.php`
- `public/mvc/*`
- `public/themes/*`
- `public/languages/*`

## Illegal override examples
- project-specific business logic directly in `SKEL80/kernel/*`
- project-only delivery behavior inside `SKEL80/classes/*`
- theme-level files redefining shared bootstrap rules

## Transitional note
The goal of Stage 3 is not to rewrite the tree. The goal is to make the **existing ownership and extension model explicit** so a new developer can enter the codebase and immediately see where shared responsibilities end and project responsibilities begin.
