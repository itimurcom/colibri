# M0 / P35 itForm2 safe duplicate cleanup pass

## Goal

Reduce local duplicate field-builder code in `SKEL80/classes/f2/itForm2.class.php` without changing form storage, event handling, submit flow, field XML structure, or public method names.

## Runtime scope

Changed runtime file:
- `SKEL80/classes/f2/itForm2.class.php`

No runtime files were added.

## What changed

A local protected helper was added inside `itForm2`:
- `addFieldFromDefaults(...)`

It centralizes the repeated pattern used by many `add_*` field builder methods:
- read defaults from `$form2_defaults`
- normalize arguments through `_arguments(...)`
- assign `kind`
- apply optional extra field attributes such as `type`
- run `_correct_field_data(...)`
- apply default `more` state where the legacy method previously did so
- append to `fields_xml`

## Methods intentionally preserved

The public builder method names remain available:
- `add_desc`
- `add_description`
- `add_row`
- `add_code`
- `add_field`
- `add_input`
- `add_phone`
- `add_email`
- `add_number`
- `add_password`
- `add_pass`
- `add_area`
- `add_itSelector`
- `add_select`
- `add_selector`
- `add_itAutoSelect`
- `add_auto`
- `add_itDate`
- `add_date`
- `add_timeSelector`
- `add_time`
- `add_set`
- `add_upgal`

Aliases still delegate to the canonical builder methods.

## What was intentionally not changed

- no form submit/event flow changes
- no stored form mutation changes
- no XML schema changes
- no new namespace
- no new controller/factory/registry layer
- no method removals
- no file deletions

## Testing focus

After applying this patch, test:
- contact form render and submit
- buy/order form render and submit
- measurement form render and submit
- editor form field add/edit flows
- select/auto/date/time/upgal fields if available in the editor
