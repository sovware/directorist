#!/usr/bin/env python3
"""Fingerprint Core PHP contracts without loading WordPress."""
import hashlib,json,subprocess
from pathlib import Path
ROOT=Path(__file__).resolve().parents[1];repo=ROOT.parents[1]
files=[repo/'directorist-base.php',repo/'config.php']+sorted((repo/'includes').rglob('*.php'))
files=[f for f in files if not any(x in f.parts for x in ['vendor','node_modules'])]
r=subprocess.run(['php',str(ROOT/'scripts/scan_php.php')],input=json.dumps([str(f) for f in files]),text=True,capture_output=True,check=True)
data=json.loads(r.stdout);out={}
for f in files:
 out[f.relative_to(repo).as_posix()]={**data[str(f)],'sha256':hashlib.sha256(f.read_bytes()).hexdigest()}
commit=subprocess.run(['git','-C',str(repo),'rev-parse','HEAD'],capture_output=True,text=True,check=True).stdout.strip()
(ROOT/'core/evidence.json').write_text(json.dumps({'path':str(repo),'commit':commit,'files':out},indent=2)+'\n')
print(json.dumps({'core_commit':commit,'files':len(out)}))
