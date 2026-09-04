(function () {
  'use strict';

  var config = window.vfwpSearchSuggestions || {};
  var minLength = parseInt(config.minLength, 10) || 2;
  var debounceTimer = null;
  var activeRequest = null;
  var requestId = 0;

  function initForm(form) {
    var input = form.querySelector('#searchitem');
    var list = form.querySelector('#vf-form--search__results-list');

    if (!input || !list || !config.ajaxUrl || !config.action) {
      return;
    }

    var suggestions = [];
    var activeIndex = -1;

    function hideList() {
      suggestions = [];
      activeIndex = -1;
      list.innerHTML = '';
      list.hidden = true;
      list.removeAttribute('data-visible');
      input.setAttribute('aria-expanded', 'false');
      input.removeAttribute('aria-activedescendant');
    }

    function setActive(index) {
      var items = list.querySelectorAll('[role="option"]');
      activeIndex = index;

      items.forEach(function (item, itemIndex) {
        var isActive = itemIndex === activeIndex;
        item.setAttribute('aria-selected', isActive ? 'true' : 'false');

        if (isActive) {
          input.setAttribute('aria-activedescendant', item.id);
        }
      });

      if (activeIndex < 0) {
        input.removeAttribute('aria-activedescendant');
      }
    }

    function selectSuggestion(suggestion) {
      if (!suggestion) {
        return;
      }

      input.value = suggestion.value || suggestion.label || '';

      if (suggestion.type === 'result' && suggestion.url) {
        window.location.assign(suggestion.url);
        return;
      }

      form.submit();
    }

    function render(items) {
      list.innerHTML = '';
      suggestions = Array.isArray(items) ? items : [];

      if (!suggestions.length) {
        hideList();
        return;
      }

      suggestions.forEach(function (suggestion, index) {
        var item = document.createElement('li');
        var label = document.createElement('span');
        var badgeLabel = suggestion.badge_label || '';
        var externalDomainLabel = suggestion.external_domain_label || '';
        var badgeGroup = null;

        item.id = 'vf-form--search__results-list--' + String(index + 1).padStart(2, '0');
        item.className = 'vf-list__item';
        if (suggestion.is_primary) {
          item.className += ' vf-form--search__results-list-item--search';
        }
        if (suggestion.is_phrase) {
          item.className += ' vf-form--search__results-list-item--phrase';
        }
        item.setAttribute('role', 'option');
        item.setAttribute('aria-selected', 'false');
        item.tabIndex = -1;

        label.className = 'vf-form--search__results-label';
        label.textContent = suggestion.label || suggestion.value || '';
        item.appendChild(label);

        if (badgeLabel || externalDomainLabel) {
          badgeGroup = document.createElement('span');
          badgeGroup.className = 'vf-form--search__results-badges';
          item.appendChild(badgeGroup);
        }

        if (badgeGroup && badgeLabel) {
          var badge = document.createElement('span');
          badge.className = 'vf-badge vf-badge--tertiary vf-form--search__results-badge';
          badge.textContent = badgeLabel;
          badgeGroup.appendChild(badge);
          item.setAttribute('aria-label', label.textContent + ' ' + badgeLabel);
        }

        if (badgeGroup && externalDomainLabel) {
          var externalBadge = document.createElement('span');
          var externalIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
          var externalIconPathOne = document.createElementNS('http://www.w3.org/2000/svg', 'path');
          var externalIconPathTwo = document.createElementNS('http://www.w3.org/2000/svg', 'path');

          externalBadge.className = 'vf-badge vf-badge--tertiary vf-search-result__external-pill vf-form--search__results-external-pill';
          externalBadge.textContent = externalDomainLabel.toLowerCase();

          externalIcon.setAttribute('class', 'vf-search-result__external-pill-icon');
          externalIcon.setAttribute('viewBox', '0 0 24 24');
          externalIcon.setAttribute('focusable', 'false');
          externalIcon.setAttribute('aria-hidden', 'true');
          externalIconPathOne.setAttribute('d', 'M14 3h7v7h-2V6.41l-9.29 9.3-1.42-1.42 9.3-9.29H14V3Z');
          externalIconPathTwo.setAttribute('d', 'M5 5h6v2H7v10h10v-4h2v6H5V5Z');
          externalIcon.appendChild(externalIconPathOne);
          externalIcon.appendChild(externalIconPathTwo);
          externalBadge.appendChild(externalIcon);
          badgeGroup.appendChild(externalBadge);
          item.setAttribute('aria-label', label.textContent + ' ' + [badgeLabel, externalDomainLabel].filter(Boolean).join(' '));
        }

        item.addEventListener('mousedown', function (event) {
          event.preventDefault();
        });

        item.addEventListener('mouseenter', function () {
          setActive(index);
        });

        item.addEventListener('click', function () {
          selectSuggestion(suggestion);
        });

        list.appendChild(item);
      });

      list.hidden = false;
      list.setAttribute('data-visible', 'true');
      input.setAttribute('aria-expanded', 'true');
      setActive(-1);
    }

    function buildRequestUrl(query) {
      var params = new URLSearchParams();
      var filterInputs = form.querySelectorAll('input[name="search_type[]"]');

      params.set('action', config.action);
      params.set('q', query);
      params.set('limit', '8');

      if (config.nonce) {
        params.set('nonce', config.nonce);
      }

      filterInputs.forEach(function (filterInput) {
        if ((filterInput.type === 'checkbox' || filterInput.type === 'radio') && !filterInput.checked) {
          return;
        }

        if (filterInput.value) {
          params.append('search_type[]', filterInput.value);
        }
      });

      return config.ajaxUrl + '?' + params.toString();
    }

    function requestSuggestions() {
      var query = input.value.trim();
      var currentRequestId;

      if (query.length < minLength) {
        if (activeRequest) {
          activeRequest.abort();
          activeRequest = null;
        }

        hideList();
        return;
      }

      if (activeRequest) {
        activeRequest.abort();
      }

      currentRequestId = ++requestId;
      activeRequest = new AbortController();

      fetch(buildRequestUrl(query), {
        credentials: 'same-origin',
        signal: activeRequest.signal
      })
        .then(function (response) {
          return response.ok ? response.json() : null;
        })
        .then(function (payload) {
          if (currentRequestId !== requestId || !payload || !payload.success) {
            return;
          }

          render(payload.data && payload.data.suggestions ? payload.data.suggestions : []);
        })
        .catch(function (error) {
          if (error && error.name === 'AbortError') {
            return;
          }

          hideList();
        });
    }

    input.addEventListener('input', function () {
      window.clearTimeout(debounceTimer);
      debounceTimer = window.setTimeout(requestSuggestions, 250);
    });

    input.addEventListener('keydown', function (event) {
      if (list.hidden || !suggestions.length) {
        return;
      }

      if (event.key === 'ArrowDown') {
        event.preventDefault();
        setActive(activeIndex < suggestions.length - 1 ? activeIndex + 1 : 0);
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        setActive(activeIndex > 0 ? activeIndex - 1 : suggestions.length - 1);
      } else if (event.key === 'Enter' && activeIndex >= 0) {
        event.preventDefault();
        selectSuggestion(suggestions[activeIndex]);
      } else if (event.key === 'Escape') {
        hideList();
      }
    });

    input.addEventListener('blur', function () {
      window.setTimeout(hideList, 150);
    });

    form.addEventListener('submit', hideList);
    hideList();
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.vf-form--search').forEach(initForm);
  });
}());
