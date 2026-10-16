# Feature and infrastructure coverage

Canonical snapshot: `mailchimp--master`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=6, infrastructure=3, support-or-generated=6.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [README.md:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/README.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-mailchimp-integration.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L1) | feature-linked | [registration](topics/registration.md), [contact-subscription](topics/contact-subscription.md) |
| [gulpfile.js:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/gulpfile.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [package.json:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/package.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [includes/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [includes/class-helper.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-helper.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [includes/class-mailchimp.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-mailchimp.php#L1) | feature-linked | [registration](topics/registration.md), [contact-subscription](topics/contact-subscription.md) |
| [includes/class-settings-manager.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-settings-manager.php#L1) | feature-linked | [registration](topics/registration.md), [contact-subscription](topics/contact-subscription.md) |
| [includes/class-subscribe-after-listing-contact.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L1) | feature-linked | [contact-subscription](topics/contact-subscription.md) |
| [includes/class-subscribe-after-registration.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-registration.php#L1) | feature-linked | [registration](topics/registration.md) |
| [languages/directorist-mailchimp-integration.pot:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/languages/directorist-mailchimp-integration.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [templates/before-registration-checkbox.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/templates/before-registration-checkbox.php#L1) | feature-linked | [registration](topics/registration.md) |
