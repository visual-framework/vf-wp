// Local WordPress integration test. Requires a temporary admin cookie JSON file.
const {chromium} = require('playwright');
const {execFileSync} = require('node:child_process');
const fs = require('node:fs');
const path = require('node:path');
const assert = require('node:assert/strict');
const session = JSON.parse(fs.readFileSync(process.argv[2], 'utf8'));
const wp = (...args) => execFileSync('docker', ['exec', '-w', '/var/www/html/dist', 'emblebivfwp_php', 'wp', ...args], {encoding:'utf8'}).trim();
(async () => {
  let browser, caseId, pageId, logout;
  try {
    browser = await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});
    const context = await browser.newContext({viewport:{width:1440,height:1100}});
    await context.addCookies(session.cookies.map(cookie => ({...cookie,url:new URL(session.url).origin,httpOnly:true})));
    const page = await context.newPage();
    page.setDefaultTimeout(20000);
    console.log('Opening admin editor');
    await page.goto(new URL('post-new.php?post_type=ells_case',session.url).href, {waitUntil:'domcontentloaded'});
    assert.match(await page.locator('#menu-posts-ells_case .wp-menu-image').getAttribute('class'), /dashicons-screenoptions/);
    logout = await page.locator('#wp-admin-bar-logout a').getAttribute('href');
    await page.locator('#title').fill('ELLS HTML upload integration test');
    for (let i=0;i<4;i++) {
      await page.locator('[data-case-add]').click();
      const row = page.locator('.ells-case-row').nth(i);
      await row.locator('input[type=text], input.widefat').fill('Exercise ' + (i+1));
      const file = process.argv[3] ? path.join(process.argv[3], 'Step ' + (i+1) + '.html') : path.resolve(__dirname,'../../wp-content/themes/vf-wp-ells/interactive/cases/epigenetic-clock/step-'+(i+1)+'.html');
      await row.locator('input[type=file]').setInputFiles(file);
    }
    console.log('Saving HTML uploads');
    await Promise.all([page.waitForNavigation({waitUntil:'domcontentloaded'}),page.locator('#save-post').click()]);
    caseId = Number(new URL(page.url()).searchParams.get('post'));
    assert(caseId > 0);
    const shortcode = '[ells_interactive case="uploaded-'+caseId+'"]';
    assert.equal(await page.locator('[data-case-shortcode]').inputValue(), shortcode);
    assert.equal(await page.locator('.ells-case-row').count(),4);
    assert.match(wp('eval','echo do_shortcode(\''+shortcode+'\');'),/unavailable/);
    const preview = await context.newPage();
    console.log('Checking saved preview');
    await preview.goto(await page.getByRole('link',{name:'Preview saved case'}).getAttribute('href'), {waitUntil:'domcontentloaded'});
    const activity = preview.locator('[data-ells-interactive]');
    assert.equal(await activity.count(),1);
    const first = activity.locator('iframe').first().contentFrame();
    assert.equal(await first.locator('nav.embl-progress-tracker').count(),0);
    await first.locator('#l1').selectOption('pos');
    await first.getByRole('button',{name:'Check answers',exact:true}).click();
    assert.match(await first.locator('#feedback').innerText(),/correct/);
    assert.equal(await activity.locator('iframe').first().getAttribute('sandbox'),'allow-scripts');
    // Reorder saved rows without selecting files again.
    await page.locator('.ells-case-row').nth(1).locator('[data-case-up]').click();
    await Promise.all([page.waitForNavigation({waitUntil:'domcontentloaded'}),page.locator('#save-post').click()]);
    await preview.reload({waitUntil:'domcontentloaded'});
    assert.equal(await activity.locator('[data-activity-panel] h3').first().innerText(),'Exercise 2');
    // Publish, then verify the real shortcode in a real public WordPress page.
    console.log('Checking public shortcode');
    await Promise.all([page.waitForNavigation({waitUntil:'domcontentloaded'}),page.locator('#publish').click()]);
    pageId = Number(wp('post','create','--post_type=page','--post_status=publish','--post_title=ELLS shortcode integration test','--post_content=<!-- wp:shortcode -->\n'+shortcode+'\n<!-- /wp:shortcode -->','--porcelain'));
    const publicUrl = wp('eval','echo get_permalink('+pageId+');');
    const publicContext = await browser.newContext();
    const publicPage = await publicContext.newPage();
    await publicPage.goto(publicUrl, {waitUntil:'domcontentloaded'});
    assert.equal(await publicPage.locator('#footer-ells a[href="https://eepurl.com/ho3aIH"]').count(), 1);
    assert.equal(await publicPage.locator('#footer-ells a[href*="twitter.com"], #footer-ells input[type="email"]').count(), 0);
    await publicPage.locator('[data-activity-next]').click();
    assert.equal(await publicPage.locator('[data-activity-status]').innerText(),'Step 2 of 4');
    await publicPage.locator('[data-activity-frame]').nth(1).contentFrame().locator('#l1').selectOption('neg');
    await page.screenshot({path:'/tmp/ells-case-admin.png',fullPage:true});
    await preview.screenshot({path:'/tmp/ells-case-preview.png',fullPage:true});
    await context.request.get(logout);
    console.log('PASS: real WP admin HTML upload, saved files, draft privacy, shortcode, sandboxed preview, original navigation removal, reorder, publish, public page/player.');
  } finally {
    if (pageId) wp('post','delete',String(pageId),'--force');
    if (caseId) wp('post','delete',String(caseId),'--force');
    if (browser) await browser.close();
  }
})().catch(e => {console.error(e);process.exitCode=1;});
