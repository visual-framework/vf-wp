/* Run against preview.php, or a PHP-rendered fixture served by --fixture. */
const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const http = require('node:http');

(async () => {
  let server;
  if (process.argv[2] === '--fixture') {
    const root = path.resolve(__dirname, '../..');
    server = http.createServer((req, res) => {
      const pathname = new URL(req.url, 'http://localhost').pathname;
      const file = pathname === '/tests/ells-interactive/preview.php'
        ? process.argv[3] : path.join(root, pathname);
      if (!file.startsWith(root + '/') && file !== process.argv[3]) { res.writeHead(403).end(); return; }
      try {
        const type = file.endsWith('.css') ? 'text/css' : file.endsWith('.js') ? 'text/javascript' : 'text/html';
        const content = fs.readFileSync(file);
        res.writeHead(200, {'Content-Type': type}); res.end(content);
      } catch { res.writeHead(404).end(); }
    });
    await new Promise(resolve => server.listen(9482, '127.0.0.1', resolve));
  }
  let browser;
  try {
    browser = await chromium.launch({executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', headless: true});
    const page = await browser.newPage({viewport: {width: 1280, height: 1000}});
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.goto('http://127.0.0.1:9482/tests/ells-interactive/preview.php');
    const activity = page.locator('[data-ells-interactive]').first();
    const second = page.locator('[data-ells-interactive]').nth(1);
    const frame = n => activity.locator('iframe').nth(n).contentFrame();
    await frame(0).locator('#l1').selectOption('neg');
    await frame(0).getByRole('button', {name: 'Check answers', exact: true}).click();
    assert.match(await frame(0).locator('#feedback').innerText(), /correct/);
    await activity.locator('[data-activity-next]').click();
    await activity.locator('[data-activity-back]').click();
    assert.equal(await frame(0).locator('#l1').inputValue(), 'neg');
    assert.equal(await second.locator('iframe').first().contentFrame().locator('#l1').inputValue(), '');
    for (const [i, value] of ['pos','none','neg','none','pos','none','neg','none'].entries()) {
      await frame(0).locator('#l' + (i + 1)).selectOption(value);
    }
    await frame(0).getByRole('button', {name: 'Check answers', exact: true}).click();
    await frame(0).locator('#summary').waitFor({state:'visible'});
    await frame(0).getByRole('button', {name:'Reset', exact:true}).click();
    assert.equal(await frame(0).locator('#l1').inputValue(), '');
    await activity.locator('[data-step="1"]').click();
    await frame(1).locator('#r1a').fill('48.9');
    await frame(1).getByRole('button', {name:'Check answers', exact:true}).click();
    assert.match(await frame(1).locator('#r1a').getAttribute('class'), /wrong/);
    for (const [id, value] of Object.entries({r1a:48,r1b:65,r2a:70,r2b:86,r3a:82,r3b:87,r4a:90,r4b:92})) {
      await frame(1).locator('#'+id).fill(String(value));
    }
    await frame(1).getByRole('button', {name:'Check answers', exact:true}).click();
    await frame(1).locator('#summary').waitFor({state:'visible'});
    await activity.locator('[data-activity-next]').click();
    for (let i=0;i<4;i++) await frame(2).locator('.chip[data-row="1"]').nth(i).click();
    await frame(2).locator('#div-1').click();
    assert.match(await frame(2).locator('#result-1').innerText(), /45.75/);
    await frame(2).locator('#calc-remaining-btn').click();
    await frame(2).locator('#summary').waitFor({state:'visible'});
    await frame(2).getByRole('button', {name:'Reset all',exact:true}).press('Enter');
    for (let i=0;i<4;i++) await frame(2).locator('.chip[data-row="1"]').nth(i).click();
    await frame(2).locator('#div-1').click();
    assert.equal(await frame(2).locator('#calc-remaining-btn').isEnabled(), true);
    await frame(2).locator('#calc-remaining-btn').click();
    await frame(2).getByRole('button', {name:'Reset all',exact:true}).press('Enter');
    await page.waitForTimeout(2600);
    assert.equal(await frame(2).locator('#result-14').innerText(), '');
    assert.equal(await frame(2).locator('#summary').isVisible(), false);
    await activity.locator('[data-activity-next]').click();
    for (const [x,y] of [[45.75,1],[65,14],[76.25,27],[85,41]]) {
      await frame(3).locator('#plot-x').fill(String(x));
      await frame(3).locator('#plot-y').fill(String(y));
      await frame(3).locator('#plot-point').click();
    }
    await frame(3).locator('#btn-bestfit').click();
    await frame(3).locator('#summary').waitFor({state:'visible'});
    assert.equal(await activity.locator('[data-activity-next]').isVisible(), false);
    // No document-wide overflow at narrow widths; wide data tables scroll locally.
    await page.setViewportSize({width:375,height:900});
    for (let i=0;i<4;i++) {
      await activity.locator('[data-step="'+i+'"]').click();
      await page.waitForTimeout(150);
      assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth));
      const size = await frame(i).locator('body').evaluate(el => el.getBoundingClientRect().height);
      const height = await activity.locator('iframe').nth(i).evaluate(el => el.getBoundingClientRect().height);
      assert(height >= size, 'frame should fit its content');
      assert(height <= size + 4, 'frame should not leave excess whitespace');
    }
    await frame(3).getByRole('button', {name:'Reset',exact:true}).focus();
    await frame(3).getByRole('button', {name:'Reset',exact:true}).press('Enter');
    await page.waitForTimeout(250);
    assert.equal(await frame(3).locator('#progress').innerText(), '0');
    assert.equal(await frame(3).locator('#plot-x').inputValue(), '');
    assert.equal(await page.locator('#outside').evaluate(el => getComputedStyle(el).backgroundColor), 'rgb(255, 192, 203)');
    assert.deepEqual(errors, []);
    await activity.screenshot({path:'/tmp/ells-interactive-mobile.png'});
    await page.setViewportSize({width:1280,height:1000});
    await activity.locator('[data-step="0"]').click();
    await page.waitForTimeout(250);
    await activity.screenshot({path:'/tmp/ells-interactive-desktop.png'});
    console.log('PASS: four exercises, answer validation, reset, retained answers, independent embeds, mobile sizing, isolated styles, no JS errors.');
  } finally {
    if (browser) await browser.close();
    if (server) await new Promise(resolve => server.close(resolve));
  }
})().catch(error => { console.error(error); process.exitCode = 1; });
