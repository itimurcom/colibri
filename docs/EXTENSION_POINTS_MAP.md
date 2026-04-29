# Extension points map

## Bootstrap/config
- `public/config.php`
- `public/config.local.php`
- `public/config.secrets.php`
- `public/config.secrets.local.php`

## Path overrides
- `public/engine/kernel.path.php` (optional)

## Project constants / ini
- `public/engine/ini/const.*.php`
- `public/engine/ini/ini.*.php`

## Project engine wiring
- `public/engine/core/engine_*.php`

## Project events / functions
- `public/engine/core/events/**/*.func.php`

## Late custom hooks
- `public/engine/ini/custom.*.php`
- `public/engine/kernel.customs.php`

## Delivery-specific hooks
- `public/mvc/controllers/*.php`
- `public/mvc/views/*.php`
- `public/themes/**`
- `public/languages/**`

## Notes
`FEED_LIMIT`, theme layout, controllers/views, forms/editor delivery, and project-specific rendering belong to the **project overlay / delivery** side, not to the shared kernel contract.
