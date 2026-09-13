# Small extension router

Start with the exact user label or error plus active plugin/builder/plan context. Run:

```sh
python3 docs/extensions/scripts/query.py route "Business Hours missing only when no plan"
```

The tool returns at most three candidate skills with **direct topic paths**. Read each selected identity and indicated topic only. Each operational topic is short; full source/symbol/data ledgers load only when a concrete symbol/key is needed. `router.json` is small routing metadata, separate from the 89 source snapshots. Automatic skill descriptions offer name/symptom hints; actual model selection is heuristic and cannot be guaranteed.

| Symptom | Candidate boundary | Expand only when evidence says |
| --- | --- | --- |
| Open now/overnight/closed status | Business Hours → schedule | Plan hides field; builder uses wrong listing; Core query filters |
| Fields missing with no plan/quota | Pricing Plans → entitlements | The affected field extension and native vs WooCommerce engine |
| Empty Business Hours in Divi | Business Hours + Divi single modules | Plan restrictions if an active plan filters widgets |
| Payment pending after browser return | Named gateway confirmation topic | Correct order purpose and package/claim/booking activation |
| WooCommerce checkout/renewal | WooCommerce Pricing Plans | WooCommerce gateway/subscription implementation, not assumed native Stripe |
| `io is not defined` | Live Chat → socket delivery | The actual service/asset source and chat participant context |
| Saved-search mail missing | Search Alert → alert delivery | Cron, captured mail and matching query |
| Weekly report repeats last listing | Analytics → tracking reports | Date range, loop identity and report generator |
| Unknown symptom, no credible match | Core boundary + actual plugin inventory | Inspect active hooks/files; do not pick an arbitrary extension |

Aliases include catalog name, plugin header, plugin filename, repository name, source-generation variants and concise product names. Long specific names disambiguate nested labels (WooCommerce Pricing Plans vs Pricing Plans). Multiple candidates remain candidates; routing is not a root-cause verdict.

Use `query.py search SYMBOL_OR_KEY --product PRODUCT_ID` to retrieve bounded call/symbol matches. Use `query.py hooks EXACT_HOOK` for emitters/subscribers across primary extension snapshots and Core. Add `--variants` only for a real source-generation difference. Dynamic hooks and names assembled at runtime require source/runtime inspection. Do not read every dependency simply because it is listed.

For intake and reproduction follow [the focused issue workflow](WORKFLOW.md). English symptom phrase aliases are in [intake-aliases.json](intake-aliases.json); they are intentionally finite hints, not general language understanding. Unsupported wording can remain unresolved, so an agent should paraphrase the symptom in English or inspect active source instead of forcing a match.
