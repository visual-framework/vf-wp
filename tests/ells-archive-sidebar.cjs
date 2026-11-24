// Local integration check: temporary Image/Paragraph blocks, restored in finally.
const {chromium} = require('playwright');
const {execFileSync} = require('node:child_process');
const assert = require('node:assert/strict');
const wp = code => execFileSync('docker', ['exec','-w','/var/www/html/dist','emblebivfwp_php','wp','eval',code], {encoding:'utf8'}).trim();
const encode = value => Buffer.from(JSON.stringify(value)).toString('base64');
const options = JSON.parse(wp('echo wp_json_encode(array("widget_block" => get_option("widget_block", null), "sidebars_widgets" => get_option("sidebars_widgets", null)));'));
const setOption = (key, value) => wp(value === null ? 'delete_option("'+key+'");' : 'update_option("'+key+'", json_decode(base64_decode("'+encode(value)+'"), true));');

(async () => {
  let browser;
  try {
    const widgets = {...(options.widget_block || {})};
    const numbers = Object.keys(widgets).filter(key => /^\d+$/.test(key)).map(Number);
    const index = Math.max(0,...numbers)+1;
    widgets[index] = {content:'<!-- wp:image {"sizeSlug":"full","align":"full"} --><figure class="wp-block-image alignfull size-full"><img src="/wp-content/themes/vf-wp-ells/screenshot.png" alt="Sidebar layout verification" width="1600" height="1000"/></figure><!-- /wp:image --><!-- wp:paragraph --><p>Editable archive sidebar verification</p><!-- /wp:paragraph -->'};
    widgets._multiwidget = 1;
    setOption('widget_block', widgets);
    setOption('sidebars_widgets', {...(options.sidebars_widgets || {}), 'ells-teacher-training-sidebar':['block-'+index]});
    const url = wp('echo get_post_type_archive_link("learninglab");');
    browser = await chromium.launch({executablePath:'/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true});
    const page = await browser.newPage({viewport:{width:1440,height:1000}});
    await page.goto(url,{waitUntil:'domcontentloaded'});
    const sidebar = page.locator('.ells-training-sidebar');
    const image = sidebar.getByAltText('Sidebar layout verification');
    await image.waitFor();
    assert.match(await sidebar.innerText(),/Editable archive sidebar verification/);
    assert.equal(await sidebar.locator('.vf-card').count(),0);
    assert.equal(await page.getByRole('heading',{name:'Upcoming training',exact:true}).count(),0);
    const intro = page.locator('.vf-grid.vf-grid__col-3.vf-content > .vf-grid__col--span-2').first();
    const left = await intro.boundingBox(), right = await sidebar.boundingBox(), img = await image.boundingBox();
    assert(left.x + left.width <= right.x + 1, 'sidebar should be in the third column');
    assert(img.x >= right.x - 1 && img.x + img.width <= right.x + right.width + 1, 'even full-width images must fit the third column');
    await page.locator('.vf-grid.vf-grid__col-3.vf-content').first().screenshot({path:'/tmp/ells-archive-sidebar-desktop.png'});
    await page.setViewportSize({width:375,height:900});
    const mobileIntro = await intro.boundingBox(), mobileSide = await sidebar.boundingBox(), mobileImage = await image.boundingBox();
    assert(mobileSide.y >= mobileIntro.y + mobileIntro.height - 1, 'column should stack beneath the introduction on mobile');
    assert(mobileImage.x + mobileImage.width <= mobileSide.x + mobileSide.width + 1);
    setOption('sidebars_widgets', {...(options.sidebars_widgets || {}), 'ells-teacher-training-sidebar':[]});
    await page.reload({waitUntil:'domcontentloaded'});
    assert.equal(await sidebar.locator('.ells-training-sidebar__widget').count(),0);
    console.log('PASS: editor block content renders only in the archive right column; image fits on desktop/mobile, card removed, empty area stays empty.');
  } finally {
    for (const [key,value] of Object.entries(options)) setOption(key,value);
    if (browser) await browser.close();
  }
})().catch(error => {console.error(error);process.exitCode=1;});
