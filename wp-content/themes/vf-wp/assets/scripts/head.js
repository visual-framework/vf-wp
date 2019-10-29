/*! VF-WP */
// Toggle JavaScript class in <html>
var $html = document.getElementsByTagName('html')[0];
$html.className = $html.className.replace(/(^|\s)vf-no-js(\s|$)/, '$1vf-js$2');
