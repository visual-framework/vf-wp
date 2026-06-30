/**
 * Precompiled Nunjucks template: vf-footer.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-footer"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var t_1;
t_1 = (runtime.contextOrFrameLookup(context, frame, "footer__theme")?runtime.contextOrFrameLookup(context, frame, "footer__theme"):"dark");
frame.set("_theme", t_1, true);
if(frame.topLevel) {
context.setVariable("_theme", t_1);
}
var t_2;
t_2 = (runtime.contextOrFrameLookup(context, frame, "_theme") == "light"?true:false);
frame.set("_isLight", t_2, true);
if(frame.topLevel) {
context.setVariable("_isLight", t_2);
}
var t_3;
t_3 = (runtime.contextOrFrameLookup(context, frame, "_isLight")?"../../assets/vf-footer/assets/site-logo-white-bg.png":"../../assets/vf-footer/assets/site-logo-dark-bg.png");
frame.set("_logoSrc", t_3, true);
if(frame.topLevel) {
context.setVariable("_logoSrc", t_3);
}
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "partner__variant")) {
output += "<div class=\"vf-footer vf-u-background-color-ui--white vf-footer__partners\">\n  <div class=\"vf-footer__inner\">\n\n";
if(runtime.contextOrFrameLookup(context, frame, "partner__variant") == "logos") {
output += "  <div>\n  <h4 class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">OUR PARTNERS/COLLABORATORS</h4>\n  <ul class=\"vf-grid vf-grid__col-6 vf-list logos-variant\">\n    <li class=\"vf-list__item\">\n      <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">\n        <img src=\"../../assets/vf-minimal-footer/assets/landscape-placeholder.svg\" alt=\"Partner logo\" width=\"174\" height=\"64\">\n      </a>\n    </li>\n    <li class=\"vf-list__item\">\n      <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">\n        <img src=\"../../assets/vf-minimal-footer/assets/landscape-placeholder.svg\" alt=\"Partner logo\" width=\"174\" height=\"64\">\n      </a>\n    </li>\n    <li class=\"vf-list__item\">\n      <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">\n        <img src=\"../../assets/vf-minimal-footer/assets/landscape-placeholder.svg\" alt=\"Partner logo\" width=\"174\" height=\"64\">\n      </a>\n    </li>\n    <li class=\"vf-list__item\">\n      <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">\n        <img src=\"../../assets/vf-minimal-footer/assets/landscape-placeholder.svg\" alt=\"Partner logo\" width=\"174\" height=\"64\">\n      </a>\n    </li>\n    <li class=\"vf-list__item\">\n      <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">\n        <img src=\"../../assets/vf-minimal-footer/assets/landscape-placeholder.svg\" alt=\"Partner logo\" width=\"174\" height=\"64\">\n      </a>\n    </li>\n    <li class=\"vf-list__item\">\n      <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">\n        <img src=\"../../assets/vf-minimal-footer/assets/landscape-placeholder.svg\" alt=\"Partner logo\" width=\"174\" height=\"64\">\n      </a>\n    </li>\n  </ul>\n  </div>\n\n";
;
}
else {
if(runtime.contextOrFrameLookup(context, frame, "partner__variant") == "columns") {
output += "  <div>\n  <h4 class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">OUR PARTNERS/COLLABORATORS</h4>\n    <ul class=\"vf-grid vf-grid__col-3 vf-list\">\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 1</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 2</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 3</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 4</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 5</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 6</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 7</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 8</a>\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Partner 9</a>\n      </li>\n    </ul>\n  </div>\n\n";
;
}
else {
if(runtime.contextOrFrameLookup(context, frame, "partner__variant") == "summary") {
output += "  <div class=\"vf-grid vf-grid__col-5 vf-footer__partners-summary-grid\">\n    <div class=\"vf-grid__col--span-2\">\n      <h4 class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">OUR PARTNERS/COLLABORATORS</h4>\n      <p class=\"vf-u-type__text-body--3 vf-u-text-color--grey--dark\">Partnership statement. A short description of the partnerships, collaborations or consortium.</p>\n      <a href=\"JavaScript:Void(0);\" class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">See all collaborators</a>\n    </div>\n    <div class=\"vf-footer__stat vf-table__label\">\n      <p class=\"vf-u-type__text-heading--2\">35</p>\n      <p class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">Data partners</p>\n    </div>\n    <div class=\"vf-footer__stat vf-table__label\">\n      <p class=\"vf-u-type__text-heading--2\">4</p>\n      <p class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">Supporting funders</p>\n    </div>\n    <div class=\"vf-footer__stat vf-table__label\">\n      <p class=\"vf-u-type__text-heading--2\">35</p>\n      <p class=\"vf-u-type__text-body--4 vf-u-text-color--grey--dark\">Research institutions</p>\n    </div>\n  </div>\n";
;
}
;
}
;
}
output += "  </div>\n</div>\n";
;
}
output += "\n<footer class=\"vf-footer ";
if(runtime.contextOrFrameLookup(context, frame, "partner__variant")) {
output += " vf-footer--without-rule";
;
}
if(runtime.contextOrFrameLookup(context, frame, "_isLight")) {
output += " vf-footer--light";
;
}
output += " | ";
if(runtime.contextOrFrameLookup(context, frame, "_isLight")) {
output += "vf-u-background-color-ui--grey--light";
;
}
else {
output += "vf-u-background-color-ui--dark-grey";
;
}
output += "\">\n<div class=\"vf-footer__inner\">\n";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_intro_notice") != false) {
output += "<p class=\"vf-footer__notice\">The Visual Framework is a toolkit to quickly and collaboratively build better life science websites.</p>\n";
;
}
output += "<div class=\"";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_intro_notice") != false) {
output += " vf-footer__links-group";
;
}
else {
output += "vf-grid vf-grid__col-2";
;
}
output += "\">\n  <!-- ROW 1: Logo + Mission (4) | Category Links (8) -->\n  <!-- Logo -->\n";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_logo") != false || runtime.contextOrFrameLookup(context, frame, "footer__show_mission") != false) {
output += "  <div class=\"vf-footer__logo-wrapper\">\n";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_logo") != false) {
output += "    <a href=\"https://www.ebi.ac.uk\" class=\"vf-logo\">\n      <img\n        class=\"vf-logo__image\"\n        src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "_logoSrc"), env.opts.autoescape);
output += "\"\n        alt=\"EMBL-EBI Logo\"\n        loading=\"eager\"\n      />\n    </a>\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "footer__show_mission") != false) {
output += "    <div class=\"vf-grid vf-grid__col-1\">\n    <!-- Mission Statement -->\n    <p class=\"vf-u-type__text-body--3 vf-footer__notice\">\n      A description of a site or organisation and what its goals are.\n    </p>\n    <p>\n    </p>\n    </div>\n";
;
}
output += "  </div>\n";
;
}
output += "  <div>\n    <!-- Category Links -->\n    <div class=\"vf-grid ";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_intro_notice") != false) {
output += "vf-grid__col-5";
;
}
else {
output += "vf-grid__col-3";
;
}
output += " \">\n      <div class=\"vf-links\">\n        <h4 class=\"vf-links__heading vf-u-text-color--grey--dark\">\n          CATEGORY\n        </h4>\n        <ul class=\"vf-links__list | vf-list\">\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n        </ul>\n      </div>\n      <div class=\"vf-links\">\n        <h4 class=\"vf-links__heading vf-u-text-color--grey--dark\">\n          CATEGORY\n        </h4>\n        <ul class=\"vf-links__list | vf-list\">\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n        </ul>\n      </div>\n      <div class=\"vf-links\">\n        <h4 class=\"vf-links__heading vf-u-text-color--grey--dark\">\n          CATEGORY\n        </h4>\n        <ul class=\"vf-links__list | vf-list\">\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n        </ul>\n      </div>\n";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_intro_notice") != false) {
output += "      <div class=\"vf-links\">\n        <h4 class=\"vf-links__heading vf-u-text-color--grey--dark\">\n          CATEGORY\n        </h4>\n        <ul class=\"vf-links__list | vf-list\">\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n        </ul>\n      </div>\n      <div class=\"vf-links\">\n        <h4 class=\"vf-links__heading vf-u-text-color--grey--dark\">\n          CATEGORY\n        </h4>\n        <ul class=\"vf-links__list | vf-list\">\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n          <li class=\"vf-list__item\">\n            <a class=\"vf-list__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">\n              Entry\n            </a>\n          </li>\n        </ul>\n      </div>\n";
;
}
output += "    </div>\n  </div>\n</div>\n\n   <!-- ROW 2: Contact Details (4) | Social Icons (8) -->\n   <div class=\"vf-grid vf-grid__col-2\">\n      <!-- Contact Details -->\n";
if(runtime.contextOrFrameLookup(context, frame, "footer__show_sublinks") != false) {
output += "      <ul class=\"vf-footer__list vf-footer__list--legal | vf-list vf-list--inline\">\n        <li class=\"vf-list__item\">\n          <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Entry</a>\n        </li>\n        <li class=\"vf-list__item\">\n          <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Entry</a>\n        </li>\n        <li class=\"vf-list__item\">\n          <a href=\"JavaScript:Void(0);\" class=\"vf-list__link\">Entry</a>\n        </li>\n      </ul>\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "footer__show_contact") != false) {
output += "      <div class=\"vf-footer__contact\">\n\n        <p class=\"vf-u-type__text-body--5 vf-u-text-color--grey--lightest\">Tel: +49 00 000 000.</p>\n        <p class=\"vf-u-type__text-body--5 vf-u-text-color--grey--lightest\">Maybe an address too, 5555, Somewhere, Earth.</p>\n        <a href=\"JavaScript:Void(0);\" class=\"vf-footer__link vf-u-text-color--white\">Full contact details</a>\n      </div>\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "footer__show_social") != false) {
output += "      <div>\n         <!-- Social Icons -->\n         <div class=\"vf-footer__social vf-u-display--flex vf-u-float__right\">\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link  vf-u-text-color--white\" aria-label=\"Facebook\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n              <path d=\"M38 22.0588C38 13.1898 30.8365 6 22 6C13.1635 6 6 13.1898 6 22.0588C6 29.5901 11.1658 35.9093 18.1348 37.6447V26.9661H14.8355V22.0588H18.1348V19.9442C18.1348 14.4783 20.5993 11.9449 25.9459 11.9449C26.9595 11.9449 28.7085 12.1444 29.4241 12.3439V16.7924C29.0464 16.7525 28.3905 16.7326 27.5756 16.7326C24.9521 16.7326 23.9384 17.73 23.9384 20.3233V22.0588H29.1645L28.2668 26.9661H23.9384V38C31.8607 37.0396 38 30.2692 38 22.0588Z\"/>\n              </svg>\n            </a>\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link vf-u-text-color--white\" aria-label=\"Bluesky\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n                <path d=\"M12.9364 9.80896C16.6051 12.5632 20.5513 18.1477 22.0001 21.1446C23.449 18.1479 27.3949 12.5632 31.0638 9.80896C33.7109 7.82161 38 6.2839 38 11.177C38 12.1542 37.4397 19.386 37.1111 20.5601C35.9689 24.6419 31.8067 25.683 28.1043 25.0529C34.576 26.1544 36.2223 29.8028 32.6668 33.4512C25.9143 40.3803 22.9615 31.7127 22.2046 29.4917C22.0659 29.0846 22.001 28.8941 22 29.0561C21.999 28.8941 21.9341 29.0846 21.7954 29.4917C21.0388 31.7127 18.0861 40.3805 11.3332 33.4512C7.77764 29.8028 9.42392 26.1541 15.8957 25.0529C12.1932 25.683 8.03094 24.6419 6.88888 20.5601C6.56026 19.3859 6 12.154 6 11.177C6 6.2839 10.2892 7.82161 12.9362 9.80896H12.9364Z\"/>\n              </svg>\n            </a>\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link vf-u-text-color--white\" aria-label=\"Instagram\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n                <g clip-path=\"url(#clip0_970_224)\">\n                <path d=\"M22 8.88125C26.275 8.88125 26.7812 8.9 28.4625 8.975C30.025 9.04375 30.8687 9.30625 31.4312 9.525C32.175 9.8125 32.7125 10.1625 33.2687 10.7188C33.8312 11.2812 34.175 11.8125 34.4625 12.5563C34.6812 13.1188 34.9438 13.9688 35.0125 15.525C35.0875 17.2125 35.1063 17.7188 35.1063 21.9875C35.1063 26.2625 35.0875 26.7688 35.0125 28.45C34.9438 30.0125 34.6812 30.8563 34.4625 31.4188C34.175 32.1625 33.825 32.7 33.2687 33.2563C32.7062 33.8188 32.175 34.1625 31.4312 34.45C30.8687 34.6688 30.0187 34.9313 28.4625 35C26.775 35.075 26.2688 35.0938 22 35.0938C17.725 35.0938 17.2188 35.075 15.5375 35C13.975 34.9313 13.1312 34.6688 12.5687 34.45C11.825 34.1625 11.2875 33.8125 10.7312 33.2563C10.1687 32.6938 9.825 32.1625 9.5375 31.4188C9.31875 30.8563 9.05625 30.0063 8.9875 28.45C8.9125 26.7625 8.89375 26.2563 8.89375 21.9875C8.89375 17.7125 8.9125 17.2063 8.9875 15.525C9.05625 13.9625 9.31875 13.1188 9.5375 12.5563C9.825 11.8125 10.175 11.275 10.7312 10.7188C11.2937 10.1562 11.825 9.8125 12.5687 9.525C13.1312 9.30625 13.9813 9.04375 15.5375 8.975C17.2188 8.9 17.725 8.88125 22 8.88125ZM22 6C17.6562 6 17.1125 6.01875 15.4062 6.09375C13.7063 6.16875 12.5375 6.44375 11.525 6.8375C10.4688 7.25 9.575 7.79375 8.6875 8.6875C7.79375 9.575 7.25 10.4688 6.8375 11.5188C6.44375 12.5375 6.16875 13.7 6.09375 15.4C6.01875 17.1125 6 17.6562 6 22C6 26.3438 6.01875 26.8875 6.09375 28.5938C6.16875 30.2938 6.44375 31.4625 6.8375 32.475C7.25 33.5313 7.79375 34.425 8.6875 35.3125C9.575 36.2 10.4688 36.75 11.5188 37.1562C12.5375 37.55 13.7 37.825 15.4 37.9C17.1062 37.975 17.65 37.9937 21.9937 37.9937C26.3375 37.9937 26.8813 37.975 28.5875 37.9C30.2875 37.825 31.4563 37.55 32.4688 37.1562C33.5188 36.75 34.4125 36.2 35.3 35.3125C36.1875 34.425 36.7375 33.5313 37.1437 32.4813C37.5375 31.4625 37.8125 30.3 37.8875 28.6C37.9625 26.8938 37.9813 26.35 37.9813 22.0063C37.9813 17.6625 37.9625 17.1188 37.8875 15.4125C37.8125 13.7125 37.5375 12.5438 37.1437 11.5312C36.75 10.4688 36.2063 9.575 35.3125 8.6875C34.425 7.8 33.5312 7.25 32.4813 6.84375C31.4625 6.45 30.3 6.175 28.6 6.1C26.8875 6.01875 26.3438 6 22 6Z\"/>\n                <path d=\"M22 13.7812C17.4625 13.7812 13.7812 17.4625 13.7812 22C13.7812 26.5375 17.4625 30.2188 22 30.2188C26.5375 30.2188 30.2188 26.5375 30.2188 22C30.2188 17.4625 26.5375 13.7812 22 13.7812ZM22 27.3312C19.0563 27.3312 16.6687 24.9438 16.6687 22C16.6687 19.0563 19.0563 16.6687 22 16.6687C24.9438 16.6687 27.3312 19.0563 27.3312 22C27.3312 24.9438 24.9438 27.3312 22 27.3312Z\"/>\n                <path d=\"M32.4625 13.4564C32.4625 14.5189 31.6 15.3752 30.5438 15.3752C29.4813 15.3752 28.625 14.5126 28.625 13.4564C28.625 12.3939 29.4875 11.5376 30.5438 11.5376C31.6 11.5376 32.4625 12.4001 32.4625 13.4564Z\"/>\n                </g>\n                <defs>\n                <clipPath id=\"clip0_970_224\">\n                <rect width=\"32\" height=\"32\" transform=\"translate(6 6)\"/>\n                </clipPath>\n                </defs>\n              </svg>\n            </a>\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link vf-u-text-color--white\" aria-label=\"X\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n                <path d=\"M30.4351 8.53841H34.933L25.1063 19.7697L36.6667 35.053H27.615L20.5254 25.7838L12.4133 35.053H7.91258L18.4232 23.0399L7.33333 8.53841H16.6148L23.0232 17.0108L30.4351 8.53841ZM28.8564 32.3608H31.3488L15.2605 11.0892H12.5859L28.8564 32.3608Z\"/>\n              </svg>\n            </a>\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link vf-u-text-color--white\" aria-label=\"LinkedIn\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n                <g clip-path=\"url(#clip0_970_229)\">\n                <path d=\"M35.6313 6H8.3625C7.05625 6 6 7.03125 6 8.30625V35.6875C6 36.9625 7.05625 38 8.3625 38H35.6313C36.9375 38 38 36.9625 38 35.6938V8.30625C38 7.03125 36.9375 6 35.6313 6ZM15.4937 33.2687H10.7438V17.9937H15.4937V33.2687ZM13.1188 15.9125C11.5938 15.9125 10.3625 14.6812 10.3625 13.1625C10.3625 11.6438 11.5938 10.4125 13.1188 10.4125C14.6375 10.4125 15.8687 11.6438 15.8687 13.1625C15.8687 14.675 14.6375 15.9125 13.1188 15.9125ZM33.2687 33.2687H28.525V25.8438C28.525 24.075 28.4937 21.7937 26.0562 21.7937C23.5875 21.7937 23.2125 23.725 23.2125 25.7188V33.2687H18.475V17.9937H23.025V20.0813H23.0875C23.7188 18.8813 25.2688 17.6125 27.575 17.6125C32.3813 17.6125 33.2687 20.775 33.2687 24.8875V33.2687Z\"/>\n                </g>\n                <defs>\n                <clipPath id=\"clip0_970_229\">\n                <rect width=\"32\" height=\"32\" transform=\"translate(6 6)\"/>\n                </clipPath>\n                </defs>\n              </svg>\n            </a>\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link vf-u-text-color--white\" aria-label=\"GitHub\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n                <g clip-path=\"url(#clip0_990_510)\">\n                <mask id=\"mask0_990_510\" style=\"mask-type:luminance\" maskUnits=\"userSpaceOnUse\" x=\"6\" y=\"6\" width=\"32\" height=\"32\">\n                <path d=\"M38 6.32654H6V37.6735H38V6.32654Z\"/>\n                </mask>\n                <g mask=\"url(#mask0_990_510)\">\n                <path d=\"M19.5313 28.9828C15.4062 28.4828 12.5 25.514 12.5 21.6703C12.5 20.1078 13.0625 18.4203 14 17.2953C13.5938 16.264 13.6563 14.0765 14.125 13.1703C15.375 13.014 17.0625 13.6703 18.0625 14.5765C19.25 14.2015 20.5 14.014 22.0312 14.014C23.5625 14.014 24.8125 14.2015 25.9375 14.5453C26.9063 13.6703 28.625 13.014 29.875 13.1703C30.3125 14.014 30.375 16.2015 29.9688 17.264C30.9688 18.4515 31.5 20.0453 31.5 21.6703C31.5 25.514 28.5938 28.4203 24.4062 28.9515C25.4687 29.639 26.1875 31.139 26.1875 32.8578V36.1078C26.1875 37.0453 26.9688 37.5765 27.9063 37.2015C33.5625 35.0453 38 29.389 38 22.389C38 13.5453 30.8125 6.32654 21.9688 6.32654C13.125 6.32654 6 13.5453 6 22.389C6 29.3265 10.4062 35.0765 16.3437 37.2328C17.1875 37.5453 18 36.9828 18 36.139V33.639C17.5625 33.8266 17 33.9515 16.5 33.9515C14.4375 33.9515 13.2187 32.8266 12.3437 30.7328C12 29.8891 11.625 29.389 10.9063 29.2953C10.5313 29.264 10.4062 29.1078 10.4062 28.9203C10.4062 28.5453 11.0312 28.264 11.6563 28.264C12.5625 28.264 13.3437 28.8266 14.1562 29.9828C14.7813 30.8891 15.4375 31.2953 16.2187 31.2953C17 31.2953 17.5 31.014 18.2187 30.2953C18.75 29.764 19.1562 29.2953 19.5313 28.9828Z\"/>\n                </g>\n                </g>\n                <defs>\n                <clipPath id=\"clip0_990_510\">\n                <rect width=\"32\" height=\"31.3469\" transform=\"translate(6 6.32654)\"/>\n                </clipPath>\n                </defs>\n              </svg>\n            </a>\n            <a href=\"JavaScript:Void(0);\" class=\"vf-footer__social-link vf-footer__link vf-u-text-color--white\" aria-label=\"mail\">\n               <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"44\" height=\"44\" viewBox=\"0 0 44 44\" fill=\"currentColor\">\n                <path d=\"M33.4453 10.9042C35.9619 10.9042 38.0029 12.9452 38.0029 15.4618V28.539C38.0028 31.0555 35.9618 33.0956 33.4453 33.0956H10.5596C8.04318 33.0954 6.00307 31.0554 6.00293 28.539V15.4618C6.00293 12.9453 8.0431 10.9044 10.5596 10.9042H33.4453ZM8.5957 15.203C8.58467 15.2877 8.57812 15.3741 8.57812 15.4618V28.539C8.57826 29.6334 9.46513 30.5212 10.5596 30.5214H33.4453C34.5399 30.5214 35.4276 29.6335 35.4277 28.539V15.4618C35.4277 15.4245 35.4229 15.3873 35.4209 15.3505L25.7168 25.2519C23.9764 27.0275 21.1331 27.0809 19.3281 25.371L8.5957 15.203ZM10.5225 13.4804L21.0996 23.5009C21.8847 24.2447 23.1209 24.2224 23.8779 23.4501L33.6396 13.4892C33.5758 13.483 33.5108 13.4794 33.4453 13.4794H10.5596C10.5472 13.4794 10.5348 13.4801 10.5225 13.4804Z\"/>\n              </svg>\n            </a>\n         </div>\n      </div>\n";
;
}
output += "   </div>\n   <!-- ROW 3: Copyright (12) -->\n  <section class=\"vf-footer__legal vf-grid__col--span-all | vf-grid vf-grid__col-1\">\n    <span class=\"vf-footer__link vf-u-text-color--grey-lightest\">Copyright © Your Organisation</span>\n      <a class=\"vf-footer__link vf-u-text-color--white\" href=\"JavaScript:Void(0);\">Terms of use</a>\n      <a class=\"vf-footer__link vf-u-text-color--grey-lightest\" href=\"JavaScript:Void(0);\">Another entry</a>\n   </section>\n</div>\n</footer>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
