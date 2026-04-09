# M0 / P3 explicit shared/project boundaries

## Purpose

This patch makes the historical split between the shared `SKEL80` kernel and the Colibri project overlay explicit.

It does **not** change the top-level layout:

```text
SKEL80/
public/
  index.php
  config.php
  engine/
  mvc/
  themes/
  languages/
```

Instead, it names ownership zones, dependency rules, official extension points and the historical mixed hotspots that should remain controlled during further modernization.

## Ownership zones

### 1. Shared core (`SKEL80`)

Owned by the platform kernel and intended for reuse across projects.

Main paths:
- `SKEL80/kernel/`
- `SKEL80/classes/`
- `SKEL80/events/`
- `SKEL80/css/`
- `SKEL80/js/`
- `SKEL80/sql/`
- `SKEL80/ver`

Responsibilities:
- runtime lifecycle and phase orchestration
- shared class/event library
- shared defaults and compatibility helpers
- shared assets/resources used by multiple projects

### 2. Project overlay (`public/config.php`, `public/engine/`)

Owned by Colibri and responsible for project-specific runtime extension.

Main paths:
- `public/config.php`
- `public/config.secrets.example.php`
- `public/engine/`
- `public/logs/`

Responsibilities:
- project runtime config and precedence
- project path overrides
- project engine bootstrap and feature wiring
- project constants / ini / customs
- project-first hooks and functions
- final post-run overlay before site compilation

### 3. Project delivery surface (`public/index.php`, `public/mvc`, `public/themes`, `public/languages`)

Owned by Colibri as the deployable request/presentation surface.

Main paths:
- `public/index.php`
- `public/mvc/controllers/`
- `public/mvc/views/`
- `public/themes/`
- `public/languages/`
- legacy direct entry files under `public/*.php`

Responsibilities:
- request entry points
- handlers/preprocessors historically stored as controllers
- responders/page assemblers historically stored as views
- theme skin and localization resources

## Modern aliases for historical project folders

The patch does not rename folders, but it documents their modern interpretation:

- `public/mvc/controllers/` → request handlers / preprocessors / actions
- `public/mvc/views/` → responders / page assemblers
- `public/themes/` → presentation skin
- `public/languages/` → localization resources
- `public/engine/core/engine_*.php` → project bootstrap modules
- `public/engine/core/events/**/*.func.php` → project hooks and feature functions

## Dependency rules

### Rule 1. Shared core owns runtime primitives
`SKEL80` may own lifecycle, shared classes, shared events and shared resources.

### Rule 2. Project overlay extends the core only through declared extension points
The project may add config, paths, engine bootstrap, constants, ini, hooks and post-run customs only through the official overlay points documented in the runtime contract.

### Rule 3. Project overlay may depend on shared core
This is expected and historical.

### Rule 4. Shared core must not depend directly on project delivery files
`SKEL80` must not grow hard dependencies on `public/mvc`, `public/themes` or other delivery files outside the declared extension points.

### Rule 5. Delivery layer may depend on both core and overlay
`public/index.php`, `public/mvc/*`, themes and localization are project-owned and may use both shared and overlay code.

## Historical mixed hotspots

These files are valid integration points, but they are also the places where future refactors must stay careful:

- `SKEL80/run.php`
- `SKEL80/kernel/core.php`
- `SKEL80/kernel/runtime_contract.php`
- `public/config.php`
- `public/engine/kernel.php`

They are mixed not because they are wrong, but because they are the negotiated seam between shared kernel and project overlay.

## New declarative files introduced by this patch

### `SKEL80/kernel/runtime_boundaries.php`
Declares ownership zones, dependency rules, modern aliases, extension points and a path-owner helper.

### `public/engine/overlay_contract.php`
Declares the Colibri overlay manifest in one place without changing runtime behavior.

## Expected result

After this patch:
- `SKEL80` vs `public/engine` responsibility is explicit
- the delivery layer is recognized as project-owned and not part of the shared kernel contract
- future refactors can target specific boundary crossings instead of broad folder-level guesses
- modernization can continue without forcing an artificial MVC rewrite
