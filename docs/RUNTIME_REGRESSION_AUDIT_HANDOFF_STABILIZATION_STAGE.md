# M0 / P70 runtime regression audit / handoff stabilization bundle

## Goal
Run a controlled stabilization pass after the P53–P69 cleanup chain, focusing only on obvious runtime regression risks and request/session guard gaps. This patch does not introduce a new architecture layer and does not rewrite form, editor, feed, DB, storage, or route behavior.

## Runtime scope
Changed files:
- `public/mvc/controllers/controller.items.php`
- `public/mvc/controllers/controller.buy.php`
- `public/mvc/controllers/controller.order.php`
- `public/mvc/controllers/controller.contacts.php`
- `public/mvc/controllers/controller.measurement.php`
- `public/mvc/controllers/controller.register.php`
- `public/mvc/controllers/controller.shop.php`
- `public/engine/core/engine_settings.php`
- `public/engine/core/engine_menus.php`
- `public/engine/core/engine_wishlist.php`
- `public/engine/core/engine_lastseen.php`
- `public/engine/core/classes/wizards/itWizard.class.php`
- `public/engine/core/classes/wizards/itObject.class.php`
- `public/engine/core/events/wizards/wizards_events.func.php`
- `SKEL80/events/objects/object_events.func.php`

## What changed
- Added local request-value guards in recently touched controllers and runtime helpers so optional `$_REQUEST[...]` keys are not read directly.
- Stabilized wishlist reads/writes when the DB request returns an empty array instead of a row.
- Stabilized logged-in wishlist rendering when `$prepared_arr['wishlist']` is not initialized.
- Stabilized last-seen session reads when the session key is not initialized.
- Fixed `itWizard::set_row_value()` so wizard setter payload (`name`, `type`, `titles`, `values`, `label`) is preserved after loading normalized wizard options.
- Added row-existence guards before wizard setter/update writes to prevent invalid wizard keys from mutating empty data.
- Hardened `itObject` wizard assembly against missing category rows, missing wizard fields, missing names, and incomplete select title/value pairs.
- Initialized object wizard render/table row buffers before `implode(...)`.
- Guarded wizard/object event request reads while preserving existing operation names and redirect behavior.

## Preserved behavior
- Existing public method names are preserved.
- Existing controller entrypoints are preserved.
- Existing event operation names are preserved.
- Existing `itForm2`, `itEditor`, `itFeed`, and `itMySQL` internals are not changed.
- Existing DB schema, serialized storage fields, routes, and URL behavior are not changed.
- No files are deleted.

## Manual checks after applying
Prioritize regression checks that were risky after P53–P69:
- Wizard field edit: change name/type/titles/values/label and verify values are saved, not blanked.
- Object form edit: save object wizard values and verify category-derived fields still render.
- Catalog/item pages: `/items/`, category page, item page by URL, item page by `rec_id` redirect.
- Wishlist: guest add/remove, logged-in add/remove, cabinet wishlist block.
- Last-seen: open several item pages and verify the last-seen block still renders.
- Forms: contacts/order/buy/measurement/register open, submit, thank-you redirects.
- Settings: admin settings, SMTP settings, password panel.

## Next recommended step
After applying this patch, do not continue with aggressive cleanup until at least the wizard/object, catalog, wishlist, and form-page flows above are manually checked. If logs show a concrete runtime error, the next patch should be a focused bugfix bundle based on those logs.
