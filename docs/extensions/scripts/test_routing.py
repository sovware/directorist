#!/usr/bin/env python3
"""Behavioral checks: candidate boundaries, selective reads, exact source lookup and safe installation."""
import json,subprocess,tempfile
from pathlib import Path
from query import route,lookup,ROOT
CASES=[
 ('Business Hours overnight schedule is wrong',{'directorist-business-hours'},'schedule'),
 ('Open now shows closed after midnight',{'directorist-business-hours'},'schedule'),
 ('Business Hours missing only when no plan',{'directorist-business-hours','directorist-pricing-plans'},'entitlements'),
 ('Business Hours empty in Divi template',{'directorist-business-hours','directorist-divi-integration'},'single-modules'),
 ('WooCommerce checkout payment fails',{'directorist-woocommerce-pricing-plans'},'orders-subscriptions'),
 ('WooCommerce Pricing Plans quota',{'directorist-woocommerce-pricing-plans'},'products-entitlements'),
 ('Stripe webhook duplicate after renewal',{'directorist-stripe','directorist-pricing-plans'},'webhooks'),
 ('io is not defined when messaging owner',{'directorist-live-chat'},'socket-delivery'),
 ('weekly report repeats duplicate last listing',{'directorist-analytics'},'tracking-reports'),
 ('Search Alert monthly alert never arrives',{'directorist-search-alert'},'alert-delivery'),
 ('google import field mapping place id duplicates',{'directorist-listing-import'},'google-import'),
 ('Claim Listing claimed badge tooltip',{'directorist-claim-listing'},'paid-claim-badge'),
 ('directorist-pricing-plans-new plan migration',{'directorist-pricing-plans'},'migration-admin'),
 ('WPML translated listing missing',{'directorist-wpml-integration'},'queries-links'),
 ('M-Pesa ratiba callback duplicate',{'directorist-mpesa-payment-gateway'},'callback-recurring'),
 ('font padding looks wrong',set(),None),
 ('unknown zephyr failure',set(),None),
 ('business hours are not saving',{'directorist-business-hours'},'schedule'),
 ('does not show open after midnight',{'directorist-business-hours'},'schedule'),
 ('without a plan business hours are not appearing',{'directorist-business-hours','directorist-pricing-plans'},'entitlements'),
 ('business hours are blank in divi',{'directorist-business-hours','directorist-divi-integration'},'single-modules'),
 ('weekly report keeps repeating the last listing',{'directorist-analytics'},'tracking-reports'),
 ('saved search emails do not arrive',{'directorist-search-alert'},'alert-delivery'),
 ('woocommerce payment is not working',{'directorist-woocommerce-pricing-plans'},'orders-subscriptions'),
 ('no pricing plan selected business hours do not appear',{'directorist-business-hours','directorist-pricing-plans'},'entitlements'),
 ('hours are blank in divi',{'directorist-business-hours','directorist-divi-integration'},'single-modules'),
 ('saved search email never arrives',{'directorist-search-alert'},'alert-delivery'),
 ('gallery image import is not working',{'directorist-gallery'},'gallery-import'),
 ('claim badge tooltip is incorrect',{'directorist-claim-listing'},'paid-claim-badge'),
]
def main():
 inv=json.loads((ROOT/'inventory.json').read_text())
 name_cases=[]
 for p in inv:
  for label in [p['name'],p['id']]:
   r=route(label);got={x['product'] for x in r['candidates']}
   assert got=={p['id']},(label,got,p['id'])
   name_cases.append(label)
 results=[]
 for text,expected,topic in CASES:
  r=route(text);got={c['product'] for c in r['candidates']}
  assert got==expected,(text,got,expected)
  paths=[p for c in r['candidates'] for p in [c['identity'],*c['topics']]]
  assert len(paths)<=9,(text,len(paths))
  assert all((ROOT/p).is_file() for p in paths)
  if topic:assert any(p.endswith('/'+topic+'.md') for p in paths),(text,topic,paths)
  results.append({'query':text,'products':sorted(got),'loaded_identity_topic_files':len(paths),'selected_paths':paths,'initial_utf8_bytes':sum((ROOT/p).stat().st_size for p in paths),'initial_words':sum(len((ROOT/p).read_text().split()) for p in paths)})
 h=lookup('directorist_field_template',kind='hooks',limit=1000)
 assert any(x['product']=='directorist-core' and x['operation']=='apply_filters' for x in h['shown'])
 assert any(x['product']=='directorist-business-hours' and x['operation']=='add_filter' for x in h['shown'])
 assert lookup('directorist_get_wp_default_timezone_identifier',product='directorist-business-hours')['total']>0
 with tempfile.TemporaryDirectory(prefix='directorist-skill-install-test-') as tmp:
  cmd=['python3',str(ROOT/'scripts/install_skills.py'),'--destination',tmp]
  subprocess.run(cmd,check=True,capture_output=True,text=True)
  subprocess.run(cmd+['--check'],check=True,capture_output=True,text=True)
  target=Path(tmp)/'directorist-ext-business-hours/SKILL.md';target.write_text('unrelated existing content')
  r=subprocess.run(cmd,capture_output=True,text=True)
  assert r.returncode!=0 and target.read_text()=='unrelated existing content'
  r=subprocess.run(cmd+['--update-managed'],capture_output=True,text=True)
  assert r.returncode!=0 and target.read_text()=='unrelated existing content'
 report={'exact_name_repo_cases_passed':len(name_cases),'routing_cases_passed':len(results),'cases':results,'source_lookup':'Core emitter and Business Hours subscriber verified','installation':'fresh/identical accepted; differing existing skill refused and preserved'}
 (ROOT/'routing-validation.json').write_text(json.dumps(report,indent=2)+'\n');print(json.dumps({k:v for k,v in report.items() if k!='cases'},indent=2))
if __name__=='__main__':main()
