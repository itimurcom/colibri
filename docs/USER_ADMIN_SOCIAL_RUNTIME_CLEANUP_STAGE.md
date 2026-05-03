# M0 / P60 user admin/social runtime cleanup bundle

## Goal
Clean up the project-side user admin/social runtime area after the customer/auth P59 pass, without introducing a new auth/admin framework layer.

## Runtime scope
Changed files:
- `public/engine/core/units/users/events/engine_admin_events.php`
- `public/engine/core/units/users/engine_admin.php`
- `public/engine/core/units/users/engine_social.php`

## What changed
- Admin link buttons now share one local helper.
- Login modal setup and OK/Cancel button assembly are centralized locally.
- Measurement panel buttons now use one local helper instead of five repeated inline AJAX button definitions.
- Admin panel rendering is split into small local helpers for logged-in buttons, moderation block and login block.
- Social network list/icon rendering is centralized locally and reused by the public social panel and settings form.

## What was removed
- Old commented-out admin/background/login fragments that were not part of runtime behavior.
- Unused globals and duplicated local button construction.

## Preserved behavior
- Public function names stay unchanged.
- Admin routes stay unchanged.
- Login form action stays unchanged.
- Measurement form operation names stay unchanged.
- Social setting keys `FB_PAGE`, `IG_PAGE`, `TW_PAGE` stay unchanged.
- No files were deleted; no remove manifest is required.

## Manual checks
After applying this patch, verify:
- login modal opens;
- admin panel appears for logged-in users;
- settings and mailing admin buttons open correct pages;
- item add admin button remains visible;
- measurement panel buttons still submit proper form ids;
- social links render;
- social settings page saves FB/IG/TW links.
