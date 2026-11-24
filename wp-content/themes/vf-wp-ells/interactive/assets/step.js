(function () {
  'use strict';
  // Give legacy table controls accessible names without changing answer logic.
  document.querySelectorAll('select, input').forEach(function (control) {
    if (control.labels.length || control.hasAttribute('aria-label')) return;
    var cell = control.closest('td');
    var row = control.closest('tr');
    var table = control.closest('table');
    var heading = table && cell ? table.querySelectorAll('thead th')[cell.cellIndex] : null;
    control.setAttribute('aria-label', [heading && heading.textContent.trim(), row && row.cells[0].textContent.trim()].filter(Boolean).join(' — ') || control.id);
  });
  document.querySelectorAll('#feedback, #summary').forEach(function (element) {
    element.setAttribute('aria-live', 'polite');
  });
  var scheduled = false;
  var lastHeight = 0;
  function measure() {
    if (scheduled) return;
    scheduled = true;
    requestAnimationFrame(function () {
      scheduled = false;
      // Body content height can shrink, unlike documentElement.scrollHeight.
      var height = Math.ceil(document.body.getBoundingClientRect().height) + 2;
      if (height > 2 && height !== lastHeight) {
        lastHeight = height;
        window.parent.postMessage({type: 'ells:height', height: height}, '*');
      }
    });
  }
  window.addEventListener('message', function (event) {
    if (event.source === window.parent && event.data && event.data.type === 'ells:measure') {
      lastHeight = 0;
      measure();
    }
  });
  new ResizeObserver(measure).observe(document.body);
  window.addEventListener('load', measure);
  window.addEventListener('resize', measure);
  measure();
})();
