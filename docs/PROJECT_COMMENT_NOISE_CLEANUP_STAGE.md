# M0 / P41 project comment noise cleanup bundle

## Goal
Remove project-wide legacy comment noise without changing runtime behavior.

## Scope
This patch removes only non-executable decorative and generated metadata comments:
- CRC metadata blocks
- line-only dot/dash/equal separators
- generated `version/hash/date` metadata lines attached to CRC blocks

## What intentionally stays
The patch does not remove comments that explain behavior, TODO/FIXME notes, runtime warnings, form semantics, SQL intent, or third-party documentation blocks.

## Runtime impact
No runtime logic is changed. The cleanup only removes lines that were comments before PHP/JS/CSS/SQL execution.

## Follow-up
Continue functional cleanup in dedicated hotspot patches. Do not combine broad formatting cleanup with behavioral changes.
