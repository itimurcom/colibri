# M0 / P79 PHP 8 dynamic property declaration stabilization bundle

## Goal

Reduce PHP 8.x dynamic-property deprecation noise in legacy UI/editor/mail/runtime classes without changing bootstrap order, routing, storage format, SQL schema, or public entrypoint behavior.

This patch is intentionally declarative: it does not move methods, does not rename legacy APIs, and does not alter persistence logic. It only declares properties that the existing constructors and methods already assign at runtime.

## Scope

Declared existing runtime properties in these legacy zones:

- form controls: `itDate`, `itInput`, `itAutoSelect`, `itArea`, `itSelector`, `itUpGal`;
- editor controls: `itCats`, `itEdGallery`, `itEdMedia`, `itEdText`;
- UI/runtime blocks: `itMenu`, `itModerator`, `itOpenClose`, `itSlider`;
- mail/runtime helpers: `itMail`, `itMailer`, legacy `PHPMailer` content-type alias;
- utility/runtime helpers: `itSiteMap`, `itMemCache`, `itMarkOrg`, `itWizard`.

## Why this is safe

PHP 8.2 emits deprecation output when code creates undeclared object properties. In this project such output can leak into HTML, AJAX, and JSON responses and make unrelated editor/form/catalog operations look broken.

The affected properties were already being assigned through `$this->...` before this patch. Declaring them keeps the same public runtime shape while preventing dynamic-property creation warnings.

## Explicit non-goals

This patch does not:

- change bootstrap/config/env behavior;
- change public routes or entrypoint names;
- change database schema or storage serialization;
- change editor/form/mail/gallery business logic;
- remove files;
- introduce namespaces, controllers, dispatchers, adapters, or a new framework layer.

## Verification

Minimum checks after applying:

```bash
php -n -l SKEL80/classes/forms/itDate.class.php
php -n -l SKEL80/classes/forms/itInput.class.php
php -n -l SKEL80/classes/forms/itAutoSelect.class.php
php -n -l SKEL80/classes/forms/itArea.class.php
php -n -l SKEL80/classes/forms/itSelector.class.php
php -n -l SKEL80/classes/forms/itUpGal.class.php
php -n -l SKEL80/classes/editor/itEdGallery.class.php
php -n -l SKEL80/classes/editor/itEdMedia.class.php
php -n -l SKEL80/classes/editor/itEdText.class.php
php -n -l SKEL80/classes/mailer/PHPMailer.class.php
php -n -l public/engine/core/classes/wizards/itWizard.class.php
```

Runtime smoke areas:

- open a public catalog page;
- open admin/editor page with editable text/media/gallery fields;
- open a form with date/input/select/autoselect/upload-gallery controls;
- trigger mail stack rendering/sending in the local dev environment if configured;
- inspect PHP/Apache logs for `Creation of dynamic property` messages.

## Next suggested step

After this declarative cleanup, the next stabilization bundle can target remaining scalar/null compatibility warnings such as `json_decode(null)`, `htmlentities(null)`, old `count(null)` assumptions, and incomplete remote-media JSON responses.
