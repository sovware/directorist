# Google reCAPTCHA: verification

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

v2/v3 branches differ. Server verification and per-form enable settings matter; client challenge success alone cannot authorize submission.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Test listing, registration, review and contact forms with valid, missing, expired and replayed tokens using test keys. Compare actual installed variant and server error.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-google-recaptcha.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1) — 31 declarations
- [recaptchalib.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/recaptchalib.php#L1) — 7 declarations
- [inc/directory_type.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/directory_type.php#L1) — 5 declarations
- [templates/google-recaptcha-fields.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/templates/google-recaptcha-fields.php#L1) — 0 declarations

[Complete topic source/data ledger](verification-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
