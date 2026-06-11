(function() {
  var config = window.vfEventsThankYouAdmin || {};
  var enableFieldName = 'acf[field_vf_events_enable_thank_you_pages]';
  var warningId = 'vf-events-thank-you-disable-warning';
  var resultNoticeId = 'vf-events-thank-you-disable-result';

  function ensureInput(form, name) {
    var input = form.querySelector('input[name="' + name + '"]:not(:disabled)');

    if (!input) {
      input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      form.appendChild(input);
    }

    return input;
  }

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

  function wrapSettingsFields() {
    var toggle = getToggle(document);
    var firstField = toggle ? getToggleField(toggle) : null;
    var parent = firstField ? firstField.parentNode : null;
    var fields;
    var box;

    if (!firstField || firstField.closest('[data-vf-events-thank-you-settings-box]')) {
      return;
    }

    fields = [
      firstField,
      document.querySelector('.acf-field[data-name="vf_events_thank_you_selected_events"]'),
      document.querySelector('.acf-field[data-name="vf_events_thank_you_builder"]')
    ].filter(function(field, index, items) {
      return field && field.parentNode === parent && items.indexOf(field) === index;
    });

    if (!fields.length || !parent) {
      return;
    }

    box = document.createElement('div');
    box.className = 'vf-events-thank-you-settings-box';
    box.setAttribute('data-vf-events-thank-you-settings-box', '');
    parent.insertBefore(box, firstField);

    fields.forEach(function(field) {
      box.appendChild(field);
    });
  }

  function getWarning(toggle) {
    var field = getToggleField(toggle);
    var warning = document.getElementById(warningId);

    if (!warning) {
      var message = document.createElement('p');

      warning = document.createElement('div');
      warning.id = warningId;
      warning.className = 'notice notice-warning inline vf-events-thank-you-disable-warning';
      warning.hidden = true;
      message.textContent = config.warning || '';
      warning.appendChild(message);
      field.parentNode.insertBefore(warning, field.nextSibling);
    }

    return warning;
  }

  function showToggleNotice(toggle, message, type) {
    var field = getToggleField(toggle);
    var notice = document.getElementById(resultNoticeId);
    var paragraph;

    if (!notice) {
      notice = document.createElement('div');
      notice.id = resultNoticeId;
      field.parentNode.insertBefore(notice, field.nextSibling);
    }

    notice.className = 'notice notice-' + type + ' inline is-dismissible vf-events-thank-you-disable-result';
    notice.innerHTML = '';
    paragraph = document.createElement('p');
    paragraph.textContent = message || '';
    notice.appendChild(paragraph);
    notice.hidden = false;
  }

  function clearToggleNotice() {
    var notice = document.getElementById(resultNoticeId);

    if (notice) {
      notice.hidden = true;
      notice.innerHTML = '';
    }
  }

  function setDisableConfirmed(form) {
    ensureInput(form, 'vf_events_thank_you_action').value = 'disable';
    ensureInput(form, 'vf_events_disable_thank_you_confirmed').value = '1';
    ensureInput(form, 'vf_events_delete_thank_you_nonce').value = config.deleteNonce || '';
  }

  function clearDisableConfirmed(form) {
    ensureInput(form, 'vf_events_disable_thank_you_confirmed').value = '';

    if (ensureInput(form, 'vf_events_thank_you_action').value === 'disable') {
      ensureInput(form, 'vf_events_thank_you_action').value = '';
    }
  }

  function getBuilderEventFields(form) {
    return form.querySelectorAll(
      '[name="acf[field_vf_events_thank_you_selected_events]"],' +
      '[name="acf[field_vf_events_thank_you_selected_events][]"]'
    );
  }

  function getUnavailableEventIds() {
    return (config.unavailableEventIds || []).map(function(id) {
      return String(id);
    });
  }

  function isUnavailableEventId(id) {
    return getUnavailableEventIds().indexOf(String(id)) !== -1;
  }

  function getAvailableEvents() {
    return (config.availableEvents || []).filter(function(event) {
      return event && event.id && !isUnavailableEventId(event.id);
    });
  }

  function setBuilderMetadata(data) {
    if (!data) {
      return;
    }

    if (data.availableEvents) {
      config.availableEvents = data.availableEvents;
    }

    if (data.unavailableEventIds) {
      config.unavailableEventIds = data.unavailableEventIds;
    }
  }

  function triggerFieldChange(field) {
    if (window.jQuery) {
      window.jQuery(field).trigger('change');
    } else if (typeof window.Event === 'function') {
      field.dispatchEvent(new window.Event('change', { bubbles: true }));
    }
  }

  function setDisabledBuilderOptions(form) {
    var fields = getBuilderEventFields(form || document);

    Array.prototype.forEach.call(fields, function(field) {
      if (field.tagName !== 'SELECT') {
        return;
      }

      Array.prototype.forEach.call(field.options, function(option) {
        option.disabled = option.value ? isUnavailableEventId(option.value) : false;
        option.classList.toggle(
          'vf-events-thank-you-option--disabled',
          option.disabled
        );
      });
    });
  }

  function removeUnavailableSelections(form) {
    var fields = getBuilderEventFields(form || document);
    var changed = false;

    Array.prototype.forEach.call(fields, function(field) {
      var fieldChanged = false;

      if (field.tagName === 'SELECT') {
        Array.prototype.forEach.call(field.options, function(option) {
          if (option.selected && isUnavailableEventId(option.value)) {
            option.selected = false;
            fieldChanged = true;
          }
        });

        if (fieldChanged) {
          triggerFieldChange(field);
          changed = true;
        }

        return;
      }

      if (!field.value) {
        return;
      }

      var ids = field.value.split(',').filter(function(id) {
        return !isUnavailableEventId(String(id).trim());
      });
      var value = ids.join(',');

      if (field.value !== value) {
        field.value = value;
        fieldChanged = true;
      }

      if (fieldChanged) {
        changed = true;
      }
    });

    return changed;
  }

  function refreshBuilderPicker(form) {
    setDisabledBuilderOptions(form);
    removeUnavailableSelections(form);
  }

  function getBuilderEventIds(form) {
    var ids = [];
    var fields = getBuilderEventFields(form);

    Array.prototype.forEach.call(fields, function(field) {
      if (field.tagName === 'SELECT') {
        Array.prototype.forEach.call(field.options, function(option) {
          if (option.selected && option.value) {
            ids.push(option.value);
          }
        });

        return;
      }

      if (field.value) {
        ids = ids.concat(field.value.split(','));
      }
    });

    ids = ids.map(function(id) {
      return String(id).trim();
    });

    return ids.filter(function(id, index) {
      return id && !isUnavailableEventId(id) && ids.indexOf(id) === index;
    });
  }

  function clearBuilderEventFields(form) {
    var fields = getBuilderEventFields(form);

    Array.prototype.forEach.call(fields, function(field) {
      if (field.tagName === 'SELECT') {
        Array.prototype.forEach.call(field.options, function(option) {
          option.selected = false;
        });
      } else {
        field.value = '';
      }

      triggerFieldChange(field);
    });
  }

  function selectAllAvailableEvents(form) {
    var events = getAvailableEvents();
    var fields = getBuilderEventFields(form);

    Array.prototype.forEach.call(fields, function(field) {
      if (field.tagName === 'SELECT') {
        Array.prototype.forEach.call(field.options, function(option) {
          option.selected = false;
        });

        events.forEach(function(event) {
          var option = Array.prototype.find.call(field.options, function(existing) {
            return String(existing.value) === String(event.id);
          });

          if (!option) {
            option = new window.Option(event.text || event.id, event.id, false, false);
            field.appendChild(option);
          }

          option.disabled = false;
          option.selected = true;
        });

        triggerFieldChange(field);
        return;
      }

      field.value = events.map(function(event) {
        return event.id;
      }).join(',');
      triggerFieldChange(field);
    });

    refreshBuilderPicker(form);
  }

  function getBuilderRoot(form) {
    return form.querySelector('[data-vf-events-thank-you-builder]');
  }

  function showBuildNotice(form, message, type) {
    var root = getBuilderRoot(form);
    var notices = root ? root.querySelector('[data-vf-events-thank-you-notices]') : null;
    var notice = document.createElement('div');
    var paragraph = document.createElement('p');

    if (!notices) {
      return;
    }

    notice.className = 'notice notice-' + type + ' inline is-dismissible';
    paragraph.textContent = message || '';
    notice.appendChild(paragraph);
    notices.innerHTML = '';
    notices.appendChild(notice);
  }

  function clearBuildNotice(form) {
    var root = getBuilderRoot(form);
    var notices = root ? root.querySelector('[data-vf-events-thank-you-notices]') : null;

    if (notices) {
      notices.innerHTML = '';
    }
  }

  function updateBuildTable(form, html) {
    var root = getBuilderRoot(form);
    var table = root ? root.querySelector('[data-vf-events-thank-you-table]') : null;

    if (table && html) {
      table.innerHTML = html;
    }
  }

  function setButtonBusy(button, busy) {
    if (!button) {
      return;
    }

    button.disabled = busy;
    button.classList.toggle('disabled', busy);
  }

  function submitBuild(form, ids, button) {
    var data = new window.FormData();

    clearBuilderEventFields(form);

    data.append('action', 'vf_events_ajax_build_thank_you_pages');
    data.append('vf_events_thank_you_nonce', config.buildNonce || '');

    ids.forEach(function(id) {
      data.append('vf_events_thank_you_event_ids[]', id);
    });

    setButtonBusy(button, true);

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
              : 'Build failed.'
          );
        }

        showBuildNotice(form, response.data.message, 'success');
        updateBuildTable(form, response.data.tableHtml);
        setBuilderMetadata(response.data);
        refreshBuilderPicker(form);
      })
      .catch(function(error) {
        showBuildNotice(form, error.message, 'error');
      })
      .finally(function() {
        setButtonBusy(button, false);
      });
  }

  function submitDeleteAll(form, toggle) {
    var data = new window.FormData();

    data.append('action', 'vf_events_ajax_delete_all_thank_you_pages');
    data.append('vf_events_delete_thank_you_nonce', config.deleteNonce || '');

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
              : (config.deleteAllFailed || 'Delete failed.')
          );
        }

        showToggleNotice(toggle, response.data.message, 'error');
        clearBuildNotice(form);
        updateBuildTable(form, response.data.tableHtml);
        config.enabled = '0';
        setBuilderMetadata(response.data);
        refreshBuilderPicker(form);
        getWarning(toggle).hidden = true;
      })
      .catch(function(error) {
        showToggleNotice(toggle, error.message, 'error');
        clearBuildNotice(form);
        toggle.checked = true;
        getWarning(toggle).hidden = true;
        clearDisableConfirmed(form);
      });
  }

  function submitDeletePage(form, pageId, button) {
    var data = new window.FormData();

    data.append('action', 'vf_events_ajax_delete_thank_you_page');
    data.append('vf_events_delete_thank_you_nonce', config.deleteNonce || '');
    data.append('vf_events_thank_you_page_id', pageId || '');

    setButtonBusy(button, true);

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
              : (config.deleteAllFailed || 'Delete failed.')
          );
        }

        showBuildNotice(form, response.data.message, 'error');
        updateBuildTable(form, response.data.tableHtml);
        setBuilderMetadata(response.data);
        refreshBuilderPicker(form);
      })
      .catch(function(error) {
        showBuildNotice(form, error.message, 'error');
      })
      .finally(function() {
        setButtonBusy(button, false);
      });
  }

  function submitSetEnabled(form, enabled) {
    var data = new window.FormData();

    data.append('action', 'vf_events_ajax_set_thank_you_pages_enabled');
    data.append('vf_events_thank_you_nonce', config.buildNonce || '');
    data.append('enabled', enabled ? '1' : '');

    return window.fetch(config.ajaxUrl || '', {
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

        if (enabled) {
          clearToggleNotice();
          clearBuildNotice(form);
        }

        if (response.data.message) {
          showBuildNotice(form, response.data.message, 'success');
        }

        return response;
      });
  }

  function ensureEnabledForBuild(form) {
    var toggle = getToggle(form);

    if (!toggle || !toggle.checked || config.enabled === '1') {
      return window.Promise.resolve();
    }

    return submitSetEnabled(form, true);
  }

  function confirmDisable(form, toggle) {
    if (window.confirm(config.confirm || config.warning || '')) {
      setDisableConfirmed(form);
      submitDeleteAll(form, toggle);
      return true;
    }

    toggle.checked = true;
    getWarning(toggle).hidden = true;
    clearDisableConfirmed(form);
    return false;
  }

  function markOpenSelect2Results() {
    if (!window.jQuery) {
      return;
    }

    window.jQuery('.select2-results__option').each(function() {
      var option = window.jQuery(this);
      var data = option.data('data');
      var id = data && data.id ? data.id : '';

      if (!id) {
        id = String(option.attr('id') || '').split('-').pop();
      }

      if (!id || !isUnavailableEventId(id)) {
        return;
      }

      option
        .addClass('vf-events-thank-you-option--disabled')
        .attr('aria-disabled', 'true');
    });
  }

  function bindSelect2Guards() {
    if (!window.jQuery) {
      return;
    }

    var selector =
      '[name="acf[field_vf_events_thank_you_selected_events]"],' +
      '[name="acf[field_vf_events_thank_you_selected_events][]"]';

    window.jQuery(document)
      .on('select2:open select2:results:all select2:results:append', selector, function() {
        window.setTimeout(markOpenSelect2Results, 0);
      })
      .on('select2:selecting', selector, function(event) {
        var params = event.params || {};
        var data = params.args && params.args.data ? params.args.data : params.data;

        if (data && data.id && isUnavailableEventId(data.id)) {
          event.preventDefault();
          markOpenSelect2Results();
        }
      })
      .on('select2:select', selector, function(event) {
        var params = event.params || {};
        var data = params.data || {};

        if (data.id && isUnavailableEventId(data.id)) {
          removeUnavailableSelections(event.target.form || document);
          markOpenSelect2Results();
        }
      });
  }

  function observeSelect2Results() {
    if (typeof window.MutationObserver !== 'function') {
      return;
    }

    function isSelect2ResultNode(node) {
      return node.nodeType === 1 && (
        node.classList.contains('select2-results__option') ||
        node.classList.contains('select2-results') ||
        (node.querySelector && node.querySelector('.select2-results__option'))
      );
    }

    var observer = new window.MutationObserver(function(mutations) {
      var shouldMark = mutations.some(function(mutation) {
        return Array.prototype.some.call(mutation.addedNodes || [], isSelect2ResultNode);
      });

      if (shouldMark) {
        markOpenSelect2Results();
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

  document.addEventListener('click', function(event) {
    var button = event.target.closest ? event.target.closest('button') : null;

    if (!button || !button.form) {
      return;
    }

    if (button.hasAttribute('data-vf-events-thank-you-select-all')) {
      event.preventDefault();
      event.stopImmediatePropagation();
      selectAllAvailableEvents(button.form);
      return false;
    }

    if (button.name === 'vf_events_build_thank_you_pages') {
      var ids = getBuilderEventIds(button.form);

      event.preventDefault();
      event.stopImmediatePropagation();
      ensureInput(button.form, 'vf_events_thank_you_action').value = 'build';
      setButtonBusy(button, true);
      ensureEnabledForBuild(button.form)
        .then(function() {
          submitBuild(button.form, ids, button);
        })
        .catch(function(error) {
          showBuildNotice(button.form, error.message, 'error');
          setButtonBusy(button, false);
        });
      return false;
    }

    if (button.name === 'vf_events_delete_thank_you_page') {
      event.preventDefault();
      event.stopImmediatePropagation();
      ensureInput(button.form, 'vf_events_thank_you_action').value = 'delete';
      ensureInput(button.form, 'vf_events_thank_you_page_id').value = button.value;
      ensureInput(button.form, 'vf_events_delete_thank_you_nonce').value = config.deleteNonce || '';
      submitDeletePage(button.form, button.value, button);
      return false;
    }
  }, true);

  document.addEventListener('change', function(event) {
    var toggle = event.target;

    if (!toggle || toggle.name !== enableFieldName || toggle.type !== 'checkbox') {
      if (event.target && event.target.form) {
        refreshBuilderPicker(event.target.form);
      }

      return;
    }

    if (config.enabled !== '1') {
      if (toggle.checked) {
        clearToggleNotice();
        clearBuildNotice(toggle.form);
        submitSetEnabled(toggle.form, true)
          .catch(function(error) {
            showBuildNotice(toggle.form, error.message, 'error');
            toggle.checked = false;
          });
      }

      return;
    }

    if (!toggle.checked) {
      window.setTimeout(function() {
        if (
          toggle.checked ||
          ensureInput(toggle.form, 'vf_events_disable_thank_you_confirmed').value === '1'
        ) {
          return;
        }

        confirmDisable(toggle.form, toggle);
      }, 0);

      return;
    }

    getWarning(toggle).hidden = true;
    clearToggleNotice();
    clearBuildNotice(toggle.form);
    clearDisableConfirmed(toggle.form);
  }, true);

  document.addEventListener('submit', function(event) {
    var form = event.target;

    if (!form || !form.querySelector('[name="' + enableFieldName + '"]')) {
      return;
    }

    var toggle = getToggle(form);
    var confirmed = ensureInput(form, 'vf_events_disable_thank_you_confirmed');

    if (
      config.enabled === '1' &&
      toggle &&
      !toggle.checked &&
      confirmed.value !== '1' &&
      !confirmDisable(form, toggle)
    ) {
      event.preventDefault();
      event.stopImmediatePropagation();
      return false;
    }
  }, true);

  bindSelect2Guards();
  observeSelect2Results();
  wrapSettingsFields();
  refreshBuilderPicker(document);
})();
