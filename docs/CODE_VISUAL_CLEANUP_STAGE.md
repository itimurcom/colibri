# M0 / P19 code visual cleanup bundle

This patch starts the code-visual cleanup stage for the current Colibri state.

Scope:
- Remove excessive non-functional comments.
- Remove separator banners and commented-out noise.
- Collapse redundant blank lines.
- Keep runtime logic and architecture intact.

Covered areas:
- Shared bootstrap and runtime helpers.
- Shared blocks/system/forms/images/editor/shop classes.
- Project overlay hot spots: mails, items, menus, wishlist, widgets, lastseen, more, ed_field, old calc, and project kernel.

Excluded on purpose:
- `SKEL80/classes/f2/itForm2.class.php` because the current archive already contains a separate syntax-level legacy issue in that file. Mixing that repair with a visual cleanup bundle would make the patch less controlled.
