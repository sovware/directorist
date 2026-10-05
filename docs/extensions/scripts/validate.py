#!/usr/bin/env python3
"""Validate documentation/source references and selective navigation invariants."""
import json,re,sys,hashlib
from pathlib import Path
from urllib.parse import unquote,urlparse
ROOT=Path(__file__).resolve().parents[1]
def main():
 errors=[];inventory=json.loads((ROOT/'inventory.json').read_text());selection=json.loads((ROOT/'selection.json').read_text());sources={};line_cache={};links=0
 assert len(inventory)==40 and {i['id'] for i in inventory}=={i['id'] for i in selection['products']}
 for p in inventory:
  for s in p['snapshots']:sources[(s['repo'],s['commit'])]=Path(s['path'])
 def check_line(file,line,label):
  if not file.is_file():errors.append('Missing source '+str(file)+' from '+label);return
  if line:
   if file not in line_cache:
    try:line_cache[file]=len(file.read_text(errors='replace').splitlines())
    except OSError:line_cache[file]=0
   if line<1 or line>max(1,line_cache[file]):errors.append('Invalid source line '+str(file)+':'+str(line))
 for md in ROOT.rglob('*.md'):
  txt=md.read_text()
  for m in re.finditer(r'\[[^\]\n]*\]\((<[^>]+>|[^)\s]+)\)',txt):
   links+=1;dest=m.group(1).strip('<>')
   if dest.startswith('https://github.com/sovware/'):
    parts=urlparse(dest);bits=unquote(parts.path).split('/')
    if len(bits)>5 and bits[3]=='blob':
     key=(bits[2],bits[4]);base=sources.get(key)
     if base:check_line(base/'/'.join(bits[5:]),int(parts.fragment[1:]) if re.fullmatch('L[0-9]+',parts.fragment) else None,str(md))
     else:errors.append('Uninspected source ref '+dest)
   elif dest.startswith(('http:','https:','mailto:','#')):continue
   else:
    dest=unquote(dest.split('#')[0]);line=None
    if re.search(r':\d+$',dest):dest,last=dest.rsplit(':',1);line=int(last)
    target=Path(dest) if dest.startswith('/') else md.parent/dest
    if not target.exists():errors.append('Broken local link '+str(md)+' -> '+dest)
    elif line:check_line(target,line,str(md))
  if md.parent.name=='topics' and not md.name.endswith('-evidence.md') and len(txt.split())>800:errors.append('Topic exceeds small-read budget '+str(md))
 for p in inventory:
  skill=ROOT/'skills'/p['skill']/'SKILL.md';s=skill.read_text()
  if not s.startswith('---\nname: '+p['skill']+'\n') or '\ndescription:' not in s:errors.append('Invalid skill '+str(skill))
  if len(s.split())>400:errors.append('Skill too large '+p['skill'])
  for snap in p['snapshots']:
   ev=json.loads((ROOT/'products'/p['id']/'evidence'/(snap['key']+'.json')).read_text())
   if ev['snapshot']['commit']!=snap['commit'] or not ev['files']:errors.append('Snapshot identity mismatch '+snap['key'])
   for f,r in ev['files'].items():
    if not re.fullmatch('[a-f0-9]{64}',r['sha256']):errors.append('Bad file fingerprint '+f)
    for z in r['symbols']+r['calls']:check_line(Path(snap['path'])/f,z['line'],snap['key'])
 core=json.loads((ROOT/'core/evidence.json').read_text())
 for f,r in core['files'].items():
  file=Path(core['path'])/f
  if not file.exists() or hashlib.sha256(file.read_bytes()).hexdigest()!=r['sha256']:errors.append('Core source drift '+f)
 report={'products':len(inventory),'skills':len(list((ROOT/'skills').glob('*/SKILL.md'))),'topics':sum(len(p['topics']) for p in inventory),'snapshots':sum(len(p['snapshots']) for p in inventory),'markdown_links_checked':links,'source_files_line_checked':len(line_cache),'errors':errors,'scope':'Local docs and inspected source targets checked; no remote HTTP or runtime compatibility assertion.'}
 (ROOT/'validation-results.json').write_text(json.dumps(report,indent=2)+'\n');print(json.dumps(report,indent=2));return bool(errors)
if __name__=='__main__':sys.exit(main())
