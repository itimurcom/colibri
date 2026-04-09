# Project patch log

## M0 / P8 product markup deprecation hotfix
- Fixed dynamic property deprecations in `SKEL80/classes/shop/itMarkUp.class.php` by declaring the product markup state explicitly: `image`, `name`, `brand`, `description`, `offers`, `url`, `price`, `currency`, `condition`, `availability`, `expire`, `seller`, `sku`, `mpn`, and `review`.
- Replaced deprecated `strftime()` usage in `itMarkUp` with `skel80_strftime_compat()` for the default `priceValidUntil` value, preserving the existing ISO-like `Y-01-01` behavior for the next year.
- Expected result: product pages stop emitting `itMarkUp` dynamic-property warnings and the schema markup generation no longer emits the local `strftime()` deprecation.

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

## M0 / P7 admin and forms deprecation bundle
- Replaced deprecated `strftime()` usage in `SKEL80/kernel/engine_functions.php` with a compatibility formatter that keeps localized date output for `ua`, `ru`, and `en` inside the shared-kernel runtime.
- Fixed dynamic property deprecations in `SKEL80/classes/forms/itModal.class.php`, `SKEL80/classes/forms/itButton.class.php`, and `SKEL80/classes/f2/itForm2.class.php` by declaring the properties explicitly.
- Fixed the `itModal::set_animation()` assignment bug that created arbitrary dynamic properties like `fadeAndPop`.
- Fixed the `itForm2` button collection typo path (`button_xml` -> `buttons_xml`) inside button insertion, because it was part of the same deprecation/runtime hotspot.
- Expected result: admin login, the mail/forms section, and the price calculator stop emitting these repeated deprecation warnings in AJAX/HTML responses.
