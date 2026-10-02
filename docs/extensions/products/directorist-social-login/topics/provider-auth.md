# Social Login: provider-auth

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Provider token verification precedes user registration/login. Callback/origin configuration and account mapping must be inspected; browser button appearance is not authentication proof.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use test provider apps and accounts; verify valid/expired/forged token, existing email vs new account and redirect. Never log tokens or mutate production provider apps.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-social-login.php:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L1) — 32 declarations
- [inc/class-compatibility.php:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/inc/class-compatibility.php#L1) — 2 declarations
- [assets/public/js/social-login.js:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/assets/public/js/social-login.js#L1) — 1 declarations

[Complete topic source/data ledger](provider-auth-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
