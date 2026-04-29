# M0 / P32B itFeed runtime code evaluation hotfix

## Scope

Runtime file changed:
- `SKEL80/classes/blocks/itFeed.class.php`

## What changed

The local feed button text helper no longer runs generated PHP code to resolve localized more/fewer button text.

## Why

The previous implementation could generate invalid PHP text for some feed names and crash inside the evaluated code path.

## What was intentionally not changed

- feed SQL logic
- more/fewer encrypted payload structure
- HTML structure of feed buttons
- public method names

## Result

More/fewer button text is resolved through the existing constant lookup path without runtime PHP code generation.
