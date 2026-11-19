import { readdirSync, readFileSync, existsSync } from 'node:fs';
import { resolve, relative, extname } from 'node:path';
import { spawnSync } from 'node:child_process';
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
function verifySyntax(command, args, file) {
  const result = spawnSync(command, args, { encoding: 'utf8' });
  if (result.status === 0 && !result.error) return;

  console.error(`Syntax check failed for ${relative(root, file)}`);
  if (result.error) console.error(result.error.message);
  if (result.signal) console.error(`Parser stopped with signal ${result.signal}`);
  if (result.stdout?.trim()) console.error(result.stdout.trim());
  if (result.stderr?.trim()) console.error(result.stderr.trim());
  process.exit(1);
}

walk(root);
let phpCount = 0, jsCount = 0;
for (const file of files) {
  if (extname(file) === '.php') {
    verifySyntax(php, ['-l', file], file);
    phpCount++;
  }
  if (['.js', '.mjs'].includes(extname(file))) {
    verifySyntax(process.execPath, ['--check', file], file);
    jsCount++;
  }
  if (!['.php', '.js', '.mjs', '.md', '.yml', '.yaml', '.json'].includes(extname(file))) continue;
  const text = readFileSync(file, 'utf8');
  if (/-----BEGIN (?:RSA |EC |OPENSSH )?PRIVATE KEY-----/.test(text) || /gh[pousr]_[A-Za-z0-9]{30,}/.test(text)) {
    throw new Error(`Possible credential in ${relative(root, file)}; inspect privately.`);
  }
}
console.log(`Syntax verified: ${phpCount} PHP and ${jsCount} JavaScript files. Credential patterns checked; this is not a complete vulnerability audit.`);
