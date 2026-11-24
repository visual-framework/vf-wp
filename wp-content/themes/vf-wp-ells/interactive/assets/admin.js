(function () {
  'use strict';
  var rows = document.querySelector('[data-case-rows]');
  var add = document.querySelector('[data-case-add]');
  var index = rows ? rows.children.length : 0;
  if (add) add.addEventListener('click', function () {
    rows.insertAdjacentHTML('beforeend', document.querySelector('#ells-case-row-template').innerHTML.replace(/__INDEX__/g, String(index++)));
  });
  if (rows) rows.addEventListener('click', function (event) {
    var row = event.target.closest('.ells-case-row');
    if (!row) return;
    if (event.target.matches('[data-case-remove]')) row.remove();
    if (event.target.matches('[data-case-up]') && row.previousElementSibling) rows.insertBefore(row, row.previousElementSibling);
    if (event.target.matches('[data-case-down]') && row.nextElementSibling) rows.insertBefore(row.nextElementSibling, row);
  });
  var copy = document.querySelector('[data-case-copy]');
  if (copy) copy.addEventListener('click', async function () {
    var field = document.querySelector('[data-case-shortcode]');
    field.focus(); field.select();
    try {
      await navigator.clipboard.writeText(field.value);
      document.querySelector('[data-case-copy-status]').textContent = ' Copied.';
    } catch (_) {
      document.querySelector('[data-case-copy-status]').textContent = ' Selected. Press Ctrl+C or ⌘C to copy.';
    }
  });
})();
