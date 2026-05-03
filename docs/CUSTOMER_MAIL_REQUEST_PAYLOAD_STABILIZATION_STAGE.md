# M0 / P72 customer mail request payload stabilization bundle

## Scope

This patch continues the M0 runtime stabilization lane after the P70/P71 regression-audit passes.

The focus is the customer/form-mail runtime path:

- `public/engine/core/engine_mails.php`
- `public/engine/core/units/users/engine_customers.php`
- `public/engine/core/units/users/events/engine_customer_events.php`

No routing, storage schema, form definitions, editor runtime, or mail transport API was changed.

## What changed

### Mail request payload guards

`engine_mails.php` now reads request and settings payloads through small local helper functions before building mail headings, addresses, reply addresses, and product-articul blocks.

This avoids warnings from missing request keys such as:

- `name`
- `email`
- `order`
- `address`
- `address2`
- `citi`
- `country`
- `index`
- `articul`

The visible mail layout and public function names remain unchanged.

### Customer lookup / PIN guards

`engine_customers.php` now guards customer lookup and PIN paths against incomplete DB/request payloads:

- empty result sets from `itMySQL::_request(...)` no longer assume `$request[0]` exists;
- missing PIN payload returns safely;
- missing SMTP settings fall back to empty strings instead of undefined-index warnings;
- `create_pin(...)` returns safely when the customer row is incomplete;
- register/update payload assembly avoids direct missing `$_REQUEST[...]` reads.

### Customer event request guards

`engine_customer_events.php` now centralizes request reads for login/register/ajax PIN flows and profile form prefill data.

This reduces warning noise around:

- missing `op` during non-submit page rendering;
- missing `controller` / `view` in AJAX PIN hidden data;
- missing `ajaxpin` during first render;
- incomplete `$_USER->data` rows in cabinet/profile forms.

## Explicitly not changed

- No new dispatcher/controller framework.
- No namespace migration.
- No DB schema changes.
- No form layout rewrite.
- No mail transport rewrite.
- No file deletion.
- No changes to `itForm2.class.php`, `itEditor.class.php`, `itMySQL.class.php`, or `itFeed.class.php`.

## Suggested manual verification

1. Open `/register/` and confirm first render does not emit undefined `op` warnings.
2. Submit customer login form with an existing email.
3. Trigger PIN login and verify both wrong PIN and valid PIN flows.
4. Open cabinet/profile edit area and confirm profile fields prefill correctly.
5. Submit contacts/order/buy/measurement forms and check admin mail generation.
6. Check PHP logs for `Undefined array key` in customer/mail paths.

## Next step

After this patch, M0 can proceed with another bounded stabilization pass around public utility entrypoints (`login.php`, `soclogin.php`, `img.php`, `mailbody.php`, `more.php`) or pause for manual runtime testing of forms/customer/mail flows.
