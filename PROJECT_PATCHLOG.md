# Project patch log

## M0 / P6 avatar null compatibility for editor save
- Fixed legacy editor save path for records where DB column `avatar` is `NOT NULL`.
- `itEditor::store()` now normalizes top-level `avatar` from `NULL` to an empty string before full-record update.
- `ava_x` event now clears avatar with an empty string instead of `NULL`.
- Expected result: `public/ed_field.php` stops returning PHP fatal HTML for editor AJAX saves, so frontend JSON parsing works again.
- Next step: if another AJAX endpoint still returns HTML instead of JSON, inspect that endpoint's backend fatal separately.

## M0 / P9 integer toggle save for item flags
- Fixed moderator toggle save for item integer flags in `public/ed_field.php`.
- `is_new`, `is_econom`, `is_shop`, and `is_replicant` now persist explicit `1/0` integers instead of raw PHP booleans.
- Expected result: repeated toggles no longer try to write an empty string into integer DB columns like `colibri_items.is_replicant`.
- Next step: if another toggle fails similarly, inspect that endpoint for boolean-to-string persistence before touching DB helpers again.

- M0 / P15 mail console and date compatibility bundle
  - fixed `itModal`, `itButton`, `itForm2` dynamic property deprecations affecting the mail section and price calculator
  - added `strftime()` compatibility helpers in `SKEL80/kernel/engine_functions.php`
  - replaced deprecated `strftime()` usage in `public/engine/core/engine_mails.php`

- M0 / P18 mixed-hotspot reduction bundle
  - reduced coupling in `itFeed` by adding explicit limit/need_total/query helpers and lazy-safe total count behavior
  - refactored `public/more.php` into named-operation dispatch + safe feed payload render path
  - replaced per-row mail history modal/forms with one shared preview modal in `engine_mails.php`
  - extracted calculator/mail-status helper handlers in `public/ed_field.php`
  - added shared runtime helpers for encrypted payload decode and JSON responses

- M0 / P20 itForm2 cleanup refactor bundle
  - removed legacy comment noise and dead commented code from `SKEL80/classes/f2/itForm2.class.php`
  - introduced shared helpers for field/button collection insert/sort/move logic inside `itForm2`
  - reformatted alias wrapper methods for cleaner visual structure
  - fixed `_reCaptcha()` session key lookup and corrected button collection move/insert behavior

- M0 / P71 router/language request baseline stabilization bundle
  - stabilized legacy router path parsing for URLs with and without trailing slash
  - added guarded request reads in itSite for controller/view/lang/table/record defaults
  - hardened itLang against stale session language values and incomplete language rows
  - fixed language switch link generation for URLs without an existing language prefix

- M0 / P72 customer mail request payload stabilization bundle
  - centralized guarded request/settings reads in the customer form-mail runtime path
  - stabilized customer lookup/PIN helpers against empty DB rows and incomplete customer payloads
  - guarded login/register/ajax PIN events against missing request keys during first render and AJAX retries
  - preserved public function names, form flow, mail transport, DB schema, and runtime architecture

- M0 / P73R environment-safe utility entrypoint stabilization bundle
  - replaced the rejected P73 direction with a bounded public utility-entrypoint guard pass
  - removed the old `public/img.php` debug abort that printed `PICTURE_ROOT` and stopped image handling
  - guarded optional request/session/server reads in `img.php`, `more.php`, `login.php`, `mailbody.php`, `maillogo.php`, and `soclogin.php`
  - preserved bootstrap/config/env order, routes, DB schema, storage format, public entrypoint names, and existing redirect/render flows
  - added `docs/ENVIRONMENT_SAFE_UTILITY_ENTRYPOINT_STABILIZATION_STAGE.md` with scope, removal note, and manual checks

- M0 / P74 form editor AJAX guard stabilization bundle
  - added guarded form-editor AJAX operation/kind reads in `SKEL80/events/f2/f2_events.func.php`
  - centralized `itForm2` field-kind normalization and safer initialization for temporary modal/control forms
  - stabilized form-field insert/change paths against missing or stale field metadata
  - declared existing runtime properties across `itForm2` field-control classes to reduce PHP 8.x deprecation output in AJAX responses
  - guarded `itSet2` selected-value reads when the stored value is empty or non-array
  - preserved form storage format, routes, bootstrap/config/env behavior, and existing form editor UI flow
  - added `docs/FORM_EDITOR_AJAX_GUARD_STABILIZATION_STAGE.md`

- M0 / P75 ed_field AJAX request guard stabilization bundle
  - centralized guarded request/upload reads inside `public/ed_field.php` without changing its legacy operation dispatch model
  - replaced direct request reads in customer AJAX, filter/sort, item, slider, settings, and profile update operation paths
  - reused `skel80_decode_encrypted_array()` for encrypted payload decoding in `openclose` and `tab` operations
  - guarded upload-backed helper paths so missing `$_FILES[DEFAULT_FILES_NAME]` no longer emits warnings before JSON/redirect responses
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, and public entrypoint names
  - added `docs/ED_FIELD_AJAX_REQUEST_GUARD_STAGE.md`

- M0 / P76 image gallery upload event guard stabilization bundle
  - stabilized form-gallery and `itImages` upload event handlers with guarded request/file reads
  - fixed the old `redy_val(...)` typo in `get_form_gallery(...)` and added safe fallbacks for optional gallery row metadata
  - declared existing `itImages` runtime properties to reduce PHP 8.x dynamic-property deprecation output in AJAX responses
  - hardened `itImages` storage initialization and image reorder operations against missing/non-array storage and out-of-range indexes
  - removed only unused local variables from upload handlers; no behavior, storage format, routes, or DB schema changed
  - added `docs/IMAGE_GALLERY_UPLOAD_EVENT_GUARD_STAGE.md`

- M0 / P77 editor content save pipeline stabilization bundle
  - centralized guarded request/data/upload reads in `SKEL80/events/editor/editor_events.func.php` for legacy WYSIWYG/content editor save operations
  - stabilized editor text/title/media/avatar/gallery/status/category/date/related-content operation paths against missing or malformed request payloads
  - hardened `itEditor::_redata()` to reuse the shared encrypted payload decoder and return an empty array for bad payloads
  - declared existing `itEditor` runtime properties and guarded editor storage initialization, field movement, zoom switching, related-content writes, cache/status checks, and `_consolidate()`
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, public entrypoint names, and legacy editor UI flow
  - added `docs/EDITOR_CONTENT_SAVE_PIPELINE_STABILIZATION_STAGE.md`

- M0 / P78 catalog DB row / null guard stabilization bundle
  - stabilized `itItem`, `itCategory`, `itObject`, and `itBlock` against missing options, empty DB rows, and incomplete runtime payloads
  - guarded public catalog item/feed/filter helpers against incomplete item rows, missing category relations, empty session filters, and invalid price-bound query results
  - preserved routes, bootstrap/config/env behavior, DB schema, storage format, legacy function names, and valid-row output structure
  - added `docs/CATALOG_DB_ROW_NULL_GUARD_STAGE.md`

- M0 / P79 PHP 8 dynamic property declaration stabilization bundle
  - declared existing runtime properties across legacy form controls, editor controls, UI blocks, mail helpers, sitemap/cache/markup helpers, and `itWizard`
  - added a legacy `PHPMailer::$ContentType` alias property used by existing mail methods to avoid PHP 8.x dynamic-property output during mail rendering/sending
  - reduced the risk of PHP 8.x deprecation text leaking into HTML/AJAX/JSON responses without changing behavior or persistence format
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, public entrypoint names, and legacy APIs
  - added `docs/PHP8_DYNAMIC_PROPERTY_DECLARATION_STAGE.md`

- M0 / P80 session catalog personalization guard stabilization bundle
  - stabilized wishlist session/database synchronization against malformed session payloads, missing user runtime state, empty DB rows, and incomplete item rows
  - normalized wishlist and last-seen item lists to valid positive item IDs before rendering, storing, or building catalog feed rows
  - guarded last-seen session/request reads and DB result iteration to prevent warnings from broken session state
  - restricted `itFocus` session assignment to existing fields to avoid PHP 8.x dynamic-property output from arbitrary session keys
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, public function names, and legacy UI behavior
  - added `docs/SESSION_CATALOG_PERSONALIZATION_GUARD_STAGE.md`

- M0 / P81 public navigation sitemap guard stabilization bundle
  - stabilized public menu/navigation helpers against malformed `$a_menu`, `$cat_cat`, and `$cat_more` rows without changing route structure
  - guarded `public/sitemap.php` against missing menu arrays, empty DB result sets, incomplete item rows, and missing localized item URLs
  - hardened `itSiteMap` XML rendering by skipping invalid page rows, escaping XML values, and replacing deprecated `strftime()` use with `date()`
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, public entrypoint names, and existing menu/sitemap semantics
  - added `docs/PUBLIC_NAVIGATION_SITEMAP_GUARD_STAGE.md`

- M0 / P82 admin moderation action guard stabilization bundle
  - stabilized legacy admin/moderation panel rendering against missing user runtime state, malformed status metadata, and empty DB rows
  - guarded content moderation action events for status/category/moderate/title/date/language rows without changing operation names or hidden payload semantics
  - guarded item admin action buttons for incomplete item rows while preserving valid-row form behavior
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, public entrypoint names, and legacy admin UI flow
  - added `docs/ADMIN_MODERATION_ACTION_GUARD_STAGE.md`

- M0 / P83 category object editor action guard stabilization bundle
  - stabilized legacy category/object editor action handlers against missing operation keys, broken encrypted payloads, incomplete rows, and stale modal submissions
  - guarded category/object modal render helpers against missing prepared arrays, absent user runtime state, and incomplete row metadata
  - fixed the PHP 8 fatal-risk `data` token in the category title modal and corrected the object-add input `label` metadata typo
  - hardened object form update target selection by preferring `rec_id` while preserving the old category-field fallback
  - guarded destructive moderation basket cleanup against malformed table/status request payloads and undefined DB-prefix state
  - preserved bootstrap/config/env behavior, routes, DB schema, storage format, public entrypoint names, and legacy editor UI flow
  - added `docs/CATEGORY_OBJECT_EDITOR_ACTION_GUARD_STAGE.md`
