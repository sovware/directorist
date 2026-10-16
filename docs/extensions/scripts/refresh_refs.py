#!/usr/bin/env python3
"""Read-only catalog and authenticated branch drift report. No checkout or edits."""
import argparse,html,json,re,subprocess,urllib.request
from pathlib import Path
ROOT=Path(__file__).resolve().parents[1]
def main():
 ap=argparse.ArgumentParser();ap.add_argument('--output',type=Path);a=ap.parse_args()
 catalog=urllib.request.urlopen('https://directorist.com/extensions/',timeout=30).read().decode()
 boundary=re.search(r'<section[^>]+class="third-party-extensions(?:\s|")',catalog)
 if not boundary:raise SystemExit('Official/third-party section boundary unavailable; manual inventory review required.')
 section=catalog[:boundary.start()]
 products={u:html.unescape(n.strip()) for u,n in re.findall(r'<h3>\s*<a href="([^"]+)">(.*?)</a>',section,re.S)}
 expected={r['url']:r['name'] for r in json.loads((ROOT/'inventory.json').read_text())}
 if not products:raise SystemExit('No catalog entries extracted; do not replace inventory with empty data.')
 old=json.loads((ROOT/'branches.json').read_text());names=list(old)
 q='query {'+' '.join('r'+str(i)+': repository(owner:"sovware", name:"'+n+'") { name defaultBranchRef { name target { oid } } refs(refPrefix:"refs/heads/",first:100) { pageInfo {hasNextPage endCursor} nodes { name target { ... on Commit { oid committedDate messageHeadline } } } } }' for i,n in enumerate(names))+'}'
 raw=subprocess.run(['gh','api','graphql','-f','query='+q],text=True,capture_output=True,check=True)
 reply=json.loads(raw.stdout)
 if reply.get('errors'):raise SystemExit('GitHub query incomplete; review authenticated access before using result.')
 changes=[]
 for v in reply['data'].values():
  if not v or v['refs']['pageInfo']['hasNextPage']:raise SystemExit('Missing repository or branch pagination; manual continuation required.')
  before={x['name']:x['target']['oid'] for x in old[v['name']]['refs']['nodes']};after={x['name']:x['target']['oid'] for x in v['refs']['nodes']}
  for branch in set(before)|set(after):
   if before.get(branch)!=after.get(branch):changes.append({'repo':v['name'],'branch':branch,'old':before.get(branch),'new':after.get(branch)})
 report={'catalog_added':{u:products[u] for u in products.keys()-expected.keys()},'catalog_removed':{u:expected[u] for u in expected.keys()-products.keys()},'catalog_renamed':{u:[expected[u],products[u]] for u in products.keys()&expected.keys() if products[u]!=expected[u]},'branch_changes':changes,'policy':'Review actual file changes before updating source selection; date/version/tag/default does not establish supported implementation.'}
 txt=json.dumps(report,indent=2)
 if a.output:a.output.write_text(txt+'\n')
 print(txt)
if __name__=='__main__':main()
