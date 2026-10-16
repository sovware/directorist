#!/usr/bin/env python3
"""Install portable repo skills with canonical links, without overwriting other skills."""
import argparse,hashlib,json,re
from pathlib import Path
ROOT=Path(__file__).resolve().parents[1]
def main():
 ap=argparse.ArgumentParser();ap.add_argument('--destination',type=Path,default=Path.home()/'.codex/skills');ap.add_argument('--check',action='store_true');ap.add_argument('--update-managed',action='store_true');a=ap.parse_args()
 plan=[]
 for file in sorted((ROOT/'skills').glob('*/SKILL.md')):
  name=file.parent.name;body=file.read_text()
  def absolute(m):
   rel=m.group(1);target=(file.parent/rel).resolve()
   if not target.exists():raise ValueError(f'Broken canonical reference {file}: {rel}')
   return '(<'+str(target)+'>)'
  body=re.sub(r'\((\.\./\.\./[^)]+)\)',absolute,body)
  dest=a.destination/name/'SKILL.md'
  if dest.exists() and dest.read_text()!=body:
   marker=dest.parent/'.directorist-extension-install.json'
   owned=json.loads(marker.read_text()) if marker.exists() else {}
   expected=hashlib.sha256(dest.read_bytes()).hexdigest()
   if not a.update_managed or owned.get('sha256')!=expected or owned.get('canonical_docs')!=str(ROOT):
    raise SystemExit(f'Existing skill differs; left untouched: {dest}. Review the change; --update-managed only replaces a previously installed, unchanged managed copy.')
  if dest.parent.exists() and not dest.exists():raise SystemExit(f'Existing unrelated folder left untouched: {dest.parent}')
  plan.append((dest,body))
 if a.check:
  missing=[str(p) for p,b in plan if not p.exists() or p.read_text()!=b]
  print(json.dumps({'skills':len(plan),'missing':missing,'status':'verified' if not missing else 'not-installed'},indent=2));return bool(missing)
 for dest,body in plan:
  dest.parent.mkdir(parents=True,exist_ok=True)
  if not dest.exists() or dest.read_text()!=body:dest.write_text(body)
  marker=dest.parent/'.directorist-extension-install.json'
  marker.write_text(json.dumps({'canonical_docs':str(ROOT),'sha256':hashlib.sha256(body.encode()).hexdigest()},indent=2)+'\n')
 print(json.dumps({'installed_or_identical':len(plan),'destination':str(a.destination),'canonical_docs':str(ROOT)}));return 0
if __name__=='__main__':raise SystemExit(main())
