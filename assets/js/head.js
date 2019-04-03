(function() {
  // Toggle JavaScript class in <html>
  const $html = document.getElementsByTagName('html')[0];
  $html.className = $html.className.replace(/(^|\s)vf-no-js(\s|$)/, '$1vf-js$2');

  // // Cut the mustard...
  // if (!'querySelector' in document) {
  //   return;
  // }

  // const $head = document.querySelector('head');

  // // Namespace theme object
  // const VFWP = (window.VFWP = {});

  // // Detect and add IE class
  // VFWP.isIE = window.navigator.msPointerEnabled;
  // if (VFWP.isIE) {
  //   $html.className += ' js-ie';
  // }

  // VFWP.loadScript = (src, callback) => {
  //   const script = document.createElement('script');
  //   script.src = src;
  //   script.type = 'text/javascript';
  //   script.async = true;
  //   if (callback) {
  //     script.onload = script.onreadystatechange = () => {
  //       if (/^($|loaded|complete)/.test(script.readyState || '')) {
  //         script.onload = script.onreadystatechange = null;
  //         callback();
  //       }
  //     };
  //   }
  //   $head.appendChild(script);
  // };
})();
