# Mail history shared modal note

## Previous behavior
Each row in the "Письма" feed rendered:
- one modal
- one iframe
- two hidden forms
- three buttons

This multiplied markup and form initialization for every mail row.

## New behavior
The mail history page now renders:
- one shared modal
- one shared iframe
- one shared spam form
- one shared remove/restore form

Each feed row only renders a lightweight launcher with:
- `data-mail-id`
- `data-mail-status`

A small JS helper fills the shared modal/forms at click time.

## Compatibility
The backend mail actions keep the same ops:
- `spam`
- `spam_x`
- `mail_x`
- `mail_not_x`

`public/ed_field.php` was updated to accept `mail_id` from direct POST request fields, not only from encrypted editor payload.
