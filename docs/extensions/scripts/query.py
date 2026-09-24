#!/usr/bin/env python3
"""Selective read-only routing, exact source lookups, and snapshot drift checks."""
import argparse,hashlib,json,re,sys
from pathlib import Path
ROOT=Path(__file__).resolve().parents[1]
def norm(s):return re.sub(r'[^\w]+',' ',s.lower()).strip()
def phrase(hay,needle):return bool(re.search(r'(?<!\w)'+re.escape(norm(needle))+r'(?!\w)',hay))
def route(text,limit=3):
 data=json.loads((ROOT/'router.json').read_text());expanded=text.lower();intake_matches=[]
 for phrase_text,replacement in json.loads((ROOT/'intake-aliases.json').read_text()).items():
  if phrase_text in expanded:
   expanded=expanded.replace(phrase_text,replacement);intake_matches.append(phrase_text)
 q=norm(expanded);scores=[];named=[]
 for r in data:
  aliases=r['aliases']+[r['id'].removeprefix('directorist-').replace('-',' '),r['name'].removeprefix('Directorist ')]
  for alias in aliases:
   if len(norm(alias))>=4 and phrase(q,alias):named.append((r['id'],norm(alias)))
 # A specific WooCommerce name must not implicitly select native Pricing Plans.
 named=[(rid,a) for rid,a in named if not any(rid!=other and a!=b and a in b for other,b in named)]
 named_ids={rid for rid,a in named}
 for r in data:
  if r['id']=='directorist-pricing-plans' and 'directorist-woocommerce-pricing-plans' in named_ids and r['id'] not in named_ids:
   continue
  topic_scores=[]
  identity_aliases=[norm(x) for x in r['aliases']]+[norm(r['id'].removeprefix('directorist-'))]
  for t in r['topics']:
   matches=[term for term in t['symptoms'].split('|') if phrase(q,term)]
   specific=[term for term in matches if not any(phrase(alias,term) for alias in identity_aliases)]
   # Identity matches choose product; meaningful feature terms choose a topic.
   matches=specific
   if r['id']=='directorist-divi-integration' and t['id']=='single-modules' and phrase(q,'divi') and any(phrase(q,x) for x in ['business hours','claim','faq','gallery','booking','job salary']):
    matches.append('extension module listing context')
   n=sum(2+len(norm(x).split()) for x in matches)
   topic_scores.append((n,t,matches))
  topic_scores.sort(key=lambda z:-z[0]);identity_symptom=any(phrase(q,term) and any(phrase(alias,term) for alias in identity_aliases) for t in r['topics'] for term in t['symptoms'].split('|'))
  score=(30 if r['id'] in named_ids else (3 if identity_symptom else 0))+topic_scores[0][0]
  if score:
   chosen=[x for x in topic_scores if x[0]>0 and x[0]>=topic_scores[0][0]*0.75][:2] or [topic_scores[0]]
   scores.append({'product':r['id'],'skill':r['skill'],'score':score,'identity':f'products/{r["id"]}/README.md','topics':[f'products/{r["id"]}/topics/{t["id"]}.md' for _,t,_ in chosen],'matched':list(dict.fromkeys([a for rid,a in named if rid==r['id']]+[m for _,_,ms in chosen for m in ms]))})
 scores.sort(key=lambda x:(-x['score'],x['product']))
 selected=scores[:limit]
 return {'query':text,'normalized_intake':expanded,'intake_alias_matches':intake_matches,'candidates':selected,'additional_candidates':max(0,len(scores)-len(selected)),'status':'candidate-owners-verify-source' if selected else 'unresolved-use-core-context-and-active-plugin-inventory','read_policy':'Read selected identities plus indicated topics only; do not open all dependency docs. Query hooks/symbols if needed.','fallback':'core/rendering.md' if any(phrase(q,x) for x in ['font','padding','css','layout']) else 'core/listings.md'}
def indexes(product=None,variants=False):
 inv=json.loads((ROOT/'inventory.json').read_text())
 for p in inv:
  if product and p['id']!=product:continue
  for s in p['snapshots']:
   if not variants and s['key']!=p['primary']:continue
   yield p,s,json.loads((ROOT/'products'/p['id']/'evidence'/f"{s['key']}.json").read_text())['files']
def lookup(term,product=None,variants=False,kind='search',limit=20):
 hits=[];needle=term.lower()
 for p,s,files in indexes(product,variants):
  for f,r in files.items():
   for c in r['calls']:
    if kind=='hooks':
     if c['name'] not in ['add_action','add_filter','do_action','apply_filters'] or not c['args']:continue
     if c['args'][0].strip('"\'')!=term:continue
    elif needle not in (c['name']+' '+' '.join(c['args'])).lower():continue
    hits.append({'product':p['id'],'snapshot':s['key'],'file':f,'line':c['line'],'operation':c['name'],'args':c['args']})
   if kind!='hooks':
    for z in r['symbols']:
     if needle in z['name'].lower():hits.append({'product':p['id'],'snapshot':s['key'],'file':f,**z})
 core=ROOT/'core/evidence.json'
 if core.exists() and not product:
  for f,r in json.loads(core.read_text())['files'].items():
   for c in r['calls']:
    ok=(c['name'] in ['add_action','add_filter','do_action','apply_filters'] and c['args'] and c['args'][0].strip('"\'')==term) if kind=='hooks' else needle in (c['name']+' '+' '.join(c['args'])).lower()
    if ok:hits.append({'product':'directorist-core','file':f,'line':c['line'],'operation':c['name'],'args':c['args']})
 return {'term':term,'total':len(hits),'shown':hits[:limit],'truncated':len(hits)>limit,'meaning':'Literal source matches only; verify active callback, arguments and priority. Increase --limit or use --product to narrow.'}
def drift(product=None):
 reports=[]
 for p,s,files in indexes(product,True):
  base=Path(s['path']);changed=[];missing=[]
  for f,r in files.items():
   target=base/f
   if not target.is_file():missing.append(f)
   elif hashlib.sha256(target.read_bytes()).hexdigest()!=r['sha256']:changed.append(f)
  # Use the same classifier so additions cannot silently escape the drift check.
  from build_maps import source_files
  actual={f.relative_to(base).as_posix() for f in source_files(base)} if base.is_dir() else set()
  added=sorted(actual-set(files))
  if changed or missing or added:reports.append({'product':p['id'],'snapshot':s['key'],'changed':changed,'missing':missing,'added':added})
 return {'drift':reports,'status':'changed' if reports else 'saved-snapshot-files-match','scope':'Local saved source snapshots only; does not refresh GitHub refs or inspect a client site.'}
def main():
 ap=argparse.ArgumentParser();ap.add_argument('mode',choices=['route','search','hooks','drift']);ap.add_argument('text',nargs='?',default='');ap.add_argument('--product');ap.add_argument('--variants',action='store_true');ap.add_argument('--limit',type=int,default=20);a=ap.parse_args()
 if a.mode=='route':r=route(a.text,min(a.limit,3))
 elif a.mode=='drift':r=drift(a.product)
 else:r=lookup(a.text,a.product,a.variants,a.mode,a.limit)
 print(json.dumps(r,indent=2,ensure_ascii=False));return 1 if a.mode=='drift' and r['drift'] else 0
if __name__=='__main__':sys.exit(main())
