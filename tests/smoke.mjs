import assert from 'node:assert/strict';
import { spawn, execFileSync } from 'node:child_process';
import { randomBytes } from 'node:crypto';
import { existsSync, readdirSync, unlinkSync } from 'node:fs';
import { resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import http from 'node:http';

const root = fileURLToPath(new URL('../', import.meta.url));
const php = process.env.PHP_BINARY || (existsSync(resolve(root, '.tools/php/php.exe')) ? resolve(root, '.tools/php/php.exe') : 'php');
const env = { ...process.env, DB_NAME: `lumora_test_${randomBytes(6).toString('hex')}`, APP_ENV: 'production' };
const port = Number(process.env.TEST_PORT || 8011);
const base = `http://127.0.0.1:${port}`;
const run = (...args) => execFileSync(php, args, { cwd: root, env, encoding: 'utf8' }).trim();
const originalImages = new Set(readdirSync(resolve(root, 'public/img')));
let server;
let checks = 0;
let logs = '';
const check = (condition, description) => { assert.ok(condition, description); checks++; console.log(`PASS ${description}`); };

function requestHttp(path, { method = 'GET', headers = {}, body } = {}) {
  return new Promise((resolve, reject) => {
    const request = http.request(base + path, { method, headers }, response => {
      let text = '';
      response.setEncoding('utf8');
      response.on('data', chunk => { text += chunk; });
      response.on('end', () => resolve({ status: response.statusCode, headers: response.headers, text }));
      response.on('error', reject);
    });
    request.on('error', reject);
    request.setTimeout(10000, () => request.destroy(new Error('HTTP request timed out')));
    request.end(body);
  });
}

class Client {
  cookie = '';
  token = '';
  async request(path, data, options = {}) {
    const headers = { ...options.headers };
    if (this.cookie) headers.Cookie = this.cookie;
    let body;
    if (data instanceof FormData) {
      const serialized = new Response(data);
      body = Buffer.from(await serialized.arrayBuffer());
      headers['Content-Type'] = serialized.headers.get('content-type');
    } else if (data) {
      headers['Content-Type'] = 'application/x-www-form-urlencoded';
      body = Buffer.from(new URLSearchParams(data).toString());
    }
    if (body) headers['Content-Length'] = body.length;
    const response = await requestHttp(path, { method: data ? 'POST' : 'GET', headers, body });
    const cookie = response.headers['set-cookie']?.[0];
    if (cookie) this.cookie = cookie.split(';')[0];
    const text = response.text;
    check(!/Fatal error|Warning:|Deprecated:|SQLSTATE|<h1>Server error/.test(text), `${path} has no PHP errors`);
    const token = text.match(/name="csrf_token" value="([a-f0-9]+)"/);
    if (token) this.token = token[1];
    return { status: response.status, text, location: response.headers.location };
  }
  post(path, data) { return this.request(path, { csrf_token: this.token, ...data }); }
  async page(path) {
    const result = await this.request(path);
    check(result.status === 200, `${path} responds 200`);
    return result.text;
  }
}

try {
  run('scripts/setup.php', '--demo');
  const fixtures = JSON.parse(run('tests/fixtures.php', 'seed'));
  const adminPassword = randomBytes(20).toString('hex');
  execFileSync(php, ['scripts/create-admin.php'], { cwd: root, env: { ...env, ADMIN_EMAIL: 'admin@example.test', ADMIN_PASSWORD: adminPassword } });
  server = spawn(php, ['-S', `127.0.0.1:${port}`, 'router.php'], { cwd: root, env, stdio: ['ignore', 'pipe', 'pipe'] });
  server.stdout.on('data', chunk => { logs += chunk; });
  server.stderr.on('data', chunk => { logs += chunk; });
  let ready = false;
  for (let attempt = 0; attempt < 50; attempt++) {
    try { if ((await requestHttp('/')).status === 200) { ready = true; break; } } catch {}
    await new Promise(resolve => setTimeout(resolve, 100));
  }
  assert.ok(ready, 'Test server failed to start: ' + logs);
  const guest = new Client();
  for (const path of ['/', '/login', '/register', '/products', '/about', '/faq', '/policies', '/cart', '/public/css/style.css', '/public/img/placeholder.svg']) await guest.page(path);
  check((await guest.request('/missing')).status === 404, 'Unknown route returns 404');
  check((await guest.request('/product?slug=missing')).status === 404, 'Missing product returns 404');
  for (const path of ['/config/config.php', '/archive/legacy/reset_admin.php', '/database/schema.sql', '/.tools/php/php.ini', '/bootstrap.php', '/app/Core/Database.php']) {
    check((await guest.request(path)).status === 404, `${path} is not publicly accessible`);
  }
  for (const path of ['/account', '/checkout', '/wishlist', '/custom-order', '/admin']) {
    check((await guest.request(path)).location === '/login', `${path} requires authentication`);
  }
  check((await guest.request('/cart/add', { product_id: fixtures.first })).status === 403, 'Missing CSRF token rejected');
  check((await guest.request('/cart/add', { product_id: fixtures.first, 'csrf_token[]': 'bad' })).status === 403, 'Array CSRF token rejected');
  check((await guest.request('/api/search-suggestions?query=Lumora')).text.includes('lumora-sample-wall-art'), 'Search suggestions return matching products');
  await guest.page('/product?slug=lumora-sample-wall-art');
  check((await guest.post('/cart/add', { product_id: fixtures.first, quantity: 0 })).status === 422, 'Zero quantity rejected');
  check((await guest.post('/cart/add', { product_id: fixtures.second, variant_id: fixtures.variant })).status === 422, 'Mismatched variant rejected');
  check((await guest.post('/cart/add', { product_id: fixtures.first, quantity: 2 })).location === '/cart', 'Guest can add product');
  const cart = await guest.page('/cart');
  const itemId = cart.match(/name="item_id" value="(\d+)"/)[1];
  const other = new Client();
  await other.page('/login');
  await other.page('/cart');
  check((await other.post('/cart/update', { item_id: itemId, quantity: 5 })).status === 422, 'Another guest cannot update cart item');
  check((await other.post('/cart/remove', { item_id: itemId })).status === 422, 'Another guest cannot remove cart item');
  check((await guest.post('/cart/update', { item_id: itemId, quantity: 500 })).status === 422, 'Quantity cannot exceed stock');
  check(JSON.parse((await guest.post('/cart/update', { item_id: itemId, quantity: 3 })).text).success, 'Owner can update quantity');
  check((await guest.request('/cart/update', { csrf_token: guest.token, item_id: itemId, quantity: 2 }, { headers: { Accept: 'text/html' } })).location === '/cart', 'Cart quantity form redirects back to cart');
  await guest.post('/cart/update', { item_id: itemId, quantity: 2 });
  check((await other.post('/cart/add', { product_id: fixtures.first, variant_id: fixtures.variant, quantity: 1 })).location === '/cart', 'Valid variant can be added');
  const variantCart = await other.page('/cart');
  check(variantCart.includes('650.00') && variantCart.includes('Large'), 'Variant price and attributes render');
  const variantItem = variantCart.match(/name="item_id" value="(\d+)"/)[1];
  check((await other.post('/cart/remove', { item_id: variantItem })).location === '/cart', 'Owner can remove cart item');
  check(!(await other.page('/cart')).includes('Lumora Sample Wall Art'), 'Removed product leaves cart');
  check((await guest.post('/register', { name: 'Test', email: 'bad-email', password: 'validpassword', contact: '01012345678' })).location === '/register', 'Malformed email registration rejected');
  const oldCookie = guest.cookie;
  await guest.page('/register');
  const password = 'Test <&" password 123';
  check((await guest.post('/register', { name: 'Test Customer', email: 'customer@example.test', password, contact: '01012345678', city: 'Cairo', address: 'Test street' })).location === '/', 'Customer registration succeeds');
  check(guest.cookie !== oldCookie, 'Session ID rotates after registration');
  check((await guest.page('/cart')).includes(`value="${itemId}"`) === false, 'Guest cart transferred into user cart');
  check((await guest.page('/cart')).includes('Lumora Sample Wall Art'), 'Transferred cart retains product');
  for (const path of ['/account', '/checkout', '/wishlist', '/custom-order', '/custom-order/status']) await guest.page(path);
  check((await guest.request('/admin')).location === '/login', 'Customer cannot access admin');
  check(JSON.parse((await guest.post('/wishlist/toggle', { product_id: fixtures.first })).text).is_wishlisted === true, 'Wishlist adds product');
  check((await guest.page('/wishlist')).includes('Lumora Sample Wall Art'), 'Wishlist displays saved product');
  check((await guest.post('/account/profile', { name: 'Customer <&>', contact: '01012345678', city: 'Cairo', address: 'Updated street' })).location === '/account', 'Profile updates');
  check((await guest.page('/account')).includes('Customer &lt;&amp;&gt;'), 'Profile text escapes HTML once');
  check((await guest.post('/custom-order', { brand: 'Test brand', model: 'Test model', art_style: 'Silhouette', art_size: 'Medium', custom_text: 'Custom <&>' })).location === '/custom-order/status', 'Custom order submits');
  check((await guest.page('/custom-order/status')).includes('Test brand'), 'Custom order status displays request');
  check((await guest.post('/checkout', { shipping_name: 'Test Customer', shipping_phone: '01012345678', shipping_city: 'Cairo', shipping_address: 'Test street' })).location === '/checkout/success', 'Checkout succeeds');
  await guest.page('/checkout/success');
  check(run('tests/fixtures.php', 'verify-order').includes('verified'), 'Order totals, stock, and cart clearing correct');
  await guest.post('/cart/add', { product_id: fixtures.first, quantity: 1 });
  run('tests/fixtures.php', 'exhaust-stock');
  check((await guest.post('/checkout', { shipping_name: 'Test Customer', shipping_phone: '01012345678', shipping_city: 'Cairo', shipping_address: 'Test street' })).location === '/checkout', 'Checkout rejects exhausted stock');
  check(run('tests/fixtures.php', 'verify-rollback').includes('rolled back'), 'Failed checkout creates no order');
  await guest.post('/logout', {});
  await guest.page('/login');
  check((await guest.post('/login', { email: 'customer@example.test', password })).location === '/', 'Password with special characters authenticates');
  const admin = new Client();
  await admin.page('/login');
  check((await admin.post('/login', { email: 'admin@example.test', password: adminPassword })).location === '/', 'Administrator login succeeds');
  await admin.page('/');
  for (const path of ['/admin', '/admin/products', '/admin/orders', '/admin/users', '/admin/categories', '/admin/custom-orders', '/admin/content']) await admin.page(path);
  check((await admin.post('/admin/content/update', { about_us_content: 'About <&>', 'faq_questions[]': 'Question <&>', 'faq_answers[]': 'Answer <&>' })).location === '/admin/content', 'CMS accepts FAQ arrays');
  check((await admin.page('/faq')).includes('Question &lt;&amp;&gt;'), 'FAQ text renders once escaped');
  const upload = new FormData();
  upload.set('csrf_token', admin.token);
  upload.set('site_logo', new Blob([Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6m3cAAAAASUVORK5CYII=', 'base64')], { type: 'image/png' }), 'logo.png');
  check((await admin.request('/admin/content/update', upload)).location === '/admin/content', 'Logo image upload succeeds');
  check(readdirSync(resolve(root, 'public/img')).some(name => !originalImages.has(name) && name.endsWith('.png')), 'Uploaded logo saved');
  const uploadedLogo = readdirSync(resolve(root, 'public/img')).find(name => !originalImages.has(name) && name.endsWith('.png'));
  check((await admin.page('/')).includes(`/public/img/${uploadedLogo}`), 'Uploaded logo appears in storefront header');
  check((await admin.post('/admin/categories/add', { category_name: 'Test Collection' })).location === '/admin/categories', 'Category creation succeeds');
  check((await admin.page('/admin/categories')).includes('Test Collection'), 'New category renders');
  check((await admin.post('/admin/products/duplicate', { product_id: fixtures.first })).location === '/admin/products', 'Product duplication succeeds');
  check((await admin.post('/admin/products/duplicate', { product_id: fixtures.first })).location === '/admin/products', 'Repeated duplication uses unique SKU');
  check((await admin.post('/admin/products/bulk-edit', { type: 'flat', action: 'subtract', amount: 999999 })).location === '/admin/products', 'Bulk price subtraction cannot become negative');
  const orderId = run('tests/fixtures.php', 'order-id');
  const customId = run('tests/fixtures.php', 'custom-id');
  await admin.post('/admin/orders/update', { order_id: orderId, shipping_status: 'Shipped', payment_status: 'Paid' });
  await admin.post('/admin/custom-orders/update', { order_id: customId, status: 'Accepted' });
  check(run('tests/fixtures.php', 'verify-statuses').includes('verified'), 'Admin order and custom-order status updates persist');
  await admin.post('/admin/orders/update', { order_id: orderId, shipping_status: 'Invalid', payment_status: 'Invalid' });
  check(run('tests/fixtures.php', 'verify-statuses').includes('verified'), 'Invalid order statuses are ignored');
  check((await admin.post('/admin/categories/delete', { category_id: run('tests/fixtures.php', 'category-id') })).location === '/admin/categories', 'Category deletion succeeds');
  check((await admin.post('/admin/products/delete', { product_id: run('tests/fixtures.php', 'duplicate-id') })).location === '/admin/products', 'Product soft deletion succeeds');
  check((await admin.post('/admin/users/delete', { user_id: run('tests/fixtures.php', 'customer-id') })).location === '/admin/users', 'Customer soft deletion succeeds');
  check(!/PHP (?:Warning|Fatal|Deprecated)|Uncaught/.test(logs), 'Server log has no PHP runtime errors');
  console.log(`\n${checks} checks passed.`);
} finally {
  if (server) {
    server.kill();
    await new Promise(resolve => { if (server.exitCode !== null) resolve(); else server.once('exit', resolve); });
  }
  for (const name of readdirSync(resolve(root, 'public/img'))) {
    if (!originalImages.has(name) && /^[a-f0-9]{32}\.png$/.test(name)) unlinkSync(resolve(root, 'public/img', name));
  }
  try { console.log(run('tests/fixtures.php', 'cleanup')); } catch { console.error('Disposable database cleanup failed.'); }
}
