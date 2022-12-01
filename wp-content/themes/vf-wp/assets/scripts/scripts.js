'use strict'; // vf-banner
// Turn the below code snippet into a banner
// <div class="vf-banner vf-banner--fixed vf-banner--bottom vf-banner--notice"
// data-vf-js-banner
// data-vf-js-banner-state="persistent|dismissible|blocking" data-vf-js-banner-esc-close="y|n"
// data-vf-js-banner-cookie-name="{{data-service-id}}"
// data-vf-js-banner-cookie-version="{{data-protection-version}}"
// data-vf-js-banner-extra-button="<a href='#'>string1</a><a href='#'>string2</a>">
//   <div class="vf-banner__content | vf-grid">
//     <p class="vf-text vf-text--body-l">
//       This website uses cookies, and the limiting processing of your personal data to function. By using the site you are agreeing to this as outlined in our <a class="vf-link" href="JavaScript:Void(0);">Privacy Notice</a> and <a class="vf-link" href="JavaScript:Void(0);">Terms Of Use</a>.
//     </p>
//
//     <button class="vf-button vf-button--secondary">
//       {{vf-data-protection-banner__link}}
//     </button>
//   </div>
// </div>

/**
 * Clear the cooke. This is mostly a development tool.
 */

/* eslint-disable no-unused-vars */

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
  } // dismiss banner


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
  } // generate the banner component, js events


  Array.prototype.forEach.call(bannerList, function (banner) {
    // map the JS data attributes to our object structure
    var bannerRemapped = JSON.parse(JSON.stringify(banner.dataset));

    if (typeof banner.dataset.vfJsBannerId != "undefined") {// don't reactivate an already processed banner
    } else {
      bannerRemapped.vfJsBannerText = banner.querySelectorAll("[data-vf-js-banner-text]")[0].innerHTML;
      var uniqueId = Math.round(Math.random() * 10000000); // set an id to target this banner

      banner.setAttribute("data-vf-js-banner-id", uniqueId); // preserve the classlist

      bannerRemapped.classList = banner.querySelectorAll("[data-vf-js-banner-text]")[0].classList; // Make the banner come alive

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
  generatedBannerHtml += banner.vfJsBannerText; // What type of banner?

  if (banner.vfJsBannerState === "persistent") {// nothing more to do for persistent, you can't close it
  } else if (banner.vfJsBannerState === "dismissible") {// nothing more to do for dismissible
  } else if (banner.vfJsBannerState === "blocking") {
    console.warn("vf-banner: Note, the blocking implementation is not yet feature complete."); // escape only works when blocking

    if (banner.vfJsBannerEscClose === "y" || banner.vfJsBannerEscClose === "Y") {
      document.onkeydown = function (evt) {
        evt = evt || window.event;

        if (evt.keyCode == 27) {
          vfBannerConfirm(targetBanner, "null");
        }
      };
    }
  } // Split passed links into buttons
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
  } // if there is a vfJsBannerButtonText and banner is blocking or dismissible,
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

  generatedBannerHtml += "</div>"; // set the html of the banner

  targetBanner.innerHTML = generatedBannerHtml; // prep for cookie

  var vfBannerCookieNameAndVersion = "null";

  if (banner.vfJsBannerCookieName && banner.vfJsBannerCookieVersion) {
    vfBannerCookieNameAndVersion = banner.vfJsBannerCookieName + "_" + banner.vfJsBannerCookieVersion;
  } // utility to reset cookie when developing
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
  } // add appropriate padding to the page to not cover up content


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
      vfBannerClose(targetBanner); // exit, nothng more to do

      return;
    } // if banner is marked as auto-accept, set as read


    if (banner.vfJsBannerAutoAccept == "true") {
      if (banner.vfJsBannerState === "blocking" || banner.vfJsBannerState === "dismissible") {
        vfBannerSetCookie(vfBannerCookieNameAndVersion, true);
      }
    }
  }
} // By default this creates banners from HTML
// optionally you can programatically supply
// Target HTML
// `<div class="vf-banner vf-banner--fixed vf-banner--bottom vf-banner--notice"
//       data-vf-js-banner
//       data-vf-js-banner-id="32423"
//
// ></div>`
// var programaticalBanner = {
//   vfJsBanner: "",
//   vfJsBannerButtonText: "I agree, dismiss this banner",
//   vfJsBannerCookieName: "MyService",
//   vfJsBannerCookieVersion: "0.1",
//   vfJsBannerExtraButton: "<a href='#'>Optional button</a><a target='_blank' href='#'>New tab button</a>",
//   vfJsBannerId: "2352286",
//   vfJsBannerText: '<p class="vf-text vf-text--body-l">This website uses cookies, and the limiting processing of your personal data to function. By using the site you are agreeing to this as outlined in our <a class="vf-link" href="JavaScript:Void(0);">Privacy Notice</a> and <a class="vf-link" href="JavaScript:Void(0);">Terms Of Use</a>.</p>',
//   vfJsBannerState: "dismissible",
//   vfJsBannerAutoAccept: "true"
// };
// vfBannerInsert(programaticalBanner,'32423');
// vf-masthead

/**
  * This was a function for making background color of banner from image file name.
  */


function vfMastheadSetStyle() {
  console.warn("vfMasthead", "This component has been deprecated, you should remove it from your VF scripts.js rollup. https://github.com/visual-framework/vf-core/pull/1406/");
} // vf-analytics-google

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

  var el = document.querySelector("body"); // debug

  vfGaLogMessage('checking ' + numberOfGaChecks + ", limit: " + numberOfGaChecksLimit);
  numberOfGaChecks++; // If successful we set `data-vf-google-analytics-loaded` on the `body` to true.

  try {
    // unset our check
    if (el.getAttribute("data-vf-google-analytics-loaded") != "true") {
      vfGaIndicateUnloaded();
    } // check to see if gtag is loaded, and then if UA is loaded, and if neither, check once more (to a limit)


    if (typeof gtag !== "undefined") {
      vfGaLogMessage('ga4 found');

      if (el.getAttribute("data-vf-google-analytics-loaded") != "true") {
        el.setAttribute("data-vf-google-analytics-loaded", "true");
        vfGaInit(vfGaTrackOptions);
      }
    } else if (ga && ga.loaded) {
      vfGaLogMessage('ua found');

      if (el.getAttribute("data-vf-google-analytics-loaded") != "true") {
        el.setAttribute("data-vf-google-analytics-loaded", "true");
        vfGaInit(vfGaTrackOptions);
      }
    } else {
      vfGaLogMessage('GA tracking code not ready, scheduling another check');

      if (numberOfGaChecks <= numberOfGaChecksLimit) {
        setTimeout(function () {
          vfGaIndicateLoaded(vfGaTrackOptions, numberOfGaChecksLimit, numberOfGaChecks, checkTimeout);
        }, 900); // give a second check if GA was slow to load
      }
    }
  } catch (err) {
    vfGaLogMessage('error in vfGaIndicateLoaded');

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
  vfGaLogMessage('initing vfGaInit');
  /* eslint-disable no-redeclare*/

  var vfGaTrackOptions = vfGaTrackOptions || {};
  /* eslint-enable no-redeclare*/

  if (vfGaTrackOptions.vfGaTrackPageLoad == null) vfGaTrackOptions.vfGaTrackPageLoad = true; // Need help?
  // How to add dimension to your property
  // https://developers.google.com/analytics/devguides/collection/analyticsjs/custom-dims-mets
  // https://support.google.com/analytics/answer/2709829?hl=en

  if (typeof gtag === "undefined") {
    // if the site is still using legacy GA, set a dummy gtag function so we don't have to add a bunch of if statements
    vfGaLogMessage('GA4 dummy function has been set.');

    window.gtag = function () {};
  }

  if (typeof ga === "undefined") {
    // if the site is still using legacy GA, set a dummy gtag function so we don't have to add a bunch of if statements
    vfGaLogMessage('GA UA dummy function has been set.');

    window.ga = function () {};
  } // standard google analytics bootstrap
  // @todo: add conditional


  ga("set", "anonymizeIp", true); // For Gtag you should do this in your tracking snippet
  // https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization
  // Use the more robust "beacon" logging, when available
  // https://developers.google.com/analytics/devguides/collection/analyticsjs/sending-hits

  ga("set", "transport", "beacon"); // lookup metadata  <meta name="vf:page-type" content="category;pageTypeHere">
  // Pass your GA dimension with a `;` divider

  var pageType = vfGetMeta("vf:page-type");

  if (pageType.length > 0) {
    var toLog = pageType.split(";");
    var dimension = toLog[1];
    var pageTypeName = toLog[0];
    ga("set", dimension, pageTypeName);
    gtag('config', vfGaTrackOptions.vfGa4MeasurementId, {
      'custom_map': {
        dimension: pageTypeName
      }
    });
  } // If you want to track the network of visitors be sure to
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
      var ga = window[window.GoogleAnalyticsObject || 'ga'];

      if (typeof ga === 'undefined') {}

      if (typeof ga == 'function') {
        ga('provide', pluginName, pluginConstructor);
      }

      setTimeout(function () {
        var inputs = document.querySelectorAll('input');

        if (inputs) {
          for (var i = 0; i < inputs.length; i++) {
            inputs[i].addEventListener('blur', riskCheck);
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
      xhr.open("POST", 'https://risk.ipmeta.io/check', !0);
      xhr.setRequestHeader('Content-Type', 'application/json');
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
      var pl = 'h=' + encodeURI(window.location.hostname);

      if (key) {
        pl += '&k=' + key;
      }

      var endpoint = 'https://ipmeta.io/api/enrich';

      if (local) {
        endpoint = 'http://ipmeta.test/api/enrich';
      }

      request.open('POST', endpoint, !0);
      request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      request.setRequestHeader('Accept', 'application/json');
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

          console.error('IpMeta lookup failed.  Returned status of ' + request.status);
          return !1;
        }
      };
    };

    var encr = function encr(str) {
      return 'IPM' + btoa(btoa('bf2414cd32581225a82cc4fb46c67643' + btoa(str)) + 'dde9caf18a8fc7d8187f3aa66da8c6bb');
    };

    var IpMeta = function IpMeta(tracker, config) {
      this.tracker = tracker;
      this.nameDimension = config.serviceProvider || config.nameDimension || 'dimension1';
      this.domainDimension = config.networkDomain || config.domainDimension || 'dimension2';
      this.typeDimension = config.networkType || config.typeDimension || 'dimension3';
      this.gtmEventKey = config.gtmEventKey || 'pageview';
      this.isLocal = config.local || !1;
      this.apiKey = config.apiKey;
      this.isDebug = config.debug;
    };

    IpMeta.prototype.loadNetworkFields = function () {
      if (typeof Window.IpMeta === 'undefined') {
        Window.IpMeta = this;
      }

      this.debugMessage('Loading network field parameters');
      enrichNetwork(this.apiKey, this.isLocal, function (fields, wasAsync) {
        var wasAsync = wasAsync || !1;
        var nameValue = fields.name || '(not set)';
        var domainValue = fields.domain || '(not set)';
        var typeValue = fields.type || '(not set)';

        if (nameValue) {
          Window.IpMeta.tracker.set(Window.IpMeta.nameDimension, nameValue);
          Window.IpMeta.debugMessage('Loaded network name: ' + nameValue + ' into ' + Window.IpMeta.nameDimension);
        }

        if (domainValue) {
          Window.IpMeta.tracker.set(Window.IpMeta.domainDimension, domainValue);
          Window.IpMeta.debugMessage('Loaded network domain: ' + domainValue + ' into ' + Window.IpMeta.domainDimension);
        }

        if (typeValue) {
          Window.IpMeta.tracker.set(Window.IpMeta.typeDimension, typeValue);
          Window.IpMeta.debugMessage('Loaded network type: ' + typeValue + ' into ' + Window.IpMeta.typeDimension);
        }

        if (wasAsync) {
          Window.IpMeta.tracker.send('event', 'IpMeta', 'Enriched', 'IpMeta Enriched', {
            nonInteraction: !0
          });
        }
      });
    };

    IpMeta.prototype.setGtagMapping = function (fields) {
      var nameValue = fields.name || '(not set)';
      var domainValue = fields.domain || '(not set)';
      var typeValue = fields.type || '(not set)';
      var mapping = {};
      mapping[this.nameDimension] = nameValue;
      mapping[this.domainDimension] = domainValue;
      mapping[this.typeDimension] = typeValue;
      mapping.non_interaction = !0;
      Window.IpMeta.tracker('event', 'ipmeta_event', mapping);
    };

    IpMeta.prototype.loadGtagNetworkFields = function () {
      if (typeof Window.IpMeta === 'undefined') {
        Window.IpMeta = this;
      }

      this.debugMessage('Loading network field parameters');
      enrichNetwork(this.apiKey, this.isLocal, function (fields, wasAsync) {
        wasAsync = wasAsync || !1;
        Window.IpMeta.setGtagMapping(fields);
      });
    };

    IpMeta.prototype.loadGtmNetworkFields = function () {
      if (typeof Window.IpMeta === 'undefined') {
        Window.IpMeta = this;
      }

      this.debugMessage('Loading network field parameters');
      var eventKey = this.gtmEventKey;
      enrichNetwork(this.apiKey, this.isLocal, function (fields, wasAsync) {
        wasAsync = wasAsync || !1;
        var nameValue = fields.name || '(not set)';
        var domainValue = fields.domain || '(not set)';
        var typeValue = fields.type || '(not set)';
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

    providePlugin('ipMeta', IpMeta);
    /* eslint-enable */
    // Track the network

    ga("require", "ipMeta", {
      serviceProvider: vfGaTrackOptions.vfGaTrackNetwork.serviceProvider,
      networkDomain: vfGaTrackOptions.vfGaTrackNetwork.networkDomain,
      networkType: vfGaTrackOptions.vfGaTrackNetwork.networkType
    });
    ga("ipMeta:loadNetworkFields");
  } // standard google analytics bootstrap


  if (vfGaTrackOptions.vfGaTrackPageLoad) {
    vfGaLogMessage('sending page view');
    ga("send", "pageview");
    gtag("event", "page_view");
  } // If we want to send metrics in one go
  // ga('set', {
  //   'dimension5': 'custom dimension data'
  //   // 'metric5': 'custom metric data'
  // });


  vfGaLogMessage('prepare vfGaLinkTrackingInit');
  vfGaLinkTrackingInit();
}
/**
 * Track clicks as events
 */


function vfGaLinkTrackingInit() {
  vfGaLogMessage('vfGaLinkTrackingInit');
  document.body.addEventListener("mousedown", function (evt) {
    // Debug event type clicked
    vfGaLogMessage(evt.target.tagName);
    vfGaLogMessage(evt.target); // we only track clicks on interactive elements (links, buttons, forms)

    if (evt.target) {
      if (evt.target.tagName) {
        var clickedElementTag = evt.target.tagName.toLowerCase();
        var actionElements = ["a", "button", "label", "input", "select", "textarea", "details", "area"];

        if (actionElements.indexOf(clickedElementTag) > -1) {
          vfGaTrackInteraction(evt.target);
          return;
        }
      }
    } // In the case that elements such as `span` are wrapped in action elements (e.g. `a`),
    // we need to find the latter and supply them for tracking


    var ancestors = ["a", "details", "label"];

    for (var i = 0; i < ancestors.length; i++) {
      var from = findParent(ancestors[i], evt.target || evt.srcElement);

      if (from) {
        vfGaTrackInteraction(from);
        return;
      }
    }
  }, false); //find first parent with tagName [tagname]

  function findParent(tagname, el) {
    while (el) {
      if ((el.nodeName || el.tagName).toLowerCase() === tagname.toLowerCase()) {
        return el;
      }

      el = el.parentNode;
    }

    return null;
  }
} // /*
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
} // Catch any use cases that may have been existing
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

    vfGaLogMessage('GA4 dummy function has been set.');
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

    linkName = actedOnItem.innerText; // console.log('linkName',linkName);
    // if there's no text, it's probably and image

    if (linkName.length == 0 && actedOnItem.hasAttribute("src")) linkName = actedOnItem.src.split("/").vfGaLinkLast();
    if (linkName.length == 0 && actedOnItem.value) linkName = actedOnItem.value; // is there an inner image?

    if (linkName.length == 0 && actedOnItem.getElementsByTagName("img")) {
      if (actedOnItem.getElementsByTagName("img")[0]) {
        // if alt text, use that
        if (actedOnItem.getElementsByTagName("img")[0].hasAttribute("alt")) {
          linkName = actedOnItem.getElementsByTagName("img")[0].alt;
        } else if (actedOnItem.getElementsByTagName("img")[0].hasAttribute("src")) {
          linkName = actedOnItem.getElementsByTagName("img")[0].src.split("/").vfGaLinkLast();
        }
      }
    } // fallback to an href value


    if (linkName.length == 0 && actedOnItem.href) linkName = actedOnItem.href; // special things for global search box
    // if (parentContainer == 'Global search') {
    //   linkName = 'query: ' + jQuery('#global-search input#query').value;
    // }
  } // Get closest parent container
  // Track the region of the link clicked (global nav, masthead, hero, main content, footer, etc)
  //data-vf-google-analytics-region="main-content-area-OR-SOME-OTHER-NAME"


  var parentContainer = actedOnItem.closest("[data-vf-google-analytics-region]");

  if (parentContainer) {
    parentContainer = parentContainer.dataset.vfGoogleAnalyticsRegion;
  } else {
    parentContainer = "No container specified";
  } // send to GA
  // Only if more than 100ms has past since last click.
  // Due to our structure, we fire multiple events, so we only send to GA the most specific event resolution


  if (Date.now() - lastGaEventTime > 150) {
    // track link name and region
    // note that we've stored an event(s)
    lastGaEventTime = Date.now(); // What type of element? `a` `button` etc.

    var elementType = "none";

    if (actedOnItem.tagName) {
      elementType = actedOnItem.tagName.toLowerCase();
    } // Track file type (PDF, DOC, etc) or if mailto
    // adapted from https://www.blastanalytics.com/blog/how-to-track-downloads-in-google-analytics


    var filetypes = /\.(zip|exe|pdf|doc*|xls*|ppt*|mp3|txt|fasta)$/i;
    var href = actedOnItem.href; // log emails and downloads to seperate event "buckets"

    /* eslint-disable no-useless-escape */

    if (href && href.match(/^mailto\:/i)) {
      // email click
      var mailLink = href.replace(/^mailto\:/i, "");
      ga && ga("send", "event", "Email", "Region / " + parentContainer, mailLink);
      gtag && gtag("event", "Region / " + parentContainer, {
        "vf_analytics": "true",
        "page_container": parentContainer,
        "event_label": mailLink,
        "event_category": "UI",
        "event_type": "Email",
        "email_address": mailLink
      });
      vfGaLogMessage("Email", "Region / " + parentContainer, mailLink, lastGaEventTime, actedOnItem);
    } else if (href && href.match(filetypes)) {
      // download event
      var extension = /[.]/.exec(href) ? /[^.]+$/.exec(href) : undefined;
      var filePath = href;
      ga && ga("send", "event", "Download", "Type / " + extension + " / " + parentContainer, filePath);
      gtag && gtag("event", "Type / " + extension + " / " + parentContainer, {
        "vf_analytics": "true",
        "page_container": parentContainer,
        "event_label": filePath,
        "file_extension": extension,
        "event_category": "UI",
        "event_type": "Download",
        "link_url": filePath
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
          "vf_analytics": "true",
          "page_container": parentContainer,
          "event_category": "UI",
          "event_type": "External link or button",
          "link_text": linkName,
          "link_url": href
        });
        vfGaLogMessage("External links", "External link / " + linkName + " / " + parentContainer, href, lastGaEventTime, actedOnItem);
      }
    } // is it a form interaction or something with text?


    var formElementTypes = ["label", "input", "select", "textarea"];

    if (formElementTypes.indexOf(elementType) > -1) {
      // create a label for form elements
      // derive a form label
      linkName = ""; // If an explicit label has been provided, use that
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
      } // track a selected value


      if (elementType == "select") {
        linkName = linkName + ", " + actedOnItem.value;
      }

      ga && ga("send", "event", "UI", "UI Element / " + parentContainer, linkName);
      gtag && gtag("event", "UI Element / " + parentContainer, {
        "vf_analytics": "true",
        "page_container": parentContainer,
        "event_label": linkName,
        "event_category": "UI",
        "event_type": "Webform",
        "link_text": linkName
      });
      vfGaLogMessage("UI Form", "UI Element / " + parentContainer, linkName, lastGaEventTime, actedOnItem);
    } else {
      // generic catch all
      vfGaLogMessage("vfGaTrackInteraction: generic catch all");
      ga && ga("send", "event", "UI", "UI Element / " + parentContainer, linkName);
      gtag && gtag("event", "UI Element / " + parentContainer, {
        "vf_analytics": "true",
        "page_container": parentContainer,
        "event_label": linkName,
        "event_category": "UI",
        "event_type": "Link, button, image or similar",
        "link_text": linkName,
        "link_url": href
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
  var conditionalLoggingCheck = document.querySelector("body"); // debug: always turn on verbose analytics
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
} // vf-tabs

/**
 * Finds all tabs on a page and activates them
 * @param {object} [scope] - the html scope to process, optional, defaults to `document`
 * @param {boolean} [activateDeepLinkOnLoad] - if deep linked tabs should be activated on page load, defaults to true
 * @example vfTabs(document.querySelectorAll('.vf-component__container')[0]);
 */


function vfTabs(scope, activateDeepLinkOnLoad) {
  /* eslint-disable no-redeclare */
  var scope = scope || document;
  var activateDeepLinkOnLoad = activateDeepLinkOnLoad || true;
  /* eslint-enable no-redeclare */
  // Get relevant elements and collections

  var tabsList = scope.querySelectorAll("[data-vf-js-tabs]");
  var panelsList = scope.querySelectorAll("[data-vf-js-tabs-content]");
  var panels = scope.querySelectorAll("[data-vf-js-tabs-content] [id^=\"vf-tabs__section\"]");
  var tabs = scope.querySelectorAll("[data-vf-js-tabs] .vf-tabs__link");

  if (!tabsList || !panels || !tabs) {
    // exit: either tabs or tabbed content not found
    return;
  }

  if (tabsList.length == 0 || panels.length == 0 || tabs.length == 0) {
    // exit: either tabs or tabbed content not found
    return;
  } // Add semantics are remove user focusability for each tab


  Array.prototype.forEach.call(tabs, function (tab, i) {
    var tabId = tab.href.split("#")[1]; // calculate an ID based off the tab href (todo: add support for a data-vf-js-tab-id, and if set use that)

    tab.setAttribute("role", "tab");
    tab.setAttribute("id", tabId);
    tab.setAttribute("data-tabs__item", tabId);
    tab.setAttribute("tabindex", "0");
    tab.parentNode.setAttribute("role", "presentation"); // Reset any active tabs from a previous JS call

    tab.removeAttribute("aria-selected");
    tab.setAttribute("tabindex", "-1");
    tab.classList.remove("is-active"); // Handle clicking of tabs for mouse users

    tab.addEventListener("click", function (e) {
      e.preventDefault();
      vfTabsSwitch(e.currentTarget, panels);
    }); // Handle keydown events for keyboard users

    tab.addEventListener("keydown", function (e) {
      // Get the index of the current tab in the tabs node list
      var index = Array.prototype.indexOf.call(tabs, e.currentTarget); // Work out which key the user is pressing and
      // Calculate the new tab's index where appropriate

      var dir = e.which === 37 ? index - 1 : e.which === 39 ? index + 1 : e.which === 40 ? "down" : null;

      if (dir !== null) {
        e.preventDefault(); // If the down key is pressed, move focus to the open panel,
        // otherwise switch to the adjacent tab

        dir === "down" ? panels[i].focus({
          preventScroll: true
        }) : tabs[dir] ? vfTabsSwitch(tabs[dir], panels) : void 0;
      }
    });
  }); // Add tab panel semantics and hide them all

  Array.prototype.forEach.call(panels, function (panel) {
    panel.setAttribute("role", "tabpanel");
    panel.setAttribute("tabindex", "-1"); // let id = panel.getAttribute("id");

    panel.setAttribute("aria-labelledby", panel.id);
    panel.hidden = true;
  }); // Add the tabsList role to the first <ul> in the .tabbed container

  Array.prototype.forEach.call(tabsList, function (tabsListset) {
    tabsListset.setAttribute("role", "tabsList"); // Initially activate the first tab

    var firstTab = tabsListset.querySelectorAll(".vf-tabs__link")[0];
    firstTab.removeAttribute("tabindex");
    firstTab.setAttribute("aria-selected", "true");
    firstTab.classList.add("is-active");
  });
  Array.prototype.forEach.call(panelsList, function (panel) {
    // Initially reveal the first tab panel
    var firstPanel = panel.querySelectorAll(".vf-tabs__section")[0];
    firstPanel.hidden = false;
  }); // activate any deeplinks to a specific tab

  if (activateDeepLinkOnLoad) {
    vfTabsDeepLinkOnLoad(tabs, panels);
  }
} // The tab switching function


var vfTabsSwitch = function vfTabsSwitch(newTab, panels) {
  // Update url based on tab id
  var data = newTab.getAttribute("id");
  var url = '#' + data;
  window.history.replaceState(data, null, url); // get the parent ul of the clicked tab

  var parentTabSet = newTab.closest(".vf-tabs__list");
  var oldTab = parentTabSet.querySelector("[aria-selected]");

  if (oldTab) {
    oldTab.removeAttribute("aria-selected");
    oldTab.setAttribute("tabindex", "-1");
    oldTab.classList.remove("is-active");

    for (var item = 0; item < panels.length; item++) {
      var panel = panels[item];

      if (panel.id === oldTab.id) {
        panel.hidden = true;
        break;
      }
    }
  }

  newTab.focus({
    preventScroll: true
  }); // Make the active tab focusable by the user (Tab key)

  newTab.removeAttribute("tabindex"); // Set the selected state

  newTab.setAttribute("aria-selected", "true");
  newTab.classList.add("is-active"); // Get the indices of the new tab to find the correct
  // tab panel to show

  for (var _item = 0; _item < panels.length; _item++) {
    var _panel = panels[_item];

    if (_panel.id === newTab.id) {
      _panel.hidden = false;
      break;
    }
  }
};

function vfTabsDeepLinkOnLoad(tabs, panels) {
  // 1. See if there is a `#vf-tabs__section--88888`
  if (window.location.hash) {
    var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
  } else {
    // No hash found
    return false;
  }

  console.log("vfTabs: will activate tab", hash); // 2. loop through all tabs, if a match then activate

  Array.prototype.forEach.call(tabs, function (tab) {
    var tabId = tab.getAttribute("data-tabs__item");

    if (tabId == hash) {
      vfTabsSwitch(tab, panels);
    }
  });
} // vf-navigation
// only required for vf-navigation--on-this-page

/**
 * Function for JS scroll to top functionality
 * That must be executed exactly once
 * @example vfNavigationOnThisPage()
 */


function vfNavigationOnThisPage() {
  var scope = document; // based on the attribute we select all navigation links

  var navLinks = scope.querySelectorAll("[data-vf-js-navigation-on-this-page-container='true'] .vf-navigation__item a"); // we store all ids from anchor tags to know the sections we should care about

  var ids = [];
  navLinks.forEach(function (link) {
    if (link.hash) {
      ids.push(link.hash.substring(1));
    }
  }); // get all elements with an id and convert it from NodeList to Array

  var sections = Array.prototype.slice.call(scope.querySelectorAll("[id]"));
  var sectionPositions = [];

  if (!navLinks || !sections) {
    // exit: either sections or section content not found
    return;
  }

  if (navLinks.length === 0 || sections.length === 0) {
    // exit: either sections or section content not found
    return;
  } // remove all the elements that doesn't appear in the navigation based on it's id


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
    }); // put sections in the bottom at the beginning of the array

    sectionPositions.reverse();
    var scrollPosition = document.documentElement.scrollTop || document.body.scrollTop; // We reverse the array because Array.find starts to search from position 0 and to simplify
    // the logic to find which element is closest to the current scroll position, otherwise it will always
    // select the first element.

    var currentSection = sectionPositions.find(function (s) {
      return !(scrollPosition <= s.position - 95);
    });
    navLinks.forEach(function (link) {
      link.setAttribute("aria-selected", "false");
    }); // if we don't match any section yet, highlight the first link

    if (!currentSection) {
      navLinks[0].setAttribute("aria-selected", "true");
    } else {
      navLinks.forEach(function (link) {
        if (link.hash === '#' + currentSection.id) {
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
      event.preventDefault(); // get current styles of element we are moving to

      var elemStyles = window.getComputedStyle(section); // take into account the padding and/or margin top

      var value = elemStyles.paddingTop !== '0px' ? elemStyles.paddingTop : elemStyles.marginTop; // we remove the px characters from the value

      var offset = parseInt(value.slice(0, -2), 10); // total offset: margin/padding top of the element plus the size of the navigation bar

      window.scroll({
        top: section.getBoundingClientRect().top + scrollPosition - (offset + 40),
        behavior: 'smooth'
      });
    });
  });
} // vf-location-nearest

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
    console.warn("vfLocationNearest", "Geolocation error code: " + error.code); // error.code can be:
    //   0: unknown error
    //   1: permission denied
    //   2: position unavailable (error response from location provider)
    //   3: timed out
    // if no match return false

    vfLocationNearestResolve(locationsList, false);
  }; // Bootstrap browserapi


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
    var closest = locationsList.default; // loop through each location, matching a close city then the next closest and so on

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
  el.setAttribute("data-vf-location-nearest-name", locationName); // indicate we've loaded

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

  var widget = "<label class=\"vf-form__label\" for=\"vf-form__select\"></label>" + "<select class=\"vf-form__select\" id=\"vf-form__select\">"; // Create the markup for a dropdown

  for (var key in locationsList) {
    /* eslint-disable no-prototype-builtins */
    if (locationsList.hasOwnProperty(key)) {
      /* eslint-enable no-prototype-builtins */
      // const element = locationsList[key];
      widget = widget + "<option value=\"".concat(key, "\">").concat(locationsList[key].name, "</option>");
    }
  }

  widget = widget + "</select>"; // assign values to widget

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
    var activeItem = e.target; // console.log('You selected: ', activeItem.options[activeItem.target.selectedIndex].text);

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
  var locationName = el.getAttribute("data-vf-location-nearest-name"); // push the active location to the dom

  function assignName() {
    var locationNameHolder = scope.querySelectorAll("[data-vf-js-location-nearest-name]");

    if (!locationNameHolder) {
      // exit: container not found
      return;
    }

    if (locationNameHolder.length == 0) {
      // exit: content not found
      return;
    } // console.log('assignName','pushing the active location to the dom')


    locationNameHolder[0].innerHTML = locationName;
  } // simple activation of any elements/components through simple click simulation


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
  }; // console.log('vfLocationNearest locationsList',locationsList)
  // unset any prior check

  vfLocationNearestIndicate("unload"); // Insert a widget of selectable locations

  vfLocationNearestOverridePopulate(locationsList); // get the current users location

  vfLocationNearestDetect(locationsList); // enable a manual override widget

  vfLocationNearestOverrideActivate();
} // You should also import it at ./components/vf-component-rollup/scripts.js
// import { vfLocationNearest } from 'vf-location-nearest/vf-location-nearest';
// Or import directly
// import { vfLocationNearest } from '../components/raw/vf-location-nearest/vf-location-nearest.js';
// And, if needed, invoke it
// vfLocationNearest();
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
  } // Determine base URL of document.


  var baseElem = document.querySelector("base");
  var baseHref = baseElem && baseElem.hasAttribute("href") ? baseElem.getAttribute("href") : ""; // If there is a base href, ensure it is of the form 'path/' (not '/path', 'path' etc)

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
    } // Each import also tracks its own state with its own context.

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
      }); // we don't need the `body` wrapper, so return the first child

      return doc.body.firstChild;
    });
  }

  function AddImport(url, async, progressObject) {
    // Note async attribute ignored (was only used for old native implementation).
    return _AddImport(url, null, null, progressObject);
  }

  window["addImport"] = AddImport;
} // embl-conditional-edit

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
} // embl-notifications

/**
  * After a notifications has been chosen, build it and insert into the document
  * @example emblNotificationsInject(notification)
  * @param {object} [message] - An object to be show on a page
  */


function emblNotificationsInject(message) {
  var output = document.createElement("div"); // @todo:
  // - add support in contentHub for extra button text, link
  // preparation

  message.body = message.body.replace(/<[/]?[p>]+>/g, " "); // no <p> tags allowed in inline messages, preserve a space to not collide words
  // add vf-link to link

  message.body = message.body.replaceAll("<a href=", "<a class=\"vf-banner__link\" href="); // we might need a more clever regex, but this should also avoid links that already have a class
  // Learn more link is conditionally shown

  if (message.field_notification_link) {
    message.body = "".concat(message.body, " <a class=\"vf-banner__link\" href=\"").concat(message.field_notification_link, "\">Learn more</a>");
  } // custom button text


  message.field_notification_button_text = message.field_notification_button_text || "Close notice"; // notification memory and cookie options

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
    output.dataset.vfJsBannerButtonText = message.field_notification_button_text; // These features are not yet supported by the notification content type in the EMBL contentHub
    // output.dataset.vfJsBannerExtraButton = "<a href='#'>Optional button</a><a target='_blank' href='#'>New tab button</a>";

    output.innerHTML = "\n      <div class=\"vf-banner__content | vf-grid\" data-vf-js-banner-text>\n        <p class=\"vf-text vf-text-body--2\">".concat(message.body, "</p>\n      </div>");
    var target = document.body.firstChild;
    target.parentNode.prepend(output);
    vfBanner();
  } else if (message.field_notification_position == "inline") {
    output.classList.add("vf-grid", "vf-u-margin__top--400"); // we wrap in vf-grid for layout
    // we use vf-u-margin__top--400 as this element is usually inserted inside a contentHub wrapper and not affected by body.vf-stack
    // if vf-stack is set, this will have no practical affect

    output.innerHTML = "\n      <div class=\"vf-banner vf-banner--alert vf-banner--info | vf-content\" data-vf-google-analytics-region=\"notifications-banner\">\n        <div class=\"vf-banner__content\">\n          <p class=\"vf-banner__text\">".concat(message.body, "</p>\n        </div>\n      </div>"); // insert after `vf-header` or at after `vf-body`
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
    output.dataset.vfJsBannerButtonText = message.field_notification_button_text; // These features are not yet supported by the notification content type in the EMBL contentHub
    // output.dataset.vfJsBannerExtraButton = "<a href='#'>Optional button</a><a target='_blank' href='#'>New tab button</a>";

    output.innerHTML = "\n      <div class=\"vf-banner__content\" data-vf-js-banner-text>\n        <p class=\"vf-banner__text\">".concat(message.body, "</p>\n      </div>");
    var _target4 = document.body.firstChild;

    _target4.parentNode.prepend(output);

    vfBanner();
  } // console.log('emblNotifications, showing:', message);

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
  currentPath = currentPath || window.location.pathname; // don't treat `wwwdev` as distinct from `www`

  currentHost = currentHost.replace(/wwwdev/g, "www"); // console.log('emblNotifications','Checking for notifications.');
  // console.log('emblNotifications, Current url info:', currentHost + "," + currentPath);
  // Process each message against a URLs

  function matchNotification(message, targetUrl) {
    var matchFound = false;

    if (message.hasBeenShown == true) {
      // console.warn('emblNotifications', 'This message has already been displayed on the page.')
      return;
    } // console.log('emblNotifications, targetUrl:', targetUrl);
    // console.log('emblNotifications, matching:', currentHost+currentPath);
    // Is there an exact match?


    matchFound = compareUrls(currentHost + currentPath, targetUrl); // Handle wildcard matches like `/about/*`

    if (targetUrl.slice(-1) == "*") {
      matchFound = compareUrls(currentHost + currentPath, targetUrl, true);
    } // if a match has been made on the current url path, show the message


    if (matchFound == true) {
      // console.log('emblNotifications: MATCH FOUND 🎉', targetUrl, currentHost, currentPath);
      message.hasBeenShown = true;
      emblNotificationsInject(message);
    }
  } // Handle string comparisons for URLs


  function compareUrls(url1, url2, isWildCard) {
    isWildCard = isWildCard || false; // we ignore case
    // we could probably optimise by moving this higher in the logic, but it's more maintainable to have it here

    url1 = url1.toLowerCase();
    url2 = url2.toLowerCase(); // don't allow matches to end in `*`

    if (url1.slice(-1) == "*") url1 = url1.substring(0, url1.length - 1);
    if (url2.slice(-1) == "*") url2 = url2.substring(0, url2.length - 1); // don't allow matches to end in `/`

    if (url1.slice(-1) == "/") url1 = url1.substring(0, url1.length - 1);
    if (url2.slice(-1) == "/") url2 = url2.substring(0, url2.length - 1); // console.log('emblNotifications, comparing:', url1 + "," + url2);

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
  } // Process each message, and its URL fragments


  function processNotifications(messages) {
    // console.log('emblNotifications', messages);
    // Process each message
    for (var index = 0; index < messages.length; index++) {
      var currentMessage = messages[index]; // track if a message has already been show on the page
      // we want to be sure a message isn't accidently shown twice

      currentMessage.hasBeenShown = false; // Process the URLs for each path in a message

      var currentUrls = currentMessage.field_notification_urls.split(",");

      for (var indexUrls = 0; indexUrls < currentUrls.length; indexUrls++) {
        var url = currentUrls[indexUrls].trim();
        matchNotification(currentMessage, url); // pass the notification and active url to compare
      }
    }
  } // Utility to fetch a file, process the JSON


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
  } // Bootstrap the message fetching
  // If on dev, reference dev server


  if (window.location.hostname.indexOf("wwwdev.") === 0) {
    loadRemoteNotifications("https://wwwdev.embl.org/api/v1/notifications?_format=json&source=contenthub");
  } else if (window.location.hostname.indexOf("localhost") === 0) {
    loadRemoteNotifications("https://wwwdev.embl.org/api/v1/notifications?_format=json&source=contenthub");
  } else {
    loadRemoteNotifications("https://www.embl.org/api/v1/notifications?_format=json&source=contenthub");
  } // Check fallback notifications


  loadRemoteNotifications("https://embl-communications.github.io/embl-notifcations-fallback/notifications.js");
} // Add this to your ./components/vf-component-rollup/scripts.js
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
    var ONE_DAY = 1000 * 60 * 60 * 24; // Convert both dates to milliseconds

    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime(); // Calculate the difference in milliseconds

    var difference_ms = Math.abs(date1_ms - date2_ms); // Convert back to days and return

    return Math.round(difference_ms / ONE_DAY) + 1;
  } // A list of all the links


  var emblContentHubLinks = document.querySelectorAll("[data-embl-js-content-hub-loader]");
  var emblContentHubLinkLoadingProgress = {};
  var emblContentHubShowTimers = false; // Handle the import of each element

  for (var i = 0; i < emblContentHubLinks.length; ++i) {
    (function () {
      var linkPosition = i; // track time it takes for link to be shown

      if (emblContentHubShowTimers) {
        console.time("timer for import " + linkPosition);
      } // await the load of the html import from the polyfill
      // note: we use polyfill in all cases; see https://github.com/visual-framework/vf-core/issues/508


      emblContentHubAwaitLoading(emblContentHubLinks[linkPosition], linkPosition);
    })();
  } // If nothing to import


  if (emblContentHubLinks.length == 0) {
    emblContentHubSignalFinished();
  } // Add a class to the body once the last item has been processed


  function emblContentHubSignalFinished() {
    // @todo, shouldn't require the body element
    document.querySelectorAll("body")[0].classList.add("embl-content-hub-loaded"); // if the JS to run embl-conditional-edit is present, run it now

    if (typeof emblConditionalEdit === "function") {
      emblConditionalEdit();
    } // if the JS to run embl-notifications is present, run it now


    if (typeof emblNotifications === "function") {
      emblNotifications();
    }
  } // Dispatch load to the pollyfill


  function emblContentHubAwaitLoading(targetLink, position) {
    /* global addImport */
    // Docs: https://github.com/AshleyScirra/html-imports-polyfill#usage
    addImport(targetLink.href, null, emblContentHubLinkLoadingProgress).then(function (value) {
      emblContentHubGrabTheContent(targetLink, position, value);

      if (position + 1 == emblContentHubLinks.length) {
        emblContentHubSignalFinished();
      }
    });
  } // Generate a unique ID for the target element on the page


  function emblContentHubGenerateID(position) {
    return "contentDbItem" + ("0000" + position).slice(-5);
  } // Show the remote content


  function emblContentHubGrabTheContent(targetLink, position, exportedContent) {
    // pickup the "meat" of the exported content
    exportedContent = exportedContent || targetLink.import.querySelector(".vf-content-hub-html"); // make sure we have something

    if (!exportedContent) {
      console.log("No content found for this import, exiting. The import may have already been preformed.", targetLink);
      return;
    } // if there is just one child element and it is a div, use that
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
      } // if data-embl-js-content-hub-loader-no-content-hide is true or has a class, hide accordingly


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


    var contentID = emblContentHubGenerateID(position); // where does the content go?

    if (targetLink.dataset.target === "self") {
      // if element already exists, remove it
      var oldElement = document.getElementById(contentID);

      if (oldElement) {
        oldElement.innerHTML = exportedContent.innerHTML;
      } else {
        // give content an ID
        exportedContent.setAttribute("id", contentID);
        exportedContent.classList.add(contentID); // just insert the new content

        exportedContent.appendAfter(targetLink);
      } // end if oldElement

    } else {
      var targetLocation = document.querySelector("." + targetLink.dataset.target); // exportedContent.appendAfter(targetLocation);

      targetLocation.classList.add(contentID);
      targetLocation.innerHTML = exportedContent.innerHTML;
    } // display how long it took to load


    if (emblContentHubShowTimers) {
      console.timeEnd("timer for import " + position);
    }

    emblContentHubAssignClasses(targetLink, position);
    emblContentHubUpdateDatesFormat(position); // run JS for some components on content, if they exist
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
    } // don't run breadcrumbs as part of contenthub, use case is different
    // if (typeof(emblBreadcrumbs) === 'function') {
    //   emblBreadcrumbs(); // no scope for emblBreadcrumbs
    // }

  } // Enable class injection after loading contents
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
      var targetElement = document.querySelector("." + emblContentHubGenerateID(position)).querySelector(targetSelectorToInject); // We can't inject space separated classes to we need to split it into arrays and add one by one.

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
        var numberOfDiffDays = days_between(dateValue, todayDate); // Update to 'Closes in 6 Days.' format if number of days is less than 30 days.

        if (numberOfDiffDays < 30 && numberOfDiffDays > 1) {
          dateRemainingList[dateRemainingIndex].innerHTML = "Closes in " + "<span>" + numberOfDiffDays + " Days.</span>";
        }

        if (numberOfDiffDays == 1) {
          dateRemainingList[dateRemainingIndex].innerHTML = "Closes in " + "<span>" + numberOfDiffDays + " Day.</span>";
        }
      }
    }
  }
} // embl-content-hub-loader


function emblContentHub() {
  // 1. make sure we have imports or a polyfill
  emblContentHubLoaderHtmlImports(); // 2. import the content

  emblContentHubFetch();
} // embl-content-meta-properties
// In addition to being queried by other components' JS, this could
// also add classes to a page to affect the overall look of a page.

/**
 * Read metaProperties from page's metatags
 * @example emblContentMetaProperties_Read()
 */


function emblContentMetaProperties_Read() {
  var metaProperties = {}; // <!-- Content descriptors -->
  // <meta name="embl:who" content="{{ meta-who }}"> <!-- the people, groups and teams involved -->
  // <meta name="embl:what" content="{{ meta-what }}"> <!-- the activities covered -->
  // <meta name="embl:where" content="{{ meta-where }}"> <!-- at which EMBL sites the content applies -->
  // <meta name="embl:active" content="{{ meta-active }}"> <!-- which of the who/what/where is active -->

  metaProperties.who = metaProperties.who || document.querySelector("meta[name='embl:who']");
  metaProperties.what = metaProperties.what || document.querySelector("meta[name='embl:what']");
  metaProperties.where = metaProperties.where || document.querySelector("meta[name='embl:where']");
  metaProperties.active = metaProperties.active || document.querySelector("meta[name='embl:active']"); // <!-- Content role -->
  // <meta name="embl:utility" content="-8"> <!-- if content is task and work based or if is meant to inspire -->
  // <meta name="embl:reach" content="-5"> <!-- if content is externally (public) or internally focused (those that work at EMBL) -->

  metaProperties.utility = metaProperties.utility || document.querySelector("meta[name='embl:utility']");
  metaProperties.reach = metaProperties.reach || document.querySelector("meta[name='embl:reach']"); // <!-- Page infromation -->
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
} // embl-breadcrumbs-lookup
// to hold the EMBL taxonomy


var emblTaxonomy = {}; // placeholders for our new breadcrumbs

var emblBreadcrumbPrimary = document.createElement("ul");
emblBreadcrumbPrimary.classList.add("vf-breadcrumbs__list", "vf-list", "vf-list--inline");
var emblBreadcrumbRelated = document.createElement("ul");
emblBreadcrumbRelated.classList.add("vf-breadcrumbs__list", "vf-breadcrumbs__list--related", "vf-list", "vf-list--inline"); // we store the primairy breadcrumb so it can be accessed by related crumbs, if needed

var primaryBreadcrumb;
/**
 * Look up a breadcrumb by its uuid and return the entry
 * @example emblBreadcumbLookupByUuid(uuid)
 * @param {string} [uuid]  - the uuid of a term
 */

function emblBreadcumbLookupByUuid(uuid) {
  // console.log('emblBreadcumbLookupByUuid',uuid);
  if (emblTaxonomy.terms[uuid]) {
    // console.log('emblBreadcumbLookupByUuid',emblTaxonomy.terms[uuid]);
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

  var majorFacets = ["who", "what", "where"]; // do the primairy breadcrumb first

  emblBreadcrumbAppend(emblBreadcrumbTarget, metaProperties[metaProperties.active], metaProperties.active, "primary"); // do the non-primairy meta terms
  // @todo: we probably shouldn't do related if there is no primairy

  for (var i = 0; i < majorFacets.length; i++) {
    if (majorFacets[i] != metaProperties.active) {
      emblBreadcrumbAppend(emblBreadcrumbTarget, metaProperties[majorFacets[i]], majorFacets[i], "related");
    }
  } // make a 'related' label


  var relatedLabel = document.createElement("span");
  relatedLabel.innerHTML = "Related:";
  relatedLabel.classList.add("vf-breadcrumbs__heading"); // If no related terms were found, hide the related label
  // we only hide it as we could add related terms later

  if (emblBreadcrumbRelated.childNodes.length == 0) {
    relatedLabel.classList.add("vf-u-display-none");
  } // now that we've processed all the meta properties, insert our rendered breadcrumbs


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
        // Resolve the promise with the response text
        resolve(req.response);
      } else {
        // Otherwise reject with the status text
        // which will hopefully be a meaningful error
        reject(Error(req.statusText));
      }
    }; // Handle network errors


    req.onerror = function () {
      reject(Error("Error loading ontology"));
    }; // Make the request


    req.send();
  });
}
/**
 * Receive a string and convert any non a-z character
 * @example emblBreadcrumbRemoveDiacritics('Spaßß')
 * @param {str} - a name or such
 * @todo this might be better as a general vf utility
 */


function emblBreadcrumbRemoveDiacritics(str) {
  str = str || ""; // https://github.com/backbone-paginator/backbone.paginator/blob/a579796a30e583c4dfa09e0a86e4abd21e0b5b56/plugins/diacritic.js

  var defaultDiacriticsRemovalMap = [{
    "base": "A",
    "letters": /[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g
  }, {
    "base": "AA",
    "letters": /[\uA732]/g
  }, {
    "base": "AE",
    "letters": /[\u00C6\u01FC\u01E2]/g
  }, {
    "base": "AO",
    "letters": /[\uA734]/g
  }, {
    "base": "AU",
    "letters": /[\uA736]/g
  }, {
    "base": "AV",
    "letters": /[\uA738\uA73A]/g
  }, {
    "base": "AY",
    "letters": /[\uA73C]/g
  }, {
    "base": "B",
    "letters": /[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g
  }, {
    "base": "C",
    "letters": /[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g
  }, {
    "base": "D",
    "letters": /[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g
  }, {
    "base": "DZ",
    "letters": /[\u01F1\u01C4]/g
  }, {
    "base": "Dz",
    "letters": /[\u01F2\u01C5]/g
  }, {
    "base": "E",
    "letters": /[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g
  }, {
    "base": "F",
    "letters": /[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g
  }, {
    "base": "G",
    "letters": /[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g
  }, {
    "base": "H",
    "letters": /[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g
  }, {
    "base": "I",
    "letters": /[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g
  }, {
    "base": "J",
    "letters": /[\u004A\u24BF\uFF2A\u0134\u0248]/g
  }, {
    "base": "K",
    "letters": /[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g
  }, {
    "base": "L",
    "letters": /[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g
  }, {
    "base": "LJ",
    "letters": /[\u01C7]/g
  }, {
    "base": "Lj",
    "letters": /[\u01C8]/g
  }, {
    "base": "M",
    "letters": /[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g
  }, {
    "base": "N",
    "letters": /[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g
  }, {
    "base": "NJ",
    "letters": /[\u01CA]/g
  }, {
    "base": "Nj",
    "letters": /[\u01CB]/g
  }, {
    "base": "O",
    "letters": /[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g
  }, {
    "base": "OI",
    "letters": /[\u01A2]/g
  }, {
    "base": "OO",
    "letters": /[\uA74E]/g
  }, {
    "base": "OU",
    "letters": /[\u0222]/g
  }, {
    "base": "P",
    "letters": /[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g
  }, {
    "base": "Q",
    "letters": /[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g
  }, {
    "base": "R",
    "letters": /[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g
  }, {
    "base": "S",
    "letters": /[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g
  }, {
    "base": "T",
    "letters": /[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g
  }, {
    "base": "TZ",
    "letters": /[\uA728]/g
  }, {
    "base": "U",
    "letters": /[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g
  }, {
    "base": "V",
    "letters": /[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g
  }, {
    "base": "VY",
    "letters": /[\uA760]/g
  }, {
    "base": "W",
    "letters": /[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g
  }, {
    "base": "X",
    "letters": /[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g
  }, {
    "base": "Y",
    "letters": /[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g
  }, {
    "base": "Z",
    "letters": /[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g
  }, {
    "base": "a",
    "letters": /[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g
  }, {
    "base": "aa",
    "letters": /[\uA733]/g
  }, {
    "base": "ae",
    "letters": /[\u00E6\u01FD\u01E3]/g
  }, {
    "base": "ao",
    "letters": /[\uA735]/g
  }, {
    "base": "au",
    "letters": /[\uA737]/g
  }, {
    "base": "av",
    "letters": /[\uA739\uA73B]/g
  }, {
    "base": "ay",
    "letters": /[\uA73D]/g
  }, {
    "base": "b",
    "letters": /[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g
  }, {
    "base": "c",
    "letters": /[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g
  }, {
    "base": "d",
    "letters": /[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g
  }, {
    "base": "dz",
    "letters": /[\u01F3\u01C6]/g
  }, {
    "base": "e",
    "letters": /[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g
  }, {
    "base": "f",
    "letters": /[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g
  }, {
    "base": "g",
    "letters": /[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g
  }, {
    "base": "h",
    "letters": /[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g
  }, {
    "base": "hv",
    "letters": /[\u0195]/g
  }, {
    "base": "i",
    "letters": /[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g
  }, {
    "base": "j",
    "letters": /[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g
  }, {
    "base": "k",
    "letters": /[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g
  }, {
    "base": "l",
    "letters": /[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g
  }, {
    "base": "lj",
    "letters": /[\u01C9]/g
  }, {
    "base": "m",
    "letters": /[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g
  }, {
    "base": "n",
    "letters": /[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g
  }, {
    "base": "nj",
    "letters": /[\u01CC]/g
  }, {
    "base": "o",
    "letters": /[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g
  }, {
    "base": "oi",
    "letters": /[\u01A3]/g
  }, {
    "base": "ou",
    "letters": /[\u0223]/g
  }, {
    "base": "oo",
    "letters": /[\uA74F]/g
  }, {
    "base": "p",
    "letters": /[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g
  }, {
    "base": "q",
    "letters": /[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g
  }, {
    "base": "r",
    "letters": /[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g
  }, {
    "base": "s",
    "letters": /[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g
  }, {
    "base": "t",
    "letters": /[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g
  }, {
    "base": "tz",
    "letters": /[\uA729]/g
  }, {
    "base": "u",
    "letters": /[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g
  }, {
    "base": "v",
    "letters": /[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g
  }, {
    "base": "vy",
    "letters": /[\uA761]/g
  }, {
    "base": "w",
    "letters": /[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g
  }, {
    "base": "x",
    "letters": /[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g
  }, {
    "base": "y",
    "letters": /[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g
  }, {
    "base": "z",
    "letters": /[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g
  }];

  for (var i = 0; i < defaultDiacriticsRemovalMap.length; i++) {
    str = str.replace(defaultDiacriticsRemovalMap[i].letters, defaultDiacriticsRemovalMap[i].base);
  } // remove all commas, apostrophes, etc
  // @todo, this should be done by an optional paramater


  str = str.replace(/[^a-zA-Z0-9 ]/, "");
  return str;
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
      // console.log('here',primaryBreadcrumb.parents)

      if (primaryBreadcrumb.parents[facet]) {
        termName = primaryBreadcrumb.parents[facet];
      }
    } // if using a `string/NameOfThing` value, not accordingly


    if (termName.indexOf("string/") >= 0) {
      console.warn("embl-js-breadcumbs-lookup: using a passed string value to make breadcrumbs " + termName);
      termName = termName.replace("string/", "");
    } // scan through all terms and find a match, if any


    function emblBreadcumbLookup(termName) {
      // @todo: if a UUID meta property is set, use that
      // if it's UUID match we use that
      termObject = emblBreadcumbLookupByUuid(termName);

      if (typeof termObject != "undefined") {
        return; //exit
      } // We prefer profiles


      Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
        var term = emblTaxonomy.terms[termId];

        if (term.type == "profile") {
          if (term.name === termName) {
            termObject = term;
            return; //exit
          }
        }
      }); // If no profile found, match other types of taxonomy entries

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
      } // If there's still no match, see if we can find a matching display name
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
      }); // If no profile found, match other types of taxonomy entries

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
    } // don't scan for junk matches


    if (termName != "notSet" && termName != "" && termName != "none") {
      emblBreadcumbLookup(termName);
    } // Validation and protection
    // we never want to return undefined


    if (termObject == undefined || termObject == null) {
      // console.warn('embl-js-breadcumbs-lookup: No matching breadcrumb found for `' + termName + '`; Will formulate a URL.');
      termObject = {};

      if (facet == "who") {
        // if we're linking to people generate a person URL
        termObject.url = "https://www.embl.org/people/person/" + emblBreadcrumbRemoveDiacritics(termName).replace(/[\W_]+/g, " ").replace(/\s+/g, "-").toLowerCase();
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
        console.warn("embl-js-breadcumbs-lookup: No matching parent found; Stopping parent lookup.");
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
      } // get parents of parent


      if (activeParent.parents) {
        if (activeParent.uuid != lastParent.uuid) {
          lastParent = activeParent;
          getBreadcrumbParentTerm(activeParent.parents, facet, lastParent);
        } else {
          console.log("embl-js-breadcumbs-lookup", "Recursion in parent lookup. Check the EMBL.org Profile. Aborting lookup.");
          console.log("embl-js-breadcumbs-lookup", "activeParent", activeParent);
          console.log("embl-js-breadcumbs-lookup", "lastParent", lastParent);
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
    primaryBreadcrumb = currentTerm; // don't show path of breadcrumb if it is the current path

    if (new URL(breadcrumbUrl).pathname == window.location.pathname) {
      breadcrumbUrl = null;
    } // in this context the active page is always "current"


    breadcrumbCurrent = true; // add breadcrumb

    emblBreadcrumbPrimary.innerHTML += formatBreadcrumb(currentTerm.name_display, breadcrumbUrl, breadcrumbCurrent); // fetch parents for primary path

    getBreadcrumbParentTerm(breadcrumbParents, facet);
  } else if (type == "related") {
    // add breadcrumb
    emblBreadcrumbRelated.innerHTML += formatBreadcrumb(currentTerm.name_display, breadcrumbUrl, breadcrumbCurrent);
  }
}

function emblBreadcrumbs() {
  // We start the breadcrumbs by first getting the EMBL taxonomy.
  // todo: some sort of caching here, perhaps we write to local storage.
  // todo: abstract this out into its own `embl-taxonomy` component?
  emblGetTaxonomy().then(function (response) {
    emblTaxonomy = JSON.parse(response); // Preprocess the emblTaxonomy for some cleanup tasks

    Array.prototype.forEach.call(Object.keys(emblTaxonomy.terms), function (termId) {
      var term = emblTaxonomy.terms[termId]; // If `name_display` is not set, use the internal name

      if (term.name_display === "") term.name_display = term.name; // handle null URL

      if (term.url === "") term.url = "#no_url_specified";
    }); // Invoke embl-content-meta-properties function to pull tags from page

    emblBreadcrumbsLookup(emblContentMetaProperties_Read());
  }, function (error) {
    console.warn("Failed to get EMBL ontology", error);
    var emblBreadcrumbTarget = document.querySelectorAll("[data-embl-js-breadcrumbs-lookup]");

    if (emblBreadcrumbTarget.length > 0) {
      emblBreadcrumbTarget[0].innerHTML = "<!-- Breadcrumbs failed to render due to network issue -->";
    }
  });
} // Prepend polyfill for IE
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
})([Element.prototype, Document.prototype, DocumentFragment.prototype]); // Run it on default
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
vfNavigationOnThisPage();
emblContentHub();
emblBreadcrumbs(); // if you use embl-content-hub-loader, it will automatically invoke emblNotifications
// emblNotifications();