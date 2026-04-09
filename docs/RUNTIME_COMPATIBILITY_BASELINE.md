# Runtime compatibility baseline

## M0 / P8 product markup deprecation hotfix
- Fixed dynamic property deprecations in `itMarkUp` by declaring the product schema fields explicitly instead of creating them lazily during markup preparation.
- Replaced deprecated `strftime()` usage in `itMarkUp` with `skel80_strftime_compat()` for the default offer expiration date, keeping the shared-kernel date compatibility path consistent with the earlier runtime baseline.

## M0 / P7 admin and forms deprecation bundle
- Replaced deprecated `strftime()` usage in `SKEL80/kernel/engine_functions.php` with a compatibility formatter that preserves localized month/day output for `ua`, `ru`, and `en` without changing the shared-kernel runtime model.
- Fixed dynamic property deprecations in `itModal`, `itButton`, and `itForm2` by declaring the used properties explicitly.
- Corrected the `itModal::set_animation()` assignment bug so animation updates no longer create arbitrary dynamic properties such as `fadeAndPop`.
- Corrected the `itForm2` button collection typo path (`button_xml` -> `buttons_xml`) inside the button insertion flow, because it belonged to the same dynamic-property/runtime-warning hotspot.
