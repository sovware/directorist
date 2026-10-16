# Google reCAPTCHA: captcha-render

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Site/secret pairing, configured domains, form options and script loading control visible challenge/badge.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Render multiple protected forms, AJAX validation errors and disabled setting; verify no duplicate SDK load or hidden required challenge.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-google-recaptcha.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1) — 31 declarations
- [recaptchalib.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/recaptchalib.php#L1) — 7 declarations
- [inc/directory_type.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/directory_type.php#L1) — 5 declarations
- [templates/google-recaptcha-fields.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/templates/google-recaptcha-fields.php#L1) — 0 declarations
- [assets/css/main.css:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/assets/css/main.css#L1) — 0 declarations
- [assets/js/main.js:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/assets/js/main.js#L1) — 0 declarations

[Complete topic source/data ledger](captcha-render-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
