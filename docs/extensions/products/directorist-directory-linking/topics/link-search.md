# Multi Directory Linking: link-search

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Linked-directory search and single display must use the configured target directory and valid IDs.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Search by linked listing and compare native single output with Divi LinkDirectory; test two directory types and legacy serialized selection.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Builder/Builder.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L1) — 10 declarations
- [app/Setup/Settings.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Setup/Settings.php#L1) — 5 declarations
- [app/base.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L1) — 14 declarations

[Complete topic source/data ledger](link-search-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
