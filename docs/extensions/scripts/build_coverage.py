#!/usr/bin/env python3
"""Explain source coverage and external dependency evidence without semantic overclaims."""
import collections,json,re
from pathlib import Path
from urllib.parse import quote
from curation import C
ROOT=Path(__file__).resolve().parents[1]
def ref(s,f,line=1):
 url=('<'+s['path']+'/'+f+':'+str(line)+'>') if s['kind']=='local' else 'https://github.com/sovware/'+s['repo']+'/blob/'+s['commit']+'/'+quote(f)+'#L'+str(line)
 return f'[{f}:{line}]({url})'
def row(*a):return '| '+' | '.join(str(x).replace('|','\\|') for x in a)+' |'
def classify(repo,f,r,topics):
 if r['classification']!='source':return 'support-or-generated','Fingerprint only; build/library/tooling/assets are not declared first-party runtime features.'
 hay=f+' '+' '.join(z['name'] for z in r['symbols']);matched=[t['id'] for t in topics if re.search(t['selectors'],hay,re.I)]
 if matched:return 'feature-linked',','.join(matched)
 if repo=='directorist-gamipress-integration' and f.startswith('inc/'):
  return 'legacy-candidate','FAQ-named compatibility/copy files; bootstrap reachability must be checked before treating these as GamiPress features.'
 if re.search(r'(^|/)(Models|DTO|Enums|Middleware|Traits)/|^database/|/Controllers/(Controller|UserController)\.php$',f):return 'infrastructure','Data shapes, shared authorization/framework or schema bootstrap; inspect when a routed feature reaches this layer.'
 if re.search(r'(^|/)(Init|init|includes|config|config-helper|const|const-helper|app)\.php$|Enqueu|enqueue|UpdateServiceProvider|MenuServiceProvider|warning-notice|helper\.php$|helpers\.php$|Helper/Serve|Helpers/helper|class-helper|utils\.js$',f,re.I):return 'infrastructure','Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature.'
 if f in ['resources/js/app.js','resources/views/index.php','app/Model/index.php','inc/index.php','inc/class-db.php'] or f.endswith(('helper-functions.php','updater.js')) or f.startswith('config/'):
  return 'infrastructure','Inspected scaffold/empty guard, shared helper, licensing/configuration or updater; not an independent user feature.'
 if '/' not in f and f.endswith('.php'):return 'infrastructure','Plugin bootstrap/header or shared root configuration; inspect include/registration chain.'
 if f.endswith(('.json','.scss','.css','.xml')):return 'infrastructure','Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence.'
 if re.search(r'/Base/(Activate|Enqueue)\.php$|/Admin/(Test|TestProvider)\.php$|routes/',f):return 'infrastructure','Registration/framework/test provider; active reachability must be verified against bootstrap.'
 return 'unresolved-feature-candidate','Not assigned by reviewed feature selectors; inspect source before claiming semantic coverage.'
def main():
 inv=json.loads((ROOT/'inventory.json').read_text());report=[]
 for p in inv:
  s=next(s for s in p['snapshots'] if s['key']==p['primary']);base=Path(s['path']);folder=ROOT/'products'/p['id'];files=json.loads((folder/'evidence'/(s['key']+'.json')).read_text())['files'];counts=collections.Counter();unresolved=[];rows=[];guards=[]
  for f,r in files.items():
   category,why=classify(p['id'],f,r,C[p['id']]['topics']);counts[category]+=1
   if category=='unresolved-feature-candidate':unresolved.append(f)
   target=', '.join(f'[{t}](topics/{t}.md)' for t in why.split(',')) if category=='feature-linked' else why
   rows.append(row(ref(s,f),category,target))
   for c in r['calls']:
    if c['name'] in ['class_exists','function_exists','defined','is_plugin_active','is_plugin_active_for_network'] or c['name'].startswith('wp_remote_'):
     guards.append(row(ref(s,f,c['line']),c['name'],'`'+', '.join(c['args']).replace('`','')+'`'))
  text=['# Feature and infrastructure coverage','','Canonical snapshot: `'+s['key']+'`.','', 'Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.','','Counts: '+', '.join(f'{k}={v}' for k,v in sorted(counts.items()))+'.','', 'Unresolved feature candidates: '+str(len(unresolved))+'.','', '| Source | Classification | Topic or reason |','| --- | --- | --- |',*rows]
  (folder/'coverage.md').write_text('\n'.join(text)+'\n')
  deps=['# External dependencies and runtime guard evidence','','Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.','','## Declared packages','','| Manifest | Package | Constraint |','| --- | --- | --- |']
  for fn in ['composer.json','package.json']:
   f=base/fn
   if not f.exists():continue
   try:cfg=json.loads(f.read_text())
   except ValueError:continue
   keys=['require'] if fn=='composer.json' else ['dependencies','peerDependencies']
   for key in keys:
    for name,version in cfg.get(key,{}).items():deps.append(row(ref(s,fn),name,version))
  deps+=['','## Guards and external requests','','| Source | Check / request | Symbol / target expression |','| --- | --- | --- |',*guards,'','[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.']
  (folder/'dependencies.md').write_text('\n'.join(deps)+'\n')
  report.append({'id':p['id'],'primary':p['primary'],'counts':dict(counts),'unresolved':unresolved})
 (ROOT/'coverage-results.json').write_text(json.dumps(report,indent=2)+'\n')
 total=collections.Counter()
 for r in report:total.update(r['counts'])
 print(json.dumps({'products':len(report),'counts':dict(total),'unresolved':{r['id']:r['unresolved'] for r in report if r['unresolved']}},indent=2))
if __name__=='__main__':main()
