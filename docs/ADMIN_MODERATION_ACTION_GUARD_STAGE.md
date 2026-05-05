# M0 / P82 admin moderation action guard stabilization bundle

## Scope

This patch stabilizes the legacy admin/moderation action boundary without changing bootstrap order, routing, DB schema, storage format, or public entrypoint names.

Touched runtime zones:

- `public/engine/core/units/users/engine_admin.php`
- `public/engine/core/units/users/events/engine_admin_events.php`
- `SKEL80/classes/blocks/itModerator.class.php`
- `SKEL80/events/blocks/get_moderate_panel.func.php`
- `SKEL80/events/blocks/get_contents_moderate_code.func.php`
- `SKEL80/events/blocks/get_moderate_killall_event.func.php`
- `SKEL80/events/user/get_admin_button_set.func.php`
- selected legacy content moderation control events in `SKEL80/events/editor/`
- `public/engine/core/units/items/events/engine_add_items_event.php`

## Why this patch exists

After the public navigation/sitemap guard pass, the next runtime-sensitive boundary is the admin/moderation UI. These helpers render buttons, forms, selectors, and moderation panels from global user state, status metadata, prepared selector arrays, and DB rows. In the old runtime those values can be absent during partial bootstrap, AJAX retries, malformed rows, or empty DB results.

The goal is to keep the legacy action flow intact while preventing warnings/deprecations from leaking into admin HTML/AJAX responses.

## Changes

### Admin panel guards

The admin panel now:

- checks that `$_USER` is an object with the expected methods before calling it;
- renders optional button helpers only when the functions are available;
- keeps the existing login/moderation/admin button layout;
- avoids direct request reads in login/background events.

### Moderation panel guards

`itModerator` now:

- normalizes constructor options before reading them;
- skips malformed DB rows without `status` or positive `id`;
- tolerates incomplete status metadata;
- safely initializes the CSS plug array;
- avoids direct `$_USER->id()` calls when the user runtime is unavailable;
- returns `false` from status/content-type writes when the target table/id/field is invalid.

The moderation panel helpers now skip malformed rows instead of rendering broken form data.

### Content moderation event guards

The legacy content action events for status/category/moderation/title/date/language now validate rows before building forms. They keep the same operation names and hidden payload structure for valid rows.

### Item admin event guards

Item admin action helpers now normalize item rows before rendering title/remove/articul/price/shop/new/replicant/econom/calculator/order buttons. Valid item rows preserve the existing forms and button behavior.

## Preserved behavior

Not changed:

- bootstrap/config/env order;
- routes;
- `.htaccess` behavior;
- DB schema;
- storage format;
- public entrypoint names;
- operation names used by legacy AJAX/admin forms;
- moderation status semantics;
- admin panel layout and button flow.

## Deletions

No files were deleted.

No business logic was removed. The patch only replaces fragile direct reads with guarded local helpers and early-return safety checks inside the same files.

## Suggested runtime checks

When a local vhost is available, check:

- admin panel render while logged out;
- admin panel render while logged in;
- moderation panel for content records;
- status change selector;
- category selector;
- content title/date/language buttons;
- item add button;
- item title/articul/price/toggle buttons;
- Apache/PHP logs for `Undefined array key`, `Trying to access array offset on value of type null`, and PHP 8.x dynamic-property/deprecation output in admin AJAX responses.
