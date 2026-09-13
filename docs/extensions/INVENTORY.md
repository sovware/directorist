# Inclusion and source-selection decisions

Checked 2026-09-13 against the live [Directorist extension catalog](https://directorist.com/extensions/), then authenticated Sovware GitHub organization inventory and plugin headers/source. The catalog's first-party section contains **40 entries** before its explicit Third Party Extensions section. Each of those 40 has a matched accessible source repository. This is an authenticated account-visible inventory, not a claim about inaccessible organization repositories.

[INDEX.md](INDEX.md) is the included A-Z list. [inventory.json](inventory.json) records product identity, header, skill, topics and source provenance. [branches.json](branches.json) records all visible branch tips returned for the 44 included product/generation repositories; pagination completion was checked. [selection.json](selection.json) records 89 concrete source snapshots, including default branches, distinct latest/development branches, generation variants and relevant local siblings.

The default branch and version/tag are not used as truth. The baseline follows the newest available implementation candidate and actual files; local Claim Listing is selected because its later claimed-badge changes are not on the visible Sovware tip. Business Hours retains its newer development source plus local changed/untracked CSV code as separate evidence. Analytics uses the report implementation branch, not the default scaffold. Pricing Plans/Stripe/PayPal/Authorize.net keep their newer implementation repositories alongside legacy source. Branch deltas are explicit; no speculative merged branch was constructed and no local plugin checkout was changed. An installed-site issue still requires matching actual files to the appropriate variant. A newest timestamp alone does not prove every feature supersedes another branch.

Product/repository name exceptions were reconciled through plugin headers and implementation:

- M-Pesa Payment Gateway → `directorist-mpesa-payment-gateway` / `directorist-mpesa.php`.
- Directorist Listing Importer → `directorist-listing-import` / `directorist-listing-import.php`; integrated Google and feed code is present. The standalone `directorist-google-importer` is retained outside the mapping as an earlier separate-source comparison candidate, not a 41st catalog product.
- Business Hours → `directorist-business-hours` / `bd-business-hour.php`.
- Image Gallery → `directorist-gallery` / `bd-directorist-gallery.php`.
- Listings Slider & Carousel → `directorist-slider-carousel` / `bd-directorist-slider.php`.
- Listings with Map → `directorist-listings-with-map` / `directorist-listings-map.php`.
- AddonsKit for Bricks is explicitly in the first-party catalog despite its different prefix. AddonsKit for Elementor is not an included catalog entry.

Excluded from this official-product mapping:

- The catalog's explicit third-party products (Booktics and wpXplore extensions); they remain third-party dependencies if a future ticket involves them.
- Directorist themes, theme cores/toolkits, Core itself as an extension entry, infrastructure/API repositories, templates, test/scaffold repositories, unrelated Sovware products, and custom client plugins such as Deals/Daily Slider/Shopping Centres.
- `*-old`, extension-base and other historical/test variants as standalone products. Necessary legacy generations for included products are recorded as variants rather than new entries.
- Local Pay Per Lead, payfast/IK and other noncatalog payment or custom plugins: their absence from this checked catalog excludes them **from this scope**, not a universal claim that they are invalid, abandoned or unsupported. Recheck official product evidence if scope changes.

Known limits: static tokenization captures literal PHP calls and declarations, plus lightweight JS declarations. It does not execute conditional registrations, resolve dynamic hook names, infer every ORM key or validate third-party SDK behavior. Vendor/dependency directories are excluded from first-party semantic coverage, and generated/support files are fingerprinted separately. Functional/browser recipes are unexecuted. The file ledger accounts for scanned source/support files; structural coverage does not certify every semantic branch or released/client behavior.
