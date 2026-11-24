(function () {
  'use strict';
  document.querySelectorAll('[data-ells-interactive]').forEach(function (activity) {
    var panels = Array.from(activity.querySelectorAll('[data-activity-panel]'));
    var frames = Array.from(activity.querySelectorAll('[data-activity-frame]'));
    var steps = Array.from(activity.querySelectorAll('[data-step]'));
    var back = activity.querySelector('[data-activity-back]');
    var next = activity.querySelector('[data-activity-next]');
    var current = 0;

    function show(index, focus) {
      current = index;
      panels.forEach(function (panel, i) { panel.hidden = i !== index; });
      steps.forEach(function (button, i) {
        if (i === index) button.setAttribute('aria-current', 'step');
        else button.removeAttribute('aria-current');
      });
      activity.querySelector('[data-activity-status]').textContent = 'Step ' + (index + 1) + ' of ' + panels.length;
      back.disabled = index === 0;
      next.hidden = index === panels.length - 1;
      // Hidden frames retain their answers; ask for a new measurement after reveal.
      frames[index].contentWindow.postMessage({type: 'ells:measure'}, '*');
      if (focus) panels[index].querySelector('h3').focus();
    }

    steps.forEach(function (button, i) {
      button.addEventListener('click', function () { show(i, true); });
    });
    back.addEventListener('click', function () { if (current > 0) show(current - 1, true); });
    next.addEventListener('click', function () { if (current < panels.length - 1) show(current + 1, true); });
    window.addEventListener('message', function (event) {
      // Sandboxed frames have opaque origins: validate the exact window, not origin.
      var frame = frames.find(function (item) { return item.contentWindow === event.source; });
      if (!frame || !event.data || event.data.type !== 'ells:height') return;
      var height = event.data.height;
      if (typeof height !== 'number' || !Number.isFinite(height) || height < 1 || height > 20000) return;
      frame.style.height = Math.ceil(height) + 'px';
    });
    frames.forEach(function (frame) {
      frame.addEventListener('load', function () {
        frame.contentWindow.postMessage({type: 'ells:measure'}, '*');
      });
    });
    activity.querySelector('[data-activity-nav]').hidden = false;
    activity.querySelector('[data-activity-controls]').hidden = false;
    show(0, false);
  });
})();
