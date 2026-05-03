# M0 / P59 customer/auth runtime cleanup bundle

## Goal
Reduce duplicated customer/auth UI and customer lookup code in the project-side user runtime without introducing a new auth layer.

## Runtime scope
Changed files:
- `public/engine/core/units/users/events/engine_customer_events.php`
- `public/engine/core/units/users/engine_customers.php`

## What changed

### Customer event UI
`engine_customer_events.php` now has local helpers for repeated customer UI patterns:
- customer form panel wrapper;
- login/email form construction;
- modal + form construction;
- OK/Cancel modal buttons;
- modal trigger code;
- profile row rendering.

The old public event functions remain in place:
- `customer_login_event(...)`
- `customer_register_event(...)`
- `customer_edit_event(...)`
- `customer_ajaxlogin_event(...)`
- `customer_ajaxpin_event(...)`
- `ajax_error_focus(...)`

### Customer lookup / PIN mail
`engine_customers.php` now has local helpers for:
- first-row lookup from SQL;
- phone normalization;
- SMTP credentials for PIN mail.

## What was removed
- Old commented-out duplicate fragments around register/login/email field handling.
- Repeated HTML wrapper assembly for login/PIN/customer profile blocks.
- Repeated modal setup inside customer edit flow.

## What intentionally did not change
- No route changes.
- No customer DB schema changes.
- No auth/session behavior changes.
- No public function names were removed or renamed.
- No new runtime files or folders were added.
- No new auth framework/layer was introduced.

## Manual checks
After applying:
- customer login page;
- customer registration page;
- AJAX login block;
- PIN entry flow;
- cabinet/profile edit modal;
- PIN email send;
- PHP logs for warnings in customer/auth files.
