# Validation report

Checked locally on 2026-09-13. These checks validate the documentation/routing/install tooling and inspected source references. They are not plugin runtime or browser compatibility results.

| Check | Observed result |
| --- | --- |
| Official inventory | 40 catalog products matched to accessible plugin headers/source; third-party section excluded |
| Source provenance | 89 selected snapshots across 44 product/generation repositories plus relevant local checkouts; all visible branch pages completed |
| Selective topic coverage | 88 authored feature topics; 41 entry skills including central router |
| Exact product name/repo routing | 80/80 cases passed |
| Symptom/integration routing | 29/29 cases passed, including 12 Bengali/Banglish cases |
| Initial docs selected for matched symptom cases | 2–4 identity/topic files; 444–1078 words; 4580–12187 UTF-8 bytes |
| Source relationship query | Exact Core emitter + Business Hours subscriber found for `directorist_field_template` |
| Skill format | Official skill-creator validator passed for 41 repo skills and 41 installed copies |
| Installation | 41 skills installed; installer check verified canonical links; differing user-edited/unrelated content refused even with managed-update flag |
| Snapshot drift | All saved snapshot hashes matched, with added/deleted file checks; no client-site assertion |
| Remote refresh | Official catalog unchanged; 44 repository branch inventories unchanged at refresh |
| PHP tokenizer | Comments/function declarations excluded; callback retained; stored meta values omitted; dynamic hook expression retained |
| Classified canonical files | 1,952 feature-linked; 444 documented infrastructure; 725 support/generated/library/tooling; 2 legacy candidates; 0 unclassified feature candidates |

Initial-read measurements include selected identity/topic files only. They exclude the router/skill instructions, any later source code or dependency expansion, and tool runtime memory. Exact-name tests and finite phrase aliases are deterministic tests, not a real new-session model autodiscovery evaluation. Unknown wording returns an unresolved result; it must not force an owner.

[Machine validation results](validation-results.json) contain the current link/source-line counts and failures. [Routing results](routing-validation.json) contain all selected paths and measured sizes. [Coverage results](coverage-results.json) contain per-product classifications. [Remote refresh result](remote-refresh-results.json) records catalog/ref checks. Link validation resolves pinned GitHub paths/lines against the inspected local snapshot; it does not claim every remote URL returned HTTP 200.

The two legacy candidates are GamiPress `inc/directory_type.php` and `inc/helper-functions.php`, whose FAQ-named code is not in the inspected normal `includes()` chain. They are documented as legacy candidates, not invented GamiPress features. Dynamic inclusion/reachability still requires issue-specific verification.

Structural coverage and feature-linked references do not prove every semantic conditional was manually audited. Vendor package internals and every historical branch are not exhaustively mapped. New branch tips/dirty files require drift review and actual source comparison. No live data, plugin state, client settings, paid services, GitHub push/PR/release or memory was changed. No plugin functional/browser recipe was executed by this documentation task.
