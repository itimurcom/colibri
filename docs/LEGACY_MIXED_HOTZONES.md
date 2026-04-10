# Legacy mixed hotzones

These locations are intentionally called out because they cross more than one responsibility boundary.

## 1. `SKEL80/kernel/engine_functions.php`
Shared helper layer that also contains formatting and delivery-adjacent helpers.

## 2. `public/ed_field.php`
Legacy mutation endpoint mixing forms, editor transport, auth and business operations.

## 3. `public/more.php`
Legacy feed endpoint mixing SQL-backed feed assembly and HTML delivery.

## 4. `public/mvc/controllers/`
Historical preprocessors that still carry delivery decisions.

## 5. `public/mvc/views/`
Historical responders that still prepare data and render markup together.

## 6. `public/engine/kernel.customs.php`
Late project hook that can cross into runtime, overlay and delivery state.

## Rule going forward
- Mixed zones are allowed to remain while needed.
- New behavior should prefer clearly owned zones.
- Extraction should happen slice by slice, not via blind rewrite.
