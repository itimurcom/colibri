# M0 / P18 mixed-hotspot reduction bundle

## Scope
This patch reduces coupling in the heaviest mixed runtime hot spots without changing the entrypoints or the shared-kernel topology.

## Changed runtime areas
- `SKEL80/classes/blocks/itFeed.class.php`
- `public/more.php`
- `public/engine/core/engine_mails.php`
- `public/ed_field.php`
- `SKEL80/kernel/engine_functions.php`

## Key outcomes
- `itFeed` now has explicit runtime state and query builder helpers instead of one large inline constructor/query path.
- `more.php` now has a safe payload decode and one dispatch path for named operations vs. feed rendering.
- Mail history rows no longer render a full modal + two hidden forms per row. The page now uses one shared modal.
- `ed_field.php` now has extracted helper handlers for calculator and mail status actions, reducing duplicated mixed logic in the admin/public switches.
- Shared runtime helpers were added for encrypted payload decode and JSON responses.

## Notes
This patch does not change the public endpoint URLs. It keeps:
- `public/more.php`
- `public/ed_field.php`
- existing feed callbacks
- existing mail action ops

The goal is to reduce hidden side effects and repeated heavy markup, not to rewrite the platform model.
