# Directorist Ads Manager: ad-shortcode

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Shortcode/widget output and dynamic styles are separate from automatic page-hook insertion.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Place same ad through widget and shortcode in a test page; verify click destination, dimensions and no unintended duplicate automatic placement.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [Inc/Controller/Base/DynamicStyle.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/DynamicStyle.php#L1) — 2 declarations
- [Inc/Controller/Base/Enqueue.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/Enqueue.php#L1) — 5 declarations
- [Inc/Controller/Base/ShortcodeHandler.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ShortcodeHandler.php#L1) — 4 declarations
- [Inc/Controller/Base/WidgetsHandler.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/WidgetsHandler.php#L1) — 3 declarations
- [Inc/Controller/Widgets/WidgetAdsManager.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Widgets/WidgetAdsManager.php#L1) — 5 declarations
- [Inc/View/swbdpam-widget-output.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/View/swbdpam-widget-output.php#L1) — 0 declarations

[Complete topic source/data ledger](ad-shortcode-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
