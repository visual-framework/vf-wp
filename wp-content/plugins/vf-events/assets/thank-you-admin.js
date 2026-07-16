(function() {
  var config = window.vfEventsThankYouAdmin || {};
  var enableFieldName = 'acf[field_vf_events_enable_thank_you_pages]';
  var noticeId = 'vf-events-thank-you-toggle-result';

  function getToggle(form) {
    return form.querySelector('input[name="' + enableFieldName + '"][type="checkbox"]');
  }

  function getToggleField(toggle) {
    var node = toggle;

    while (node && node !== document.body) {
      if (node.classList && node.classList.contains('acf-field')) {
        return node;
      }

      node = node.parentNode;
    }

    return toggle.parentNode;
  }

  function wrapSettingsField() {
    var toggle = getToggle(document);
    var field = toggle ? getToggleField(toggle) : null;
    var parent = field ? field.parentNode : null;
    var box;

    if (!field || !parent || field.closest('[data-vf-events-thank-you-settings-box]')) {
      return;
    }

    box = document.createElement('div');
    box.className = 'vf-events-thank-you-settings-box';
    box.setAttribute('data-vf-events-thank-you-settings-box', '');
    parent.insertBefore(box, field);
    box.appendChild(field);
  }

  function showNotice(toggle, message, type) {
    var field = getToggleField(toggle);
    var notice = document.getElementById(noticeId);
    var paragraph;

    if (!notice) {
      notice = document.createElement('div');
      notice.id = noticeId;
      field.parentNode.insertBefore(notice, field.nextSibling);
    }

    notice.className = 'notice notice-' + type + ' inline is-dismissible vf-events-thank-you-toggle-result';
    notice.innerHTML = '';
    paragraph = document.createElement('p');
    paragraph.textContent = message || '';
    notice.appendChild(paragraph);
    notice.hidden = false;
  }

  function clearNotice() {
    var notice = document.getElementById(noticeId);

    if (notice) {
      notice.hidden = true;
      notice.innerHTML = '';
    }
  }

  function setToggleBusy(toggle, busy) {
    toggle.disabled = busy;
    getToggleField(toggle).classList.toggle('is-busy', busy);
  }

  function saveEnabled(toggle) {
    var data = new window.FormData();
    var enabled = toggle.checked;

    data.append('action', 'vf_events_ajax_set_thank_you_pages_enabled');
    data.append('vf_events_thank_you_nonce', config.buildNonce || '');
    data.append('enabled', enabled ? '1' : '');

    setToggleBusy(toggle, true);

    window.fetch(config.ajaxUrl || '', {
      method: 'POST',
      credentials: 'same-origin',
      body: data
    })
      .then(function(response) {
        return response.json();
      })
      .then(function(response) {
        if (!response || !response.success) {
          throw new Error(
            response && response.data && response.data.message
              ? response.data.message
              : 'Settings update failed.'
          );
        }

        config.enabled = response.data.enabled;

        if (response.data.message) {
          showNotice(toggle, response.data.message, enabled ? 'success' : 'warning');
        } else {
          clearNotice();
        }
      })
      .catch(function(error) {
        toggle.checked = !enabled;
        showNotice(toggle, error.message, 'error');
      })
      .finally(function() {
        setToggleBusy(toggle, false);
      });
  }

  document.addEventListener('change', function(event) {
    var toggle = event.target;

    if (!toggle || toggle.name !== enableFieldName || toggle.type !== 'checkbox') {
      return;
    }

    saveEnabled(toggle);
  }, true);

  wrapSettingsField();
})();
