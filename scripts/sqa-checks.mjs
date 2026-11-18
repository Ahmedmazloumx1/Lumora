import { readdirSync, readFileSync, existsSync } from 'node:fs';
import { resolve, relative, extname } from 'node:path';
import { execFileSync } from 'node:child_process';
const root = resolve(import.meta.dirname, '..');
const php = process.env.PHP_BINARY || (existsSync(resolve(root, '.tools/php/php.exe')) ? resolve(root, '.tools/php/php.exe') : 'php');
const files = [];
function walk(dir) {
  for (const entry of readdirSync(dir, { withFileTypes: true })) {
    if (['.git', '.tools', 'archive', 'storage', 'legacy', 'node_modules'].includes(entry.name)) continue;
    const path = resolve(dir, entry.name);
    if (entry.isDirectory()) walk(path);
    else if (entry.name !== 'local.php') files.push(path);
  }
}
walk(root);
let phpCount = 0, jsCount = 0;
for (const file of files) {
  if (extname(file) === '.php') { execFileSync(php, ['-l', file], { stdio: 'pipe' }); phpCount++; }
  if (['.js', '.mjs'].includes(extname(file))) { execFileSync(process.execPath, ['--check', file], { stdio: 'pipe' }); jsCount++; }
  if (!['.php', '.js', '.mjs', '.md', '.yml', '.yaml', '.json'].includes(extname(file))) continue;
  const text = readFileSync(file, 'utf8');
  if (/-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----/.test(text) || /gh[pousr]_[A-Za-z0-9]{30,}/.test(text)) {
    throw new Error(`Possible credential in ${relative(root, file)}; inspect privately.`);
  }
}
console.log(`Syntax verified: ${phpCount} PHP and ${jsCount} JavaScript files. Credential patterns checked; this is not a complete vulnerability audit.`);
