#!/usr/bin/env python3
"""Build navigation and mechanical evidence from explicitly selected local snapshots.
No network, checkout, plugin execution, or global skill installation.
"""
import argparse,collections,hashlib,json,os,re,subprocess
from pathlib import Path
from urllib.parse import quote
from curation import C
ROOT=Path(__file__).resolve().parents[1]
def dump(path,obj):
 path.parent.mkdir(parents=True,exist_ok=True);path.write_text(json.dumps(obj,indent=2,ensure_ascii=False)+'\n')
def write(path,text):
 path.parent.mkdir(parents=True,exist_ok=True);path.write_text(text.rstrip()+'\n')
def git(p,*args):
 r=subprocess.run(['git','-C',str(p),*args],capture_output=True,text=True)
 return r.stdout.strip() if r.returncode==0 else ''
def digest(f):return hashlib.sha256(f.read_bytes()).hexdigest()
def link(s,f,line=1):
 if s['kind']=='local':return '<'+s['path']+'/'+f+':'+str(line)+'>'
 return 'https://github.com/sovware/'+s['repo']+'/blob/'+s['commit']+'/'+quote(f)+'#L'+str(line)
def source_files(p):
 # Track every on-disk file except .git, dependencies and local transient artifacts.
 excludes={'.git','node_modules','vendor','vendor-src','.idea','.vscode','artifacts','__build'}
 out=[]
 for here,dirs,files in os.walk(p):
  dirs[:]=sorted(d for d in dirs if d not in excludes)
  for n in sorted(files):
   f=Path(here)/n
   if n=='.git' or f.is_symlink():continue
   out.append(f)
 return out

def analyze(s):
 p=Path(s['path']);fs=source_files(p);data={};php=[]
 for f in fs:
  rel=f.relative_to(p).as_posix();parts=[x.lower() for x in f.relative_to(p).parts]
  generated=any(x in parts for x in ['build','dist','languages','fonts','images','lib','library','libraries','sdk','2checkout','customsniffs','tests','scripts','dev-tools']) or '.min.' in f.name or 'edd' in f.name.lower() or f.suffix=='.map' or f.name in ['popper.js','fslightbox.js','daterangepicker.js','flatpickr.js','fullcalendar.js','jquery.timepicker.js','intlTelInput.js','jquery.repeater.js'] or '.test.' in f.name or 'webpack' in parts or f.name.startswith(('postcss.','webpack-entry-list','webpack.','Gruntfile.','gulpfile.','pot.js'))
  source=f.suffix.lower() in ['.php','.js','.jsx','.ts','.tsx','.vue','.json','.scss','.css','.xml'] and not generated
  rec={'sha256':digest(f),'bytes':f.stat().st_size,'classification':'source' if source else 'support-or-generated','symbols':[],'calls':[]}
  if source and f.suffix=='.php':php.append(str(f))
  elif source and f.suffix in ['.js','.jsx','.ts','.tsx','.vue']:
   txt=f.read_text(errors='replace')
   rec['symbols']=[{'name':m.group(1),'kind':'JS','line':txt[:m.start()].count('\n')+1} for m in re.finditer(r'(?:function\s+|class\s+|(?:export\s+)?const\s+)([A-Za-z_$][\w$]*)',txt)]
  data[rel]=rec
 if php:
  proc=subprocess.run(['php',str(ROOT/'scripts/scan_php.php')],input=json.dumps(php),text=True,capture_output=True,check=True)
  for f,r in json.loads(proc.stdout).items():data[str(Path(f).relative_to(p))].update(r)
 return data

def mdrow(*values):return '| '+' | '.join(str(v).replace('|','\\|').replace('\n',' ') for v in values)+' |'
def source_ref(s,f,line=1):return f'[{f}:{line}]({link(s,f,line)})'
def call_desc(c):return '`'+c['name']+'('+', '.join(c['args']).replace('`','')+')`'
def main():
 ap=argparse.ArgumentParser();ap.add_argument('--selection',type=Path,default=ROOT/'selection.json');a=ap.parse_args()
 selection=json.loads(a.selection.read_text());inventory=[];route=[]
 for item in selection['products']:
  repo=item['id'];cfg=C[repo];product=ROOT/'products'/repo
  snapshots=[]
  for s in item['snapshots']:
   p=Path(s['path']);assert p.is_dir(),p
   actual=git(p,'rev-parse','HEAD')
   if s['kind']!='local' and actual!=s['commit']:raise ValueError(f'Snapshot changed: {p}')
   data=analyze(s);snapshots.append((s,data));dump(product/'evidence'/f"{s['key']}.json",{'snapshot':s,'files':data})
  primary=next((s,d) for s,d in snapshots if s['key']==item['primary'])
  s,data=primary;headers=[]
  for f,r in data.items():
   if '/' in f or not f.endswith('.php'):continue
   txt=(Path(s['path'])/f).read_text(errors='replace')[:12000]
   name=re.search(r'Plugin Name:\s*(.+)',txt)
   if name:
    version=re.search(r'(?m)^\s*\*?\s*Version:\s*(.+)',txt)
    headers.append({'file':f,'name':name.group(1).strip(),'version':version.group(1).strip() if version else 'unavailable'})
  assert headers,repo
  aliases=list(dict.fromkeys([item['name'],repo,*[h['name'] for h in headers],*[h['file'] for h in headers],*item.get('aliases',[])]))
  intro=[f'# {item["name"]}', '',cfg['purpose'],'',f'Official catalog: [{item["name"]}]({item["url"]}). Catalog identity checked {selection["checked_at"]}.','',f'Canonical investigation baseline: **{s["repo"]} / {s["branch"]} / {s["commit"]}**. {item["selection_reason"]}','',f'Observed plugin header: '+', '.join(f'`{h["name"]}` — `{h["version"]}` ({source_ref(s,h["file"])})' for h in headers)+'. Header/tag is an identifier, not source authority or release proof.','', 'Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.','', '## Topics','',mdrow('Topic','Symptoms / feature boundary'),mdrow('---','---')]
  for t in cfg['topics']:intro.append(mdrow(f'[{t["id"]}](topics/{t["id"]}.md)',t['symptoms'].replace('|',', ')))
  intro+=['','## Source and compatibility','', '[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.', '[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.', '[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.', '[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.', '[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.', '', 'Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.','', 'If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).']
  write(product/'README.md','\n'.join(intro))
  # Variant ledger with explicit source hashes and differences, never silently merge branches.
  v=['# Source variants','','Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.','',mdrow('Snapshot','Branch / commit','File delta versus baseline','Evidence'),mdrow('---','---','---','---')]
  for ss,dd in snapshots:
   changed=sorted(f for f in set(dd)|set(data) if dd.get(f,{}).get('sha256')!=data.get(f,{}).get('sha256'))
   v.append(mdrow(ss['key'],ss['branch']+' / '+ss['commit'],len(changed),f'[machine index](evidence/{ss["key"]}.json)'))
   if changed:
    v+=['',f'## {ss["key"]} differences','', 'These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.']
    v.extend('- '+(source_ref(ss,f) if f in dd else '`'+f+'` (absent in this snapshot)') for f in changed if (dd.get(f) or data.get(f))['classification']=='source')
  v+=['','## Visible remote branch tips','','All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.','', 'Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.']
  write(product/'source-variants.md','\n'.join(v))
  # Full classified inventory includes all snapshot files; topic links keep routine reading small.
  fmd=['# Classified file inventory','','Every scanned file is classified and fingerprinted. First-party source declarations and literal call sites are indexed; this is structural coverage, not a guarantee that every semantic branch was manually reviewed. Dependency/vendor trees are deliberately excluded; their packages must be inspected if an issue reaches them.','',mdrow('Snapshot / file','Class','Symbols','Topic candidates'),mdrow('---','---','---','---')]
  contracts=['# Public contracts and dependency evidence','','Literal registrations, emissions and guards extracted with PHP tokenization. Dynamic hooks/callbacks need source evaluation; matching a hook name is a candidate relationship, not proof that a callback executes. Values of options/credentials are not collected.','',mdrow('Snapshot / source','Call / key / callback'),mdrow('---','---')]
  topic_files={t['id']:[] for t in cfg['topics']}
  # Select relevant source files by names AND declarations, but cap display through per-file references, not truncation of stored evidence.
  for ss,dd in snapshots:
   for f,r in dd.items():
    topic_ids=[]
    if r['classification']=='source':
     hay=f+' '+' '.join(x['name'] for x in r['symbols'])
     topic_ids=[t['id'] for t in cfg['topics'] if re.search(t['selectors'],hay,re.I)]
     for tid in topic_ids:topic_files[tid].append((ss,f,r))
    fmd.append(mdrow(ss['key']+' / '+source_ref(ss,f),r['classification'],len(r['symbols']),', '.join(f'[{t}](topics/{t}.md)' for t in topic_ids) or 'inspect by file / supporting infrastructure'))
    for c in r['calls']:
     if c['name'] in ['add_action','add_filter','do_action','apply_filters','class_exists','function_exists','defined','is_plugin_active','is_plugin_active_for_network','register_rest_route','register_post_type','register_taxonomy','register_shortcode']:
      contracts.append(mdrow(ss['key']+' / '+source_ref(ss,f,c['line']),call_desc(c)))
  write(product/'files.md','\n'.join(fmd));write(product/'contracts.md','\n'.join(contracts))
  for t in cfg['topics']:
   refs=topic_files[t['id']];assert refs,(repo,t['id'])
   body=[f'# {item["name"]}: {t["id"]}','','[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)','','## Behavior and limits','',t['contract'],'','This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.','','## Reproduction and browser regression recipe','',t['qa'],'','Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.','','## Entry points and feature coverage','', 'Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.','',mdrow('Snapshot / source','Functions or classes'),mdrow('---','---')]
   # Canonical first and deduplicate equal file content across variants; full fingerprints retained in JSON.
   refs.sort(key=lambda x:(x[0]['key']!=item['primary'],x[0]['key'],x[1]));seen=set();selected=[]
   for ss,f,r in refs:
    if (f,r['sha256']) in seen:continue
    seen.add((f,r['sha256']));selected.append((ss,f,r))
    body.append(mdrow(ss['key']+' / '+source_ref(ss,f),', '.join(f'`{z["name"]}` (L{z["line"]})' for z in r['symbols']) or 'template / configuration / styling; inspect file'))
   body+=['','## Data readers, writers and lifecycle','','Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.','',mdrow('Snapshot / source','Operation and key'),mdrow('---','---')]
   for ss,f,r in selected:
    for c in r['calls']:
     if c['name'] not in ['class_exists','function_exists','defined']:
      body.append(mdrow(ss['key']+' / '+source_ref(ss,f,c['line']),call_desc(c)))
   body+=['','## Core and integration context','','Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.']
   if cfg['dependencies']:
    body+=['','Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):']
    body += [f'- [{d}](../../{d}/README.md)' for d in cfg['dependencies']]
   body+=['','For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.']
   write(product/'topics'/f"{t['id']}-evidence.md",'\n'.join(body))
   # Keep the operational topic short. Large call/symbol ledgers load only on demand.
   lean=body[:body.index('## Entry points and feature coverage')]
   lean+=['## Relevant source entry points','','Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.','']
   primary_refs=[r for r in selected if r[0]['key']==item['primary']]
   ranked=sorted(primary_refs,key=lambda x:(not x[1].endswith('.php'), 0 if any(v in x[1] for v in ['/Controllers/','/Providers/','/Services/']) else (1 if '/' not in x[1] else 2), not bool(x[2]['symbols']),x[1]))
   lean += ['- '+source_ref(ss,f)+' — '+str(len(r['symbols']))+' declarations' for ss,f,r in ranked[:8]]
   lean += ['',f"[Complete topic source/data ledger]({t['id']}-evidence.md) (search only the relevant symbol/key).", '', '## Expand only when indicated','', 'Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).']
   lean += [f'- [{d}](../../{d}/README.md) — only if active configuration or source connects it.' for d in cfg['dependencies']]
   write(product/'topics'/f"{t['id']}.md",'\n'.join(lean))
  skill='directorist-ext-'+repo.removeprefix('directorist-')
  symptom_terms=list(dict.fromkeys(x for t in cfg['topics'] for x in t['symptoms'].split('|')))
  skillbody=f'''---
name: {skill}
description: Investigate or extend {item['name']} in WordPress Directorist. Route {', '.join(symptom_terms[:6])} issues to source-backed feature docs and verify the installed implementation.
---

# {item['name']}

Read [identity and topic index](../../products/{repo}/README.md), then only the issue-related topic linked there. A-Z is stored coverage, not a mandatory reading list. If the owner is uncertain, use [the small router](../../ROUTER.md); retain multiple candidates until evidence disambiguates them.

Canonical starting snapshot: `{s['branch']}` at `{s['commit']}`. Match actual changed files, active plugin basename and source hashes before diagnosing; version/tag/default branch is not authoritative. Read source-variants only when the installed files differ.

{cfg['purpose']}

For ticket work follow [focused evidence workflow](../../WORKFLOW.md) and reuse `rabbi-support-delivery-orchestrator` only for the required generic stages. An unsuccessful local reproduction does not invalidate the issue. Source, local, client, CI and release proof remain separate. This skill grants no additional mutation, sending or publishing authorization.
'''
  write(ROOT/'skills'/skill/'SKILL.md',skillbody)
  rec={'id':repo,'name':item['name'],'url':item['url'],'skill':skill,'primary':item['primary'],'headers':headers,'aliases':aliases,'topics':cfg['topics'],'dependencies':cfg['dependencies'],'snapshots':item['snapshots'],'source_files':sum(r['classification']=='source' for r in data.values()),'classified_files':len(data),'runtime_status':'not-tested'}
  inventory.append(rec);route.append({k:rec[k] for k in ['id','name','skill','aliases','topics','dependencies']})
 dump(ROOT/'inventory.json',inventory)
 # Compact routing data omits source evidence and large prose.
 dump(ROOT/'router.json',[{**r,'topics':[{'id':t['id'],'symptoms':t['symptoms']} for t in r['topics']]} for r in route])
 rows=['# Official extension index','','40 official catalog products, alphabetically indexed. Select one product/topic; do not read this entire corpus for one ticket. [Quick routing](ROUTER.md) · [Usage and validation](README.md) · [Inventory decisions](INVENTORY.md)','','| Product | Skill | Topics |','| --- | --- | --- |']
 for i in sorted(inventory,key=lambda r:r['name'].lower()):rows.append(mdrow(f'[{i["name"]}](products/{i["id"]}/README.md)',f'`${i["skill"]}`',', '.join(t['id'] for t in i['topics'])))
 write(ROOT/'INDEX.md','\n'.join(rows));print(json.dumps({'products':len(inventory),'topics':sum(len(r['topics']) for r in inventory),'snapshots':sum(len(r['snapshots']) for r in inventory)}))
if __name__=='__main__':main()
