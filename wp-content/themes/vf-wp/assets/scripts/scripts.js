'use strict';

// vf-banner

/**
 * Clear the cooke. This is mostly a development tool.
 */
/* eslint-disable no-unused-vars */
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i.return) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function vfBannerReset(vfBannerCookieNameAndVersion) {
  vfBannerSetCookie(vfBannerCookieNameAndVersion, false);
}
/* eslint-enable no-unused-vars */

/**
 * Dismiss a banner
 */
function vfBannerClose(targetBanner) {
  // remove padding added to not cover up content
  if (targetBanner.classList.contains("vf-banner--fixed")) {
    var height = targetBanner.offsetHeight || 0;
    var pagePadding;
    if (targetBanner.classList.contains("vf-banner--top")) {
      pagePadding = document.body.style.paddingTop.replace(/\D/g, "") || 0;
      pagePadding = pagePadding - height;
      document.body.style.paddingTop = pagePadding + "px";
    }
    if (targetBanner.classList.contains("vf-banner--bottom")) {
      pagePadding = document.body.style.paddingBottom.replace(/\D/g, "") || 0;
      pagePadding = pagePadding - height;
      document.body.style.paddingBottom = pagePadding + "px";
    }
  }

  // dismiss banner
  targetBanner.classList.add("vf-u-display-none");
}

/**
 * Confirm a banner, initiate cookie logging
 */
function vfBannerConfirm(banner, vfBannerCookieNameAndVersion) {
  vfBannerClose(banner);
  if (vfBannerCookieNameAndVersion !== "null") {
    vfBannerSetCookie(vfBannerCookieNameAndVersion, true);
  }
}

/**
 * Log a cookie
 */
function vfBannerSetCookie(c_name, value, exdays) {
  // var value = value || 'true';
  /* eslint-disable no-redeclare */
  var exdays = exdays || 90;
  /* eslint-enable no-redeclare */
  var exdate = new Date();
  var c_value;
  exdate.setDate(exdate.getDate() + exdays);
  c_value = escape(value) + (exdays === null ? "" : ";expires=" + exdate.toUTCString()) + ";domain=" + document.domain + ";path=/";
  document.cookie = c_name + "=" + c_value;
}

/**
 * See if a cookie has been set
 */
function vfBannerGetCookie(c_name) {
  var x,
    y,
    ARRcookies = document.cookie.split(";");
  for (var i = 0; i < ARRcookies.length; i++) {
    x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
    y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
    x = x.replace(/^\s+|\s+$/g, "");
    if (x === c_name) {
      return unescape(y);
    }
  }
}

/**
 * Finds all vf-banner on a page and activates them
 * @param {object} [scope] - the html scope to process, optional, defaults to `document`
 * @example vfBanner(document.querySelectorAll('.vf-component__container')[0]);
 */
function vfBanner(scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  /* eslint-enable no-redeclare */
  var bannerList = scope.querySelectorAll("[data-vf-js-banner]");
  if (!bannerList) {
    // exit: banners not found
    return;
  }
  if (bannerList.length == 0) {
    // exit: banner content not found
    return;
  }

  // generate the banner component, js events
  Array.prototype.forEach.call(bannerList, function (banner) {
    // map the JS data attributes to our object structure
    var bannerRemapped = JSON.parse(JSON.stringify(banner.dataset));
    if (typeof banner.dataset.vfJsBannerId != "undefined") {
      // don't reactivate an already processed banner
    } else {
      bannerRemapped.vfJsBannerText = banner.querySelectorAll("[data-vf-js-banner-text]")[0].innerHTML;
      var uniqueId = Math.round(Math.random() * 10000000);

      // set an id to target this banner
      banner.setAttribute("data-vf-js-banner-id", uniqueId);

      // preserve the classlist
      bannerRemapped.classList = banner.querySelectorAll("[data-vf-js-banner-text]")[0].classList;

      // Make the banner come alive
      vfBannerInsert(bannerRemapped, uniqueId);
    }
  });
}

/**
 * Takes a banner object and creates the necesary html markup, js events, and inserts
 * @example vfBannerInsert()
 * @param {object} [banner]  -
 * @param {string} [bannerId] - the id of the target div, `data-vf-js-banner-id="1"`
 * @param {object} [scope] - the html scope to process, optional, defaults to `document`
 */
function vfBannerInsert(banner, bannerId, scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  /* eslint-enable no-redeclare */
  var targetBanner = scope.querySelectorAll("[data-vf-js-banner-id=\"" + bannerId + "\"]")[0];
  if (targetBanner == undefined) {
    return;
  }
  var generatedBannerHtml = "<div class=\"" + banner.classList + "\" data-vf-js-banner-text>";
  generatedBannerHtml += banner.vfJsBannerText;

  // What type of banner?
  if (banner.vfJsBannerState === "persistent") {
    // nothing more to do for persistent, you can't close it
  } else if (banner.vfJsBannerState === "dismissible") {
    // nothing more to do for dismissible
  } else if (banner.vfJsBannerState === "blocking") {
    console.warn("vf-banner: Note, the blocking implementation is not yet feature complete.");
    // escape only works when blocking
    if (banner.vfJsBannerEscClose === "y" || banner.vfJsBannerEscClose === "Y") {
      document.onkeydown = function (evt) {
        evt = evt || window.event;
        if (evt.keyCode == 27) {
          vfBannerConfirm(targetBanner, "null");
        }
      };
    }
  }

  // Split passed links into buttons
  // <a href='#'>string1</a>\<a href='#'>string2</a>
  if (banner.vfJsBannerExtraButton) {
    var vfBannerExtraButtons = banner.vfJsBannerExtraButton.split("</a>");
    vfBannerExtraButtons.forEach(function (button) {
      if (button.length > 1) {
        button += "</a>";
        var newButton = document.createElement("button");
        newButton.innerHTML = button;
        newButton = newButton.firstChild;
        newButton.classList.add("vf-button", "vf-button--primary");
        generatedBannerHtml += newButton.outerHTML;
      }
    });
  }

  // if there is a vfJsBannerButtonText and banner is blocking or dismissible,
  // add a button so user can close the banner
  if (banner.vfJsBannerButtonText && (banner.vfJsBannerState === "blocking" || banner.vfJsBannerState === "dismissible")) {
    if (banner.vfJsBannerButtonTheme == "primary") {
      generatedBannerHtml += "<button class=\"vf-button vf-button--primary\" data-vf-js-banner-close>" + banner.vfJsBannerButtonText + "</button>";
    } else if (banner.vfJsBannerButtonTheme == "secondary") {
      generatedBannerHtml += "<button class=\"vf-button vf-button--secondary\" data-vf-js-banner-close>" + banner.vfJsBannerButtonText + "</button>";
    } else if (banner.vfJsBannerButtonTheme == "tertiary") {
      generatedBannerHtml += "<button class=\"vf-button vf-button--tertiary\" data-vf-js-banner-close>" + banner.vfJsBannerButtonText + "</button>";
    } else {
      // default
      generatedBannerHtml += "<button class=\"vf-button vf-button--primary\" data-vf-js-banner-close>" + banner.vfJsBannerButtonText + "</button>";
    }
  }
  generatedBannerHtml += "</div>";

  // set the html of the banner
  targetBanner.innerHTML = generatedBannerHtml;

  // prep for cookie
  var vfBannerCookieNameAndVersion = "null";
  if (banner.vfJsBannerCookieName && banner.vfJsBannerCookieVersion) {
    vfBannerCookieNameAndVersion = banner.vfJsBannerCookieName + "_" + banner.vfJsBannerCookieVersion;
  }

  // utility to reset cookie when developing
  // console.warn('vf-banner: vfBannerReset cookie reset override is on.');
  // vfBannerReset(vfBannerCookieNameAndVersion);

  // if blocking or dismissible, allow the user to close it, store a cookie (if specified)
  if (banner.vfJsBannerState === "blocking" || banner.vfJsBannerState === "dismissible") {
    // On click: close banner, pass any cookie name (or `null`)
    if (banner.vfJsBannerButtonText) {
      targetBanner.querySelectorAll("[data-vf-js-banner-close]")[0].addEventListener("click", function () {
        vfBannerConfirm(targetBanner, vfBannerCookieNameAndVersion);
      }, false);
    }
  }

  // add appropriate padding to the page to not cover up content
  if (targetBanner.classList.contains("vf-banner--fixed")) {
    var height = Number(targetBanner.offsetHeight || 0);
    var pagePadding;
    if (targetBanner.classList.contains("vf-banner--top")) {
      pagePadding = Number(document.body.style.paddingTop.replace(/\D/g, "") || 0);
      pagePadding = pagePadding + height;
      document.body.style.paddingTop = pagePadding + "px";
    }
    if (targetBanner.classList.contains("vf-banner--bottom")) {
      pagePadding = Number(document.body.style.paddingBottom.replace(/\D/g, "") || 0);
      pagePadding = pagePadding + height;
      document.body.style.paddingBottom = pagePadding + "px";
    }
  }
  if (vfBannerCookieNameAndVersion != "null") {
    // if banner has been previously accepted
    if (vfBannerGetCookie(vfBannerCookieNameAndVersion) === "true") {
      // banner has been accepted, close
      vfBannerClose(targetBanner);
      // exit, nothng more to do
      return;
    }

    // if banner is marked as auto-accept, set as read
    if (banner.vfJsBannerAutoAccept == "true") {
      if (banner.vfJsBannerState === "blocking" || banner.vfJsBannerState === "dismissible") {
        vfBannerSetCookie(vfBannerCookieNameAndVersion, true);
      }
    }
  }
}

// vf-masthead

/**
  * This was a function for making background color of banner from image file name.
  */
function vfMastheadSetStyle() {
  console.warn("vfMasthead", "This component has been deprecated, you should remove it from your VF scripts.js rollup. https://github.com/visual-framework/vf-core/pull/1406/");
}

// vf-analytics-google

/*
 * A note on the Visual Framework and JavaScript:
 * The VF is primarily a CSS framework so we've included only a minimal amount
 * of JS in components and it's fully optional (just remove the JavaScript selectors
 * i.e. `data-vf-js-tabs`). So if you'd rather use Angular or Bootstrap for your
 * tabs, the Visual Framework won't get in the way.
 *
 * When querying the DOM for elements that should be acted on:
 * 🚫 Don't: const tabs = document.querySelectorAll('.vf-tabs');
 * ✅ Do:    const tabs = document.querySelectorAll('[data-vf-js-tabs]');
 *
 * This allows users who would prefer not to have this JS engage on an element
 * to drop `data-vf-js-component` and still maintain CSS styling.
 */

// Declare `ga` as a global for eslint
/* global ga */
window.dataLayer = window.dataLayer || [];
function gtag() {
  window.dataLayer.push(arguments);
}
/**
 * Utility method to invalidate prior GA check.
 */
function vfGaIndicateUnloaded() {
  var el = document.querySelector("body");
  el.setAttribute("data-vf-google-analytics-loaded", "false");
}

/**
 * Track the last time an event was sent (don't double send)
 * @param {Date} lastGaEventTime
 */
var lastGaEventTime = Date.now();

/**
 * We poll the document until we find GA has loaded, or we've tried a few times.
 * Port of https://github.com/ebiwd/EBI-Framework/blob/v1.3/js/foundationExtendEBI.js#L4
 * @param {object} [vfGaTrackOptions]
 * @param {binary} [vfGaTrackOptions.vfGaTrackPageLoad=true] If true, the function will track the initial page view. Set this to false if you track the page view in your HTML.
 * @param {string} [vfGaTrackOptions.vfGa4MeasurementId] The GA4 site measurement ID.
 * @param {number} [numberOfGaChecksLimit=2]
 * @param {number} [checkTimeout=900]
 * @example
 * let vfGaTrackOptions = {
 *  vfGaTrackPageLoad: true
 *  vfGaTrackNetwork: {
 *    serviceProvider: 'dimension2',
 *    networkDomain: 'dimension3',
 *    networkType: 'dimension4'
 *  }
 * };
 * vfGaIndicateLoaded(vfGaTrackOptions);
 */
function vfGaIndicateLoaded(vfGaTrackOptions, numberOfGaChecksLimit, numberOfGaChecks, checkTimeout) {
  /* eslint-disable no-redeclare*/
  var vfGaTrackOptions = vfGaTrackOptions || {};
  if (vfGaTrackOptions.vfGaTrackPageLoad == null) vfGaTrackOptions.vfGaTrackPageLoad = true;
  var numberOfGaChecks = numberOfGaChecks || 0;
  var numberOfGaChecksLimit = numberOfGaChecksLimit || 5;
  var checkTimeout = checkTimeout || 900;
  /* eslint-enable no-redeclare*/
  var el = document.querySelector("body");

  // debug
  vfGaLogMessage("checking " + numberOfGaChecks + ", limit: " + numberOfGaChecksLimit);
  numberOfGaChecks++;

  // If successful we set `data-vf-google-analytics-loaded` on the `body` to true.
  try {
    // unset our check
    if (el.getAttribute("data-vf-google-analytics-loaded") != "true") {
      vfGaIndicateUnloaded();
    }

    // check to see if gtag is loaded, and then if UA is loaded, and if neither, check once more (to a limit)
    if (typeof gtag !== "undefined") {
      vfGaLogMessage("ga4 found");
      if (el.getAttribute("data-vf-google-analytics-loaded") != "true") {
        el.setAttribute("data-vf-google-analytics-loaded", "true");
        vfGaInit(vfGaTrackOptions);
      }
    } else if (ga && ga.loaded) {
      vfGaLogMessage("ua found");
      if (el.getAttribute("data-vf-google-analytics-loaded") != "true") {
        el.setAttribute("data-vf-google-analytics-loaded", "true");
        vfGaInit(vfGaTrackOptions);
      }
    } else {
      vfGaLogMessage("GA tracking code not ready, scheduling another check");
      if (numberOfGaChecks <= numberOfGaChecksLimit) {
        setTimeout(function () {
          vfGaIndicateLoaded(vfGaTrackOptions, numberOfGaChecksLimit, numberOfGaChecks, checkTimeout);
        }, 900); // give a second check if GA was slow to load
      }
    }
  } catch (err) {
    vfGaLogMessage("error in vfGaIndicateLoaded");
    if (numberOfGaChecks <= numberOfGaChecksLimit) {
      setTimeout(function () {
        vfGaIndicateLoaded(vfGaTrackOptions, numberOfGaChecksLimit, numberOfGaChecks, checkTimeout);
      }, 900); // give a second check if GA was slow to load
    }
  }
}

/**
 * Get Meta Tag Content
 * via https://jonlabelle.com/snippets/view/javascript/get-meta-tag-content
 *
 * @param {string} metaName The meta tag name.
 * @return {string} The meta tag content value, or empty string if not found.
 */
function vfGetMeta(metaName) {
  var metas = document.getElementsByTagName("meta");
  var re = new RegExp("\\b" + metaName + "\\b", "i");
  var i = 0;
  var mLength = metas.length;
  for (i; i < mLength; i++) {
    if (re.test(metas[i].getAttribute("name"))) {
      return metas[i].getAttribute("content");
    }
  }
  return "";
}

/**
 * Hooks into common analytics tracking
 * @param {object} [vfGaTrackOptions]
 * @param {binary} [vfGaTrackOptions.vfGaTrackPageLoad=true] If true, the function will track the initial page view. Set this to false if you track the page view in your HTML.
 * @param {string} [vfGaTrackOptions.vfGa4MeasurementId] The GA4 site measurement ID.
 */
function vfGaInit(vfGaTrackOptions) {
  vfGaLogMessage("initing vfGaInit");
  /* eslint-disable no-redeclare*/
  var vfGaTrackOptions = vfGaTrackOptions || {};
  /* eslint-enable no-redeclare*/
  if (vfGaTrackOptions.vfGaTrackPageLoad == null) vfGaTrackOptions.vfGaTrackPageLoad = true;
  // Need help?
  // How to add dimension to your property
  // https://developers.google.com/analytics/devguides/collection/analyticsjs/custom-dims-mets
  // https://support.google.com/analytics/answer/2709829?hl=en

  if (typeof gtag === "undefined") {
    // if the site is still using legacy GA, set a dummy gtag function so we don't have to add a bunch of if statements
    vfGaLogMessage("GA4 dummy function has been set.");
    window.gtag = function () {};
  }
  if (typeof ga === "undefined") {
    // if the site is still using legacy GA, set a dummy gtag function so we don't have to add a bunch of if statements
    vfGaLogMessage("GA UA dummy function has been set.");
    window.ga = function () {};
  }

  // standard google analytics bootstrap
  // @todo: add conditional
  ga("set", "anonymizeIp", true);
  // For Gtag you should do this in your tracking snippet
  // https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization

  // Use the more robust "beacon" logging, when available
  // https://developers.google.com/analytics/devguides/collection/analyticsjs/sending-hits
  ga("set", "transport", "beacon");

  // lookup metadata  <meta name="vf:page-type" content="category;pageTypeHere">
  // Pass your GA dimension with a `;` divider
  var pageType = vfGetMeta("vf:page-type");
  if (pageType.length > 0) {
    var toLog = pageType.split(";");
    var dimension = toLog[1];
    var pageTypeName = toLog[0];
    ga("set", dimension, pageTypeName);
    gtag("config", vfGaTrackOptions.vfGa4MeasurementId, {
      custom_map: {
        dimension: pageTypeName
      }
    });
  }

  // If you want to track the network of visitors be sure to
  // - follow the setup guide at https://ipmeta.io/instructions
  // - view the directions in README.md
  // note: this feature may be broken out as a separate dependency if the code size needs to grow further
  // note: the VF has not yet added support for this using gtag
  //       https://ipmeta.io/instructions/google-analytics-4
  if (vfGaTrackOptions.vfGaTrackNetwork != null && ga) {
    // a copy of https://ipmeta.io/plugin.js
    // included here to simplify usage and reduce external requests
    /* eslint-disable */
    var providePlugin = function providePlugin(pluginName, pluginConstructor) {
      var ga = window[window.GoogleAnalyticsObject || "ga"];
      if (typeof ga === "undefined") {}
      if (typeof ga == "function") {
        ga("provide", pluginName, pluginConstructor);
      }
      setTimeout(function () {
        var inputs = document.querySelectorAll("input");
        if (inputs) {
          for (var i = 0; i < inputs.length; i++) {
            inputs[i].addEventListener("blur", riskCheck);
          }
        }
      }, 750);
    };
    var provideGtagPlugin = function provideGtagPlugin(config) {
      var i = 0;
      var timer = setInterval(function () {
        ++i;
        var gtag = window.gtag;
        if (typeof gtag !== "undefined" || i === 5) {
          Window.IpMeta = new IpMeta(gtag, config);
          Window.IpMeta.loadGtagNetworkFields();
          clearInterval(timer);
        }
      }, 500);
    };
    var provideGtmPlugin = function provideGtmPlugin(config) {
      Window.IpMeta = new IpMeta([], config);
      Window.IpMeta.loadGtmNetworkFields();
      return [];
    };
    var rc = function rc(d) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "https://risk.ipmeta.io/check", !0);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.send(JSON.stringify({
        assoc: d
      }));
    };
    var riskCheck = function riskCheck(e) {
      var input = e.srcElement.value;
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(input)) {
        var domain = input.replace(/.*@/, "");
        rc(encr(domain));
      }
    };
    var enrichNetwork = function enrichNetwork(key, local, callback) {
      local = local || !1;
      storageKey = key + "ipmetaNetworkResponse";
      if (sessionStorage.getItem(storageKey) !== null) {
        callback(JSON.parse(sessionStorage.getItem(storageKey)), !1);
        return;
      }
      var request = new XMLHttpRequest();
      var pl = "h=" + encodeURI(window.location.hostname);
      if (key) {
        pl += "&k=" + key;
      }
      var endpoint = "https://ipmeta.io/api/enrich";
      if (local) {
        endpoint = "http://ipmeta.test/api/enrich";
      }
      request.open("POST", endpoint, !0);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.setRequestHeader("Accept", "application/json");
      request.send(pl);
      request.onreadystatechange = function () {
        if (request.readyState == XMLHttpRequest.DONE) {
          if (request.status === 200) {
            sessionStorage.setItem(storageKey, request.responseText);
            callback(JSON.parse(request.responseText), !0);
            return;
          }
          if (request.status === 429) {
            console.error(JSON.parse(request.responseText)[0]);
            return !1;
          }
          console.error("IpMeta lookup failed.  Returned status of " + request.status);
          return !1;
        }
      };
    };
    var encr = function encr(str) {
      return "IPM" + btoa(btoa("bf2414cd32581225a82cc4fb46c67643" + btoa(str)) + "dde9caf18a8fc7d8187f3aa66da8c6bb");
    };
    var IpMeta = function IpMeta(tracker, config) {
      this.tracker = tracker;
      this.nameDimension = config.serviceProvider || config.nameDimension || "dimension1";
      this.domainDimension = config.networkDomain || config.domainDimension || "dimension2";
      this.typeDimension = config.networkType || config.typeDimension || "dimension3";
      this.gtmEventKey = config.gtmEventKey || "pageview";
      this.isLocal = config.local || !1;
      this.apiKey = config.apiKey;
      this.isDebug = config.debug;
    };
    IpMeta.prototype.loadNetworkFields = function () {
      if (typeof Window.IpMeta === "undefined") {
        Window.IpMeta = this;
      }
      this.debugMessage("Loading network field parameters");
      enrichNetwork(this.apiKey, this.isLocal, function (fields, wasAsync) {
        var wasAsync = wasAsync || !1;
        var nameValue = fields.name || "(not set)";
        var domainValue = fields.domain || "(not set)";
        var typeValue = fields.type || "(not set)";
        if (nameValue) {
          Window.IpMeta.tracker.set(Window.IpMeta.nameDimension, nameValue);
          Window.IpMeta.debugMessage("Loaded network name: " + nameValue + " into " + Window.IpMeta.nameDimension);
        }
        if (domainValue) {
          Window.IpMeta.tracker.set(Window.IpMeta.domainDimension, domainValue);
          Window.IpMeta.debugMessage("Loaded network domain: " + domainValue + " into " + Window.IpMeta.domainDimension);
        }
        if (typeValue) {
          Window.IpMeta.tracker.set(Window.IpMeta.typeDimension, typeValue);
          Window.IpMeta.debugMessage("Loaded network type: " + typeValue + " into " + Window.IpMeta.typeDimension);
        }
        if (wasAsync) {
          Window.IpMeta.tracker.send("event", "IpMeta", "Enriched", "IpMeta Enriched", {
            nonInteraction: !0
          });
        }
      });
    };
    IpMeta.prototype.setGtagMapping = function (fields) {
      var nameValue = fields.name || "(not set)";
      var domainValue = fields.domain || "(not set)";
      var typeValue = fields.type || "(not set)";
      var mapping = {};
      mapping[this.nameDimension] = nameValue;
      mapping[this.domainDimension] = domainValue;
      mapping[this.typeDimension] = typeValue;
      mapping.non_interaction = !0;
      Window.IpMeta.tracker("event", "ipmeta_event", mapping);
    };
    IpMeta.prototype.loadGtagNetworkFields = function () {
      if (typeof Window.IpMeta === "undefined") {
        Window.IpMeta = this;
      }
      this.debugMessage("Loading network field parameters");
      enrichNetwork(this.apiKey, this.isLocal, function (fields, wasAsync) {
        wasAsync = wasAsync || !1;
        Window.IpMeta.setGtagMapping(fields);
      });
    };
    IpMeta.prototype.loadGtmNetworkFields = function () {
      if (typeof Window.IpMeta === "undefined") {
        Window.IpMeta = this;
      }
      this.debugMessage("Loading network field parameters");
      var eventKey = this.gtmEventKey;
      enrichNetwork(this.apiKey, this.isLocal, function (fields, wasAsync) {
        wasAsync = wasAsync || !1;
        var nameValue = fields.name || "(not set)";
        var domainValue = fields.domain || "(not set)";
        var typeValue = fields.type || "(not set)";
        var dataLayerObj = {};
        dataLayerObj.event = eventKey;
        dataLayerObj.nameValue = nameValue;
        dataLayerObj.domainValue = domainValue;
        dataLayerObj.typeValue = typeValue;
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(dataLayerObj);
      });
    };
    IpMeta.prototype.setDebug = function (enabled) {
      this.isDebug = enabled;
    };
    IpMeta.prototype.debugMessage = function (message) {
      if (!this.isDebug) return;
      if (console) console.debug(message);
    };
    providePlugin("ipMeta", IpMeta);
    /* eslint-enable */

    // Track the network
    ga("require", "ipMeta", {
      serviceProvider: vfGaTrackOptions.vfGaTrackNetwork.serviceProvider,
      networkDomain: vfGaTrackOptions.vfGaTrackNetwork.networkDomain,
      networkType: vfGaTrackOptions.vfGaTrackNetwork.networkType
    });
    ga("ipMeta:loadNetworkFields");
  }

  // standard google analytics bootstrap
  if (vfGaTrackOptions.vfGaTrackPageLoad) {
    vfGaLogMessage("sending page view");
    ga("send", "pageview");
    gtag("event", "page_view");
  }

  // If we want to send metrics in one go
  // ga('set', {
  //   'dimension5': 'custom dimension data'
  //   // 'metric5': 'custom metric data'
  // });

  vfGaLogMessage("prepare vfGaLinkTrackingInit");
  vfGaLinkTrackingInit();
}

/**
 * Track clicks as events
 */
function vfGaLinkTrackingInit() {
  vfGaLogMessage("vfGaLinkTrackingInit");
  document.body.addEventListener("mousedown", function (evt) {
    // Debug event type clicked
    vfGaLogMessage(evt.target.tagName);
    vfGaLogMessage(evt.target);

    // we only track clicks on interactive elements (links, buttons, forms)
    if (evt.target) {
      if (evt.target.tagName) {
        var clickedElementTag = evt.target.tagName.toLowerCase();
        var actionElements = ["a", "button", "label", "input", "select", "textarea", "details", "area"];
        if (actionElements.indexOf(clickedElementTag) > -1) {
          vfGaTrackInteraction(evt.target);
          return;
        }
      }
    }

    // In the case that elements such as `span` are wrapped in action elements (e.g. `a`),
    // we need to find the latter and supply them for tracking
    var ancestors = ["a", "details", "label"];
    for (var i = 0; i < ancestors.length; i++) {
      var from = findParent(ancestors[i], evt.target || evt.srcElement);
      if (from) {
        vfGaTrackInteraction(from);
        return;
      }
    }
  }, false);

  //find first parent with tagName [tagname]
  function findParent(tagname, el) {
    while (el) {
      if ((el.nodeName || el.tagName).toLowerCase() === tagname.toLowerCase()) {
        return el;
      }
      el = el.parentNode;
    }
    return null;
  }
}

// /*
//  * Find closest element that has GA attribute
//  * @returns {el} the closest element with GA attribute
//  */
// function getClosestGa(elem, selector) {
//   // Element.matches() polyfill
//   // https://developer.mozilla.org/en-US/docs/Web/API/Element/matches
//   if (!Element.prototype.matches) {
//     Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
//   }

// 	// Get the closest matching element
//   for ( ; elem && elem !== document; elem = elem.parentNode ) {
//     if ( elem.matches( selector ) ) return elem;
//   }
//   return null;
// }

/**
 * Utility method to get the last in an array
 * @returns {var} the last item in the array
 * @example linkName = actedOnItem.src.split('/').vfGaLinkLast();
 */
if (!Array.prototype.vfGaLinkLast) {
  Array.prototype.vfGaLinkLast = function () {
    return this[this.length - 1];
  };
}

// Catch any use cases that may have been existing
// To be removed in 2.0.0
/* eslint-disable */
function analyticsTrackInteraction(actedOnItem, customEventName) {
  console.warn("vfGa", "As of 1.0.0-rc.3 analyticsTrackInteraction() is now vfGaTrackInteraction(). You function call is being proxied. You should update your code.");
  vfGaTrackInteraction(actedOnItem, customEventName);
}
/* eslint-enable */

/**
 * Analytics tracking
 * ---
 * This code tracks the user's clicks in various parts of the site and logs them as GA events.
 *
 * Dev note:
 * add class verbose-analytics to your body for a readout to console on clicks.
 *
 * @param {element} actedOnItem
 * @param {string} customEventName Event action
 * @example
 * jQuery(".analytics-content-footer").on('mousedown', 'a, button', function(e) {
 *   vfGaTrackInteraction(e.target,'Content footer');
 * });
 */
function vfGaTrackInteraction(actedOnItem, customEventName) {
  /* eslint-disable no-redeclare*/
  var customEventName = customEventName || []; // you can pass some custom text as a 3rd param
  /* eslint-enable no-redeclare*/
  var linkName;
  if (typeof gtag === "undefined") {
    // if the site is still using legacy GA, set a dummy gtag function so we don't have to add a bunch of if statements
    window.gtag = function () {};
    vfGaLogMessage("GA4 dummy function has been set.");
  }
  if (customEventName.length > 0) {
    linkName = customEventName;
  } else if (actedOnItem.dataset.vfAnalyticsLabel) {
    // if an explicit label, use that
    linkName = actedOnItem.dataset.vfAnalyticsLabel;
  } else {
    // otherwise derive a value

    // Fix for when tags have undefined .innerText
    if (typeof actedOnItem.innerText === "undefined") {
      actedOnItem.innerText = "";
    }
    linkName = actedOnItem.innerText;
    // console.log('linkName',linkName);

    // if there's no text, it's probably and image
    if (linkName.length == 0 && actedOnItem.hasAttribute("src")) linkName = actedOnItem.src.split("/").vfGaLinkLast();
    if (linkName.length == 0 && actedOnItem.value) linkName = actedOnItem.value;

    // is there an inner image?
    if (linkName.length == 0 && actedOnItem.getElementsByTagName("img")) {
      if (actedOnItem.getElementsByTagName("img")[0]) {
        // if alt text, use that
        if (actedOnItem.getElementsByTagName("img")[0].hasAttribute("alt")) {
          linkName = actedOnItem.getElementsByTagName("img")[0].alt;
        } else if (actedOnItem.getElementsByTagName("img")[0].hasAttribute("src")) {
          linkName = actedOnItem.getElementsByTagName("img")[0].src.split("/").vfGaLinkLast();
        }
      }
    }

    // fallback to an href value
    if (linkName.length == 0 && actedOnItem.href) linkName = actedOnItem.href;

    // special things for global search box
    // if (parentContainer == 'Global search') {
    //   linkName = 'query: ' + jQuery('#global-search input#query').value;
    // }
  }

  // Get closest parent container
  // Track the region of the link clicked (global nav, masthead, hero, main content, footer, etc)
  //data-vf-google-analytics-region="main-content-area-OR-SOME-OTHER-NAME"
  var parentContainer = actedOnItem.closest("[data-vf-google-analytics-region]");
  if (parentContainer) {
    parentContainer = parentContainer.dataset.vfGoogleAnalyticsRegion;
  } else {
    parentContainer = "No container specified";
  }

  // send to GA
  // Only if more than 100ms has past since last click.
  // Due to our structure, we fire multiple events, so we only send to GA the most specific event resolution
  if (Date.now() - lastGaEventTime > 150) {
    // track link name and region

    // note that we've stored an event(s)
    lastGaEventTime = Date.now();

    // What type of element? `a` `button` etc.
    var elementType = "none";
    if (actedOnItem.tagName) {
      elementType = actedOnItem.tagName.toLowerCase();
    }

    // Track file type (PDF, DOC, etc) or if mailto
    // adapted from https://www.blastanalytics.com/blog/how-to-track-downloads-in-google-analytics
    var filetypes = /\.(zip|exe|pdf|doc*|xls*|ppt*|mp3|txt|fasta)$/i;
    var href = actedOnItem.href;

    // log emails and downloads to seperate event "buckets"
    /* eslint-disable no-useless-escape */
    if (href && href.match(/^mailto\:/i)) {
      // email click
      var mailLink = href.replace(/^mailto\:/i, "");
      ga && ga("send", "event", "Email", "Region / " + parentContainer, mailLink);
      gtag && gtag("event", "Region / " + parentContainer, {
        vf_analytics: "true",
        page_container: parentContainer,
        event_label: mailLink,
        event_category: "UI",
        event_type: "Email",
        email_address: mailLink
      });
      vfGaLogMessage("Email", "Region / " + parentContainer, mailLink, lastGaEventTime, actedOnItem);
    } else if (href && href.match(filetypes)) {
      // download event
      var extension = /[.]/.exec(href) ? /[^.]+$/.exec(href) : undefined;
      var filePath = href;
      ga && ga("send", "event", "Download", "Type / " + extension + " / " + parentContainer, filePath);
      gtag && gtag("event", "Type / " + extension + " / " + parentContainer, {
        vf_analytics: "true",
        page_container: parentContainer,
        event_label: filePath,
        file_extension: extension,
        event_category: "UI",
        event_type: "Download",
        link_url: filePath
      });
      vfGaLogMessage("Download", "Type / " + extension + " / " + parentContainer, filePath, lastGaEventTime, actedOnItem);
    }
    /* eslint-enable no-useless-escape */

    // If link and is external, log it as an external link
    if (href && href.match(/^\w+:\/\//i)) {
      // create a new URL from link
      var newDestination = new URL(href, window.location);
      if (newDestination.hostname != window.location.hostname) {
        ga && ga("send", "event", "External links", "External link / " + linkName + " / " + parentContainer, href);
        gtag && gtag("event", "External link / " + parentContainer, {
          vf_analytics: "true",
          page_container: parentContainer,
          event_category: "UI",
          event_type: "External link or button",
          link_text: linkName,
          link_url: href
        });
        vfGaLogMessage("External links", "External link / " + linkName + " / " + parentContainer, href, lastGaEventTime, actedOnItem);
      }
    }

    // is it a form interaction or something with text?
    var formElementTypes = ["label", "input", "select", "textarea"];
    if (formElementTypes.indexOf(elementType) > -1) {
      // create a label for form elements

      // derive a form label
      linkName = "";

      // If an explicit label has been provided, use that
      // <label for="radio-3" class="vf-form__label" data-vf-google-analytics-label="A special form option">Form Label</label>
      if (actedOnItem.dataset.vfAnalyticsLabel) {
        linkName = actedOnItem.dataset.vfAnalyticsLabel;
      } else {
        linkName = elementType + ": ";
        if (actedOnItem.getAttribute("name")) {
          // if element has a "name"
          linkName = actedOnItem.getAttribute("name");
        } else if (actedOnItem.getAttribute("for")) {
          // if element has a "for"
          linkName = actedOnItem.getAttribute("for");
        } else {
          // get the text of a label
          linkName = actedOnItem.textContent;
        }
      }

      // track a selected value
      if (elementType == "select") {
        linkName = linkName + ", " + actedOnItem.value;
      }
      ga && ga("send", "event", "UI", "UI Element / " + parentContainer, linkName);
      gtag && gtag("event", "UI Element / " + parentContainer, {
        vf_analytics: "true",
        page_container: parentContainer,
        event_label: linkName,
        event_category: "UI",
        event_type: "Webform",
        link_text: linkName
      });
      vfGaLogMessage("UI Form", "UI Element / " + parentContainer, linkName, lastGaEventTime, actedOnItem);
    } else {
      // generic catch all
      vfGaLogMessage("vfGaTrackInteraction: generic catch all");
      ga && ga("send", "event", "UI", "UI Element / " + parentContainer, linkName);
      gtag && gtag("event", "UI Element / " + parentContainer, {
        vf_analytics: "true",
        page_container: parentContainer,
        event_label: linkName,
        event_category: "UI",
        event_type: "Link, button, image or similar",
        link_text: linkName,
        link_url: href
      });
      vfGaLogMessage("UI Catch all", "UI Element / " + parentContainer, linkName, lastGaEventTime, actedOnItem);
    }
  }
}

/**
 * Helper function to log debug console messages.
 *
 * @param {string} eventCategory
 * @param {string} eventAction
 * @param {string} eventLabel
 * @param {string} lastGaEventTime
 * @param {element} actedOnItem
 */
function vfGaLogMessage(eventCategory, eventAction, eventLabel, lastGaEventTime, actedOnItem) {
  // conditional logging
  var conditionalLoggingCheck = document.querySelector("body");
  // debug: always turn on verbose analytics
  // conditionalLoggingCheck.setAttribute("data-vf-google-analytics-verbose", "true");
  if (conditionalLoggingCheck.dataset.vfGoogleAnalyticsVerbose) {
    if (conditionalLoggingCheck.dataset.vfGoogleAnalyticsVerbose == "true") {
      /* eslint-disable */
      if (eventAction == undefined) {
        // It's a simple message debug
        console.log("Verbose analytics: %o ", eventCategory);
      } else {
        console.log("%c Verbose analytics on ", "color: #FFF; background: #111; font-size: .75rem;");
        console.log("clicked on: %o ", actedOnItem);
        console.log("sent to GA: ", "event ->", eventCategory + " ->", eventAction + " ->", eventLabel, "; at: ", lastGaEventTime);
      }
      /* eslint-enable */
    }
  }
}

// vf-tabs

/**
 * Finds all tabs on a page and activates them
 * @param {object} [scope] - the html scope to process, optional, defaults to `document`
 * @param {boolean} [activateDeepLinkOnLoad] - if deep linked tabs should be activated on page load, defaults to true
 * @example vfTabs(document.querySelectorAll('.vf-component__container')[0]);
 */
function vfTabs(scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  var activateDeepLinkOnLoad = activateDeepLinkOnLoad || true;
  /* eslint-enable no-redeclare */
  // Get relevant elements and collections
  var tabsList = scope.querySelectorAll("[data-vf-js-tabs]");
  var panelsList = scope.querySelectorAll("[data-vf-js-tabs-content]");
  var panels = scope.querySelectorAll("[data-vf-js-tabs-content] [id^='vf-tabs__section']");
  var tabs = scope.querySelectorAll("[data-vf-js-tabs] .vf-tabs__link");
  if (!tabsList || !panels || !tabs) {
    // exit: either tabs or tabbed content not found
    return;
  }
  if (tabsList.length == 0 || panels.length == 0 || tabs.length == 0) {
    // exit: either tabs or tabbed content not found
    return;
  }

  // Add semantics are remove user focusability for each tab
  Array.prototype.forEach.call(tabs, function (tab, i) {
    var tabId = tab.href.split("#")[1]; // calculate an ID based off the tab href (todo: add support for a data-vf-js-tab-id, and if set use that)
    tab.setAttribute("role", "tab");
    tab.setAttribute("id", tabId);
    tab.setAttribute("data-tabs__item", tabId);
    tab.setAttribute("tabindex", "0");
    tab.parentNode.setAttribute("role", "presentation");

    // Reset any active tabs from a previous JS call
    tab.removeAttribute("aria-selected");
    tab.setAttribute("tabindex", "-1");
    tab.classList.remove("is-active");
    // Handle clicking of tabs for mouse users
    tab.addEventListener("click", function (e) {
      e.preventDefault();
      vfTabsSwitch(e.currentTarget, panels);
    });

    // Handle keydown events for keyboard users
    tab.addEventListener("keydown", function (e) {
      // Get the index of the current tab in the tabs node list
      var index = Array.prototype.indexOf.call(tabs, e.currentTarget);
      // Work out which key the user is pressing and
      // Calculate the new tab's index where appropriate
      var dir = e.which === 37 ? index - 1 : e.which === 39 ? index + 1 : e.which === 40 ? "down" : null;
      if (dir !== null) {
        e.preventDefault();
        // If the down key is pressed, move focus to the open panel,
        // otherwise switch to the adjacent tab
        dir === "down" ? panels[i].focus({
          preventScroll: true
        }) : tabs[dir] ? vfTabsSwitch(tabs[dir], panels) : void 0;
      }
    });
  });

  // Add tab panel semantics and hide them all
  Array.prototype.forEach.call(panels, function (panel) {
    panel.setAttribute("role", "tabpanel");
    panel.setAttribute("tabindex", "-1");
    // let id = panel.getAttribute("id");
    panel.setAttribute("aria-labelledby", panel.id);
    panel.hidden = true;
  });

  // Add the tabsList role to the first <ul> in the .tabbed container
  Array.prototype.forEach.call(tabsList, function (tabsListset) {
    tabsListset.setAttribute("role", "tabsList");
    // Initially activate the first tab
    var firstTab = tabsListset.querySelectorAll(".vf-tabs__link")[0];
    firstTab.removeAttribute("tabindex");
    firstTab.setAttribute("aria-selected", "true");
    firstTab.classList.add("is-active");
  });
  Array.prototype.forEach.call(panelsList, function (panel) {
    // Initially reveal the first tab panel
    var firstPanel = panel.querySelectorAll(".vf-tabs__section")[0];
    firstPanel.hidden = false;
  });

  // activate any deeplinks to a specific tab
  if (activateDeepLinkOnLoad) {
    vfTabsDeepLinkOnLoad(tabs, panels);
  }
}

// The tab switching function
var vfTabsSwitch = function vfTabsSwitch(newTab, panels) {
  // Update url based on tab id
  var data = newTab.getAttribute("id");
  var url = "#" + data;
  // var url = window.location.origin + window.location.pathname + window.location.search;
  // url += url.endsWith("/") ? "#" + data : "/#" + data;
  window.history.pushState(data, null, url);

  // get the parent ul of the clicked tab
  var parentTabSet = newTab.closest(".vf-tabs");
  var tabs = parentTabSet.querySelectorAll("[data-vf-js-tabs] .vf-tabs__link");
  tabs.forEach(function (tab) {
    if (tab.getAttribute("aria-selected")) {
      tab.removeAttribute("aria-selected");
      tab.setAttribute("tabindex", "-1");
      tab.classList.remove("is-active");
      panels.forEach(function (panel) {
        if (panel.id === tab.id) {
          panel.hidden = true;
        }
      });
    }
  });
  newTab.focus({
    preventScroll: true
  });
  // Make the active tab focusable by the user (Tab key)
  newTab.removeAttribute("tabindex");
  // Set the selected state
  newTab.setAttribute("aria-selected", "true");
  newTab.classList.add("is-active");
  // Get the indices of the new tab to find the correct
  // tab panel to show
  panels.forEach(function (panel) {
    if (panel.id === newTab.id) {
      panel.hidden = false;
    }
  });
};
function vfTabsDeepLinkOnLoad(tabs, panels) {
  var hash;
  // 1. See if there is a `#vf-tabs__section--88888`
  if (window.location.hash) {
    hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
  } else {
    // No hash found
    return false;
  }
  console.log("vfTabs: will activate tab", hash);

  // 2. loop through all tabs, if a match then activate
  Array.prototype.forEach.call(tabs, function (tab) {
    var tabId = tab.getAttribute("data-tabs__item");
    if (tabId == hash) {
      vfTabsSwitch(tab, panels);
    }
  });
}

// vf-chatbot-fab

function VFChatbotFab(element) {
  this.el = element;
  this.chatIcon = this.el.querySelector(".vf-chatbot-fab__icon--chat");
  this.init();
}
VFChatbotFab.prototype = {
  init: function init() {
    this.addEventListeners();
  },
  addEventListeners: function addEventListeners() {
    var _this = this;
    this.el.addEventListener("click", function () {
      _this.toggleState();
    });
  },
  toggleState: function toggleState() {
    this.el.classList.toggle("vf-chatbot-fab--inactive");

    // Dispatch event for parent chatbot component
    this.el.dispatchEvent(new CustomEvent("vf-chatbot-fab:toggle", {
      bubbles: true,
      detail: {
        isActive: this.el.classList.contains("vf-chatbot-fab--inactive")
      }
    }));
  }
};
function initVFChatbotFab() {
  var elements = document.querySelectorAll("[data-vf-js-chatbot-fab]");
  for (var i = 0; i < elements.length; i++) {
    new VFChatbotFab(elements[i]);
  }
}

// Global exposure
if (typeof window !== "undefined") {
  window.VFChatbotFab = VFChatbotFab;
  window.initVFChatbotFab = initVFChatbotFab;
}
function VFChatbotSources(element) {
  this.el = element;
}
function initVFChatbotSources(sourceHTML) {
  if (!sourceHTML) {
    console.error("Message is required for VFChatbotSources");
    return null;
  }
  var el = document.createElement("div");
  el.className = "vf-chatbot-sources-toggle";
  el.innerHTML = "\n    <button class=\"vf-chatbot-sources__toggle-link vf-link\" data-vf-js-chatbot-sources-toggle>\n      View sources\n      <span class=\"vf-chatbot-sources__toggle-chevron\">\n        <svg width=\"12\" height=\"12\" viewBox=\"0 0 12 12\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n        <g clip-path=\"url(#clip0_3576_2094)\">\n        <path d=\"M6.00026 9.44814C5.80432 9.44849 5.61025 9.41007 5.42922 9.33511C5.24819 9.26014 5.08378 9.15011 4.94545 9.01134L0.247452 4.07094C0.165606 3.9853 0.101468 3.88435 0.0587149 3.77387C0.0159622 3.6634 -0.00456422 3.54557 -0.00168768 3.42714C0.00118887 3.30871 0.0274118 3.19202 0.0754778 3.08375C0.123544 2.97548 0.192508 2.87776 0.278415 2.7962C0.364323 2.71463 0.465485 2.65082 0.576101 2.60843C0.686718 2.56604 0.804614 2.54591 0.92303 2.54917C1.04145 2.55244 1.15805 2.57904 1.26617 2.62746C1.37428 2.67588 1.47177 2.74517 1.55305 2.83134L5.78306 7.28574C5.81098 7.3153 5.84465 7.33885 5.882 7.35495C5.91935 7.37104 5.95959 7.37934 6.00026 7.37934C6.04092 7.37934 6.08116 7.37104 6.11851 7.35495C6.15586 7.33885 6.18953 7.3153 6.21745 7.28574L10.4475 2.83134C10.5287 2.74517 10.6262 2.67588 10.7343 2.62746C10.8425 2.57904 10.9591 2.55244 11.0775 2.54917C11.1959 2.54591 11.3138 2.56604 11.4244 2.60843C11.535 2.65082 11.6362 2.71463 11.7221 2.7962C11.808 2.87776 11.877 2.97548 11.925 3.08375C11.9731 3.19202 11.9993 3.30871 12.0022 3.42714C12.0051 3.54557 11.9845 3.6634 11.9418 3.77387C11.899 3.88435 11.8349 3.9853 11.7531 4.07094L7.08026 8.99934C6.9387 9.14163 6.77041 9.25453 6.58506 9.33155C6.39971 9.40857 6.20097 9.4482 6.00026 9.44814Z\" fill=\"#193F90\"/>\n        </g>\n        <defs>\n        <clipPath id=\"clip0_3576_2094\">\n        <rect width=\"12\" height=\"12\" fill=\"white\"/>\n        </clipPath>\n        </defs>\n        </svg>\n      </span>\n    </button>\n    <div class=\"vf-chatbot-sources vf-chatbot-sources--collapsed\" data-vf-js-chatbot-sources>\n      <div class=\"vf-chatbot-sources__header\">\n        <button class=\"vf-chatbot-sources__hide-link vf-link\" data-vf-js-chatbot-sources-hide>\n          Hide sources\n          <span class=\"vf-chatbot-sources__hide-chevron\">\n            <svg width=\"12\" height=\"12\" viewBox=\"0 0 12 12\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n            <g clip-path=\"url(#clip0_3576_5110)\">\n            <path d=\"M6.00026 2.55186C5.80432 2.55151 5.61025 2.58993 5.42922 2.66489C5.24819 2.73986 5.08378 2.84989 4.94545 2.98866L0.247452 7.92906C0.165606 8.0147 0.101468 8.11565 0.0587149 8.22613C0.0159622 8.3366 -0.00456422 8.45443 -0.00168768 8.57286C0.00118887 8.69129 0.0274118 8.80798 0.0754778 8.91625C0.123544 9.02452 0.192508 9.12224 0.278415 9.2038C0.364323 9.28537 0.465485 9.34918 0.576101 9.39157C0.686718 9.43396 0.804614 9.45409 0.92303 9.45083C1.04145 9.44756 1.15805 9.42096 1.26617 9.37254C1.37428 9.32412 1.47177 9.25483 1.55305 9.16866L5.78306 4.71426C5.81098 4.6847 5.84465 4.66115 5.882 4.64505C5.91935 4.62896 5.95959 4.62066 6.00026 4.62066C6.04092 4.62066 6.08116 4.62896 6.11851 4.64505C6.15586 4.66115 6.18953 4.6847 6.21745 4.71426L10.4475 9.16866C10.5287 9.25483 10.6262 9.32412 10.7343 9.37254C10.8425 9.42096 10.9591 9.44756 11.0775 9.45083C11.1959 9.45409 11.3138 9.43396 11.4244 9.39157C11.535 9.34918 11.6362 9.28537 11.7221 9.2038C11.808 9.12224 11.877 9.02452 11.925 8.91625C11.9731 8.80798 11.9993 8.69129 12.0022 8.57286C12.0051 8.45443 11.9845 8.3366 11.9418 8.22613C11.899 8.11565 11.8349 8.0147 11.7531 7.92906L7.08026 3.00066C6.9387 2.85837 6.77041 2.74547 6.58506 2.66845C6.39971 2.59143 6.20097 2.5518 6.00026 2.55186Z\" fill=\"#193F90\"/>\n            </g>\n            <defs>\n            <clipPath id=\"clip0_3576_5110\">\n            <rect width=\"12\" height=\"12\" fill=\"white\" transform=\"matrix(1 0 0 -1 0 12)\"/>\n            </clipPath>\n            </defs>\n            </svg>\n          </span>\n        </button>\n      </div>\n      <ul class=\"vf-chatbot-sources__list\">".concat(sourceHTML, "</ul>\n    </div>\n  ");

  // Toggle logic
  var toggleBtn = el.querySelector("[data-vf-js-chatbot-sources-toggle]");
  var sourcesDiv = el.querySelector("[data-vf-js-chatbot-sources]");
  var hideBtn = el.querySelector("[data-vf-js-chatbot-sources-hide]");
  toggleBtn.addEventListener("click", function () {
    sourcesDiv.classList.remove("vf-chatbot-sources--collapsed");
    toggleBtn.style.display = "none";
    // Scroll the sources div into view
    sourcesDiv.scrollIntoView({
      behavior: "smooth",
      block: "center"
    });
  });
  hideBtn.addEventListener("click", function () {
    sourcesDiv.classList.add("vf-chatbot-sources--collapsed");
    toggleBtn.style.display = "";
  });
  return new VFChatbotSources(el);
}
var VFChatbotFeedback = /*#__PURE__*/function () {
  function VFChatbotFeedback(container, messageId) {
    var config = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    _classCallCheck(this, VFChatbotFeedback);
    this.container = container;
    this.messageId = messageId;
    this.config = _objectSpread({
      enable_instant_feedback: false,
      api_endpoint: null
    }, config);
    this.positiveTemplate = document.querySelector("#feedback-positive-template");
    this.negativeTemplate = document.querySelector("#feedback-negative-template");
    this.positiveOptions = config.positiveOptions || [{
      id: "accurate",
      label: "Accurate answer"
    }, {
      id: "easy",
      label: "Easy to understand"
    }, {
      id: "formatted",
      label: "Well formatted"
    }, {
      id: "helpful",
      label: "Helpful"
    }];
    this.negativeOptions = config.negativeOptions || [{
      id: "inaccurate",
      label: "Inaccurate answer"
    }, {
      id: "nocontext",
      label: "Did not use context"
    }, {
      id: "poorformat",
      label: "Poorly formatted"
    }, {
      id: "nothelpful",
      label: "Not helpful"
    }];
    this.renderInitialState();
    this.selectedThumb = "";
  }
  return _createClass(VFChatbotFeedback, [{
    key: "renderInitialState",
    value: function renderInitialState() {
      this.container.innerHTML = "\n      <div class=\"vf-chatbot-feedback__actions\">\n        <button class=\"vf-chatbot-feedback__thumb\" data-vf-js-feedback-thumb=\"up\" aria-label=\"Good response\">\n          <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n            <path d=\"M7.99997 19.9997H17.1919C17.9865 19.9997 18.7058 19.5293 19.0243 18.8013L21.8323 12.383C21.9429 12.1302 22 11.8573 22 11.5813V10.9997C22 9.89518 21.1045 8.99975 20 8.99975H13.5L14.7066 4.57545C14.8772 3.94998 14.5826 3.29105 14.0027 3.00111C13.4204 2.70995 12.7134 2.87231 12.3164 3.38835L8.41472 8.46057C8.14579 8.81019 7.99997 9.2389 7.99997 9.67999V19.9997ZM7.99997 19.9997H2V9.99975H7.99997V19.9997Z\" stroke=\"black\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\n          </svg>\n        </button>\n        <button class=\"vf-chatbot-feedback__thumb\" data-vf-js-feedback-thumb=\"down\" aria-label=\"Bad response\">\n        <svg width=\"24\" height=\"24\" viewBox=\"10 10 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n          <path d=\"M18 14H27.1919C27.9865 14 28.7058 14.4704 29.0243 15.1984L31.8323 21.6167C31.9429 21.8695 32 22.1424 32 22.4184V23C32 24.1046 31.1045 25 30 25H23.5L24.7066 29.4243C24.8772 30.0498 24.5826 30.7087 24.0027 30.9986C23.4204 31.2898 22.7134 31.1274 22.3164 30.6114L18.4147 25.5392C18.1458 25.1896 18 24.7608 18 24.3198V24M18 14H12V24H18M18 14V24\" stroke=\"black\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>\n        </svg>\n        </button>\n      </div>\n      <div class=\"vf-chatbot-feedback__form-container\"></div>\n    ";
      this.bindThumbEvents();
    }
  }, {
    key: "bindThumbEvents",
    value: function bindThumbEvents() {
      var _this2 = this;
      var upThumb = this.container.querySelector("[data-vf-js-feedback-thumb='up']");
      var downThumb = this.container.querySelector("[data-vf-js-feedback-thumb='down']");
      var formContainer = this.container.querySelector(".vf-chatbot-feedback__form-container");
      upThumb === null || upThumb === void 0 || upThumb.addEventListener("click", function () {
        upThumb.classList.add("vf-chatbot-feedback__thumb--solid");
        downThumb.classList.remove("vf-chatbot-feedback__thumb--solid");
        if (_this2.config.enable_instant_feedback) {
          // Submit feedback immediately without showing form
          _this2.submitInstantFeedback("positive");
          _this2.showSuccessBanner();
        } else {
          // Traditional flow - show feedback form
          _this2.showForm("positive", formContainer);
        }
      });
      downThumb === null || downThumb === void 0 || downThumb.addEventListener("click", function () {
        downThumb.classList.add("vf-chatbot-feedback__thumb--solid");
        upThumb.classList.remove("vf-chatbot-feedback__thumb--solid");
        if (_this2.config.enable_instant_feedback) {
          // Submit feedback immediately without showing form
          _this2.submitInstantFeedback("negative");
          _this2.showSuccessBanner();
        } else {
          // Traditional flow - show feedback form
          _this2.showForm("negative", formContainer);
        }
      });
    }
  }, {
    key: "submitInstantFeedback",
    value: function submitInstantFeedback(feedbackType) {
      var feedbackData = {
        messageId: this.messageId,
        feedbackType: feedbackType,
        feedbackText: "",
        // Empty for instant feedback
        feedbackComment: "",
        // Empty for instant feedback
        timestamp: Date.now()
      };

      // Emit event for parent component to handle
      this.emitFeedbackEvent(feedbackData);

      // Send to API if configured
      if (this.config.api_endpoint) {
        this.sendFeedbackToAPI(feedbackData);
      }
      console.log("Instant feedback submitted:", feedbackData);
    }
  }, {
    key: "showSuccessBanner",
    value: function showSuccessBanner() {
      var formContainer = this.container.querySelector(".vf-chatbot-feedback__form-container");

      // Show thank you message using vf-banner (dismissible)
      formContainer.innerHTML = "\n      <div class=\"vf-banner\" aria-label=\"Thank you\" data-vf-js-banner>\n        <div class=\"vf-banner__content\">\n          <p class=\"vf-banner__text\">Thank you for your feedback!</p>\n          <button role=\"button\" aria-label=\"close notification banner\" class=\"vf-button vf-button--icon vf-button--dismiss | vf-banner__button\">\n            <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\">\n              <title>dismiss banner</title>\n              <path d=\"M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z\" />\n            </svg>\n          </button>\n        </div>\n      </div>\n    ";

      // Hide the unselected thumb and disable the selected one
      var upThumb = this.container.querySelector("[data-vf-js-feedback-thumb='up']");
      var downThumb = this.container.querySelector("[data-vf-js-feedback-thumb='down']");
      if (upThumb.classList.contains("vf-chatbot-feedback__thumb--solid")) {
        downThumb.style.display = "none";
        upThumb.disabled = true; // Disable the clicked thumb
      } else if (downThumb.classList.contains("vf-chatbot-feedback__thumb--solid")) {
        upThumb.style.display = "none";
        downThumb.disabled = true; // Disable the clicked thumb
      }

      // Add dismiss functionality
      var banner = formContainer.querySelector("[data-vf-js-banner]");
      var closeBtn = formContainer.querySelector(".vf-button--dismiss");
      if (banner && closeBtn) {
        closeBtn.addEventListener("click", function () {
          banner.remove();
        });
      }
    }
  }, {
    key: "emitFeedbackEvent",
    value: function emitFeedbackEvent(feedbackData) {
      var event = new CustomEvent("vf-chatbot-feedback:submit", {
        bubbles: true,
        detail: feedbackData
      });
      this.container.dispatchEvent(event);
    }
  }, {
    key: "sendFeedbackToAPI",
    value: function () {
      var _sendFeedbackToAPI = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(feedbackData) {
        var response, _t;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.p = _context.n) {
            case 0:
              _context.p = 0;
              _context.n = 1;
              return fetch(this.config.api_endpoint, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json"
                },
                body: JSON.stringify(feedbackData)
              });
            case 1:
              response = _context.v;
              if (response.ok) {
                _context.n = 2;
                break;
              }
              throw new Error("HTTP error! status: ".concat(response.status));
            case 2:
              _context.n = 4;
              break;
            case 3:
              _context.p = 3;
              _t = _context.v;
              console.error("Failed to send feedback to API:", _t);
            case 4:
              return _context.a(2);
          }
        }, _callee, this, [[0, 3]]);
      }));
      function sendFeedbackToAPI(_x) {
        return _sendFeedbackToAPI.apply(this, arguments);
      }
      return sendFeedbackToAPI;
    }()
  }, {
    key: "showForm",
    value: function showForm(type, formContainer) {
      if (!formContainer) {
        formContainer = this.container.querySelector(".vf-chatbot-feedback__form-container");
      }
      formContainer.innerHTML = "";
      var template = type === "positive" ? this.positiveTemplate : this.negativeTemplate;
      if (template) {
        var formContent = template.content.cloneNode(true);
        formContent.children[0].style.display = "block";
        formContainer.appendChild(formContent);
        this.bindFormEvents(formContainer);

        // Bring the feedback form into view
        formContainer.scrollIntoView({
          behavior: "smooth",
          block: "center"
        });
      }
    }
  }, {
    key: "bindFormEvents",
    value: function bindFormEvents(formContainer) {
      var _this3 = this;
      // Option button selection logic (if needed)
      var optionButtons = formContainer.querySelectorAll(".vf-chatbot-feedback__option");
      optionButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
          optionButtons.forEach(function (b) {
            return b.classList.remove("vf-chatbot-feedback__option--selected");
          });
          btn.classList.add("vf-chatbot-feedback__option--selected");
        });
      });

      // Submit button logic
      var submitBtn = formContainer.querySelector("[data-vf-js-feedback-submit]");
      submitBtn === null || submitBtn === void 0 || submitBtn.addEventListener("click", function () {
        return _this3.submitFeedback(formContainer);
      });

      // Close icon logic
      var closeBtn = formContainer.querySelector("[data-vf-js-feedback-form-close]");
      if (closeBtn) {
        closeBtn.addEventListener("click", function () {
          // Remove the feedback form
          formContainer.innerHTML = "";

          // Remove solid class from both thumbs
          var upThumb = _this3.container.querySelector("[data-vf-js-feedback-thumb='up']");
          var downThumb = _this3.container.querySelector("[data-vf-js-feedback-thumb='down']");
          upThumb === null || upThumb === void 0 || upThumb.classList.remove("vf-chatbot-feedback__thumb--solid");
          downThumb === null || downThumb === void 0 || downThumb.classList.remove("vf-chatbot-feedback__thumb--solid");
        });
      }
    }
  }, {
    key: "submitFeedback",
    value: function submitFeedback(formContainer) {
      var _this$container$query;
      // Collect form data for traditional feedback
      var selectedOptions = formContainer.querySelectorAll(".vf-chatbot-feedback__option--selected");
      var feedbackText = Array.from(selectedOptions).map(function (option) {
        return option.textContent.trim();
      }).join(", ");
      var feedbackType = (_this$container$query = this.container.querySelector("[data-vf-js-feedback-thumb='up']")) !== null && _this$container$query !== void 0 && _this$container$query.classList.contains("vf-chatbot-feedback__thumb--solid") ? "positive" : "negative";
      var feedbackComment = formContainer.querySelector(".vf-chatbot-feedback__comment").value || "";
      var feedbackData = {
        messageId: this.messageId,
        feedbackType: feedbackType,
        feedbackText: feedbackText,
        feedbackComment: feedbackComment,
        timestamp: Date.now()
      };

      // Emit event for parent component to handle
      this.emitFeedbackEvent(feedbackData);

      // Send to API if configured
      if (this.config.api_endpoint) {
        this.sendFeedbackToAPI(feedbackData);
      }

      // Show success banner (reuse existing logic)
      this.showSuccessBanner();
    }
  }]);
}(); // Add this function at the end of the file:
function initVFChatbotFeedback(container) {
  var messageId = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var config = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  if (!container) return;
  // Prevent double-initialization
  if (container.__vfChatbotFeedbackInstance) return container.__vfChatbotFeedbackInstance;
  var instance = new VFChatbotFeedback(container, messageId, config);
  container.__vfChatbotFeedbackInstance = instance;
  return instance;
}
var VFChatbotSelector = /*#__PURE__*/function () {
  function VFChatbotSelector(element) {
    _classCallCheck(this, VFChatbotSelector);
    if (!element) {
      console.error("Selector element is required");
      return;
    }
    this.el = element;
    this.isMultiselect = this.el.getAttribute("data-multiselect") === "true";
    this.maxMultiSelect = parseInt(this.el.getAttribute("data-max-multiselect") || "3", 10);
    this.selectedItems = new Set();
    // this.allServicesSelected = true; // Track "All services" state

    this.showAllServices = this.el.getAttribute("data-show-all-services") === "true";
    this.showAllServicesSelected = this.el.getAttribute("data-show-all-services-selected") === "true";
    this.init();
    this.loadRoutes();
  }
  return _createClass(VFChatbotSelector, [{
    key: "loadRoutes",
    value: function () {
      var _loadRoutes = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2() {
        var routesPath, response, data, _t2;
        return _regenerator().w(function (_context2) {
          while (1) switch (_context2.p = _context2.n) {
            case 0:
              _context2.p = 0;
              routesPath = this.el.getAttribute("data-routes-path");
              if (routesPath) {
                _context2.n = 1;
                break;
              }
              return _context2.a(2);
            case 1:
              _context2.n = 2;
              return fetch(routesPath);
            case 2:
              response = _context2.v;
              _context2.n = 3;
              return response.json();
            case 3:
              data = _context2.v;
              // Update routes and refresh UI
              this.routes = data.routes;
              this.updateRoutesList();
              _context2.n = 5;
              break;
            case 4:
              _context2.p = 4;
              _t2 = _context2.v;
              console.error("Failed to load routes:", _t2);
            case 5:
              return _context2.a(2);
          }
        }, _callee2, this, [[0, 4]]);
      }));
      function loadRoutes() {
        return _loadRoutes.apply(this, arguments);
      }
      return loadRoutes;
    }()
  }, {
    key: "init",
    value: function init() {
      var _this4 = this;
      // Ensure element exists before querying
      if (!this.el) return;

      // Get DOM elements
      this.titleEl = this.el.querySelector("[data-vf-js-selector-toggle]");
      this.dropdownEl = this.el.querySelector("[data-vf-js-selector-dropdown]");
      this.searchEl = this.el.querySelector("[data-vf-js-selector-search]");
      this.clearEl = this.el.querySelector("[data-vf-js-selector-clear]");
      this.listItems = this.el.querySelectorAll("[data-vf-js-selector-item]");
      this.allServicesItem = this.el.querySelector("[data-route-id='all']");

      // Initialize dropdown as closed
      if (this.dropdownEl) {
        this.dropdownEl.style.display = "none";
      }

      // Bind events
      this.bindEvents();

      // Check if any items are pre-selected (not including "All services")
      var hasPreSelectedItems = false;
      this.listItems.forEach(function (item) {
        var itemId = item.getAttribute("data-route-id");
        if (item.classList.contains("vf-chatbot-selector__item--selected")) {
          if (itemId !== "all") {
            // Only count non-"All services" items as pre-selected
            _this4.selectedItems.add(itemId);
            hasPreSelectedItems = true;
          }
        }
      });

      // If no items are pre-selected, default to "All services"
      if (!hasPreSelectedItems && this.showAllServices) {
        this.selectAllServices();
      } else if (!hasPreSelectedItems && !this.showAllServices) {
        // If no "All services" option and no pre-selection, select first item for single-select
        if (!this.isMultiselect && this.listItems.length > 0) {
          var firstItem = this.listItems[0];
          var firstItemId = firstItem.getAttribute("data-route-id");
          this.selectedItems.add(firstItemId);
          firstItem.classList.add("vf-chatbot-selector__item--selected");
        }
      } else if (hasPreSelectedItems) {
        // If items are pre-selected, ensure "All services" is not selected
        this.allServicesSelected = false;
        if (this.allServicesItem) {
          this.allServicesItem.classList.remove("vf-chatbot-selector__item--selected");
        }
      }

      // Update display after initial selection
      this.updateSelectionDisplay();
      this.updateClearButton();
    }
  }, {
    key: "updateRoutesList",
    value: function updateRoutesList() {
      var listEl = this.el.querySelector("[data-vf-js-chatbot-selector-list]");
      if (!listEl || !this.routes) return;

      // Clear existing list
      listEl.innerHTML = "";

      // Check if any routes have pre-selected state
      var hasPreSelectedRoutes = this.routes.some(function (route) {
        return route.selected;
      });

      // Add "All services" option if enabled
      if (this.showAllServices) {
        var allServicesItem = document.createElement("li");
        allServicesItem.className = "vf-chatbot-selector__item";
        allServicesItem.setAttribute("data-vf-js-selector-item", "");
        allServicesItem.setAttribute("data-route-id", "all");
        allServicesItem.setAttribute("data-title", "All services");
        allServicesItem.setAttribute("role", "button");
        allServicesItem.setAttribute("tabindex", "0");
        allServicesItem.setAttribute("aria-label", "Select all services");
        if (!hasPreSelectedRoutes && this.showAllServicesSelected) {
          allServicesItem.className += " vf-chatbot-selector__item--selected";
          allServicesItem.setAttribute("data-selected", "true");
        }
        allServicesItem.innerHTML = "<div class=\"vf-chatbot-selector__item-content\">\n          <div class=\"vf-chatbot-selector__item-title\">All services</div>\n          <div class=\"vf-chatbot-selector__item-description\">This would select all services</div>\n        </div>\n        <span class=\"vf-chatbot-selector__tick\">\n          <svg width=\"24\" height=\"20\" viewBox=\"0 0 24 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n          <path d=\"M6.8478 19.4278C6.33162 19.4208 5.82376 19.2967 5.36257 19.0647C4.90137 18.8328 4.49889 18.4991 4.18551 18.0889L0.426086 13.8152C0.149005 13.4712 0.0154068 13.0335 0.0531476 12.5934C0.0908883 12.1533 0.297055 11.7447 0.62866 11.4529C0.960265 11.1611 1.39171 11.0085 1.83304 11.0271C2.27438 11.0456 2.69152 11.2338 2.99751 11.5523L6.52037 15.562C6.55956 15.6066 6.60755 15.6425 6.66133 15.6675C6.71511 15.6925 6.77349 15.7061 6.83279 15.7074C6.89209 15.7087 6.95101 15.6976 7.00582 15.675C7.06063 15.6523 7.11015 15.6185 7.15123 15.5758L21.0369 1.10375C21.1921 0.940538 21.3778 0.809473 21.5836 0.71804C21.7893 0.626608 22.0111 0.576599 22.2362 0.570868C22.4613 0.565137 22.6853 0.603797 22.8954 0.684641C23.1056 0.765484 23.2977 0.886928 23.4609 1.04204C23.6242 1.19715 23.7552 1.38289 23.8467 1.58865C23.9381 1.79441 23.9881 2.01617 23.9938 2.24126C23.9996 2.46635 23.9609 2.69036 23.8801 2.90051C23.7992 3.11066 23.6778 3.30282 23.5227 3.46604L9.46209 18.2655C9.1402 18.6414 8.7385 18.9409 8.28624 19.1419C7.83398 19.343 7.34257 19.4406 6.8478 19.4278Z\" fill=\"#54585A\"/>\n          </svg>\n        </span>";
        listEl.appendChild(allServicesItem);
      }

      // Add route items
      this.routes.forEach(function (route) {
        var item = document.createElement("li");
        item.className = "vf-chatbot-selector__item";
        if (route.selected) {
          item.className += " vf-chatbot-selector__item--selected";
        }
        item.setAttribute("data-vf-js-selector-item", "");
        item.setAttribute("data-route-id", route.id);
        item.setAttribute("data-title", route.title);
        item.setAttribute("role", "button");
        item.setAttribute("tabindex", "0");
        item.setAttribute("aria-label", "Select ".concat(route.title));
        item.innerHTML = "\n        <div class=\"vf-chatbot-selector__item-content\">\n          <div class=\"vf-chatbot-selector__item-title\">".concat(route.title, "</div>\n  ").concat(route.description ? "<div class=\"vf-chatbot-selector__item-description\">".concat(route.description, "</div>") : "", "\n        </div>\n        <span class=\"vf-chatbot-selector__tick\">\n          <svg width=\"24\" height=\"20\" viewBox=\"0 0 24 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n          <path d=\"M6.8478 19.4278C6.33162 19.4208 5.82376 19.2967 5.36257 19.0647C4.90137 18.8328 4.49889 18.4991 4.18551 18.0889L0.426086 13.8152C0.149005 13.4712 0.0154068 13.0335 0.0531476 12.5934C0.0908883 12.1533 0.297055 11.7447 0.62866 11.4529C0.960265 11.1611 1.39171 11.0085 1.83304 11.0271C2.27438 11.0456 2.69152 11.2338 2.99751 11.5523L6.52037 15.562C6.55956 15.6066 6.60755 15.6425 6.66133 15.6675C6.71511 15.6925 6.77349 15.7061 6.83279 15.7074C6.89209 15.7087 6.95101 15.6976 7.00582 15.675C7.06063 15.6523 7.11015 15.6185 7.15123 15.5758L21.0369 1.10375C21.1921 0.940538 21.3778 0.809473 21.5836 0.71804C21.7893 0.626608 22.0111 0.576599 22.2362 0.570868C22.4613 0.565137 22.6853 0.603797 22.8954 0.684641C23.1056 0.765484 23.2977 0.886928 23.4609 1.04204C23.6242 1.19715 23.7552 1.38289 23.8467 1.58865C23.9381 1.79441 23.9881 2.01617 23.9938 2.24126C23.9996 2.46635 23.9609 2.69036 23.8801 2.90051C23.7992 3.11066 23.6778 3.30282 23.5227 3.46604L9.46209 18.2655C9.1402 18.6414 8.7385 18.9409 8.28624 19.1419C7.83398 19.343 7.34257 19.4406 6.8478 19.4278Z\" fill=\"#54585A\"/>\n          </svg>\n        </span>");
        listEl.appendChild(item);
      });

      // Update references and re-bind events ONCE
      this.listItems = this.el.querySelectorAll("[data-vf-js-selector-item]");
      this.allServicesItem = this.el.querySelector("[data-route-id='all']");

      // Only bind list item events (don't call full bindEvents or init)
      this.bindListItemEvents();

      // Handle initial selections for loaded routes
      this.handleInitialSelections();

      // Dispatch event to signal routes are loaded and UI is ready
      this.el.dispatchEvent(new CustomEvent("routesloaded"));
    }

    // New method to bind only list item events
  }, {
    key: "bindListItemEvents",
    value: function bindListItemEvents() {
      var _this5 = this;
      this.listItems.forEach(function (item) {
        // Remove any existing listeners first
        item.removeEventListener("click", _this5.itemClickHandler);
        item.removeEventListener("keydown", _this5.itemKeydownHandler);

        // Create bound handlers
        _this5.itemClickHandler = function (e) {
          e.stopPropagation();
          _this5.handleItemSelection(item);
        };
        _this5.itemKeydownHandler = function (e) {
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            e.stopPropagation();
            _this5.handleItemSelection(item);
          }
        };

        // Add new listeners
        item.addEventListener("click", _this5.itemClickHandler);
        item.addEventListener("keydown", _this5.itemKeydownHandler);
      });
    }

    // New method to handle initial selections without re-binding events
  }, {
    key: "handleInitialSelections",
    value: function handleInitialSelections() {
      var _this6 = this;
      // Don't reset the selectedItems Set here - it may already have pre-selected items
      var hasPreSelectedItems = this.selectedItems.size > 0; // Check existing selectedItems first

      // Also check DOM for any additional pre-selected items
      this.listItems.forEach(function (item) {
        var itemId = item.getAttribute("data-route-id");
        if (item.classList.contains("vf-chatbot-selector__item--selected")) {
          if (itemId !== "all") {
            _this6.selectedItems.add(itemId);
            hasPreSelectedItems = true;
          }
        }
      });

      // Set default selections only if no items are pre-selected
      if (!hasPreSelectedItems && this.showAllServices && this.showAllServicesSelected) {
        this.selectAllServices();
      } else if (hasPreSelectedItems) {
        // If items are pre-selected, ensure "All services" is not selected
        this.allServicesSelected = false;
        if (this.allServicesItem) {
          this.allServicesItem.classList.remove("vf-chatbot-selector__item--selected");
        }
      }

      // Update display
      this.updateSelectionDisplay();
      this.updateClearButton();
    }

    // Update bindEvents to store handlers for cleanup
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this7 = this;
      // Toggle dropdown
      if (this.titleEl) {
        this.titleEl.removeEventListener("click", this.toggleDropdownHandler);
        this.toggleDropdownHandler = function (e) {
          e.preventDefault();
          e.stopImmediatePropagation();
          _this7.toggleDropdown();
        };
        this.titleEl.addEventListener("click", this.toggleDropdownHandler);
      }

      // Search functionality
      if (this.searchEl) {
        this.searchEl.removeEventListener("input", this.searchHandler);
        this.searchHandler = function (e) {
          e.stopPropagation();
          _this7.handleSearch(e.target.value);
        };
        this.searchEl.addEventListener("input", this.searchHandler);
      }

      // Clear all selections
      if (this.clearEl) {
        this.clearEl.removeEventListener("click", this.clearHandler);
        this.clearHandler = function (e) {
          e.preventDefault();
          e.stopPropagation();
          _this7.clearAllSelections();
        };
        this.clearEl.addEventListener("click", this.clearHandler);
      }

      // Bind list items
      this.bindListItemEvents();

      // Document click handler (only add once)
      if (!this.documentClickHandler) {
        this.documentClickHandler = function (e) {
          if (!_this7.el.contains(e.target)) {
            _this7.closeDropdown();
          }
        };
        document.addEventListener("click", this.documentClickHandler);
      }

      // Dropdown click handler
      if (this.dropdownEl) {
        this.dropdownEl.removeEventListener("click", this.dropdownClickHandler);
        this.dropdownClickHandler = function (e) {
          return e.stopPropagation();
        };
        this.dropdownEl.addEventListener("click", this.dropdownClickHandler);
      }
    }
  }, {
    key: "toggleDropdown",
    value: function toggleDropdown() {
      var isExpanded = this.dropdownEl.style.display === "block";
      if (isExpanded) {
        this.closeDropdown();
      } else {
        this.openDropdown();
      }
    }
  }, {
    key: "openDropdown",
    value: function openDropdown() {
      this.dropdownEl.style.display = "block";
      this.titleEl.classList.add("vf-chatbot-selector__title--expanded");
    }
  }, {
    key: "closeDropdown",
    value: function closeDropdown() {
      if (this.dropdownEl) {
        this.dropdownEl.style.display = "none";
      }
      if (this.titleEl) {
        this.titleEl.classList.remove("vf-chatbot-selector__title--expanded");
      }
    }
  }, {
    key: "handleSearch",
    value: function handleSearch(query) {
      var searchQuery = query.toLowerCase();
      this.listItems.forEach(function (item) {
        var title = item.querySelector(".vf-chatbot-selector__item-title").textContent.toLowerCase();
        var descriptionEl = item.querySelector(".vf-chatbot-selector__item-description");
        var description = descriptionEl ? descriptionEl.textContent.toLowerCase() : "";
        var matches = title.includes(searchQuery) || description.includes(searchQuery);
        item.style.display = matches ? "flex" : "none";
      });
    }
  }, {
    key: "handleItemSelection",
    value: function handleItemSelection(item) {
      var itemId = item.getAttribute("data-route-id");
      var isAllServices = itemId === "all";
      if (this.isMultiselect) {
        if (isAllServices) {
          // If "All services" is clicked, deselect everything else
          this.selectAllServices();
        } else {
          // If other service is clicked
          if (this.selectedItems.has(itemId)) {
            // Deselect the item
            this.selectedItems.delete(itemId);
            item.classList.remove("vf-chatbot-selector__item--selected");

            // If nothing else is selected, select "All services"
            if (this.selectedItems.size === 0) {
              this.selectAllServices();
            }
          } else {
            // Select the item and deselect "All services"
            if (this.allServicesSelected) {
              var _this$allServicesItem;
              (_this$allServicesItem = this.allServicesItem) === null || _this$allServicesItem === void 0 || _this$allServicesItem.classList.remove("vf-chatbot-selector__item--selected");
              this.allServicesSelected = false;
            }
            if (this.selectedItems.size < this.maxMultiSelect) {
              this.selectedItems.add(itemId);
              item.classList.add("vf-chatbot-selector__item--selected");
            }
          }
        }
      } else {
        // Single select mode
        this.listItems.forEach(function (listItem) {
          listItem.classList.remove("vf-chatbot-selector__item--selected");
        });
        this.selectedItems.clear();
        this.selectedItems.add(itemId);
        item.classList.add("vf-chatbot-selector__item--selected");

        // Update title text immediately
        var title = item.querySelector(".vf-chatbot-selector__item-title").textContent;
        var titleText = this.el.querySelector(".vf-chatbot-selector__title-text");
        if (titleText) {
          titleText.textContent = title;
        }
        this.closeDropdown();
      }
      this.updateSelectionDisplay();
      this.updateClearButton();
      this.dispatchSelectionEvent();
    }
  }, {
    key: "handleItemClick",
    value: function handleItemClick(item) {
      var itemId = item.getAttribute("data-route-id");
      if (itemId === "all") {
        // Handle "All services" selection
        this.selectAllServices();
      } else {
        // Handle individual item selection
        if (this.isMultiselect) {
          // Multi-select logic
          if (this.selectedItems.has(itemId)) {
            // Deselect item
            this.selectedItems.delete(itemId);
            item.classList.remove("vf-chatbot-selector__item--selected");
          } else {
            // Select item and ensure we don't exceed max selection
            if (this.selectedItems.size < this.maxMultiSelect) {
              this.selectedItems.add(itemId);
              item.classList.add("vf-chatbot-selector__item--selected");
            }
          }

          // If we deselected all individual items, select "All services"
          if (this.selectedItems.size === 0 && this.showAllServices) {
            this.selectAllServices();
          } else {
            // Deselect "All services" when individual items are selected
            this.allServicesSelected = false;
            if (this.allServicesItem) {
              this.allServicesItem.classList.remove("vf-chatbot-selector__item--selected");
            }
          }
        } else {
          // Single-select logic
          // Clear all previous selections
          this.selectedItems.clear();
          this.listItems.forEach(function (li) {
            li.classList.remove("vf-chatbot-selector__item--selected");
          });

          // Select the clicked item
          this.selectedItems.add(itemId);
          item.classList.add("vf-chatbot-selector__item--selected");

          // Deselect "All services"
          this.allServicesSelected = false;

          // Close dropdown for single-select
          this.closeDropdown();
        }
      }

      // Update display and clear button
      this.updateSelectionDisplay();
      this.updateClearButton();
    }
  }, {
    key: "setSelection",
    value: function setSelection(selectedItems) {
      var _this8 = this;
      // Deselect all first
      this.selectedItems.clear();
      this.listItems.forEach(function (item) {
        item.classList.remove("vf-chatbot-selector__item--selected");
      });

      // If "all" is selected
      if (selectedItems.includes("all")) {
        this.selectAllServices();
      } else {
        selectedItems.forEach(function (id) {
          var item = _this8.el.querySelector("[data-route-id=\"".concat(id, "\"]"));
          if (item) {
            item.classList.add("vf-chatbot-selector__item--selected");
            _this8.selectedItems.add(id);
          }
        });
        this.allServicesSelected = false;
        if (this.allServicesItem) {
          this.allServicesItem.classList.remove("vf-chatbot-selector__item--selected");
        }
      }
      this.updateSelectionDisplay();
      this.updateClearButton();
      this.dispatchSelectionEvent();
    }
  }, {
    key: "selectAllServices",
    value: function selectAllServices() {
      if (!this.showAllServices) return;

      // Clear individual selections
      this.selectedItems.clear();

      // Remove selected class from all individual items
      this.listItems.forEach(function (item) {
        var itemId = item.getAttribute("data-route-id");
        if (itemId !== "all") {
          item.classList.remove("vf-chatbot-selector__item--selected");
        }
      });

      // Select "All services"
      this.allServicesSelected = true;
      if (this.allServicesItem) {
        this.allServicesItem.classList.add("vf-chatbot-selector__item--selected");
      }
    }
  }, {
    key: "clearAllSelections",
    value: function clearAllSelections() {
      // Clear everything and select "All services"
      this.selectAllServices();
      this.updateSelectionDisplay();
      this.updateClearButton();
      this.dispatchSelectionEvent();
    }
  }, {
    key: "updateSelectionDisplay",
    value: function updateSelectionDisplay() {
      var titleText = this.el.querySelector(".vf-chatbot-selector__title-text");
      if (!titleText) return;
      if (this.allServicesSelected) {
        titleText.textContent = "All services";
      } else if (this.selectedItems.size === 0) {
        titleText.textContent = "Select services";
      } else if (!this.isMultiselect) {
        var selectedId = Array.from(this.selectedItems)[0];
        var selectedItem = this.el.querySelector("[data-route-id=\"".concat(selectedId, "\"]"));
        if (selectedItem) {
          var title = selectedItem.querySelector(".vf-chatbot-selector__item-title").textContent;
          titleText.textContent = title;
        }
      } else {
        titleText.textContent = "".concat(this.selectedItems.size, " service").concat(this.selectedItems.size > 1 ? "s" : "", " selected");
      }
    }
  }, {
    key: "dispatchSelectionEvent",
    value: function dispatchSelectionEvent() {
      this.el.dispatchEvent(new CustomEvent("routeselection", {
        detail: {
          selectedItems: this.allServicesSelected ? ["all"] : Array.from(this.selectedItems),
          isMultiselect: this.isMultiselect,
          isAllServices: this.allServicesSelected
        }
      }));
    }
  }, {
    key: "updateClearButton",
    value: function updateClearButton() {
      if (this.clearEl) {
        if (this.selectedItems.size > 0) {
          this.clearEl.classList.add("vf-chatbot-selector__clear--active");
        } else {
          this.clearEl.classList.remove("vf-chatbot-selector__clear--active");
        }
      }
    }
  }]);
}(); // Function to initialize the component
function initVFChatbotSelector(element) {
  return new VFChatbotSelector(element);
}

// vf-chatbot-welcome.js
var VFChatbotWelcome = /*#__PURE__*/function () {
  function VFChatbotWelcome(element) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    _classCallCheck(this, VFChatbotWelcome);
    this.el = element;
    this.suggestionsGrid = this.el.querySelector("[data-vf-js-chatbot-welcome-suggestions-grid]");
    this.qaData = null;
    this.predefinedQA = null;
    this.fallbackResponses = null;
    this.boundHandleSuggestionClick = this.handleSuggestionClick.bind(this);

    // Configuration from data attributes with fallbacks
    this.config = {
      welcome_max_suggestions: options.welcome_max_suggestions || parseInt(this.el.dataset.maxQuestions, 10) || 4,
      enable_qa_data_loading: options.enable_qa_data_loading !== undefined ? options.enable_qa_data_loading : this.el.dataset.enableQaDataLoading !== "false",
      enable_predefined_qa: options.enable_predefined_qa !== undefined ? options.enable_predefined_qa : this.el.dataset.enablePredefinedQa !== "false",
      enable_fallback_responses: options.enable_fallback_responses !== undefined ? options.enable_fallback_responses : this.el.dataset.enableFallbackResponses !== "false",
      qa_data_url: this.el.dataset.qaDataUrl
    };
    this.init();
  }
  return _createClass(VFChatbotWelcome, [{
    key: "init",
    value: function () {
      var _init = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3() {
        var _t3;
        return _regenerator().w(function (_context3) {
          while (1) switch (_context3.p = _context3.n) {
            case 0:
              _context3.p = 0;
              _context3.n = 1;
              return this.loadQAData();
            case 1:
              this.populateSuggestions();
              this.bindEvents();
              return _context3.a(2, Promise.resolve());
            case 2:
              _context3.p = 2;
              _t3 = _context3.v;
              console.error("Failed to initialize welcome component:", _t3);
              return _context3.a(2, Promise.reject(_t3));
          }
        }, _callee3, this, [[0, 2]]);
      }));
      function init() {
        return _init.apply(this, arguments);
      }
      return init;
    }()
  }, {
    key: "loadQAData",
    value: function () {
      var _loadQAData = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4() {
        var response, data, _t4;
        return _regenerator().w(function (_context4) {
          while (1) switch (_context4.p = _context4.n) {
            case 0:
              if (this.config.enable_qa_data_loading) {
                _context4.n = 1;
                break;
              }
              console.log("Q&A data loading disabled for welcome component");
              return _context4.a(2);
            case 1:
              if (this.config.qa_data_url) {
                _context4.n = 2;
                break;
              }
              console.warn("No Q&A data URL provided for welcome component");
              return _context4.a(2);
            case 2:
              _context4.p = 2;
              console.log("Loading Q&A data from: ".concat(this.config.qa_data_url));
              _context4.n = 3;
              return fetch(this.config.qa_data_url);
            case 3:
              response = _context4.v;
              if (response.ok) {
                _context4.n = 4;
                break;
              }
              throw new Error("Failed to fetch Q&A data: ".concat(response.status, " ").concat(response.statusText));
            case 4:
              _context4.n = 5;
              return response.json();
            case 5:
              data = _context4.v;
              this.qaData = data;

              // Store predefined Q&A if enabled
              if (this.config.enable_predefined_qa && data.predefinedQA) {
                this.predefinedQA = data.predefinedQA;
                console.log("Predefined Q&A loaded successfully");
              }

              // Store fallback responses if enabled
              if (this.config.enable_fallback_responses && data.fallbackResponses && data.fallbackResponses.length > 0) {
                this.fallbackResponses = data.fallbackResponses;
                console.log("Fallback responses loaded successfully");
              }
              _context4.n = 7;
              break;
            case 6:
              _context4.p = 6;
              _t4 = _context4.v;
              console.error("Failed to load Q&A data:", _t4);
              // Provide default fallback responses if loading fails
              this.setDefaultFallbackResponses();
            case 7:
              return _context4.a(2);
          }
        }, _callee4, this, [[2, 6]]);
      }));
      function loadQAData() {
        return _loadQAData.apply(this, arguments);
      }
      return loadQAData;
    }()
  }, {
    key: "setDefaultFallbackResponses",
    value: function setDefaultFallbackResponses() {
      if (this.config.enable_fallback_responses) {
        this.fallbackResponses = [{
          answer: "I'm here to help with your questions. Please try asking about our services or general information.",
          sources: [],
          prompts: ["What services do you offer?", "How can I get started?", "Tell me more about your organization"]
        }, {
          answer: "I'm an AI assistant designed to help with information and basic inquiries. How can I assist you today?",
          sources: [],
          prompts: ["What can you help me with?", "How do I contact support?", "Where can I find more information?"]
        }];
        console.log("Using default fallback responses for welcome component");
      }
    }
  }, {
    key: "populateSuggestions",
    value: function populateSuggestions() {
      var _this9 = this;
      if (!this.suggestionsGrid) return;

      // Clear existing suggestions
      this.suggestionsGrid.innerHTML = "";
      var questionsToShow = [];

      // Try to get questions from predefined Q&A first
      if (this.config.enable_predefined_qa && this.predefinedQA) {
        questionsToShow = Object.keys(this.predefinedQA);
      }
      // If no predefined Q&A, try to get prompts from fallback responses
      else if (this.config.enable_fallback_responses && this.fallbackResponses) {
        questionsToShow = this.fallbackResponses.filter(function (response) {
          return response.prompts && response.prompts.length > 0;
        }).flatMap(function (response) {
          return response.prompts;
        });
      }

      // If we still don't have questions, show a default message
      if (questionsToShow.length === 0) {
        console.log("No questions available for welcome suggestions");
        return;
      }

      // Get random questions
      var randomQuestions = questionsToShow.sort(function () {
        return 0.5 - Math.random();
      }).slice(0, this.config.welcome_max_suggestions);

      // Create suggestion elements using template-based rendering
      randomQuestions.forEach(function (question) {
        var suggestionTemplate = document.querySelector("#welcome-suggestion-template");
        if (suggestionTemplate) {
          var suggestionEl = suggestionTemplate.content.cloneNode(true);
          var link = suggestionEl.querySelector(".vf-chatbot-action-prompt__link");
          var wrapper = suggestionEl.querySelector(".vf-chatbot-action-prompt");
          if (link && wrapper) {
            link.textContent = question;
            wrapper.setAttribute("data-vf-js-chatbot-welcome-suggestion", question);
            _this9.suggestionsGrid.appendChild(suggestionEl);
          }
        } else {
          console.warn("Welcome suggestion template not found");
        }
      });
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this$suggestionsGrid, _this$suggestionsGrid2;
      // Remove existing event listener if any
      (_this$suggestionsGrid = this.suggestionsGrid) === null || _this$suggestionsGrid === void 0 || _this$suggestionsGrid.removeEventListener("click", this.boundHandleSuggestionClick);

      // Add single event listener using event delegation
      (_this$suggestionsGrid2 = this.suggestionsGrid) === null || _this$suggestionsGrid2 === void 0 || _this$suggestionsGrid2.addEventListener("click", this.boundHandleSuggestionClick);
    }
  }, {
    key: "handleSuggestionClick",
    value: function handleSuggestionClick(e) {
      e.preventDefault();
      var suggestionEl = e.target.closest("[data-vf-js-chatbot-welcome-suggestion]");
      if (!suggestionEl) return;
      var question = suggestionEl.getAttribute("data-vf-js-chatbot-welcome-suggestion");
      if (!question) return;

      // Get answer data from predefined Q&A or fallback responses
      var answerData = null;

      // First try predefined Q&A
      if (this.config.enable_predefined_qa && this.predefinedQA && this.predefinedQA[question]) {
        answerData = this.predefinedQA[question];
      }
      // If not found in predefined Q&A, try to find in fallback responses
      else if (this.config.enable_fallback_responses && this.fallbackResponses) {
        var fallbackResponse = this.fallbackResponses.find(function (response) {
          return response.prompts && response.prompts.includes(question);
        });
        if (fallbackResponse) {
          answerData = fallbackResponse;
        }
      }

      // If still no answer data, provide a default response
      if (!answerData) {
        answerData = {
          answer: "I'm here to help with your questions. Please try asking about our services or general information.",
          sources: [],
          prompts: ["What services do you offer?", "How can I get started?", "Tell me more about your organization"]
        };
      }

      // Dispatch event only once
      this.el.dispatchEvent(new CustomEvent("vf-chatbot-welcome:suggestion-click", {
        bubbles: true,
        detail: {
          question: question,
          answer: answerData.answer || "",
          sources: answerData.sources || [],
          prompts: answerData.prompts || []
        }
      }));
    }
  }]);
}(); // Initialize
function initVFChatbotWelcome(element) {
  return new VFChatbotWelcome(element);
}
var VFChatbotDialog = /*#__PURE__*/function () {
  function VFChatbotDialog(element) {
    _classCallCheck(this, VFChatbotDialog);
    this.el = element;
    this.cancelBtn = this.el.querySelector("[data-vf-js-dialog-cancel]");
    this.confirmBtn = this.el.querySelector("[data-vf-js-dialog-confirm]");
    this.closeBtn = this.el.querySelector("[data-vf-js-dialog-close]");
    this.bindEvents();
  }
  return _createClass(VFChatbotDialog, [{
    key: "bindEvents",
    value: function bindEvents() {
      var _this$cancelBtn,
        _this0 = this,
        _this$confirmBtn,
        _this$closeBtn;
      (_this$cancelBtn = this.cancelBtn) === null || _this$cancelBtn === void 0 || _this$cancelBtn.addEventListener("click", function () {
        _this0.hide();
        _this0.el.dispatchEvent(new CustomEvent("vf-chatbot-dialog:cancel"));
      });
      (_this$confirmBtn = this.confirmBtn) === null || _this$confirmBtn === void 0 || _this$confirmBtn.addEventListener("click", function () {
        _this0.hide();
        _this0.el.dispatchEvent(new CustomEvent("vf-chatbot-dialog:confirm"));
      });
      (_this$closeBtn = this.closeBtn) === null || _this$closeBtn === void 0 || _this$closeBtn.addEventListener("click", function () {
        _this0.hide();
        _this0.el.dispatchEvent(new CustomEvent("vf-chatbot-dialog:cancel"));
      });
    }
  }, {
    key: "show",
    value: function show() {
      this.el.style.display = "flex";
    }
  }, {
    key: "hide",
    value: function hide() {
      this.el.style.display = "none";
    }
  }]);
}();
function initVFChatbotDialog(element) {
  return new VFChatbotDialog(element);
}

// vf-chatbot-modal.js
var VFChatbotModal = /*#__PURE__*/function () {
  function VFChatbotModal(element) {
    var customConfig = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    _classCallCheck(this, VFChatbotModal);
    this.container = element;
    this.loadConfiguration(customConfig);
    this.setupDOMElements();
    this.setupState();
    this.setupEventHandlers();
    this.init();
  }
  return _createClass(VFChatbotModal, [{
    key: "loadConfiguration",
    value: function loadConfiguration(customConfig) {
      // Get config from data-vf-chatbot-config attribute
      var dataConfig = {};
      var attr = this.container.getAttribute("data-vf-chatbot-config");
      if (attr) {
        try {
          dataConfig = JSON.parse(attr);
        } catch (e) {
          console.warn("Invalid JSON in data-vf-chatbot-config:", e);
        }
      }

      // Default configuration
      var defaultConfig = {
        type: "modal",
        title: "AI Assistant",
        welcome_logo: true,
        welcome_message: "Welcome! I'm here to help",
        welcome_logo_alt: "AI Assistant",
        welcome_suggestions_title: "Try asking me:",
        input_placeholder: "Ask me ...",
        welcome_max_suggestions: 4,
        disclaimer: 'Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. See our <a class="vf-banner__link" target="_blank" rel="noopener noreferrer" aria-label="disclaimer notes (opens in new tab)" href="https://www.ebi.ac.uk/data-protection/privacy-notice/embl-ebi-public-website/">disclaimer notes</a>.',
        footnote: 'Review AI generated content for accuracy. <a class="vf-link" target="_blank" rel="noopener noreferrer" aria-label="Leave feedback (opens in new tab)" href="https://embl.service-now.com/esc?id=sc_cat_item&sys_id=5eeb8eb91b92e650b376da88b04bcbc1">Leave feedback</a>.',
        icons: {
          assistant_avatar: "../../assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg",
          user_avatar: "../../assets/vf-chatbot/assets/vf-chatbot--avatar-user.svg",
          send_button: "../../assets/vf-chatbot/assets/vf-chatbot--icon-send.svg",
          main_logo_url: "../../assets/vf-chatbot/assets/vf-chatbot--icon-32x32-dark-green.svg",
          minimize: "../../assets/vf-chatbot/assets/vf-chatbot--icon-minimize.svg",
          close: "../../assets/vf-chatbot/assets/vf-chatbot--icon-close.svg"
        },
        api: {
          chat_endpoint: false,
          //"/api/chat", // Disabled to use fallback responses
          feedback_endpoint: false,
          //"/api/feedback",
          qa_data_url: "../../assets/vf-chatbot/assets/vf-chatbot-qa.json",
          headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer your-token"
          },
          timeout: 10000
        },
        features: {
          enable_welcome: true,
          enable_feedback: true,
          enable_sources: true,
          enable_sources_custom_format: false,
          enable_welcome_suggestions: true,
          enable_typing_indicator: true,
          enable_disclaimer: true,
          enable_predefined_qa: true,
          enable_fallback_responses: true,
          enable_qa_data_loading: true,
          enable_instant_feedback: false
        },
        behavior: {
          auto_scroll: true,
          typing_delay: 800,
          show_scrollbar: false
        },
        selectorContext: {
          chatbotRoutes: {
            multiSelect: true,
            maxMultiSelect: 3,
            showSearch: true,
            showSearchThreshold: 5,
            showAllServices: true,
            showAllServicesSelected: true,
            routes: "../../assets/vf-chatbot/assets/vf-chatbot-selector-services.json",
            placeholder: "Select services",
            title: "Services",
            selector_logo_url: "../../assets/vf-chatbot/assets/vf-chatbot--icon-24x24-dark-green.svg",
            selector_logo_title: "AI Assistant"
          }
        },
        handlers: {
          on_message_send: "handleMessageSend",
          on_response_receive: "handleResponseReceive",
          on_feedback_submit: "handleFeedbackSubmit",
          on_suggestion_click: "handleSuggestionClick",
          on_error: "handleError",
          on_conversation_start: "handleConversationStart",
          on_conversation_end: "handleConversationEnd",
          on_fab_click: "handleFabClick",
          on_dialog_confirm: "handleDialogConfirm",
          on_dialog_cancel: "handleDialogCancel",
          on_minimize: "handleMinimize"
        },
        feedback_options: {
          positive: [{
            id: "accurate",
            label: "Accurate"
          }, {
            id: "easy",
            label: "Easy to understand"
          }, {
            id: "formatted",
            label: "Well formatted"
          }],
          negative: [{
            id: "inaccurate",
            label: "Inaccurate answer"
          }, {
            id: "nocontext",
            label: "Did not use context"
          }, {
            id: "poorformat",
            label: "Poorly formatted"
          }]
        },
        enable_session_persistence: true,
        restore_minimized_state: true // If true, restore minimized state after navigation
      };

      // Merge configurations: default < data-attribute < custom
      this.config = this.deepMerge(defaultConfig, dataConfig, customConfig);
      console.log("Final merged config:", this.config);
    }
  }, {
    key: "deepMerge",
    value: function deepMerge() {
      var _this1 = this;
      for (var _len = arguments.length, objects = new Array(_len), _key = 0; _key < _len; _key++) {
        objects[_key] = arguments[_key];
      }
      return objects.reduce(function (prev, obj) {
        // ✅ FIX: Skip null/undefined objects
        if (!obj || _typeof(obj) !== "object") {
          return prev;
        }
        Object.keys(obj).forEach(function (key) {
          var pVal = prev[key];
          var oVal = obj[key];

          // ✅ FIX: Skip undefined values to maintain priority
          if (oVal === undefined) {
            return;
          }
          if (Array.isArray(pVal) && Array.isArray(oVal)) {
            prev[key] = pVal.concat.apply(pVal, _toConsumableArray(oVal));
          } else if (pVal && oVal && _typeof(pVal) === "object" && _typeof(oVal) === "object" && !Array.isArray(pVal) && !Array.isArray(oVal)) {
            prev[key] = _this1.deepMerge(pVal, oVal);
          } else {
            // ✅ FIX: Only assign if oVal is not null/undefined
            if (oVal !== null && oVal !== undefined) {
              prev[key] = oVal;
            }
          }
        });
        return prev;
      }, {});
    }
  }, {
    key: "setupDOMElements",
    value: function setupDOMElements() {
      var _this$disclaimer;
      this.fab = document.querySelector("[data-vf-js-chatbot-fab]");
      this.minimizeBtn = this.container.querySelector("[data-vf-js-chatbot-modal-minimize]");
      this.closeBtn = this.container.querySelector("[data-vf-js-chatbot-modal-close]");
      this.welcomeScreen = this.container.querySelector("[data-vf-js-chatbot-welcome]");
      this.messagesContainer = this.container.querySelector("[data-vf-js-chatbot-modal-messages]");
      this.input = this.container.querySelector("[data-vf-js-chatbot-modal-input]");
      this.sendBtn = this.container.querySelector("[data-vf-js-chatbot-modal-send]");
      this.disclaimer = this.container.querySelector("[data-vf-js-chatbot-modal-disclaimer]");
      this.disclaimerCloseBtn = (_this$disclaimer = this.disclaimer) === null || _this$disclaimer === void 0 ? void 0 : _this$disclaimer.querySelector(".vf-button--dismiss");
      this.selectorEl = this.container.querySelector("[data-vf-js-chatbot-selector]");

      // Templates
      this.userTemplate = this.container.querySelector("#user-message-template");
      this.assistantTemplate = this.container.querySelector("#assistant-message-template");
      this.loadingTemplate = this.container.querySelector("#loading-indicator-template");
      this.actionPromptsTemplate = this.container.querySelector("#action-prompts-template");
      this.singlePromptTemplate = this.container.querySelector("#single-action-prompt-template");
      this.dialog = this.container.querySelector("[data-vf-js-chatbot-dialog]");
      // Apply configuration to DOM elements
      this.applyConfigurationToDOM();
    }
  }, {
    key: "applyConfigurationToDOM",
    value: function applyConfigurationToDOM() {
      // Update input placeholder
      if (this.input) {
        this.input.placeholder = this.config.input_placeholder;
        // Initialize textarea height
        this.autoResizeTextarea();
      }

      // Update auto-scroll behavior
      if (this.messagesContainer) {
        this.messagesContainer.dataset.autoScroll = this.config.behavior.auto_scroll;
      }
    }
  }, {
    key: "setupState",
    value: function setupState() {
      var _this10 = this;
      this.currentAssistant = "";
      this.conversationId = this.generateConversationId();
      this.messageHistory = [];
      this.loadingIndicator = null;
      this.apiResponseListener = null;

      // Load Q&A data if using fallback responses
      this.loadQADataAndPopulateSuggestions();

      // Restore conversation HTML from sessionStorage
      var persistedHTML = this.loadConversationHTML();
      if (persistedHTML && this.messagesContainer) {
        this.messagesContainer.innerHTML = persistedHTML;
        if (this.welcomeScreen) {
          this.welcomeScreen.style.display = "none";
        }
        this.messagesContainer.style.display = "flex";
        // Hide disclaimer if present
        if (this.disclaimer) {
          this.disclaimer.classList.add("vf-u-display-none");
        }
        // Hide all loading indicator divs
        var loadingDivs = this.messagesContainer.querySelectorAll(".vf-chatbot-message--loading");
        loadingDivs.forEach(function (div) {
          div.style.display = "none";
        });
        // Hide all feedback response banners
        var feedbackBanners = this.messagesContainer.querySelectorAll(".vf-chatbot-feedback__form-container");
        feedbackBanners.forEach(function (div) {
          div.style.display = "none";
        });
        // Re-initialize feedback thumbs
        var feedbackContainers = this.messagesContainer.querySelectorAll("[data-vf-js-chatbot-feedback]");
        var feedbackState = JSON.parse(sessionStorage.getItem("chatbotModalFeedbackState") || "{}");
        feedbackContainers.forEach(function (container) {
          var messageId = container.dataset.messageId;
          var feedbackType = feedbackState[messageId];
          if (feedbackType) {
            // Find thumb icons and set solid/active state
            var positiveThumb = container.querySelector("[data-vf-js-feedback-thumb='up']");
            var negativeThumb = container.querySelector("[data-vf-js-feedback-thumb='down']");
            if (positiveThumb && feedbackType === "positive") {
              positiveThumb.classList.add("vf-chatbot-feedback__thumb--solid");
              if (negativeThumb) {
                negativeThumb.classList.remove("vf-chatbot-feedback__thumb--solid");
                negativeThumb.style.display = "none";
              }
            }
            if (negativeThumb && feedbackType === "negative") {
              negativeThumb.classList.add("vf-chatbot-feedback__thumb--solid");
              if (positiveThumb) {
                positiveThumb.classList.remove("vf-chatbot-feedback__thumb--solid");
                positiveThumb.style.display = "none";
              }
            }
          } else {
            var _this10$config$feedba, _this10$config$feedba2;
            new VFChatbotFeedback(container, messageId, {
              enable_instant_feedback: _this10.config.features.enable_instant_feedback,
              api_endpoint: _this10.config.api.feedback_endpoint,
              positiveOptions: (_this10$config$feedba = _this10.config.feedback_options) === null || _this10$config$feedba === void 0 ? void 0 : _this10$config$feedba.positive,
              negativeOptions: (_this10$config$feedba2 = _this10.config.feedback_options) === null || _this10$config$feedba2 === void 0 ? void 0 : _this10$config$feedba2.negative
            });
          }
        });
        // Re-initialize 'View sources' event listeners
        var sourcesContainers = this.messagesContainer.querySelectorAll(".vf-chatbot-sources-toggle");
        if (sourcesContainers) {
          sourcesContainers.forEach(function (sourcesContainer) {
            var toggleBtn = sourcesContainer.querySelector("[data-vf-js-chatbot-sources-toggle]");
            var sourcesDiv = sourcesContainer.querySelector("[data-vf-js-chatbot-sources]");
            var hideBtn = sourcesContainer.querySelector("[data-vf-js-chatbot-sources-hide]");
            toggleBtn.addEventListener("click", function () {
              sourcesDiv.classList.remove("vf-chatbot-sources--collapsed");
              toggleBtn.style.display = "none";
              // Scroll the sources div into view
              // sourcesDiv.scrollIntoView({ behavior: "smooth", block: "center" });
            });
            hideBtn.addEventListener("click", function () {
              sourcesDiv.classList.add("vf-chatbot-sources--collapsed");
              toggleBtn.style.display = "";
            });
            hideBtn.click();
          });
        }
        // Keep chatbot open if there is persistedHTML
        if (this.container) {
          this.fab.classList.add("vf-chatbot-fab--inactive");
          this.container.classList.remove("vf-chatbot-modal-container--inactive");
          this.container.classList.add("vf-chatbot-modal-container--active");
          this.container.setAttribute("aria-modal", true);

          // Focus on input if it exists
          var input = this.container.querySelector("[data-vf-js-chatbot-modal-input]");
          if (input) {
            setTimeout(function () {
              return input.focus();
            }, 300);
          }
        }
        var wasMinimized = sessionStorage.getItem("chatbotModalMinimized") === "true";
        if (this.container) {
          if (this.config.restore_minimized_state && !wasMinimized) {
            this.fab.classList.add("vf-chatbot-fab--inactive");
            this.container.classList.remove("vf-chatbot-modal-container--inactive");
            this.container.classList.add("vf-chatbot-modal-container--active");
            this.container.setAttribute("aria-modal", true);
          } else {
            this.fab.classList.remove("vf-chatbot-fab--inactive");
            this.container.classList.remove("vf-chatbot-modal-container--active");
            this.container.classList.add("vf-chatbot-modal-container--inactive");
            this.container.setAttribute("aria-modal", false);
            // Focus input if needed
          }
        }
        return; // Skip history-based rendering if HTML is present
      }
    }
  }, {
    key: "setupEventHandlers",
    value: function setupEventHandlers() {
      // Setup global event handlers for custom functions
      this.setupCustomEventHandlers();

      // Bind internal events
      this.bindEvents();
    }
  }, {
    key: "setupCustomEventHandlers",
    value: function setupCustomEventHandlers() {
      var _this11 = this;
      // Create wrapper functions that call user-defined handlers and emit events
      var handlers = this.config.handlers;

      // Message send handler
      this.onMessageSend = function (message) {
        var eventData = {
          message: message,
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:message-send", eventData);
        if (handlers.on_message_send && typeof window[handlers.on_message_send] === "function") {
          window[handlers.on_message_send](eventData);
        }
      };

      // Response receive handler
      this.onResponseReceive = function (response, sources, prompts) {
        var eventData = {
          response: response,
          sources: sources,
          prompts: prompts,
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:response-receive", eventData);
        if (handlers.on_response_receive && typeof window[handlers.on_response_receive] === "function") {
          window[handlers.on_response_receive](eventData);
        }
      };

      // Feedback submit handler
      this.onFeedbackSubmit = function (messageId, feedbackType) {
        var feedbackText = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "";
        var feedbackComment = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : "";
        var eventData = {
          messageId: messageId,
          feedbackType: feedbackType,
          feedbackText: feedbackText,
          feedbackComment: feedbackComment,
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:feedback-submit", eventData);

        // Call API if configured
        if (_this11.config.api.feedback_endpoint) {
          _this11.submitFeedbackToAPI(eventData);
        }

        // Update local storage feedback state
        if (_this11.config.enable_session_persistence) {
          var feedbackState = JSON.parse(sessionStorage.getItem("chatbotModalFeedbackState") || "{}");
          feedbackState[messageId] = feedbackType; // e.g., "positive" or "negative"
          sessionStorage.setItem("chatbotModalFeedbackState", JSON.stringify(feedbackState));
        }
        if (handlers.on_feedback_submit && typeof window[handlers.on_feedback_submit] === "function") {
          window[handlers.on_feedback_submit](eventData);
        }
      };

      // Suggestion click handler
      this.onSuggestionClick = function (suggestion) {
        var eventData = {
          suggestion: suggestion,
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:suggestion-click", eventData);
        if (handlers.on_suggestion_click && typeof window[handlers.on_suggestion_click] === "function") {
          window[handlers.on_suggestion_click](eventData);
        }
      };

      // Error handler
      this.onError = function (error, context) {
        var eventData = {
          error: error,
          context: context,
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:error", eventData);
        if (handlers.on_error && typeof window[handlers.on_error] === "function") {
          window[handlers.on_error](eventData);
        }
      };

      // Conversation start handler
      this.onConversationStart = function () {
        var eventData = {
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:conversation-start", eventData);
        if (handlers.on_conversation_start && typeof window[handlers.on_conversation_start] === "function") {
          window[handlers.on_conversation_start](eventData);
        }
      };

      // Conversation end handler
      this.onConversationEnd = function () {
        var eventData = {
          conversationId: _this11.conversationId,
          messageCount: _this11.messageHistory.length,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot:conversation-end", eventData);
        if (handlers.on_conversation_end && typeof window[handlers.on_conversation_end] === "function") {
          window[handlers.on_conversation_end](eventData);
        }
      };

      // FAB click handler
      this.onFabClick = function () {
        var eventData = {
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot-fab:click", eventData);
        if (handlers.on_fab_click && typeof window[handlers.on_fab_click] === "function") {
          window[handlers.on_fab_click](eventData);
        }
      };

      // Dialog confirm handler
      this.onDialogConfirm = function () {
        var eventData = {
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot-dialog:confirm", eventData);
        if (handlers.on_dialog_confirm && typeof window[handlers.on_dialog_confirm] === "function") {
          window[handlers.on_dialog_confirm](eventData);
        }
      };

      // Dialog cancel handler
      this.onDialogCancel = function () {
        var eventData = {
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot-dialog:cancel", eventData);
        if (handlers.on_dialog_cancel && typeof window[handlers.on_dialog_cancel] === "function") {
          window[handlers.on_dialog_cancel](eventData);
        }
      };

      // Minimize handler
      this.onMinimize = function () {
        var eventData = {
          conversationId: _this11.conversationId,
          timestamp: Date.now()
        };
        _this11.emitEvent("vf-chatbot-modal:minimize", eventData);
        if (handlers.on_minimize && typeof window[handlers.on_minimize] === "function") {
          window[handlers.on_minimize](eventData);
        }
      };
    }
  }, {
    key: "emitEvent",
    value: function emitEvent(eventName, data) {
      var event = new CustomEvent(eventName, {
        bubbles: true,
        detail: data
      });
      this.container.dispatchEvent(event);
    }
  }, {
    key: "generateConversationId",
    value: function generateConversationId() {
      return "conv_".concat(Date.now(), "_").concat(Math.random().toString(36).substr(2, 9));
    }
  }, {
    key: "init",
    value: function () {
      var _init2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee5() {
        var _this12 = this;
        var selector, existingListeners;
        return _regenerator().w(function (_context5) {
          while (1) switch (_context5.n) {
            case 0:
              // Initialize selector if present
              if (this.selectorEl) {
                selector = initVFChatbotSelector(this.selectorEl);
                this.selectorEl.addEventListener("routeselection", function (e) {
                  _this12.handleRouteSelection(e.detail);
                  // Save selection on change
                  if (_this12.config.enable_session_persistence) {
                    sessionStorage.setItem("vfChatbotSelectorSelection", JSON.stringify(e.detail.selectedItems));
                  }
                });

                // Restore selection only after routes are loaded/rendered
                this.selectorEl.addEventListener("routesloaded", function () {
                  if (_this12.config.enable_session_persistence) {
                    _this12.savedSelection = sessionStorage.getItem("vfChatbotSelectorSelection");
                    if (_this12.savedSelection) {
                      var selectedItems = JSON.parse(_this12.savedSelection);
                      selector.setSelection(selectedItems);
                    }
                  }
                });
              }

              // Initialize welcome component
              if (this.welcomeScreen && this.config.features.enable_welcome_suggestions) {
                try {
                  initVFChatbotWelcome(this.welcomeScreen);

                  // Only add the event listener if not already present
                  existingListeners = this.welcomeScreen._hasSuggestionClickListener;
                  if (!existingListeners) {
                    this.welcomeScreen.addEventListener("vf-chatbot-welcome:suggestion-click", function (event) {
                      var question = event.detail.question;
                      _this12.onSuggestionClick(question);
                      _this12.showChatInterface();
                      _this12.sendUserMessage(question);
                    });
                    // Mark that the listener has been added
                    this.welcomeScreen._hasSuggestionClickListener = true;
                  }
                  this.welcomeScreen.scrollTop = this.welcomeScreen.scrollHeight;
                } catch (error) {
                  console.error("Failed to initialize welcome component:", error);
                  this.onError(error, "welcome_component_init");
                }
              }

              // Trigger conversation start
              this.onConversationStart();
              console.log("Modal chatbot initialized successfully");
            case 1:
              return _context5.a(2);
          }
        }, _callee5, this);
      }));
      function init() {
        return _init2.apply(this, arguments);
      }
      return init;
    }()
  }, {
    key: "loadQADataAndPopulateSuggestions",
    value: function () {
      var _loadQADataAndPopulateSuggestions = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee6() {
        var response, data, _t5;
        return _regenerator().w(function (_context6) {
          while (1) switch (_context6.p = _context6.n) {
            case 0:
              if (this.config.features.enable_qa_data_loading) {
                _context6.n = 1;
                break;
              }
              console.log("Q&A data loading is disabled");
              return _context6.a(2);
            case 1:
              if (this.config.api.qa_data_url) {
                _context6.n = 2;
                break;
              }
              console.log("No Q&A data URL configured");
              return _context6.a(2);
            case 2:
              _context6.p = 2;
              _context6.n = 3;
              return fetch(this.config.api.qa_data_url);
            case 3:
              response = _context6.v;
              if (response.ok) {
                _context6.n = 4;
                break;
              }
              throw new Error("HTTP ".concat(response.status, ": ").concat(response.statusText));
            case 4:
              _context6.n = 5;
              return response.json();
            case 5:
              data = _context6.v;
              // Load predefined Q&A if enabled
              if (this.config.features.enable_predefined_qa && data.predefinedQA) {
                this.qaData = data.predefinedQA;
              } else {
                console.log("Predefined Q&A loading is disabled or no data available");
              }

              // Load fallback responses if enabled
              if (this.config.features.enable_fallback_responses && data.fallbackResponses && data.fallbackResponses.length > 0) {
                this.fallbackResponses = data.fallbackResponses;
              } else {
                console.log("Using default fallback responses");
              }
              _context6.n = 7;
              break;
            case 6:
              _context6.p = 6;
              _t5 = _context6.v;
              console.error("Failed to load Q&A data:", _t5);
              this.onError(_t5, "qa_data_load");
            case 7:
              return _context6.a(2);
          }
        }, _callee6, this, [[2, 6]]);
      }));
      function loadQADataAndPopulateSuggestions() {
        return _loadQADataAndPopulateSuggestions.apply(this, arguments);
      }
      return loadQADataAndPopulateSuggestions;
    }()
  }, {
    key: "handleRouteSelection",
    value: function handleRouteSelection(detail) {
      var selectedItems = detail.selectedItems;
      if (selectedItems && selectedItems.length > 0) {
        this.currentAssistant = selectedItems.join(", ");
        console.log("Switched to ".concat(this.currentAssistant, " assistant"));
        this.emitEvent("vf-chatbot:assistant-change", {
          selectedAssistants: this.currentAssistant,
          conversationId: this.conversationId
        });
      }
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this$minimizeBtn,
        _this13 = this,
        _this$closeBtn2,
        _this$sendBtn,
        _this$input,
        _this$input2,
        _this$input3,
        _this$fab,
        _this$dialog,
        _this$dialog2;
      (_this$minimizeBtn = this.minimizeBtn) === null || _this$minimizeBtn === void 0 || _this$minimizeBtn.addEventListener("click", function () {
        _this13.onMinimize();
        _this13.minimize();
      });
      (_this$closeBtn2 = this.closeBtn) === null || _this$closeBtn2 === void 0 || _this$closeBtn2.addEventListener("click", function () {
        return _this13.showCloseDialog();
      });

      // Send message events
      (_this$sendBtn = this.sendBtn) === null || _this$sendBtn === void 0 || _this$sendBtn.addEventListener("click", function () {
        return _this13.sendMessage();
      });
      (_this$input = this.input) === null || _this$input === void 0 || _this$input.addEventListener("keypress", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          _this13.sendMessage();
        }
      });

      // Auto-resize textarea functionality
      (_this$input2 = this.input) === null || _this$input2 === void 0 || _this$input2.addEventListener("input", function () {
        return _this13.autoResizeTextarea();
      });
      (_this$input3 = this.input) === null || _this$input3 === void 0 || _this$input3.addEventListener("paste", function () {
        // Use setTimeout to ensure the pasted content is processed
        setTimeout(function () {
          return _this13.autoResizeTextarea();
        }, 0);
      });

      // Disclaimer close
      if (this.disclaimer && this.disclaimerCloseBtn) {
        this.disclaimerCloseBtn.addEventListener("click", function () {
          _this13.disclaimer.classList.add("vf-u-display-none");
        });
      }

      // Global feedback event listener
      this.container.addEventListener("vf-chatbot-feedback:submit", function (event) {
        var _event$detail = event.detail,
          messageId = _event$detail.messageId,
          feedbackType = _event$detail.feedbackType,
          feedbackText = _event$detail.feedbackText,
          feedbackComment = _event$detail.feedbackComment;
        _this13.onFeedbackSubmit(messageId, feedbackType, feedbackText, feedbackComment);
      });

      // Action prompt click listener
      this.container.addEventListener("vf-chatbot-action-prompt:click", function (event) {
        var text = event.detail.text;
        _this13.sendUserMessage(text);
      });
      (_this$fab = this.fab) === null || _this$fab === void 0 || _this$fab.addEventListener("click", this.onFabClick);
      (_this$dialog = this.dialog) === null || _this$dialog === void 0 || _this$dialog.addEventListener("vf-chatbot-dialog:confirm", this.onDialogConfirm);
      (_this$dialog2 = this.dialog) === null || _this$dialog2 === void 0 || _this$dialog2.addEventListener("vf-chatbot-dialog:cancel", this.onDialogCancel);
    }
  }, {
    key: "sendMessage",
    value: function sendMessage() {
      if (!this.input || !this.input.value.trim()) return;
      var text = this.input.value.trim();
      this.showChatInterface();
      this.sendUserMessage(text);
    }
  }, {
    key: "autoResizeTextarea",
    value: function autoResizeTextarea() {
      if (!this.input) return;

      // Reset height to auto to get the actual scroll height
      // this.input.style.height = 'auto';
      // Get computed styles
      var computedStyle = window.getComputedStyle(this.input);
      var lineHeight = parseFloat(computedStyle.lineHeight) || 24;
      var paddingTop = parseFloat(computedStyle.paddingTop) || 0;
      var paddingBottom = parseFloat(computedStyle.paddingBottom) || 0;
      var borderTop = parseFloat(computedStyle.borderTopWidth) || 0;
      var borderBottom = parseFloat(computedStyle.borderBottomWidth) || 0;

      // Calculate heights more accurately
      var extraHeight = paddingTop + paddingBottom + borderTop + borderBottom;
      var minHeight = lineHeight + extraHeight; // Height for 1 row
      var maxHeight = lineHeight * 5 + extraHeight; // Height for 5 rows

      // Get the scroll height (content height)
      var scrollHeight = this.input.scrollHeight;

      // Calculate the new height, constrained by min and max
      var newHeight = Math.max(minHeight, scrollHeight);
      if (newHeight >= maxHeight) {
        // If content exceeds 5 rows, set to max height and enable scrolling
        newHeight = maxHeight;
        this.input.classList.add("vf-chatbot-modal__input--scrollable");
      } else {
        // Remove scrollable class if content fits within 5 rows
        this.input.classList.remove("vf-chatbot-modal__input--scrollable");
      }

      // Apply the new height
      this.input.style.height = newHeight + "px";
    }
  }, {
    key: "showChatInterface",
    value: function showChatInterface() {
      if (this.welcomeScreen) {
        this.welcomeScreen.style.display = "none";
      }
      if (this.messagesContainer) {
        this.messagesContainer.style.display = "flex";
      }
      if (this.input) {
        this.input.focus();
      }
    }
  }, {
    key: "sendUserMessage",
    value: function sendUserMessage(text) {
      if (!text || !this.messagesContainer) return;

      // Add to message history
      this.messageHistory.push({
        type: "user",
        content: text,
        timestamp: Date.now()
      });

      // Trigger message send event
      this.onMessageSend(text);

      // Create user message element
      var userMessage = this.userTemplate.content.cloneNode(true);
      var content = userMessage.querySelector(".vf-chatbot-message__content-prompt");
      content.textContent = text;

      // Update avatar if configured
      var avatar = userMessage.querySelector("img");
      if (avatar) {
        avatar.src = this.config.icons.user_avatar;
      }
      this.messagesContainer.appendChild(userMessage);
      this.saveConversationHTML(this.messagesContainer.innerHTML);

      // Clear input
      if (this.input) {
        this.input.value = "";
        this.input.style.height = "auto";
        // Reset textarea to minimum height and remove scrollable class
        this.input.classList.remove("vf-chatbot-modal__input--scrollable");
        this.autoResizeTextarea();
      }
      this.scrollToBottom();
      this.processUserMessage(text);
    }
  }, {
    key: "processUserMessage",
    value: function () {
      var _processUserMessage = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee7(text) {
        var _this14 = this;
        var response, _t6;
        return _regenerator().w(function (_context7) {
          while (1) switch (_context7.p = _context7.n) {
            case 0:
              this.setLoadingState(true);
              _context7.p = 1;
              if (!(this.qaData && this.qaData[text])) {
                _context7.n = 2;
                break;
              }
              setTimeout(function () {
                var answer = _this14.qaData[text];
                _this14.addAssistantResponse(answer.answer || answer.html, answer.sources || [], answer.prompts || []);
                _this14.setLoadingState(false);
              }, this.config.behavior.typing_delay);
              return _context7.a(2);
            case 2:
              if (!this.config.api.chat_endpoint) {
                _context7.n = 4;
                break;
              }
              _context7.n = 3;
              return this.callChatAPI(text);
            case 3:
              response = _context7.v;
              this.addAssistantResponse(response.response, response.sources || [], response.prompts || []);
              this.setLoadingState(false);
              _context7.n = 5;
              break;
            case 4:
              // Use fallback response
              setTimeout(function () {
                var fallbackResponse;
                if (_this14.fallbackResponses && _this14.fallbackResponses.length > 0) {
                  fallbackResponse = _this14.fallbackResponses[Math.floor(Math.random() * _this14.fallbackResponses.length)];
                  _this14.addAssistantResponse(fallbackResponse.answer, [], fallbackResponse.prompts || []);
                }
                _this14.setLoadingState(false);
              }, this.config.behavior.typing_delay);
            case 5:
              _context7.n = 7;
              break;
            case 6:
              _context7.p = 6;
              _t6 = _context7.v;
              console.error("Error processing message:", _t6);
              this.onError(_t6, "message_processing");
              this.setLoadingState(false);
              // this.showErrorMessage();
            case 7:
              return _context7.a(2);
          }
        }, _callee7, this, [[1, 6]]);
      }));
      function processUserMessage(_x2) {
        return _processUserMessage.apply(this, arguments);
      }
      return processUserMessage;
    }()
  }, {
    key: "callChatAPI",
    value: function () {
      var _callChatAPI = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee8(message) {
        var requestData, response;
        return _regenerator().w(function (_context8) {
          while (1) switch (_context8.n) {
            case 0:
              requestData = {
                message: message,
                assistant: this.currentAssistant,
                conversationId: this.conversationId,
                messageHistory: this.config.features.enable_conversation_history ? this.messageHistory : []
              };
              _context8.n = 1;
              return fetch(this.config.api.chat_endpoint, {
                method: "POST",
                headers: this.config.api.headers,
                body: JSON.stringify(requestData),
                signal: AbortSignal.timeout(this.config.api.timeout)
              });
            case 1:
              response = _context8.v;
              if (response.ok) {
                _context8.n = 2;
                break;
              }
              throw new Error("API call failed: ".concat(response.status, " ").concat(response.statusText));
            case 2:
              _context8.n = 3;
              return response.json();
            case 3:
              return _context8.a(2, _context8.v);
          }
        }, _callee8, this);
      }));
      function callChatAPI(_x3) {
        return _callChatAPI.apply(this, arguments);
      }
      return callChatAPI;
    }()
  }, {
    key: "submitFeedbackToAPI",
    value: function () {
      var _submitFeedbackToAPI = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee9(feedbackData) {
        var _t7;
        return _regenerator().w(function (_context9) {
          while (1) switch (_context9.p = _context9.n) {
            case 0:
              _context9.p = 0;
              _context9.n = 1;
              return fetch(this.config.api.feedback_endpoint, {
                method: "POST",
                headers: this.config.api.headers,
                body: JSON.stringify(feedbackData)
              });
            case 1:
              _context9.n = 3;
              break;
            case 2:
              _context9.p = 2;
              _t7 = _context9.v;
              console.error("Failed to submit feedback:", _t7);
              this.onError(_t7, "feedback_submission");
            case 3:
              return _context9.a(2);
          }
        }, _callee9, this, [[0, 2]]);
      }));
      function submitFeedbackToAPI(_x4) {
        return _submitFeedbackToAPI.apply(this, arguments);
      }
      return submitFeedbackToAPI;
    }()
  }, {
    key: "addAssistantResponse",
    value: function addAssistantResponse(text) {
      var sources = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var prompts = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];
      if (!this.assistantTemplate || !this.messagesContainer) return;
      var messageId = "msg_".concat(Date.now(), "_").concat(Math.random().toString(36).substr(2, 9));

      // Add to message history
      this.messageHistory.push({
        type: "assistant",
        content: text,
        sources: sources,
        prompts: prompts,
        messageId: messageId,
        timestamp: Date.now()
      });

      // Trigger response receive event
      this.onResponseReceive(text, sources, prompts);
      var assistantMessage = this.assistantTemplate.content.cloneNode(true);
      var content = assistantMessage.querySelector(".vf-chatbot-message__content-prompt");
      content.innerHTML = text;

      // Update avatar if configured
      var avatar = assistantMessage.querySelector("img");
      if (avatar) {
        avatar.src = this.config.icons.assistant_avatar;
      }

      // Initialize feedback if enabled
      if (this.config.features.enable_feedback) {
        var feedbackContainer = assistantMessage.querySelector("[data-vf-js-chatbot-feedback]");
        if (feedbackContainer) {
          var _this$config$feedback, _this$config$feedback2;
          feedbackContainer.dataset.messageId = messageId;

          // Pass configuration to VFChatbotFeedback component
          new VFChatbotFeedback(feedbackContainer, messageId, {
            enable_instant_feedback: this.config.features.enable_instant_feedback,
            api_endpoint: this.config.api.feedback_endpoint,
            positiveOptions: (_this$config$feedback = this.config.feedback_options) === null || _this$config$feedback === void 0 ? void 0 : _this$config$feedback.positive,
            negativeOptions: (_this$config$feedback2 = this.config.feedback_options) === null || _this$config$feedback2 === void 0 ? void 0 : _this$config$feedback2.negative
          });
        }
      }

      // Add sources if enabled and present
      if (this.config.features.enable_sources && sources && (sources.length > 0 || sources != "")) {
        var _feedbackContainer = assistantMessage.querySelector("[data-vf-js-chatbot-feedback]");
        var sourceHTML = "";
        if (!this.config.features.enable_sources_custom_format && sources.length > 0) {
          sourceHTML = sources.map(function (message) {
            return "\n          <li class=\"vf-chatbot-sources__item\">\n            <div class=\"vf-chatbot-sources__label\">".concat(message.domain, "</div>\n            <a class=\"vf-link vf-chatbot-sources__link\" href=\"").concat(message.url, "\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"").concat(message.title, " (opens in new tab)\">\n              ").concat(message.title, "\n            </a>\n            <div class=\"vf-chatbot-sources__description\">").concat(message.description, "</div>\n          </li>\n        ");
          }).join("");
        } else if (this.config.features.enable_sources_custom_format && sources != "") {
          sourceHTML = sources;
        }
        if (sourceHTML != "") {
          var sourcesEl = initVFChatbotSources(sourceHTML);
          assistantMessage.insertBefore(sourcesEl.el, _feedbackContainer);
        }
      }

      // Add prompts if present
      if (prompts && prompts.length > 0) {
        this.addActionPrompts(assistantMessage, prompts, content);
      }
      this.messagesContainer.appendChild(assistantMessage);
      this.saveConversationHTML(this.messagesContainer.innerHTML);
      this.scrollToBottom();
      return messageId;
    }
  }, {
    key: "addActionPrompts",
    value: function addActionPrompts(assistantMessage, prompts, contentElement) {
      var _this15 = this;
      if (!this.actionPromptsTemplate || !this.singlePromptTemplate) return;
      var promptsContainer = this.actionPromptsTemplate.content.cloneNode(true);
      var promptsList = promptsContainer.querySelector("[data-vf-js-action-prompts-list]");
      prompts.forEach(function (prompt) {
        var promptEl = _this15.singlePromptTemplate.content.cloneNode(true);
        var link = promptEl.querySelector(".vf-chatbot-action-prompt__link");
        if (link) {
          var _prompt$action_url;
          link.href = prompt.action_url || "#";
          link.textContent = prompt.action_text;

          // Set target and add accessibility attributes
          if ((_prompt$action_url = prompt.action_url) !== null && _prompt$action_url !== void 0 && _prompt$action_url.startsWith("tel:")) {
            link.target = "_self";
          } else if (prompt.action_url) {
            link.target = "_blank";
            // Add aria-label for screen readers to indicate it opens in a new tab
            link.setAttribute("aria-label", "".concat(prompt.action_text, " (opens in new tab)"));
            // Add rel="noopener noreferrer" for security
            link.rel = "noopener noreferrer";
          }
          if (!prompt.action_url) {
            link.addEventListener("click", function (e) {
              e.preventDefault();
              _this15.emitEvent("vf-chatbot-action-prompt:click", {
                text: prompt.action_text
              });
            });
          }
        }
        promptsList.appendChild(promptEl);
      });
      contentElement.appendChild(promptsContainer);
    }
  }, {
    key: "setLoadingState",
    value: function setLoadingState(isLoading) {
      if (!this.config.features.enable_typing_indicator) return;
      if (isLoading) {
        if (!this.loadingIndicator && this.loadingTemplate) {
          var loadingContent = this.loadingTemplate.content.cloneNode(true);
          this.loadingIndicator = loadingContent.firstElementChild;

          // Update avatar
          var avatar = this.loadingIndicator.querySelector("img");
          if (avatar) {
            avatar.src = this.config.icons.assistant_avatar;
          }
          this.messagesContainer.appendChild(this.loadingIndicator);
        } else if (this.loadingIndicator && !this.loadingIndicator.parentNode) {
          // Re-append if it was removed from DOM
          this.messagesContainer.appendChild(this.loadingIndicator);
        }
        if (this.loadingIndicator) {
          this.loadingIndicator.style.display = "block";
        }
      } else {
        if (this.loadingIndicator) {
          this.loadingIndicator.style.display = "none";
          this.messagesContainer.removeChild(this.loadingIndicator);
        }
      }

      // Disable/enable controls
      if (this.sendBtn) this.sendBtn.disabled = isLoading;
      // if (this.input) this.input.disabled = isLoading;

      this.scrollToBottom();
    }
  }, {
    key: "scrollToBottom",
    value: function scrollToBottom() {
      if (this.config.behavior.auto_scroll && this.messagesContainer) {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
      }
    }

    // Public API methods
  }, {
    key: "resetConversation",
    value: function resetConversation() {
      this.messageHistory = [];
      this.conversationId = this.generateConversationId();
      if (this.messagesContainer) {
        this.messagesContainer.innerHTML = "";
        if (this.config.enable_session_persistence) {
          sessionStorage.removeItem("chatbotModalConversationHTML");
          sessionStorage.removeItem("chatbotModalFeedbackState");
          sessionStorage.removeItem("chatbotModalMinimized");
        }
      }
      if (this.savedSelection && this.config.enable_session_persistence) {
        sessionStorage.removeItem("vfChatbotSelectorSelection");
      }
      if (this.welcomeScreen && this.config.features.enable_welcome_suggestions) {
        this.welcomeScreen.style.display = "flex";
      }
      this.onConversationStart();
    }
  }, {
    key: "updateConfiguration",
    value: function updateConfiguration(newConfig) {
      this.config = this.deepMerge(this.config, newConfig);
      this.applyConfigurationToDOM();
      // this.applyTheme();
    }
  }, {
    key: "getConfiguration",
    value: function getConfiguration() {
      return _objectSpread({}, this.config);
    }
  }, {
    key: "getConversationHistory",
    value: function getConversationHistory() {
      return _toConsumableArray(this.messageHistory);
    }
  }, {
    key: "minimize",
    value: function minimize() {
      var event = new Event("vf-chatbot-modal-container:close", {
        bubbles: true
      });
      this.container.dispatchEvent(event);
    }
  }, {
    key: "showCloseDialog",
    value: function showCloseDialog() {
      var _this16 = this;
      if (this.dialog) {
        // Initialize and show the dialog
        initVFChatbotDialog(this.dialog);
        this.dialog.style.display = "flex";

        // Listen for dialog events
        var _onConfirm = function onConfirm() {
          _this16.resetConversation(); // <-- Reset to original state
          _this16.dialog.removeEventListener("vf-chatbot-dialog:confirm", _onConfirm);
          _this16.minimize();
        };
        this.dialog.addEventListener("vf-chatbot-dialog:confirm", _onConfirm);
      }
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$sendBtn2, _this$input4;
      this.onConversationEnd();

      // Remove event listeners
      (_this$sendBtn2 = this.sendBtn) === null || _this$sendBtn2 === void 0 || _this$sendBtn2.removeEventListener("click", this.sendMessage);
      (_this$input4 = this.input) === null || _this$input4 === void 0 || _this$input4.removeEventListener("keypress", this.handleKeyPress);

      // Clean up API response listener
      if (this.apiResponseListener) {
        this.container.removeEventListener("vf-chatbot:api-response", this.apiResponseListener);
      }
    }

    // Helper functions for conversation persistence
  }, {
    key: "saveConversationHTML",
    value: function saveConversationHTML(html) {
      if (this.config.enable_session_persistence) {
        sessionStorage.setItem("chatbotModalConversationHTML", html);
      }
    }
  }, {
    key: "loadConversationHTML",
    value: function loadConversationHTML() {
      if (this.config.enable_session_persistence) {
        return sessionStorage.getItem("chatbotModalConversationHTML") || "";
      }
      return "";
    }
  }]);
}(); // Global initialization function with configuration support
function initVFChatbotModal() {
  var customConfig = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  console.log("Looking for modal chatbot elements...");
  var chatbotElements = document.querySelectorAll("[data-vf-js-chatbot-modal-container]");
  if (chatbotElements.length === 0) {
    console.warn("No modal chatbot elements found on page");
    return [];
  }
  var instances = [];
  chatbotElements.forEach(function (element) {
    instances.push(new VFChatbotModal(element, customConfig));
  });
  return instances;
}

// Global exposure
if (typeof window !== "undefined") {
  window.VFChatbotModal = VFChatbotModal;
  window.initVFChatbotModal = initVFChatbotModal;
}

// vf-chatbot-standalone.js
var VFChatbotStandalone = /*#__PURE__*/function () {
  function VFChatbotStandalone(element) {
    var customConfig = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    _classCallCheck(this, VFChatbotStandalone);
    this.container = element;
    this.loadConfiguration(customConfig);
    this.setupDOMElements();
    this.setupState();
    this.setupEventHandlers();
    this.init();
  }
  return _createClass(VFChatbotStandalone, [{
    key: "loadConfiguration",
    value: function loadConfiguration(customConfig) {
      // Get config from data-vf-chatbot-config attribute
      var dataConfig = {};
      var attr = this.container.getAttribute("data-vf-chatbot-config");
      if (attr) {
        try {
          dataConfig = JSON.parse(attr);
        } catch (e) {
          console.warn("Invalid JSON in data-vf-chatbot-config:", e);
        }
      }

      // Default configuration
      var defaultConfig = {
        type: "standalone",
        title: "AI Assistant",
        welcome_logo: true,
        welcome_message: "Welcome! I'm here to help",
        welcome_logo_alt: "AI Assistant",
        welcome_suggestions_title: "Try asking me:",
        input_placeholder: "Ask me ...",
        welcome_max_suggestions: 4,
        disclaimer: 'Disclaimer: This chatbot is designed to assist you with general information and basic inquiries. See our <a class="vf-banner__link" target="_blank" rel="noopener noreferrer" aria-label="disclaimer notes (opens in new tab)" href="https://www.ebi.ac.uk/data-protection/privacy-notice/embl-ebi-public-website/">disclaimer notes</a>.',
        footnote: 'Review AI generated content for accuracy. <a class="vf-link" target="_blank" rel="noopener noreferrer" aria-label="Leave feedback (opens in new tab)" href="https://embl.service-now.com/esc?id=sc_cat_item&sys_id=5eeb8eb91b92e650b376da88b04bcbc1">Leave feedback</a>.',
        icons: {
          assistant_avatar: "../../assets/vf-chatbot/assets/vf-chatbot--icon-16x16-dark-green.svg",
          user_avatar: "../../assets/vf-chatbot/assets/vf-chatbot--avatar-user.svg",
          send_button: "../../assets/vf-chatbot/assets/vf-chatbot--icon-send.svg",
          main_logo_url: "../../assets/vf-chatbot/assets/vf-chatbot--icon-32x32-dark-green.svg"
        },
        api: {
          chat_endpoint: false,
          //"/api/chat", // Disabled to use fallback responses
          feedback_endpoint: false,
          //"/api/feedback",
          qa_data_url: "../../assets/vf-chatbot/assets/vf-chatbot-qa.json",
          headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer your-token"
          },
          timeout: 10000
        },
        features: {
          enable_welcome: true,
          enable_feedback: true,
          enable_sources: true,
          enable_sources_custom_format: false,
          enable_welcome_suggestions: true,
          enable_typing_indicator: true,
          enable_disclaimer: true,
          enable_predefined_qa: true,
          enable_fallback_responses: true,
          enable_qa_data_loading: true,
          enable_instant_feedback: false
        },
        behavior: {
          auto_scroll: true,
          typing_delay: 800,
          show_scrollbar: false
        },
        selectorContext: {
          chatbotRoutes: {
            multiSelect: true,
            maxMultiSelect: 3,
            showSearch: true,
            showSearchThreshold: 5,
            showAllServices: true,
            showAllServicesSelected: true,
            routes: "../../assets/vf-chatbot/assets/vf-chatbot-selector-services.json",
            placeholder: "Select services",
            title: "Services",
            selector_logo_url: "../../assets/vf-chatbot/assets/vf-chatbot--icon-24x24-dark-green.svg",
            selector_logo_title: "AI Assistant"
          }
        },
        handlers: {
          on_message_send: "handleMessageSend",
          on_response_receive: "handleResponseReceive",
          on_feedback_submit: "handleFeedbackSubmit",
          on_suggestion_click: "handleSuggestionClick",
          on_error: "handleError",
          on_conversation_start: "handleConversationStart",
          on_conversation_end: "handleConversationEnd"
        },
        feedback_options: {
          positive: [{
            id: "accurate",
            label: "Accurate"
          }, {
            id: "easy",
            label: "Easy to understand"
          }, {
            id: "formatted",
            label: "Well formatted"
          }],
          negative: [{
            id: "inaccurate",
            label: "Inaccurate answer"
          }, {
            id: "nocontext",
            label: "Did not use context"
          }, {
            id: "poorformat",
            label: "Poorly formatted"
          }]
        }
      };

      // Merge configurations: default < data-attribute < custom
      this.config = this.deepMerge(defaultConfig, dataConfig, customConfig);
      console.log("Final merged config:", this.config);
    }
  }, {
    key: "deepMerge",
    value: function deepMerge() {
      var _this17 = this;
      for (var _len2 = arguments.length, objects = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
        objects[_key2] = arguments[_key2];
      }
      return objects.reduce(function (prev, obj) {
        // ✅ FIX: Skip null/undefined objects
        if (!obj || _typeof(obj) !== "object") {
          return prev;
        }
        Object.keys(obj).forEach(function (key) {
          var pVal = prev[key];
          var oVal = obj[key];

          // ✅ FIX: Skip undefined values to maintain priority
          if (oVal === undefined) {
            return;
          }
          if (Array.isArray(pVal) && Array.isArray(oVal)) {
            prev[key] = pVal.concat.apply(pVal, _toConsumableArray(oVal));
          } else if (pVal && oVal && _typeof(pVal) === "object" && _typeof(oVal) === "object" && !Array.isArray(pVal) && !Array.isArray(oVal)) {
            prev[key] = _this17.deepMerge(pVal, oVal);
          } else {
            // ✅ FIX: Only assign if oVal is not null/undefined
            if (oVal !== null && oVal !== undefined) {
              prev[key] = oVal;
            }
          }
        });
        return prev;
      }, {});
    }
  }, {
    key: "setupDOMElements",
    value: function setupDOMElements() {
      var _this$disclaimer2;
      this.welcomeScreen = this.container.querySelector("[data-vf-js-chatbot-welcome]");
      this.messagesContainer = this.container.querySelector("[data-vf-js-chatbot-standalone-messages]");
      this.input = this.container.querySelector("[data-vf-js-chatbot-standalone-input]");
      this.sendBtn = this.container.querySelector("[data-vf-js-chatbot-standalone-send]");
      this.disclaimer = this.container.querySelector("[data-vf-js-chatbot-standalone-disclaimer]");
      this.disclaimerCloseBtn = (_this$disclaimer2 = this.disclaimer) === null || _this$disclaimer2 === void 0 ? void 0 : _this$disclaimer2.querySelector(".vf-button--dismiss");
      this.selectorEl = this.container.querySelector("[data-vf-js-chatbot-selector]");

      // Templates
      this.userTemplate = this.container.querySelector("#user-message-template");
      this.assistantTemplate = this.container.querySelector("#assistant-message-template");
      this.loadingTemplate = this.container.querySelector("#loading-indicator-template");
      this.actionPromptsTemplate = this.container.querySelector("#action-prompts-template");
      this.singlePromptTemplate = this.container.querySelector("#single-action-prompt-template");

      // Apply configuration to DOM elements
      this.applyConfigurationToDOM();
    }
  }, {
    key: "applyConfigurationToDOM",
    value: function applyConfigurationToDOM() {
      // Update input placeholder
      if (this.input) {
        this.input.placeholder = this.config.input_placeholder;
        // Initialize textarea height
        this.autoResizeTextarea();
      }

      // Update auto-scroll behavior
      if (this.messagesContainer) {
        this.messagesContainer.dataset.autoScroll = this.config.behavior.auto_scroll;
      }
    }
  }, {
    key: "setupState",
    value: function setupState() {
      this.currentAssistant = "";
      this.conversationId = this.generateConversationId();
      this.messageHistory = [];
      this.loadingIndicator = null;
      this.apiResponseListener = null;

      // Load Q&A data if using fallback responses
      this.loadQADataAndPopulateSuggestions();
    }
  }, {
    key: "setupEventHandlers",
    value: function setupEventHandlers() {
      // Setup global event handlers for custom functions
      this.setupCustomEventHandlers();

      // Bind internal events
      this.bindEvents();
    }
  }, {
    key: "setupCustomEventHandlers",
    value: function setupCustomEventHandlers() {
      var _this18 = this;
      // Create wrapper functions that call user-defined handlers and emit events
      var handlers = this.config.handlers;

      // Message send handler
      this.onMessageSend = function (message) {
        var eventData = {
          message: message,
          conversationId: _this18.conversationId,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:message-send", eventData);
        if (handlers.on_message_send && typeof window[handlers.on_message_send] === "function") {
          window[handlers.on_message_send](eventData);
        }
      };

      // Response receive handler
      this.onResponseReceive = function (response, sources, prompts) {
        var eventData = {
          response: response,
          sources: sources,
          prompts: prompts,
          conversationId: _this18.conversationId,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:response-receive", eventData);
        if (handlers.on_response_receive && typeof window[handlers.on_response_receive] === "function") {
          window[handlers.on_response_receive](eventData);
        }
      };

      // Feedback submit handler
      this.onFeedbackSubmit = function (messageId, feedbackType) {
        var feedbackText = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "";
        var feedbackComment = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : "";
        var eventData = {
          messageId: messageId,
          feedbackType: feedbackType,
          feedbackText: feedbackText,
          feedbackComment: feedbackComment,
          conversationId: _this18.conversationId,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:feedback-submit", eventData);

        // Call API if configured
        if (_this18.config.api.feedback_endpoint) {
          _this18.submitFeedbackToAPI(eventData);
        }
        if (handlers.on_feedback_submit && typeof window[handlers.on_feedback_submit] === "function") {
          window[handlers.on_feedback_submit](eventData);
        }
      };

      // Suggestion click handler
      this.onSuggestionClick = function (suggestion) {
        var eventData = {
          suggestion: suggestion,
          conversationId: _this18.conversationId,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:suggestion-click", eventData);
        if (handlers.on_suggestion_click && typeof window[handlers.on_suggestion_click] === "function") {
          window[handlers.on_suggestion_click](eventData);
        }
      };

      // Error handler
      this.onError = function (error, context) {
        var eventData = {
          error: error,
          context: context,
          conversationId: _this18.conversationId,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:error", eventData);
        if (handlers.on_error && typeof window[handlers.on_error] === "function") {
          window[handlers.on_error](eventData);
        }
      };

      // Conversation start handler
      this.onConversationStart = function () {
        var eventData = {
          conversationId: _this18.conversationId,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:conversation-start", eventData);
        if (handlers.on_conversation_start && typeof window[handlers.on_conversation_start] === "function") {
          window[handlers.on_conversation_start](eventData);
        }
      };

      // Conversation end handler
      this.onConversationEnd = function () {
        var eventData = {
          conversationId: _this18.conversationId,
          messageCount: _this18.messageHistory.length,
          timestamp: Date.now()
        };
        _this18.emitEvent("vf-chatbot:conversation-end", eventData);
        if (handlers.on_conversation_end && typeof window[handlers.on_conversation_end] === "function") {
          window[handlers.on_conversation_end](eventData);
        }
      };
    }
  }, {
    key: "emitEvent",
    value: function emitEvent(eventName, data) {
      var event = new CustomEvent(eventName, {
        bubbles: true,
        detail: data
      });
      this.container.dispatchEvent(event);
    }
  }, {
    key: "generateConversationId",
    value: function generateConversationId() {
      return "conv_".concat(Date.now(), "_").concat(Math.random().toString(36).substr(2, 9));
    }
  }, {
    key: "init",
    value: function () {
      var _init3 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee0() {
        var _this19 = this;
        return _regenerator().w(function (_context0) {
          while (1) switch (_context0.n) {
            case 0:
              // Initialize selector if present
              if (this.selectorEl) {
                initVFChatbotSelector(this.selectorEl);
                this.selectorEl.addEventListener("routeselection", function (e) {
                  _this19.handleRouteSelection(e.detail);
                });
              }
              if (this.welcomeScreen && this.config.features.enable_welcome_suggestions) {
                try {
                  initVFChatbotWelcome(this.welcomeScreen);
                  // await this.welcomeComponent.init();
                  this.welcomeScreen.addEventListener("vf-chatbot-welcome:suggestion-click", function (event) {
                    var question = event.detail.question;
                    _this19.onSuggestionClick(question);
                    _this19.showChatInterface();
                    _this19.sendUserMessage(question);
                  });
                  this.welcomeScreen.scrollTop = this.welcomeScreen.scrollHeight;
                } catch (error) {
                  console.error("Failed to initialize welcome component:", error);
                  this.onError(error, "welcome_component_init");
                }
              }
              // }

              // Trigger conversation start
              this.onConversationStart();
              console.log("Standalone chatbot initialized successfully");
            case 1:
              return _context0.a(2);
          }
        }, _callee0, this);
      }));
      function init() {
        return _init3.apply(this, arguments);
      }
      return init;
    }()
  }, {
    key: "loadQADataAndPopulateSuggestions",
    value: function () {
      var _loadQADataAndPopulateSuggestions2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee1() {
        var response, data, _t8;
        return _regenerator().w(function (_context1) {
          while (1) switch (_context1.p = _context1.n) {
            case 0:
              if (this.config.features.enable_qa_data_loading) {
                _context1.n = 1;
                break;
              }
              console.log("Q&A data loading is disabled");
              return _context1.a(2);
            case 1:
              if (this.config.api.qa_data_url) {
                _context1.n = 2;
                break;
              }
              console.log("No Q&A data URL configured");
              return _context1.a(2);
            case 2:
              _context1.p = 2;
              _context1.n = 3;
              return fetch(this.config.api.qa_data_url);
            case 3:
              response = _context1.v;
              if (response.ok) {
                _context1.n = 4;
                break;
              }
              throw new Error("HTTP ".concat(response.status, ": ").concat(response.statusText));
            case 4:
              _context1.n = 5;
              return response.json();
            case 5:
              data = _context1.v;
              // Load predefined Q&A if enabled
              if (this.config.features.enable_predefined_qa && data.predefinedQA) {
                this.qaData = data.predefinedQA;
              } else {
                console.log("Predefined Q&A loading is disabled or no data available");
              }

              // Load fallback responses if enabled
              if (this.config.features.enable_fallback_responses && data.fallbackResponses && data.fallbackResponses.length > 0) {
                this.fallbackResponses = data.fallbackResponses;
              } else {
                console.log("Using default fallback responses");
              }
              _context1.n = 7;
              break;
            case 6:
              _context1.p = 6;
              _t8 = _context1.v;
              console.error("Failed to load Q&A data:", _t8);
              this.onError(_t8, "qa_data_load");
            case 7:
              return _context1.a(2);
          }
        }, _callee1, this, [[2, 6]]);
      }));
      function loadQADataAndPopulateSuggestions() {
        return _loadQADataAndPopulateSuggestions2.apply(this, arguments);
      }
      return loadQADataAndPopulateSuggestions;
    }()
  }, {
    key: "handleRouteSelection",
    value: function handleRouteSelection(detail) {
      var selectedItems = detail.selectedItems;
      if (selectedItems && selectedItems.length > 0) {
        this.currentAssistant = selectedItems.join(", ");
        console.log("Switched to ".concat(this.currentAssistant, " assistant"));
        this.emitEvent("vf-chatbot:assistant-change", {
          selectedAssistants: this.currentAssistant,
          conversationId: this.conversationId
        });
      }
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this$sendBtn3,
        _this20 = this,
        _this$input5,
        _this$input6,
        _this$input7;
      // Send message events
      (_this$sendBtn3 = this.sendBtn) === null || _this$sendBtn3 === void 0 || _this$sendBtn3.addEventListener("click", function () {
        return _this20.sendMessage();
      });
      (_this$input5 = this.input) === null || _this$input5 === void 0 || _this$input5.addEventListener("keypress", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          _this20.sendMessage();
        }
      });

      // Auto-resize textarea functionality
      (_this$input6 = this.input) === null || _this$input6 === void 0 || _this$input6.addEventListener("input", function () {
        return _this20.autoResizeTextarea();
      });
      (_this$input7 = this.input) === null || _this$input7 === void 0 || _this$input7.addEventListener("paste", function () {
        // Use setTimeout to ensure the pasted content is processed
        setTimeout(function () {
          return _this20.autoResizeTextarea();
        }, 0);
      });

      // Disclaimer close
      if (this.disclaimer && this.disclaimerCloseBtn) {
        this.disclaimerCloseBtn.addEventListener("click", function () {
          _this20.disclaimer.classList.add("vf-u-display-none");
        });
      }

      // Global feedback event listener
      this.container.addEventListener("vf-chatbot-feedback:submit", function (event) {
        var _event$detail2 = event.detail,
          messageId = _event$detail2.messageId,
          feedbackType = _event$detail2.feedbackType,
          feedbackText = _event$detail2.feedbackText,
          feedbackComment = _event$detail2.feedbackComment;
        _this20.onFeedbackSubmit(messageId, feedbackType, feedbackText, feedbackComment);
      });

      // Action prompt click listener
      this.container.addEventListener("vf-chatbot-action-prompt:click", function (event) {
        var text = event.detail.text;
        _this20.sendUserMessage(text);
      });
    }
  }, {
    key: "sendMessage",
    value: function sendMessage() {
      if (!this.input || !this.input.value.trim()) return;
      var text = this.input.value.trim();
      this.showChatInterface();
      this.sendUserMessage(text);
    }
  }, {
    key: "autoResizeTextarea",
    value: function autoResizeTextarea() {
      if (!this.input) return;

      // Reset height to auto to get the actual scroll height
      // this.input.style.height = 'auto';
      // Get computed styles
      var computedStyle = window.getComputedStyle(this.input);
      var lineHeight = parseFloat(computedStyle.lineHeight) || 24;
      var paddingTop = parseFloat(computedStyle.paddingTop) || 0;
      var paddingBottom = parseFloat(computedStyle.paddingBottom) || 0;
      var borderTop = parseFloat(computedStyle.borderTopWidth) || 0;
      var borderBottom = parseFloat(computedStyle.borderBottomWidth) || 0;

      // Calculate heights more accurately
      var extraHeight = paddingTop + paddingBottom + borderTop + borderBottom;
      var minHeight = lineHeight + extraHeight; // Height for 1 row
      var maxHeight = lineHeight * 5 + extraHeight; // Height for 5 rows

      // Get the scroll height (content height)
      var scrollHeight = this.input.scrollHeight;

      // Calculate the new height, constrained by min and max
      var newHeight = Math.max(minHeight, scrollHeight);
      if (newHeight >= maxHeight) {
        // If content exceeds 5 rows, set to max height and enable scrolling
        newHeight = maxHeight;
        this.input.classList.add("vf-chatbot-standalone__input--scrollable");
      } else {
        // Remove scrollable class if content fits within 5 rows
        this.input.classList.remove("vf-chatbot-standalone__input--scrollable");
      }

      // Apply the new height
      this.input.style.height = newHeight + "px";
    }
  }, {
    key: "showChatInterface",
    value: function showChatInterface() {
      if (this.welcomeScreen) {
        this.welcomeScreen.style.display = "none";
      }
      if (this.messagesContainer) {
        this.messagesContainer.style.display = "flex";
      }
      if (this.input) {
        this.input.focus();
      }
    }
  }, {
    key: "sendUserMessage",
    value: function sendUserMessage(text) {
      if (!text || !this.messagesContainer) return;

      // Add to message history
      this.messageHistory.push({
        type: "user",
        content: text,
        timestamp: Date.now()
      });

      // Trigger message send event
      this.onMessageSend(text);

      // Create user message element
      var userMessage = this.userTemplate.content.cloneNode(true);
      var content = userMessage.querySelector(".vf-chatbot-message__content-prompt");
      content.textContent = text;

      // Update avatar if configured
      var avatar = userMessage.querySelector("img");
      if (avatar) {
        avatar.src = this.config.icons.user_avatar;
      }
      this.messagesContainer.appendChild(userMessage);

      // Clear input
      if (this.input) {
        this.input.value = "";
        this.input.style.height = "auto";
        // Reset textarea to minimum height and remove scrollable class
        this.input.classList.remove("vf-chatbot-standalone__input--scrollable");
        this.autoResizeTextarea();
      }
      this.scrollToBottom();
      this.processUserMessage(text);
    }
  }, {
    key: "processUserMessage",
    value: function () {
      var _processUserMessage2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee10(text) {
        var _this21 = this;
        var response, _t9;
        return _regenerator().w(function (_context10) {
          while (1) switch (_context10.p = _context10.n) {
            case 0:
              this.setLoadingState(true);
              _context10.p = 1;
              if (!(this.qaData && this.qaData[text])) {
                _context10.n = 2;
                break;
              }
              setTimeout(function () {
                var answer = _this21.qaData[text];
                _this21.addAssistantResponse(answer.answer || answer.html, answer.sources || [], answer.prompts || []);
                _this21.setLoadingState(false);
              }, this.config.behavior.typing_delay);
              return _context10.a(2);
            case 2:
              if (!this.config.api.chat_endpoint) {
                _context10.n = 4;
                break;
              }
              _context10.n = 3;
              return this.callChatAPI(text);
            case 3:
              response = _context10.v;
              this.addAssistantResponse(response.response, response.sources || [], response.prompts || []);
              this.setLoadingState(false);
              _context10.n = 5;
              break;
            case 4:
              // Use fallback response
              setTimeout(function () {
                var fallbackResponse;
                if (_this21.fallbackResponses && _this21.fallbackResponses.length > 0) {
                  fallbackResponse = _this21.fallbackResponses[Math.floor(Math.random() * _this21.fallbackResponses.length)];
                  _this21.addAssistantResponse(fallbackResponse.answer, [], fallbackResponse.prompts || []);
                }
                _this21.setLoadingState(false);
              }, this.config.behavior.typing_delay);
            case 5:
              _context10.n = 7;
              break;
            case 6:
              _context10.p = 6;
              _t9 = _context10.v;
              console.error("Error processing message:", _t9);
              this.onError(_t9, "message_processing");
              this.setLoadingState(false);
              // this.showErrorMessage();
            case 7:
              return _context10.a(2);
          }
        }, _callee10, this, [[1, 6]]);
      }));
      function processUserMessage(_x5) {
        return _processUserMessage2.apply(this, arguments);
      }
      return processUserMessage;
    }()
  }, {
    key: "callChatAPI",
    value: function () {
      var _callChatAPI2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee11(message) {
        var requestData, response;
        return _regenerator().w(function (_context11) {
          while (1) switch (_context11.n) {
            case 0:
              requestData = {
                message: message,
                assistant: this.currentAssistant,
                conversationId: this.conversationId,
                messageHistory: this.config.features.enable_conversation_history ? this.messageHistory : []
              };
              _context11.n = 1;
              return fetch(this.config.api.chat_endpoint, {
                method: "POST",
                headers: this.config.api.headers,
                body: JSON.stringify(requestData),
                signal: AbortSignal.timeout(this.config.api.timeout)
              });
            case 1:
              response = _context11.v;
              if (response.ok) {
                _context11.n = 2;
                break;
              }
              throw new Error("API call failed: ".concat(response.status, " ").concat(response.statusText));
            case 2:
              _context11.n = 3;
              return response.json();
            case 3:
              return _context11.a(2, _context11.v);
          }
        }, _callee11, this);
      }));
      function callChatAPI(_x6) {
        return _callChatAPI2.apply(this, arguments);
      }
      return callChatAPI;
    }()
  }, {
    key: "submitFeedbackToAPI",
    value: function () {
      var _submitFeedbackToAPI2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee12(feedbackData) {
        var _t0;
        return _regenerator().w(function (_context12) {
          while (1) switch (_context12.p = _context12.n) {
            case 0:
              _context12.p = 0;
              _context12.n = 1;
              return fetch(this.config.api.feedback_endpoint, {
                method: "POST",
                headers: this.config.api.headers,
                body: JSON.stringify(feedbackData)
              });
            case 1:
              _context12.n = 3;
              break;
            case 2:
              _context12.p = 2;
              _t0 = _context12.v;
              console.error("Failed to submit feedback:", _t0);
              this.onError(_t0, "feedback_submission");
            case 3:
              return _context12.a(2);
          }
        }, _callee12, this, [[0, 2]]);
      }));
      function submitFeedbackToAPI(_x7) {
        return _submitFeedbackToAPI2.apply(this, arguments);
      }
      return submitFeedbackToAPI;
    }()
  }, {
    key: "addAssistantResponse",
    value: function addAssistantResponse(text) {
      var sources = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var prompts = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];
      if (!this.assistantTemplate || !this.messagesContainer) return;
      var messageId = "msg_".concat(Date.now(), "_").concat(Math.random().toString(36).substr(2, 9));

      // Add to message history
      this.messageHistory.push({
        type: "assistant",
        content: text,
        sources: sources,
        prompts: prompts,
        messageId: messageId,
        timestamp: Date.now()
      });

      // Trigger response receive event
      this.onResponseReceive(text, sources, prompts);
      var assistantMessage = this.assistantTemplate.content.cloneNode(true);
      var content = assistantMessage.querySelector(".vf-chatbot-message__content-prompt");
      content.innerHTML = text;

      // Update avatar if configured
      var avatar = assistantMessage.querySelector("img");
      if (avatar) {
        avatar.src = this.config.icons.assistant_avatar;
      }

      // Initialize feedback if enabled
      if (this.config.features.enable_feedback) {
        var feedbackContainer = assistantMessage.querySelector("[data-vf-js-chatbot-feedback]");
        if (feedbackContainer) {
          var _this$config$feedback3, _this$config$feedback4;
          feedbackContainer.dataset.messageId = messageId;

          // Pass configuration to VFChatbotFeedback component
          new VFChatbotFeedback(feedbackContainer, messageId, {
            enable_instant_feedback: this.config.features.enable_instant_feedback,
            api_endpoint: this.config.api.feedback_endpoint,
            positiveOptions: (_this$config$feedback3 = this.config.feedback_options) === null || _this$config$feedback3 === void 0 ? void 0 : _this$config$feedback3.positive,
            negativeOptions: (_this$config$feedback4 = this.config.feedback_options) === null || _this$config$feedback4 === void 0 ? void 0 : _this$config$feedback4.negative
          });
        }
      }

      // Add sources if enabled and present
      if (this.config.features.enable_sources && sources && (sources.length > 0 || sources != "")) {
        var _feedbackContainer2 = assistantMessage.querySelector("[data-vf-js-chatbot-feedback]");
        var sourceHTML = "";
        if (!this.config.features.enable_sources_custom_format && sources.length > 0) {
          sourceHTML = sources.map(function (message) {
            return "\n          <li class=\"vf-chatbot-sources__item\">\n            <div class=\"vf-chatbot-sources__label\">".concat(message.domain, "</div>\n            <a class=\"vf-link vf-chatbot-sources__link\" href=\"").concat(message.url, "\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"").concat(message.title, " (opens in new tab)\">\n              ").concat(message.title, "\n            </a>\n            <div class=\"vf-chatbot-sources__description\">").concat(message.description, "</div>\n          </li>\n        ");
          }).join("");
        } else if (this.config.features.enable_sources_custom_format && sources != "") {
          sourceHTML = sources;
        }
        if (sourceHTML != "") {
          var sourcesEl = initVFChatbotSources(sourceHTML);
          assistantMessage.insertBefore(sourcesEl.el, _feedbackContainer2);
        }
      }

      // Add prompts if present
      if (prompts && prompts.length > 0) {
        this.addActionPrompts(assistantMessage, prompts, content);
      }
      this.messagesContainer.appendChild(assistantMessage);
      this.scrollToBottom();
      return messageId;
    }
  }, {
    key: "addActionPrompts",
    value: function addActionPrompts(assistantMessage, prompts, contentElement) {
      var _this22 = this;
      if (!this.actionPromptsTemplate || !this.singlePromptTemplate) return;
      var promptsContainer = this.actionPromptsTemplate.content.cloneNode(true);
      var promptsList = promptsContainer.querySelector("[data-vf-js-action-prompts-list]");
      prompts.forEach(function (prompt) {
        var promptEl = _this22.singlePromptTemplate.content.cloneNode(true);
        var link = promptEl.querySelector(".vf-chatbot-action-prompt__link");
        if (link) {
          var _prompt$action_url2;
          link.href = prompt.action_url || "#";
          link.textContent = prompt.action_text;

          // Set target and add accessibility attributes
          if ((_prompt$action_url2 = prompt.action_url) !== null && _prompt$action_url2 !== void 0 && _prompt$action_url2.startsWith("tel:")) {
            link.target = "_self";
          } else if (prompt.action_url) {
            link.target = "_blank";
            // Add aria-label for screen readers to indicate it opens in a new tab
            link.setAttribute("aria-label", "".concat(prompt.action_text, " (opens in new tab)"));
            // Add rel="noopener noreferrer" for security
            link.rel = "noopener noreferrer";
          }
          if (!prompt.action_url) {
            link.addEventListener("click", function (e) {
              e.preventDefault();
              _this22.emitEvent("vf-chatbot-action-prompt:click", {
                text: prompt.action_text
              });
            });
          }
        }
        promptsList.appendChild(promptEl);
      });
      contentElement.appendChild(promptsContainer);
    }
  }, {
    key: "setLoadingState",
    value: function setLoadingState(isLoading) {
      if (!this.config.features.enable_typing_indicator) return;
      if (isLoading) {
        if (!this.loadingIndicator && this.loadingTemplate) {
          var loadingContent = this.loadingTemplate.content.cloneNode(true);
          this.loadingIndicator = loadingContent.firstElementChild;

          // Update avatar
          var avatar = this.loadingIndicator.querySelector("img");
          if (avatar) {
            avatar.src = this.config.icons.assistant_avatar;
          }
          this.messagesContainer.appendChild(this.loadingIndicator);
        } else if (this.loadingIndicator && !this.loadingIndicator.parentNode) {
          // Re-append if it was removed from DOM
          this.messagesContainer.appendChild(this.loadingIndicator);
        }
        if (this.loadingIndicator) {
          this.loadingIndicator.style.display = "block";
        }
      } else {
        if (this.loadingIndicator) {
          this.loadingIndicator.style.display = "none";
          this.messagesContainer.removeChild(this.loadingIndicator);
        }
      }

      // Disable/enable controls
      if (this.sendBtn) this.sendBtn.disabled = isLoading;
      // if (this.input) this.input.disabled = isLoading;

      this.scrollToBottom();
    }
  }, {
    key: "scrollToBottom",
    value: function scrollToBottom() {
      if (this.config.behavior.auto_scroll && this.messagesContainer) {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
      }
    }

    // Public API methods
  }, {
    key: "resetConversation",
    value: function resetConversation() {
      this.messageHistory = [];
      this.conversationId = this.generateConversationId();
      if (this.messagesContainer) {
        this.messagesContainer.innerHTML = "";
      }
      if (this.welcomeScreen && this.config.features.enable_welcome_suggestions) {
        this.welcomeScreen.style.display = "block";
        this.messagesContainer.style.display = "none";
      }
      this.onConversationStart();
    }
  }, {
    key: "updateConfiguration",
    value: function updateConfiguration(newConfig) {
      this.config = this.deepMerge(this.config, newConfig);
      this.applyConfigurationToDOM();
      // this.applyTheme();
    }
  }, {
    key: "getConfiguration",
    value: function getConfiguration() {
      return _objectSpread({}, this.config);
    }
  }, {
    key: "getConversationHistory",
    value: function getConversationHistory() {
      return _toConsumableArray(this.messageHistory);
    }
  }, {
    key: "destroy",
    value: function destroy() {
      var _this$sendBtn4, _this$input8;
      this.onConversationEnd();

      // Remove event listeners
      (_this$sendBtn4 = this.sendBtn) === null || _this$sendBtn4 === void 0 || _this$sendBtn4.removeEventListener("click", this.sendMessage);
      (_this$input8 = this.input) === null || _this$input8 === void 0 || _this$input8.removeEventListener("keypress", this.handleKeyPress);

      // Clean up API response listener
      if (this.apiResponseListener) {
        this.container.removeEventListener("vf-chatbot:api-response", this.apiResponseListener);
      }
    }
  }]);
}(); // Global initialization function with configuration support
function initVFChatbotStandalone() {
  var customConfig = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  console.log("Looking for standalone chatbot elements...");
  var chatbotElements = document.querySelectorAll("[data-vf-js-chatbot-standalone-container]");
  if (chatbotElements.length === 0) {
    console.warn("No standalone chatbot elements found on page");
    return [];
  }
  var instances = [];
  chatbotElements.forEach(function (element) {
    instances.push(new VFChatbotStandalone(element, customConfig));
  });
  return instances;
}

// Global exposure
if (typeof window !== "undefined") {
  window.VFChatbotStandalone = VFChatbotStandalone;
  window.initVFChatbotStandalone = initVFChatbotStandalone;
}

// import { initVFChatbotFab } from "@visual-framework/vf-chatbot-fab/vf-chatbot-fab.js";
// Accessibility helpers
var previouslyFocusedElement = null;
var focusTrapHandler = null;
var escapeHandler = null;

// vf-chatbot
function VFChatbot(element) {
  this.el = element;
  this.fab = this.el.querySelector("[data-vf-js-chatbot-fab]");
  this.modal = this.el.querySelector("[data-vf-js-chatbot-modal-container]");
  this.init();
}
VFChatbot.prototype = {
  init: function init() {
    var _this23 = this;
    if (!this.fab || !this.modal) return;

    // Handle FAB toggle event
    this.el.addEventListener("vf-chatbot-fab:toggle", function (e) {
      e.stopPropagation();
      _this23.openChat();
    });

    // Handle modal events
    this.modal.addEventListener("vf-chatbot-modal-container:close", function () {
      _this23.closeChat();
    });

    // Handle escape key (handled in focus trap for accessibility)
    // document.addEventListener("keydown", e => {
    //   if (
    //     e.key === "Escape" &&
    //     !this.modal.classList.contains("vf-chatbot-modal-container--inactive")
    //   ) {
    //     this.closeChat();
    //   }
    // });
  },
  enableFocusTrap: function enableFocusTrap() {
    var _this24 = this;
    var modal = this.modal;
    var focusableSelectors = ["a[href]", "area[href]", "input:not([disabled])", "select:not([disabled])", "textarea:not([disabled])", "button:not([disabled])", "[tabindex]:not([tabindex='-1'])"];
    var getFocusable = function getFocusable() {
      return Array.from(modal.querySelectorAll(focusableSelectors.join(","))).filter(function (el) {
        return el.offsetParent !== null;
      });
    };

    // Focus first focusable element or modal itself
    var focusables = getFocusable();
    // Try to focus the input first
    var input = modal.querySelector("[data-vf-js-chatbot-modal-input]");
    if (input) {
      input.focus();
    } else if (focusables.length) {
      focusables[0].focus();
    } else {
      modal.setAttribute("tabindex", "-1");
      modal.focus();
    }

    // Trap focus
    focusTrapHandler = function focusTrapHandler(e) {
      if (!modal.classList.contains("vf-chatbot-modal-container--active")) return;
      if (e.key !== "Tab") return;
      var focusables = getFocusable();
      if (!focusables.length) return;
      var first = focusables[0];
      var last = focusables[focusables.length - 1];
      if (e.shiftKey) {
        if (document.activeElement === first) {
          e.preventDefault();
          last.focus();
        }
      } else {
        if (document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    };

    // Escape closes modal
    escapeHandler = function escapeHandler(e) {
      if (!modal.classList.contains("vf-chatbot-modal-container--active")) return;
      if (e.key === "Escape") {
        e.preventDefault();
        _this24.closeChat();
      }
    };
    document.addEventListener("keydown", focusTrapHandler);
    document.addEventListener("keydown", escapeHandler);
  },
  disableFocusTrap: function disableFocusTrap() {
    document.removeEventListener("keydown", focusTrapHandler);
    document.removeEventListener("keydown", escapeHandler);
  },
  openChat: function openChat() {
    this.fab.classList.add("vf-chatbot-fab--inactive");
    this.modal.classList.remove("vf-chatbot-modal-container--inactive");
    this.modal.classList.add("vf-chatbot-modal-container--active");
    this.modal.setAttribute("aria-modal", true);

    // Accessibility: store and trap focus
    previouslyFocusedElement = document.activeElement;
    this.enableFocusTrap();

    // Focus on input if it exists
    var input = this.modal.querySelector("[data-vf-js-chatbot-modal-input]");
    if (input) {
      setTimeout(function () {
        return input.focus();
      }, 300);

      // Enter to send message (unless Shift+Enter)
      input.addEventListener("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          // You may want to call your send message logic here
          var sendBtn = input.parentElement.querySelector("[data-vf-js-chatbot-modal-send]");
          if (sendBtn) sendBtn.click();
        }
      });
    }
    sessionStorage.setItem("chatbotModalMinimized", "false");
  },
  closeChat: function closeChat() {
    this.fab.classList.remove("vf-chatbot-fab--inactive");
    this.modal.classList.remove("vf-chatbot-modal-container--active");
    this.modal.classList.add("vf-chatbot-modal-container--inactive");
    this.modal.setAttribute("aria-modal", false);

    // Accessibility: restore focus and remove trap
    this.disableFocusTrap();
    if (previouslyFocusedElement && previouslyFocusedElement.focus) {
      previouslyFocusedElement.focus();
    }
    sessionStorage.setItem("chatbotModalMinimized", "true");
  }
};

// Utility to update bottom/right margin for all chatbots
function updateChatbotBottomMargin() {
  var bottomMarginPx = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
  var rightMarginPx = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  document.querySelectorAll("[data-vf-js-chatbot]").forEach(function (element) {
    element.style.setProperty("--vf-chatbot-modal-bottom-margin", "".concat(bottomMarginPx, "px"));
    element.style.setProperty("--vf-chatbot-modal-right-margin", "".concat(rightMarginPx, "px"));
  });
}
function getChatbotBottomMargin(userSuppliedMargin) {
  // If user provided a margin, use it
  if (typeof userSuppliedMargin === "number") return userSuppliedMargin;

  // Find all visible banners with .vf-banner--bottom
  var banners = Array.from(document.querySelectorAll(".vf-banner--bottom")).filter(function (banner) {
    // Only consider banners that are displayed (not display: none)
    return !!(banner.offsetParent || window.getComputedStyle(banner).display !== "none" && banner.offsetHeight > 0);
  });

  // Get the tallest banner's offsetHeight
  var maxHeight = 0;
  banners.forEach(function (banner) {
    // Listen for close events to update margin
    banner.addEventListener("vf-banner:close", function () {
      updateChatbotBottomMargin(0, 0);
    });
    var closeBtn = banner.querySelector("[data-vf-js-banner-close]");
    if (closeBtn) {
      closeBtn.addEventListener("click", function () {
        updateChatbotBottomMargin(0, 0);
      });
    }
    if (banner.offsetHeight > maxHeight) {
      maxHeight = banner.offsetHeight;
    }
  });
  return [maxHeight, 0];
}
function initVFChatbot() {
  var config = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  if (config && config.type == "modal") {
    var elements = document.querySelectorAll("[data-vf-js-chatbot]");
    var chatbotBottomMargin = getChatbotBottomMargin(config.chatbotBottomMargin) || [0, 0];
    var modalInstances = [];
    elements.forEach(function (element) {
      // Set CSS variable for FAB and modal margin
      element.style.setProperty("--vf-chatbot-modal-bottom-margin", "".concat(chatbotBottomMargin[0], "px"));
      element.style.setProperty("--vf-chatbot-modal-right-margin", "".concat(chatbotBottomMargin[1], "px"));
      new VFChatbot(element);
      initVFChatbotFab();
      modalInstances = modalInstances.concat(initVFChatbotModal(config));
    });
    return modalInstances;
  } else if (config && config.type == "standalone") {
    return initVFChatbotStandalone(config);
  }
}

// Global exposure
if (typeof window !== "undefined") {
  window.VFChatbot = VFChatbot;
  window.initVFChatbot = initVFChatbot;
}

// vf-navigation
// only required for vf-navigation--on-this-page

/**
 * Function for JS scroll to top functionality
 * That must be executed exactly once
 * @example vfNavigationOnThisPage()
 */

function vfNavigationOnThisPage() {
  var scope = document;
  // based on the attribute we select all navigation links
  var navLinks = scope.querySelectorAll("[data-vf-js-navigation-on-this-page-container='true'] .vf-navigation__item a");
  // we store all ids from anchor tags to know the sections we should care about
  var ids = [];
  navLinks.forEach(function (link) {
    if (link.hash) {
      ids.push(link.hash.substring(1));
    }
  });
  // get all elements with an id and convert it from NodeList to Array
  var sections = Array.prototype.slice.call(scope.querySelectorAll("[id]"));
  var sectionPositions = [];
  if (!navLinks || !sections) {
    // exit: either sections or section content not found
    return;
  }
  if (navLinks.length === 0 || sections.length === 0) {
    // exit: either sections or section content not found
    return;
  }
  // remove all the elements that doesn't appear in the navigation based on it's id
  sections = sections.filter(function (section) {
    return ids.indexOf(section.id) !== -1;
  });
  function activateNavigationItem() {
    // althought costly, we recalculate the position of elements each time as things move or load dynamically
    sectionPositions = [];
    sections.forEach(function (e) {
      var rect = e.getBoundingClientRect();
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      sectionPositions.push({
        id: e.id,
        position: rect.top + scrollTop
      });
    });
    // put sections in the bottom at the beginning of the array
    sectionPositions.reverse();
    var scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
    // We reverse the array because Array.find starts to search from position 0 and to simplify
    // the logic to find which element is closest to the current scroll position, otherwise it will always
    // select the first element.
    var currentSection = sectionPositions.find(function (s) {
      return !(scrollPosition <= s.position - 95);
    });
    navLinks.forEach(function (link) {
      link.setAttribute("aria-selected", "false");
    });
    // if we don't match any section yet, highlight the first link
    if (!currentSection) {
      navLinks[0].setAttribute("aria-selected", "true");
    } else {
      navLinks.forEach(function (link) {
        if (link.hash === "#" + currentSection.id) {
          link.setAttribute("aria-selected", "true");
        }
      });
    }
    isCalculating = false;
  }
  var isCalculating = false;
  window.onscroll = function () {
    if (!isCalculating) {
      isCalculating = true;
      window.requestAnimationFrame(activateNavigationItem);
    }
  };
  navLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      var section = document.querySelector(link.hash);
      var scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
      if (!section) return;
      event.preventDefault();
      // get current styles of element we are moving to
      var elemStyles = window.getComputedStyle(section);
      // take into account the padding and/or margin top
      var value = elemStyles.paddingTop !== "0px" ? elemStyles.paddingTop : elemStyles.marginTop;
      // we remove the px characters from the value
      var offset = parseInt(value.slice(0, -2), 10);
      // total offset: margin/padding top of the element plus the size of the navigation bar
      window.scroll({
        top: section.getBoundingClientRect().top + scrollPosition - (offset + 40),
        behavior: "smooth"
      });
    });
  });
}

// vf-location-nearest

/**
 * Utility method to invalidate prior check.
  * @example vfLocationNearest('load')
  * @param {string} [type] - 'load' or 'unload' to set or unset
 */
function vfLocationNearestIndicate(type) {
  var el = document.querySelector("body");
  if (type == "unload") {
    el.setAttribute("data-vf-location-nearest-loaded", "false");
  } else if (type == "load") {
    el.setAttribute("data-vf-location-nearest-loaded", "true");
    vfLocationNearestDomActions();
  }
}

/**
 * Use the browser location API to try to atuodetct location.
 * @example vfLocationNearestDetect(locationsList)
 * @param {object} [locationsList] - An object of locations
 */
function vfLocationNearestDetect(locationsList) {
  // Via: https://developers.google.com/web/fundamentals/native-hardware/user-location#dont_keep_the_user_waiting_set_a_timeout
  var startPos;
  var geoOptions = {
    enableHighAccuracy: false,
    timeout: 4 * 1000
  };
  var geoSuccess = function geoSuccess(position) {
    startPos = position;
    vfLocationNearestResolve(locationsList, startPos.coords);
  };
  var geoError = function geoError(error) {
    console.warn("vfLocationNearest", "Geolocation error code: " + error.code);
    // error.code can be:
    //   0: unknown error
    //   1: permission denied
    //   2: position unavailable (error response from location provider)
    //   3: timed out
    // if no match return false
    vfLocationNearestResolve(locationsList, false);
  };

  // Bootstrap browserapi
  navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
}

/**
 * Receive a location and process it against a user location if any.
 * @example vfLocationNearestResolve(locationsList, userLocation)
 * @param {object} [locationsList] - An object of locations
 * @param {object} [userLocation] - An object with .latitude and .longitude
 */
function vfLocationNearestResolve(locationsList, userLocation) {
  // console.log(locationsList, userLocation);
  // console.log("user at",userLocation.latitude + ", " + userLocation.longitude);

  // Determine which location is closest using circles
  // https://stackoverflow.com/questions/21279559/geolocation-closest-locationlat-long-from-my-position/21297385#21297385
  function calculateNearestCity(latitude, longitude) {
    // Convert Degrees to Radians
    function Deg2Rad(deg) {
      return deg * Math.PI / 180;
    }
    function PythagorasEquirectangular(lat1, lon1, lat2, lon2) {
      lat1 = Deg2Rad(lat1);
      lat2 = Deg2Rad(lat2);
      lon1 = Deg2Rad(lon1);
      lon2 = Deg2Rad(lon2);
      var R = 6371; // km
      var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
      var y = lat2 - lat1;
      var d = Math.sqrt(x * x + y * y) * R;
      return d;
    }
    var minDif = 99999;
    var closest = locationsList.default;

    // loop through each location, matching a close city then the next closest and so on
    for (var key in locationsList) {
      /* eslint-disable no-prototype-builtins */
      if (locationsList.hasOwnProperty(key)) {
        /* eslint-enable no-prototype-builtins */
        if (key != "default") {
          var evalutedLocation = locationsList[key];
          var dif = PythagorasEquirectangular(latitude, longitude, evalutedLocation.latlon.split(", ")[0], evalutedLocation.latlon.split(", ")[1]);
          if (dif < minDif) {
            closest = evalutedLocation;
            closest.id = key;
            minDif = dif;
          }
        }
      }
    }
    return closest;
  }
  if (userLocation == false) {
    // if no match, use the default location
    console.warn("vfLocationNearest", "No user location detected, will use default");
    vfLocationNearestSave(locationsList["default"].name, "default");
  } else {
    // geo diameter matching
    var closestCity = calculateNearestCity(userLocation.latitude, userLocation.longitude);
    vfLocationNearestSave(closestCity.name, closestCity.id);
  }
}

/**
 * Receive a resolved location and assign it to the DOM
 * @example vfLocationNearestSave(locationsList, userLocation)
 * @param {object} [locationName] - A user-facing string
 * @param {string} [locationId] - The ID
 */
function vfLocationNearestSave(locationName, locationId) {
  // console.log('vfLocationNearestSave location',locationName,locationId)

  // assign to the body
  var el = document.querySelector("body");
  el.setAttribute("data-vf-location-nearest-location", locationId);
  el.setAttribute("data-vf-location-nearest-name", locationName);

  // indicate we've loaded
  vfLocationNearestIndicate("load");
}

/**
  * Observe an element for changes to specify a manual location
  * This may support a "type" but for now it's only a select list
  * @param {object} [locationsList] - An object of locations
  * @param {object} [scope] - the html scope to process, optional, defaults to `document`
  * @example vfLocationNearestOverridePopulate(locationsList, document.vfLocationNearestDomActions('.vf-component__container')[0]);
  */
function vfLocationNearestOverridePopulate(locationsList, scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  /* eslint-enable no-redeclare */

  var locationWidget = scope.querySelectorAll("[data-vf-js-location-nearest-override-widget]");
  if (!locationWidget) {
    // exit: container not found
    return;
  }
  if (locationWidget.length == 0) {
    // exit: content not found
    return;
  }
  var widget = "<label class=\"vf-form__label\" for=\"vf-form__select\"></label>" + "<select class=\"vf-form__select\" id=\"vf-form__select\">";

  // Create the markup for a dropdown
  for (var key in locationsList) {
    /* eslint-disable no-prototype-builtins */
    if (locationsList.hasOwnProperty(key)) {
      /* eslint-enable no-prototype-builtins */
      // const element = locationsList[key];
      widget = widget + "<option value=\"".concat(key, "\">").concat(locationsList[key].name, "</option>");
    }
  }
  widget = widget + "</select>";

  // assign values to widget
  locationWidget[0].innerHTML = widget;
}

/**
  * Observe an element for changes to specify a manual location
  * @param {object} [scope] - the html scope to process, optional, defaults to `document`
  * @example vfLocationNearestOverrideActivate(locationsList, document.vfLocationNearestDomActions('.vf-component__container')[0]);
  */
function vfLocationNearestOverrideActivate(scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  /* eslint-enable no-redeclare */

  var overrideElement = scope.querySelectorAll("[data-vf-js-location-nearest-override-widget]");
  if (!overrideElement) {
    // exit: container not found
    return;
  }
  if (overrideElement.length == 0) {
    // exit: content not found
    return;
  }
  overrideElement[0].addEventListener("change", function (e) {
    var activeItem = e.target;
    // console.log('You selected: ', activeItem.options[activeItem.target.selectedIndex].text);
    vfLocationNearestSave(activeItem.options[activeItem.selectedIndex].text, activeItem.value);
  });
}

/**
  * With attributes saved to the dom, we can take further action
  * <body data-vf-location-nearest-loaded="true"
  *   data-vf-location-nearest-location="default"
  *   data-vf-location-nearest-name="Heidelberg"
  * @param {object} [scope] - the html scope to process, optional, defaults to `document`
  * @example vfLocationNearestDomActions(document.vfLocationNearestDomActions('.vf-component__container')[0]);
  */
function vfLocationNearestDomActions(scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  /* eslint-enable no-redeclare */

  // Get items from dom
  var el = document.querySelector("body");
  var locationId = el.getAttribute("data-vf-location-nearest-location");
  var locationName = el.getAttribute("data-vf-location-nearest-name");

  // push the active location to the dom
  function assignName() {
    var locationNameHolder = scope.querySelectorAll("[data-vf-js-location-nearest-name]");
    if (!locationNameHolder) {
      // exit: container not found
      return;
    }
    if (locationNameHolder.length == 0) {
      // exit: content not found
      return;
    }
    // console.log('assignName','pushing the active location to the dom')
    locationNameHolder[0].innerHTML = locationName;
  }

  // simple activation of any elements/components through simple click simulation
  function activateElements() {
    var locationActivationTargets = scope.querySelectorAll("[data-vf-js-location-nearest-activation-target]");
    if (!locationActivationTargets) {
      // exit: container not found
      return;
    }
    if (locationActivationTargets.length == 0) {
      // exit: content not found
      return;
    }
    locationActivationTargets.forEach(function (element) {
      // console.log(element.getAttribute('data-vf-js-location-nearest-activation-target'));
      if (element.getAttribute("data-vf-js-location-nearest-activation-target") == locationId) {
        element.click();
      }
    });
  }
  assignName();
  activateElements();
}

/**
  * The global function for this component
  * @example vfLocationNearest(locationsList)
  * @param {object} [locationsList] - An object of locations
  */
function vfLocationNearest(locationsList) {
  locationsList = locationsList || {
    default: {
      name: "Heidelberg",
      latlon: "49.4076, 8.6907"
    }
  };
  // console.log('vfLocationNearest locationsList',locationsList)

  // unset any prior check
  vfLocationNearestIndicate("unload");

  // Insert a widget of selectable locations
  vfLocationNearestOverridePopulate(locationsList);

  // get the current users location
  vfLocationNearestDetect(locationsList);

  // enable a manual override widget
  vfLocationNearestOverrideActivate();
}

// You should also import it at ./components/vf-component-rollup/scripts.js
// import { vfLocationNearest } from 'vf-location-nearest/vf-location-nearest';
// Or import directly
// import { vfLocationNearest } from '../components/raw/vf-location-nearest/vf-location-nearest.js';
// And, if needed, invoke it
// vfLocationNearest();

// vf-tree

/*
 * A note on the Visual Framework and JavaScript:
 * The VF is primairly a CSS framework so we've included only a minimal amount
 * of JS in components and it's fully optional (just remove the JavaScript selectors
 * i.e. `data-vf-js-tabs`). So if you'd rather use Angular or Bootstrap for your
 * tabs, the Visual Framework won't get in the way.
 *
 * When querying the DOM for elements that should be acted on:
 * 🚫 Don't: const tabs = document.querySelectorAll('.vf-tabs');
 * ✅ Do:    const tabs = document.querySelectorAll('[data-vf-js-tabs]');
 *
 * This allows users who would prefer not to have this JS engange on an element
 * to drop `data-vf-js-component` and still maintain CSS styling.
 */

// if you need to import any other components' JS to use here
// import { vfOthercomponent } from 'vf-other-component/vf-other-component';

/**
  * The global function for this component
  * @example vfTree(firstPassedVar)
  * @param {object} [scope] - the html scope to process, optional, defaults to `document`
  */
function vfTree(scope) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  /* eslint-enable no-redeclare */

  // Get relevant elements and collections
  var treelist = scope.querySelectorAll("[data-vf-js-tree]");
  // const panelsList = scope.querySelectorAll('[data-vf-js-tabs-content]');
  // const panels = scope.querySelectorAll('[data-vf-js-tabs-content] [id^="vf-tabs__section"]');
  // const tabs = scope.querySelectorAll('[data-vf-js-tabs] .vf-tabs__link');
  // if (!tablist || !panels || !tabs) {
  if (!treelist) {
    // exit: either trees or tabbed content not found
    return;
  }
  // if (tablist.length == 0 || panels.length == 0 || tabs.length == 0) {
  if (treelist.length == 0) {
    // exit: either trees or tabbed content not found
    return;
  }

  // Get screen-reader only text from root tree node data attributes
  var treeNodeOpenText = treelist[0].dataset.vfJsButtonHiddenOpenText;
  var treeNodeCloseText = treelist[0].dataset.vfJsButtonHiddenCloseText;
  // Receive a target scope and toggle if it is active
  function vfTreeToggleActive(target) {
    var collpasedState = target.dataset["vfJsTree-Collapsed"];
    if (collpasedState === "true") {
      collpasedState = false;
      target.classList.remove("vf-tree--collapsed");
      target.classList.add("vf-tree__item--expanded");
      target.setAttribute("aria-expanded", true);
    } else {
      collpasedState = true;
      target.classList.add("vf-tree--collapsed");
      target.classList.remove("vf-tree__item--expanded");
      target.setAttribute("aria-expanded", false);
    }

    // set screen reader text based on tree state
    target.querySelector("[data-vf-js-tree-button-hidden-text]").innerText = collpasedState ? treeNodeOpenText : treeNodeCloseText;
    target.dataset["vfJsTree-Collapsed"] = collpasedState;
  }

  // Logic to show/hide section of tree
  function vfTreeButtonHandler(target) {
    // if want to only get the direct children matches
    // this future proofs but also adds and edge case, so we won't use for now
    // let targetButton = Array.prototype.filter.call(target.children, function (item) {
    //   return item.matches('[data-vf-js-tree--button]');
    // });

    var targetButton = target.querySelectorAll("[data-vf-js-tree--button]");
    if (targetButton.length == 0) {
      // if no tree buttons found, nothing to do
      return;
    }

    // Handle clicking
    // Target the closest item
    targetButton[0].addEventListener("click", function (e) {
      console.log(target);
      e.preventDefault();
      vfTreeToggleActive(target);
    });
  }

  // For each treelist section, invoke handlers
  Array.prototype.forEach.call(treelist, function (treelistset) {
    // Handle hide/show for tree sets
    vfTreeButtonHandler(treelistset);
  });
}

// embl-content-hub-loader__html-imports

// A trimmed down version of
// https://github.com/AshleyScirra/html-imports-polyfill/blob/master/htmlimports.js
// mostly we dropped CSS and sub-imports

function emblContentHubLoaderHtmlImports() {
  // Map a script URL to its import document for GetImportDocument()
  // const scriptUrlToImportDoc = new Map();

  function GetPathFromURL(url) {
    if (!url.length) return url; // empty string

    var lastCh = url.charAt(url.length - 1);
    if (lastCh === "/" || lastCh === "\\") return url; // already a path terminated by slash

    var last_slash = url.lastIndexOf("/");
    if (last_slash === -1) last_slash = url.lastIndexOf("\\");
    if (last_slash === -1) return ""; // neither slash found, assume no path (e.g. "file.ext" returns "" as path)

    return url.substr(0, last_slash + 1);
  }

  // Determine base URL of document.
  var baseElem = document.querySelector("base");
  var baseHref = baseElem && baseElem.hasAttribute("href") ? baseElem.getAttribute("href") : "";

  // If there is a base href, ensure it is of the form 'path/' (not '/path', 'path' etc)
  if (baseHref) {
    if (baseHref.startsWith("/")) baseHref = baseHref.substr(1);
    if (!baseHref.endsWith("/")) baseHref += "/";
  }
  function GetBaseURL() {
    return GetPathFromURL(location.origin + location.pathname) + baseHref;
  }
  function FetchAs(url, responseType) {
    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
          resolve(xhr.response);
        } else {
          reject(new Error("Failed to fetch '" + url + "': " + xhr.status + " " + xhr.statusText));
        }
      };
      xhr.onerror = reject;
      xhr.open("GET", url);
      xhr.responseType = responseType;
      xhr.send();
    });
  }
  function _AddImport(url, preFetchedDoc, rootContext, progressObject) {
    /* eslint-disable no-unused-vars */
    var isRoot = false;
    /* eslint-enable no-unused-vars */

    // The initial import creates a root context, which is passed along to all sub-imports.
    if (!rootContext) {
      isRoot = true;
      rootContext = {
        alreadyImportedUrls: new Set(),
        // for deduplicating imports
        stylePromises: [],
        scriptPromises: [],
        progress: progressObject || {} // progress written to this object (loaded, total)
      };
      rootContext.progress.loaded = 0;
      rootContext.progress.total = 1; // add root import
    }

    // Each import also tracks its own state with its own context.
    /* eslint-disable no-unused-vars */
    var context = {
      importDoc: null,
      baseUrl: GetPathFromURL(url),
      dependencies: []
    };
    /* eslint-enable no-unused-vars */

    // preFetchedDoc is passed for sub-imports which pre-fetch their documents as an optimisation. If it's not passed,
    // fetch the URL to get the document.
    var loadDocPromise;
    if (preFetchedDoc) loadDocPromise = Promise.resolve(preFetchedDoc);else loadDocPromise = FetchAs(url, "document");
    return loadDocPromise.then(function (doc) {
      // HACK: in Edge, due to this bug: https://developer.microsoft.com/en-us/microsoft-edge/platform/issues/12458748/
      // the fetched document URL is incorrect. doc.URL is also read-only so cannot directly be assigned. To work around this,
      // calculate the correct URL and use Object.defineProperty to override the returned document URL.
      Object.defineProperty(doc, "URL", {
        value: new URL(url, GetBaseURL()).toString()
      });

      // we don't need the `body` wrapper, so return the first child
      return doc.body.firstChild;
    });
  }
  function AddImport(url, async, progressObject) {
    // Note async attribute ignored (was only used for old native implementation).
    return _AddImport(url, null, null, progressObject);
  }
  window["addImport"] = AddImport;
}

// embl-conditional-edit

/**
 * Invoke emblConditionalEditDetectParam scopped to objects where
 * data-embl-js-conditional-edit is present
 * This will be dynamically run once emblContentHubSignalFinished is triggered.
 */
function emblConditionalEdit() {
  var emblConditionalEditItems = document.querySelectorAll("[data-embl-js-conditional-edit]");
  if (!emblConditionalEditItems) {
    // exit: lists not found
    return;
  }
  if (emblConditionalEditItems.length == 0) {
    // exit: lists not found
    return;
  }
  Array.prototype.forEach.call(emblConditionalEditItems, function (element) {
    emblConditionalEditDetectParam(location.href, element);
  });
}

/**
 * Detects `?embl-conditional-edit=enabled` or `?embl-conditional-edit=1` or ?embl-conditional-edit=true`
 * and adds `.embl-coditional-edit__enabled` to display the edit links
 * @param {string} [url] - the url to check for an enabling param
 * @param {element} [element] - the scopped element to be processed
 * @param {string} [referrer] - what part of the page is asking for a check, we pass this to avoid recursion
 */
function emblConditionalEditDetectParam(url, element, referrer) {
  var captured = /embl-conditional-edit=([^&]+)/.exec(url);
  if (captured == null && referrer != "iframe") {
    // value not found

    // also try against any parent iframe url
    if (window.self !== window.top) {
      console.log(url, parent.window.location.href);
      emblConditionalEditDetectParam(parent.window.location.href, element, "iframe");
    }
    return;
  }
  captured = captured || false; // avoid null
  captured = captured[1];
  if (captured == "1" || captured == "enabled" || captured == "true") {
    element.className += " " + "embl-coditional-edit__enabled";
  }
}

// embl-notifications

/**
  * After a notifications has been chosen, build it and insert into the document
  * @example emblNotificationsInject(notification)
  * @param {object} [message] - An object to be show on a page
  */
function emblNotificationsInject(message) {
  var output = document.createElement("div");

  // @todo:
  // - add support in contentHub for extra button text, link

  // preparation
  message.body = message.body.replace(/<[/]?[p>]+>/g, " "); // no <p> tags allowed in inline messages, preserve a space to not collide words
  // add vf-link to link
  message.body = message.body.replaceAll("<a href=", "<a class=\"vf-banner__link\" href="); // we might need a more clever regex, but this should also avoid links that already have a class
  // Learn more link is conditionally shown
  if (message.field_notification_link) {
    message.body = "".concat(message.body, " <a class=\"vf-banner__link\" href=\"").concat(message.field_notification_link, "\">Learn more</a>");
  }
  // custom button text
  message.field_notification_button_text = message.field_notification_button_text || "Close notice";
  // notification memory and cookie options
  if (message.field_notification_cookie == "True") {
    output.dataset.vfJsBannerCookieName = message.cookieName;
    output.dataset.vfJsBannerCookieVersion = message.cookieVersion;
    if (message.field_notification_auto_accept == "True") {
      output.dataset.vfJsBannerAutoAccept = true;
    }
  }
  if (message.field_notification_position == "fixed") {
    output.classList.add("vf-banner", "vf-banner--fixed", "vf-banner--bottom", "vf-banner--notice");
    output.dataset.vfJsBanner = true;
    output.dataset.vfJsBannerState = message.field_notification_presentation;
    output.dataset.vfJsBannerButtonText = message.field_notification_button_text;
    // These features are not yet supported by the notification content type in the EMBL contentHub
    // output.dataset.vfJsBannerExtraButton = "<a href='#'>Optional button</a><a target='_blank' href='#'>New tab button</a>";
    output.innerHTML = "\n      <div class=\"vf-banner__content | vf-grid\" data-vf-js-banner-text>\n        <p class=\"vf-text vf-text-body--2\">".concat(message.body, "</p>\n      </div>");
    var target = document.body.firstChild;
    target.parentNode.prepend(output);
    vfBanner();
  } else if (message.field_notification_position == "inline") {
    output.classList.add("vf-grid", "vf-u-margin__top--400"); // we wrap in vf-grid for layout
    // we use vf-u-margin__top--400 as this element is usually inserted inside a contentHub wrapper and not affected by body.vf-stack
    // if vf-stack is set, this will have no practical affect
    output.innerHTML = "\n      <div class=\"vf-banner vf-banner--alert vf-banner--info | vf-content\" data-vf-google-analytics-region=\"notifications-banner\">\n        <div class=\"vf-banner__content\">\n          <p class=\"vf-banner__text\">".concat(message.body, "</p>\n        </div>\n      </div>");

    // insert after `vf-header` or at after `vf-body`
    // @todo: add support for where "inline" message should be shown
    // @todo: don't rely on the presence of vf-header to show inline notification, maybe <div data-notifications-go-here>
    var _target = document.getElementsByClassName("vf-header");
    if (_target.length > 0) {
      _target[0].parentNode.insertBefore(output, _target[0].nextSibling);
    } else {
      // if no vf-header, perhaps there's a masthead-black-bar?
      var _target2 = document.getElementsByClassName("masthead-black-bar");
      if (_target2.length > 0) {
        _target2[0].parentNode.insertBefore(output, _target2[0].nextSibling);
      } else {
        // if no vf-header, show at vf-body
        // @thought: we might instead make this show as "top"
        var _target3 = document.getElementsByClassName("vf-body");
        if (_target3.length > 0) {
          // output.classList.add('vf-u-grid--reset');
          _target3[0].prepend(output);
        } // if still no success, we soft fail
      }
    }
  } else if (message.field_notification_position == "top") {
    output.classList.add("vf-banner", "vf-banner--fixed", "vf-banner--top", "vf-banner--phase");
    output.dataset.vfJsBanner = true;
    output.dataset.vfJsBannerState = message.field_notification_presentation;
    output.dataset.vfJsBannerButtonText = message.field_notification_button_text;
    // These features are not yet supported by the notification content type in the EMBL contentHub
    // output.dataset.vfJsBannerExtraButton = "<a href='#'>Optional button</a><a target='_blank' href='#'>New tab button</a>";
    output.innerHTML = "\n      <div class=\"vf-banner__content\" data-vf-js-banner-text>\n        <p class=\"vf-banner__text\">".concat(message.body, "</p>\n      </div>");
    var _target4 = document.body.firstChild;
    _target4.parentNode.prepend(output);
    vfBanner();
  }

  // console.log('emblNotifications, showing:', message);
}

/**
  * The global function for this component
  * Note: if you use embl-content-hub-loader, it will automatically invoke emblNotifications
  * @example emblNotifications(currentHost, currentPath)
  * @param {string} [currentHost] - a host url www.embl.org
  * @param {string} [currentPath] - a path /people/name
  */
function emblNotifications(currentHost, currentPath) {
  currentHost = currentHost || window.location.hostname;
  currentPath = currentPath || window.location.pathname;
  // don't treat `wwwdev` as distinct from `www`
  currentHost = currentHost.replace(/wwwdev/g, "www");

  // console.log('emblNotifications','Checking for notifications.');
  // console.log('emblNotifications, Current url info:', currentHost + "," + currentPath);

  // Process each message against a URLs
  function matchNotification(message, targetUrl) {
    var matchFound = false;
    if (message.hasBeenShown == true) {
      // console.warn('emblNotifications', 'This message has already been displayed on the page.')
      return;
    }

    // console.log('emblNotifications, targetUrl:', targetUrl);
    // console.log('emblNotifications, matching:', currentHost+currentPath);

    // Is there an exact match?
    matchFound = compareUrls(currentHost + currentPath, targetUrl);

    // Handle wildcard matches like `/about/*`
    if (targetUrl.slice(-1) == "*") {
      matchFound = compareUrls(currentHost + currentPath, targetUrl, true);
    }

    // if a match has been made on the current url path, show the message
    if (matchFound == true) {
      // console.log('emblNotifications: MATCH FOUND 🎉', targetUrl, currentHost, currentPath);
      message.hasBeenShown = true;
      emblNotificationsInject(message);
    }
  }

  // Handle string comparisons for URLs
  function compareUrls(url1, url2, isWildCard) {
    isWildCard = isWildCard || false;

    // we ignore case
    // we could probably optimise by moving this higher in the logic, but it's more maintainable to have it here
    url1 = url1.toLowerCase();
    url2 = url2.toLowerCase();

    // don't allow matches to end in `*`
    if (url1.slice(-1) == "*") url1 = url1.substring(0, url1.length - 1);
    if (url2.slice(-1) == "*") url2 = url2.substring(0, url2.length - 1);

    // don't allow matches to end in `/`
    if (url1.slice(-1) == "/") url1 = url1.substring(0, url1.length - 1);
    if (url2.slice(-1) == "/") url2 = url2.substring(0, url2.length - 1);

    // console.log('emblNotifications, comparing:', url1 + "," + url2);

    if (url1 == url2) {
      return true;
    } else if (isWildCard) {
      // console.log('emblNotifications, wildcard comparison:', url1, url2)
      if (url1.indexOf(url2) == 0) {
        // only success if match found from beginning of string
        // we only support wildcards on the right side
        // console.log('emblNotifications: WILDCARD MATCH FOUND 🎉');
        return true;
      }
    }
    return false;
  }

  // Process each message, and its URL fragments
  function processNotifications(messages) {
    // console.log('emblNotifications', messages);

    // Process each message
    for (var index = 0; index < messages.length; index++) {
      var currentMessage = messages[index];

      // track if a message has already been show on the page
      // we want to be sure a message isn't accidently shown twice
      currentMessage.hasBeenShown = false;

      // Process the URLs for each path in a message
      var currentUrls = currentMessage.field_notification_urls.split(",");
      for (var indexUrls = 0; indexUrls < currentUrls.length; indexUrls++) {
        var url = currentUrls[indexUrls].trim();
        matchNotification(currentMessage, url); // pass the notification and active url to compare
      }
    }
  }

  // Utility to fetch a file, process the JSON
  function loadRemoteNotifications(file) {
    // console.log('emblNotifications','Opening URL :' + file);
    if (window.XMLHttpRequest) {
      var xmlhttp = new XMLHttpRequest();
    }
    xmlhttp.open("GET", file, true);
    xmlhttp.onload = function () {
      if (xmlhttp.readyState === 4) {
        if (xmlhttp.status === 200) {
          // eval(xmlhttp.responseText);
          // var m = m || ''; // make sure the message isn't null
          processNotifications(eval(xmlhttp.responseText));
        } else {
          console.error(xmlhttp.statusText);
        }
      }
    };
    xmlhttp.onerror = function () {
      console.error(xmlhttp.statusText);
    };
    xmlhttp.send(null);
  }

  // Bootstrap the message fetching
  // If on dev, reference dev server
  if (window.location.hostname.indexOf("wwwdev.") === 0) {
    loadRemoteNotifications("https://wwwdev.embl.org/api/v1/notifications?_format=json&source=contenthub");
  } else if (window.location.hostname.indexOf("localhost") === 0) {
    loadRemoteNotifications("https://wwwdev.embl.org/api/v1/notifications?_format=json&source=contenthub");
  } else {
    loadRemoteNotifications("https://www.embl.org/api/v1/notifications?_format=json&source=contenthub");
  }

  // Check fallback notifications
  loadRemoteNotifications("https://embl-communications.github.io/embl-notifcations-fallback/notifications.js");
}

// Add this to your ./components/vf-component-rollup/scripts.js
// import { emblNotifications } from '../components/raw/embl-notifications/embl-notifications.js';
// And invoke it
// Note: if you use embl-content-hub-loader, it will automatically invoke emblNotifications
// emblNotifications();

// embl-content-hub-loader__fetch

/**
 * Fetch html links from content.embl.org
 */
function emblContentHubFetch() {
  // Some JS utilities
  // via https://stackoverflow.com/a/32135318
  Element.prototype.appendBefore = function (element) {
    element.parentNode.insertBefore(this, element);
  }, false;
  Element.prototype.appendAfter = function (element) {
    element.parentNode.insertBefore(this, element.nextSibling);
  }, false;

  /**
   * Get the number of days between two dates.
   */
  function days_between(date1, date2) {
    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24;

    // Convert both dates to milliseconds
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();

    // Calculate the difference in milliseconds
    var difference_ms = Math.abs(date1_ms - date2_ms);

    // Convert back to days and return
    return Math.round(difference_ms / ONE_DAY) + 1;
  }

  // A list of all the links
  var emblContentHubLinks = document.querySelectorAll("[data-embl-js-content-hub-loader]");
  var emblContentHubLinkLoadingProgress = {};
  var emblContentHubShowTimers = false;

  // Handle the import of each element
  for (var i = 0; i < emblContentHubLinks.length; ++i) {
    (function () {
      var linkPosition = i;

      // track time it takes for link to be shown
      if (emblContentHubShowTimers) {
        console.time("timer for import " + linkPosition);
      }

      // await the load of the html import from the polyfill
      // note: we use polyfill in all cases; see https://github.com/visual-framework/vf-core/issues/508
      emblContentHubAwaitLoading(emblContentHubLinks[linkPosition], linkPosition);
    })();
  }

  // If nothing to import
  if (emblContentHubLinks.length == 0) {
    emblContentHubSignalFinished();
  }

  // Add a class to the body once the last item has been processed
  function emblContentHubSignalFinished() {
    // @todo, shouldn't require the body element
    document.querySelectorAll("body")[0].classList.add("embl-content-hub-loaded");

    // if the JS to run embl-conditional-edit is present, run it now
    if (typeof emblConditionalEdit === "function") {
      emblConditionalEdit();
    }

    // if the JS to run embl-notifications is present, run it now
    if (typeof emblNotifications === "function") {
      emblNotifications();
    }
  }

  // Dispatch load to the pollyfill
  function emblContentHubAwaitLoading(targetLink, position) {
    /* global addImport */
    // Docs: https://github.com/AshleyScirra/html-imports-polyfill#usage
    addImport(targetLink.href, null, emblContentHubLinkLoadingProgress).then(function (value) {
      emblContentHubGrabTheContent(targetLink, position, value);
      if (position + 1 == emblContentHubLinks.length) {
        emblContentHubSignalFinished();
      }
    });
  }

  // Generate a unique ID for the target element on the page
  function emblContentHubGenerateID(position) {
    return "contentDbItem" + ("0000" + position).slice(-5);
  }

  // Show the remote content
  function emblContentHubGrabTheContent(targetLink, position, exportedContent) {
    // pickup the "meat" of the exported content
    exportedContent = exportedContent || targetLink.import.querySelector(".vf-content-hub-html");

    // make sure we have something
    if (!exportedContent) {
      console.log("No content found for this import, exiting. The import may have already been preformed.", targetLink);
      return;
    }

    // if there is just one child element and it is a div, use that
    // (this helps with css grid layout)
    if (exportedContent.childElementCount === 1 && exportedContent.firstElementChild.innerHTML.trimLeft().substr(0, 4) === "<div") {
      exportedContent = exportedContent.firstElementChild;
      exportedContent.classList.add("vf-content-hub-html");
      exportedContent.classList.add("vf-content-hub-html__derived-div");
    } else if (exportedContent.childNodes.length <= 3) {
      // if there are three or fewer child nodes this is likely a no-results reply
      // We'll still inject the content from the contentHub along with any passed "no matches" text
      var noContentMessage = targetLink.getAttribute("data-embl-js-content-hub-loader-no-content");
      if (noContentMessage !== "null") {
        if (noContentMessage == "true") {
          // use a default
          noContentMessage = "No content was found found for this query.";
        }
        var noContentMessageElement = document.createElement("div");
        noContentMessageElement.classList.add("vf-text");
        noContentMessageElement.classList.add("embl-content-hub-html__no-content-found");
        noContentMessageElement.innerHTML = noContentMessage;
        exportedContent.appendChild(noContentMessageElement.firstChild);
      }

      // if data-embl-js-content-hub-loader-no-content-hide is true or has a class, hide accordingly
      var noContentHideBehavior = targetLink.getAttribute("data-embl-js-content-hub-loader-no-content-hide");
      if (noContentHideBehavior) {
        if (noContentHideBehavior == "true") {
          // if true, just hide the response
          exportedContent.classList.add("vf-u-display-none");
        } else {
          // otherwise hide any element specified
          document.querySelector(noContentHideBehavior).classList.add("vf-u-display-none");
        }
      } // END noContentHideBehavior
    } // END exportedContent.childElementCount

    var contentID = emblContentHubGenerateID(position);

    // where does the content go?
    if (targetLink.dataset.target === "self") {
      // if element already exists, remove it
      var oldElement = document.getElementById(contentID);
      if (oldElement) {
        oldElement.innerHTML = exportedContent.innerHTML;
      } else {
        // give content an ID
        exportedContent.setAttribute("id", contentID);
        exportedContent.classList.add(contentID);
        // just insert the new content
        exportedContent.appendAfter(targetLink);
      } // end if oldElement
    } else {
      var targetLocation = document.querySelector("." + targetLink.dataset.target);
      // exportedContent.appendAfter(targetLocation);
      targetLocation.classList.add(contentID);
      targetLocation.innerHTML = exportedContent.innerHTML;
    }

    // display how long it took to load
    if (emblContentHubShowTimers) {
      console.timeEnd("timer for import " + position);
    }
    emblContentHubAssignClasses(targetLink, position);
    emblContentHubUpdateDatesFormat(position);

    // run JS for some components on content, if they exist
    // note: why do we use "try" here?
    // we would use `typeof(vfBanner)` but if the function is not present it becomes aliased as `vfBanner.vfBanner`,
    // so this `try` method is more reliable
    try {
      vfBanner(targetLocation);
    } catch (error) {
      console.warn("emblContentHubLoader", "vfBanner not found, any contentHub banner-based content will not correctly render.");
    }
    try {
      vfTabs(targetLocation);
    } catch (error) {
      console.warn("emblContentHubLoader", "vfTabs not found, any contentHub tabs-based content will not correctly render.");
    }
    // don't run breadcrumbs as part of contenthub, use case is different
    // if (typeof(emblBreadcrumbs) === 'function') {
    //   emblBreadcrumbs(); // no scope for emblBreadcrumbs
    // }
  }

  // Enable class injection after loading contents
  // ... for all those edge cases
  // Background: https://gitlabci.ebi.ac.uk/emblorg/backlog/issues/82
  // Sample:
  //  <link rel="import" href="url" data-target="self"
  //        data-inject-class="vf-grid vf-grid__col-2"
  //        data-inject-class-target="ul"
  //        data-embl-js-content-hub-loader>
  //  This would make the ul a two-column grid.
  function emblContentHubAssignClasses(targetLink, position) {
    // var injectRequests = document.querySelectorAll('[data-inject-class][data-inject-class-target]');
    //
    // for (var i = 0; i < injectRequests.length; ++i) {

    var classesToInject = targetLink.getAttribute("data-inject-class");
    var targetSelectorToInject = targetLink.getAttribute("data-inject-class-target");
    if (classesToInject && targetSelectorToInject) {
      // Limit scope to the imported element
      var targetElement = document.querySelector("." + emblContentHubGenerateID(position)).querySelector(targetSelectorToInject);

      // We can't inject space separated classes to we need to split it into arrays and add one by one.
      classesToInject = classesToInject.split(" ");
      for (var classNumber = 0; classNumber < classesToInject.length; classNumber++) {
        targetElement.classList.add(classesToInject[classNumber]);
      }
    }
  }

  /**
   * Update the format of close date.
   */
  function emblContentHubUpdateDatesFormat(position) {
    var dateRemainingList = document.querySelector("." + emblContentHubGenerateID(position)).querySelectorAll(".date-days-remaining");
    var todayDate = new Date();
    if (dateRemainingList.length > 0) {
      for (var dateRemainingIndex = 0; dateRemainingIndex < dateRemainingList.length; dateRemainingIndex++) {
        var dateValue = parseInt(dateRemainingList[dateRemainingIndex].getAttribute("data-datetime")) * 1000;
        dateValue = new Date(dateValue);
        var numberOfDiffDays = days_between(dateValue, todayDate);
        // Update to 'Closes in 6 Days.' format if number of days is less than 30 days.
        if (numberOfDiffDays < 30 && numberOfDiffDays > 1) {
          dateRemainingList[dateRemainingIndex].innerHTML = "Closes in " + "<span>" + numberOfDiffDays + " Days.</span>";
        }
        if (numberOfDiffDays == 1) {
          dateRemainingList[dateRemainingIndex].innerHTML = "Closes in " + "<span>" + numberOfDiffDays + " Day.</span>";
        }
      }
    }
  }
}

// embl-content-hub-loader

function emblContentHub() {
  // 1. make sure we have imports or a polyfill
  emblContentHubLoaderHtmlImports();

  // 2. import the content
  emblContentHubFetch();
}

// embl-content-meta-properties

// In addition to being queried by other components' JS, this could
// also add classes to a page to affect the overall look of a page.

/**
 * Read metaProperties from page's metatags
 * @example emblContentMetaProperties_Read()
 */
function emblContentMetaProperties_Read() {
  var metaProperties = {};
  // <!-- Content descriptors -->
  // <meta name="embl:who" content="{{ meta-who }}"> <!-- the people, groups and teams involved -->
  // <meta name="embl:what" content="{{ meta-what }}"> <!-- the activities covered -->
  // <meta name="embl:where" content="{{ meta-where }}"> <!-- at which EMBL sites the content applies -->
  // <meta name="embl:active" content="{{ meta-active }}"> <!-- which of the who/what/where is active -->
  metaProperties.who = metaProperties.who || document.querySelector("meta[name='embl:who']");
  metaProperties.what = metaProperties.what || document.querySelector("meta[name='embl:what']");
  metaProperties.where = metaProperties.where || document.querySelector("meta[name='embl:where']");
  metaProperties.active = metaProperties.active || document.querySelector("meta[name='embl:active']");

  // <!-- Content role -->
  // <meta name="embl:utility" content="-8"> <!-- if content is task and work based or if is meant to inspire -->
  // <meta name="embl:reach" content="-5"> <!-- if content is externally (public) or internally focused (those that work at EMBL) -->
  metaProperties.utility = metaProperties.utility || document.querySelector("meta[name='embl:utility']");
  metaProperties.reach = metaProperties.reach || document.querySelector("meta[name='embl:reach']");

  // <!-- Page infromation -->
  // <meta name="embl:maintainer" content="{{ meta-maintainer }}"> <!-- the contact person or group responsible for the page -->
  // <meta name="embl:last-review" content="{{ meta-last-review }}"> <!-- the last time the page was reviewed or updated -->
  // <meta name="embl:review-cycle" content="{{ meta-review-cycle }}"> <!-- how long in days before the page should be checked -->
  // <meta name="embl:expiry" content="{{ meta-expiry }}"> <!-- if there is a fixed point in time when the page is no longer relevant -->
  metaProperties.maintainer = metaProperties.maintainer || document.querySelector("meta[name='embl:maintainer']");
  metaProperties.lastReview = metaProperties.lastReview || document.querySelector("meta[name='embl:last-review']");
  metaProperties.reviewCycle = metaProperties.reviewCycle || document.querySelector("meta[name='embl:review-cycle']");
  metaProperties.expiry = metaProperties.expiry || document.querySelector("meta[name='embl:expiry']");
  for (var key in metaProperties) {
    if (metaProperties[key] != null && metaProperties[key].getAttribute("content").length != 0) {
      metaProperties[key] = metaProperties[key].getAttribute("content");
    } else {
      metaProperties[key] = 'notSet';
    }
  }
  return metaProperties;
}

// embl-breadcrumbs-lookup

// to hold the EMBL taxonomy
var emblTaxonomy = {};

// placeholders for our new breadcrumbs
var emblBreadcrumbPrimary = document.createElement("ul");
emblBreadcrumbPrimary.classList.add("vf-breadcrumbs__list", "vf-list", "vf-list--inline");
var emblBreadcrumbRelated = document.createElement("ul");
emblBreadcrumbRelated.classList.add("vf-breadcrumbs__list", "vf-breadcrumbs__list--related", "vf-list", "vf-list--inline");

// we store the primarily breadcrumb so it can be accessed by related crumbs, if needed
var primaryBreadcrumb;

/**
 * Look up a breadcrumb by its uuid and return the entry
 * @example emblBreadcrumbLookupByUuid(uuid)
 * @param {string} [uuid]  - the uuid of a term
 */
function emblBreadcrumbLookupByUuid(uuid) {
  // console.log('emblBreadcrumbLookupByUuid',uuid);
  if (emblTaxonomy.terms[uuid]) {
    // console.log('emblBreadcrumbLookupByUuid',emblTaxonomy.terms[uuid]);
    return emblTaxonomy.terms[uuid];
  }
}

/**
 * Take any appropriate actions depending on present metaTags
 * @example emblBreadcrumbsLookup()
 * @param {object} [metaProperties] - if you do not have meta tags on the page,
 *                                    you can explicitly pass options
 */
function emblBreadcrumbsLookup(metaProperties) {
  var emblBreadcrumbTarget = document.querySelectorAll("[data-embl-js-breadcrumbs-lookup]");
  if (emblBreadcrumbTarget.length === 0) {
    // console.warn('There is no `[data-embl-js-breadcrumbs-lookup]` in which to insert the breadcrumbs; exiting');
    return false;
  }
  if (emblBreadcrumbTarget.length > 1) {
    console.warn("There is more than one `[data-embl-js-breadcrumbs-lookup]` in which to insert the breadcrumbs; continuing but only the first element will be updated.");
  }
  if (metaProperties.active == "notSet") {
    // @todo: we could infer the active breadcrumb if only one is passed
    console.warn("There is no active EMBL breadcrumb specified, cannot proceed looking up breadcrumbs.");
    return false;
  }
  var majorFacets = ["who", "what", "where"];

  // do the primary breadcrumb first
  emblBreadcrumbAppend(emblBreadcrumbTarget, metaProperties[metaProperties.active], metaProperties.active, "primary");

  // do the non-primary meta terms
  // @todo: we probably shouldn't do related if there is no primary
  for (var i = 0; i < majorFacets.length; i++) {
    if (majorFacets[i] != metaProperties.active) {
      emblBreadcrumbAppend(emblBreadcrumbTarget, metaProperties[majorFacets[i]], majorFacets[i], "related");
    }
  }

  // make a 'related' label
  var relatedLabel = document.createElement("span");
  relatedLabel.innerHTML = "Related:";
  relatedLabel.classList.add("vf-breadcrumbs__heading");

  // If no related terms were found, hide the related label
  // we only hide it as we could add related terms later
  if (emblBreadcrumbRelated.childNodes.length == 0) {
    relatedLabel.classList.add("vf-u-display-none");
  }

  // now that we've processed all the meta properties, insert our rendered breadcrumbs
  emblBreadcrumbTarget[0].innerHTML = emblBreadcrumbPrimary.outerHTML + relatedLabel.outerHTML + emblBreadcrumbRelated.outerHTML;
}

/**
 * Get the EMBL taxonomy json from the ContentHub
 * @example emblGetTaxonomy()
 * @param {string} [url] - URL to pull the taxonomy from
 */
function emblGetTaxonomy(url) {
  /* eslint-disable no-redeclare */
  var url = url || "https://www.embl.org/api/v1/pattern.json?pattern=embl-ontology&source=contenthub";
  /* eslint-disable no-redeclare */

  return new Promise(function (resolve, reject) {
    // Do the usual XHR stuff
    var req = new XMLHttpRequest();
    req.open("GET", url);
    req.onload = function () {
      // This is called even on 404 etc
      // so check the status
      if (req.status == 200) {
        // @todo: some sort of caching here, perhaps we write to local storage.
        // @todo: abstract this out into its own `embl-taxonomy` component?
        // capture the taxonomy in the global var
        emblTaxonomy = JSON.parse(req.response);

        // Resolve
        resolve();
      } else {
        // Otherwise reject with the status text
        // which will hopefully be a meaningful error
        reject(Error(req.statusText));
      }
    };

    // Handle network errors
    req.onerror = function () {
      reject(Error("Error loading ontology"));
    };

    // Make the request
    req.send();
  });
}

/**
 * Receive a term and its context and create a breadcrumb
 * @example emblBreadcrumbAppend(breadcrumbTarget,term,facet,type)
 * @param {dom elements} [breadcrumbTarget]  - elements with data-embl-js-breadcrumbs-lookup
 * @param {string} [termName]  - the taxonomy item found, e.g. `Cancer`
 * @param {string} [facet] - the facet of the taxonomy (`who`, `what` or `where`)
 * @param {string} [type]  - if this is a `primary` or `related` path
 */
function emblBreadcrumbAppend(breadcrumbTarget, termName, facet, type) {
  // console.log('Processing breadcrumb for:', termName + ', ' + facet + ', ' + type);

  function getCurrentTerm(termName) {
    var termObject; // store the match
    if (termName === "EMBL") termName = "All EMBL sites"; // hack as we're not using IDs

    // if a term has not been passed, attempt to use the primary term's parent information
    // @todo: add a flag to explicitly "dontLookup" or "doLookup"
    if (termName == "notSet") {
      termName = ""; // we'll either find a positive termObject or not show anything
      // console.log('here',primaryBreadcrumb)
      if (primaryBreadcrumb.parents) {
        if (primaryBreadcrumb.parents[facet]) {
          termName = primaryBreadcrumb.parents[facet];
        }
      }
    }

    // if using a `string/NameOfThing` value, not accordingly
    if (termName.indexOf("string/") >= 0) {
      console.warn("embl-js-breadcrumbs-lookup: using a passed string value to make breadcrumbs " + termName);
      termName = termName.replace("string/", "");
    }

    // scan through all terms and find a match, if any
    function emblBreadcrumbLookup(termName) {
      // @todo: if a UUID meta property is set, use that

      // if it's UUID match we use that
      termObject = emblBreadcrumbLookupByUuid(termName);
      if (typeof termObject != "undefined") {
        return; //exit
      }

      // We prefer profiles
      Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
        var term = emblTaxonomy.terms[termId];
        if (term.type == "profile") {
          if (term.name === termName) {
            termObject = term;
            return; //exit
          }
        }
      });

      // If no profile found, match other types of taxonomy entries
      if (typeof termObject === "undefined") {
        Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
          var term = emblTaxonomy.terms[termId];
          if (term.type != "profile") {
            if (term.name === termName) {
              termObject = term;
              return; //exit
            }
          }
        });
      }

      // If there's still no match, see if we can find a matching display name
      // @todo: this is an easy win but creates messy matching, but maybe that's ok if you're not using UUID
      // There's a risk of multiple "training" entries

      // We prefer profiles
      Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
        var term = emblTaxonomy.terms[termId];
        if (term.type == "profile") {
          if (term.name_display === termName) {
            termObject = term;
            return; //exit
          }
        }
      });

      // If no profile found, match other types of taxonomy entries
      if (typeof termObject === "undefined") {
        Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
          var term = emblTaxonomy.terms[termId];
          if (term.type != "profile") {
            if (term.name_display === termName) {
              termObject = term;
              return; //exit
            }
          }
        });
      }
    }

    // don't scan for junk matches
    if (termName != "notSet" && termName != "" && termName != "none") {
      emblBreadcrumbLookup(termName);
    }

    // Validation and protection
    // we never want to return undefined
    if (termObject == undefined || termObject == null) {
      termObject = {};

      // generate fallback links
      // console.warn('embl-js-breadcrumbs-lookup: No matching breadcrumb found for `' + termName + '`; Will formulate a URL.');
      if (facet == "who") {
        // fallback for people: search people directory
        termObject.url = "https://www.embl.org/search/#stq=" + termName.replace(/[\W_]+/g, " ").replace(/\s+/g, "-").toLowerCase() + "&activeFacet=People%20directory&origin=breadcrumbTermNotFound";
      } else {
        // otherwise try and search
        termObject.url = "https://www.embl.org/search/#stq=" + termName + "&taxonomyFacet=" + facet + "&origin=breadcrumbTermNotFound"; // if no link specified, do a search
      }
      termObject.name_display = termName;
      termObject.uuid = "null";
      termObject.uuid = [];
    } else if (typeof termObject.url == "undefined") {
      // if entry was found but no link specified, generate a url for a search
      var urlFacet = "";
      if (termObject.primary != undefined) {
        // prepare a search facet if available
        urlFacet = "&taxonomyFacet=" + termObject.primary;
      }
      termObject.url = "https://www.embl.org/search/#stq=" + termObject.name + urlFacet + "&origin=breadcrumbTaxonomy";
    }
    return termObject;
  }

  /**
   * Take a term and get its parent term UUID
   * todo: this lookup is, perhaps, flawed as it gives us each ancestor, irregardless
   *       of it's who/what/where path, but maybe this will provide an interesting
   *       "odeur d'information"
   * @example getBreadcrumbParentTerm(parents,context)
   * @param {array} [parents]  - array of UUIDs
   * @param {string} [facet] - who, what, where
   * @param {object} [lastParent] - term object to prevent recursion, optional
   */
  function getBreadcrumbParentTerm(parents, facet, lastParent) {
    // var parentTodos = {
    //   // 1: 'Respect the parent term context: who/what/where'
    //   // 2: 'scan the taxonomy and get any parent IDs',
    //   // 3: 'if there are parent IDs, add breadcrumb and set URL',
    //   // 4: 'if parent was found, does the parent have a parent?'
    // };
    // console.log('Todos for getBreadcrumbParentTerm():',parentTodos);

    /* eslint-disable no-redeclare */
    var lastParent = lastParent || {}; // track last insertion to prevent recursion
    /* eslint-enable no-redeclare */

    if (parents == undefined || parents == null) {
      // no parent breadcrumb preset, exiting
      return;
    }
    function insertParent(activeParent) {
      if (activeParent == undefined || activeParent == null) {
        console.warn("embl-js-breadcrumbs-lookup: No matching parent found; Stopping parent lookup.");
        return;
      }
      activeParent.url = activeParent.url || "#no_url_specified";
      if (activeParent.name.indexOf(" root term") > 0) {
        // if we've reached a root term, abort lookups and don't insert a root term as a crumb
        return;
      }
      if (activeParent.primary == facet) {
        // only insert crumb if it respects the original term context: who/what/where
        if (activeParent.uuid != lastParent.uuid) {
          // no recursive output
          emblBreadcrumbPrimary.innerHTML = formatBreadcrumb(activeParent.name_display, activeParent.url, false) + emblBreadcrumbPrimary.innerHTML;
        }
      }

      // get parents of parent
      if (activeParent.parents) {
        if (activeParent.uuid != lastParent.uuid) {
          lastParent = activeParent;
          getBreadcrumbParentTerm(activeParent.parents, facet, lastParent);
        } else {
          console.log("embl-js-breadcrumbs-lookup", "Recursion in parent lookup. Check the EMBL.org Profile. Aborting lookup.");
          console.log("embl-js-breadcrumbs-lookup", "activeParent", activeParent);
          console.log("embl-js-breadcrumbs-lookup", "lastParent", lastParent);
        }
      }
    }
    var activeParent;
    if (parents[facet]) {
      // if a parent has structured who/what/where parents
      activeParent = emblTaxonomy.terms[parents[facet]];
      insertParent(activeParent);
    } else {
      // otherwise lookup each parent
      parents.forEach(function (parentId) {
        // recursive test
        // parentId = '0c79d36e-ed33-482d-a396-15a0b2bc4540';
        activeParent = emblTaxonomy.terms[parentId];
        insertParent(activeParent);
      });
    }
    return;
  }

  /**
   * Generate HTML for a new breadcrumb
   * @example formatBreadcrumb(term,breadcrumbUrl)
   * @param {string} [termName]  - the taxonomy string of the item, e.g. `Cancer`
   * @param {string} [breadcrumbUrl] - a fully formed URL, or 'null' to not make a link
   * @param {boolean} [current] - if the breadcrumb is the current page
   */
  function formatBreadcrumb(termName, breadcrumbUrl, current) {
    if (termName == "" || termName == "none") {
      // if no term, do nothing
      return "";
    }
    if (current) {
      current = " aria-current=\"location\"";
    }
    var newBreadcrumb = "<li class=\"vf-breadcrumbs__item\"" + current + ">";
    if (breadcrumbUrl && breadcrumbUrl !== "null" && breadcrumbUrl !== "#no_url_specified") {
      newBreadcrumb += "<a href=\"" + breadcrumbUrl + "\" class=\"vf-breadcrumbs__link\">" + termName + "</a>";
    } else {
      newBreadcrumb += termName;
    }
    newBreadcrumb += "</li>";
    return newBreadcrumb;
  }
  var currentTerm = getCurrentTerm(termName);
  /* eslint-disable no-unused-vars */
  var breadcrumbId = currentTerm.uuid,
    breadcrumbUrl = currentTerm.url,
    breadcrumbParents = currentTerm.parents,
    breadcrumbCurrent = false;
  /* eslint-enable no-unused-vars */

  // narrow down to the first matching element
  breadcrumbTarget = breadcrumbTarget[0];
  if (type == "primary") {
    // save it
    primaryBreadcrumb = currentTerm;
    var breadcrumbPath = new URL(breadcrumbUrl).pathname;
    var breadcrumbPathPrefix = ""; // if a crumb does or does not end in a "/" account for that
    if (breadcrumbPath.slice(-1) == "/") {
      breadcrumbPathPrefix == breadcrumbPath.slice(0, -1);
    } else {
      breadcrumbPathPrefix = breadcrumbPath + "/";
    }

    // check if the current breadcrumb is the current url
    if (breadcrumbPath == window.location.pathname || breadcrumbPathPrefix == window.location.pathname) {
      breadcrumbUrl = null;
      breadcrumbCurrent = true;
    }

    // add breadcrumb
    emblBreadcrumbPrimary.innerHTML += formatBreadcrumb(currentTerm.name_display, breadcrumbUrl, breadcrumbCurrent);

    // fetch parents for primary path
    getBreadcrumbParentTerm(breadcrumbParents, facet);
  } else if (type == "related") {
    // add breadcrumb
    emblBreadcrumbRelated.innerHTML += formatBreadcrumb(currentTerm.name_display, breadcrumbUrl, breadcrumbCurrent);
  }
}
function emblBreadcrumbs() {
  //clear existing breadcrumbs
  emblBreadcrumbPrimary.innerText = "";
  emblBreadcrumbRelated.innerText = "";

  // We start the breadcrumbs by first getting the EMBL taxonomy.
  emblGetTaxonomy().then(function () {
    // console.log('emblTaxonomy', emblTaxonomy);

    // Preprocess the emblTaxonomy for some cleanup tasks
    Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
      var term = emblTaxonomy.terms[termId];
      // If `name_display` is not set, use the internal name
      if (term.name_display === "") term.name_display = term.name;
      // handle null URL
      if (term.url === "") term.url = "#no_url_specified";
    });

    // Invoke embl-content-meta-properties function to pull tags from page
    emblBreadcrumbsLookup(emblContentMetaProperties_Read());
  }, function (error) {
    console.warn("Failed to get EMBL ontology", error);
    var emblBreadcrumbTarget = document.querySelectorAll("[data-embl-js-breadcrumbs-lookup]");
    if (emblBreadcrumbTarget.length > 0) {
      emblBreadcrumbTarget[0].innerHTML = "<!-- Breadcrumbs failed to render due to network issue -->";
    }
  });
}

// Prepend polyfill for IE
// Source: https://github.com/jserz/js_piece/blob/master/DOM/ParentNode/prepend()/prepend().md
(function (arr) {
  arr.forEach(function (item) {
    /* eslint-disable no-prototype-builtins */
    if (item.hasOwnProperty("prepend")) {
      return;
    }
    /* eslint-enable no-prototype-builtins */
    Object.defineProperty(item, "prepend", {
      configurable: true,
      enumerable: true,
      writable: true,
      value: function prepend() {
        var argArr = Array.prototype.slice.call(arguments),
          docFrag = document.createDocumentFragment();
        argArr.forEach(function (argItem) {
          var isNode = argItem instanceof Node;
          docFrag.appendChild(isNode ? argItem : document.createTextNode(String(argItem)));
        });
        this.insertBefore(docFrag, this.firstChild);
      }
    });
  });
})([Element.prototype, Document.prototype, DocumentFragment.prototype]);

// Run it on default
// emblBreadcrumbs();

/*
 *
 * scripts.css
 * The Visual Framework kitchen sink of JavaScript.
 * Import this as a quick way to get *everything*,
 *
 */
vfBanner();
vfMastheadSetStyle();
var vfGaTrackOptions = {
  vfGaTrackPageLoad: false,
  vfGaTrackNetwork: {
    serviceProvider: 'dimension2',
    networkDomain: 'dimension3',
    networkType: 'dimension4'
  }
};
vfGaIndicateLoaded(vfGaTrackOptions);
vfTabs();
window.addEventListener("load", function () {
  initVFChatbot(config);
});
vfNavigationOnThisPage();
vfTree();
emblContentHub();
emblBreadcrumbs();
// if you use embl-content-hub-loader, it will automatically invoke emblNotifications
// emblNotifications();
