(function($) {
  if (!window.acf || !window.vfPersonAutocomplete || !$ || !$.fn.select2) {
    return;
  }

  var config = window.vfPersonAutocomplete;
  var helperSelector = '.vf-person-record-select-helper';

  function getScope(field) {
    if (field.classList.contains('vf-person-scope-internal')) {
      return 'internal';
    }

    return 'public';
  }

  function getHelper($field) {
    var $helper = $field.find(helperSelector);

    if ($helper.length) {
      return $helper;
    }

    $helper = $(
      '<div class="vf-person-record-select-helper vf-banner vf-banner--alert vf-banner--info">' +
        '<div class="vf-banner__content">' +
          '<p class="vf-banner__text">Enter a search query to find a person.</p>' +
        '</div>' +
      '</div>'
    );

    $field.append($helper);

    return $helper;
  }

  function hasValue($select) {
    var value = $select.val();

    if (Array.isArray(value)) {
      value = value[0];
    }

    return !!(value && String(value).trim());
  }

  function updateHelper($field, isSearching) {
    var $select = $field.find('select');
    var $helper = getHelper($field);

    if (hasValue($select) || isSearching) {
      $helper.hide();
      return;
    }

    $helper.show();
  }

  function setupField(field) {
    var $field = $(field);
    var $select = $field.find('select');

    if (!$select.length) {
      return;
    }

    if ($select.data('select2')) {
      $select.select2('destroy');
    }

    $select.off('.vfPersonAutocomplete');

    $select.select2({
      ajax: {
        delay: 250,
        transport: function(params, success, failure) {
          var term = params.data && params.data.term ? params.data.term.trim() : '';

          if (term.length < config.minLength) {
            success({ results: [] });
            return null;
          }

          return $.ajax({
            url: config.ajaxUrl,
            dataType: 'json',
            data: {
              action: config.action,
              nonce: config.nonce,
              q: term,
              scope: getScope(field),
              limit: config.limit || 8
            },
            success: function(response) {
              var results = response && response.success && response.data && response.data.results
                ? response.data.results
                : [];

              success({ results: results });
            },
            error: failure
          });
        },
        processResults: function(data) {
          return data;
        }
      },
      allowClear: true,
      minimumInputLength: config.minLength,
      placeholder: 'Search for a person',
      width: '100%'
    });

    $select.on('select2:open.vfPersonAutocomplete', function() {
      updateHelper($field, true);

      window.setTimeout(function() {
        var $search = $('.select2-container--open .select2-search__field');

        $search.off('.vfPersonAutocomplete');
        $search.on('input.vfPersonAutocomplete', function() {
          updateHelper($field, !!this.value.trim());
        });
      }, 0);
    });

    $select.on('select2:close.vfPersonAutocomplete change.vfPersonAutocomplete', function() {
      updateHelper($field, false);
    });

    $select.attr('data-placeholder', 'Search for a person');
    $field.attr('data-vf-person-record-select-ready', '1');
    updateHelper($field, false);
  }

  function getRoot(context) {
    if (!context) {
      return document;
    }

    if (context.nodeType && typeof context.querySelectorAll === 'function') {
      return context;
    }

    if (context.jquery && context[0] && typeof context[0].querySelectorAll === 'function') {
      return context[0];
    }

    if (context.el && typeof context.el.querySelectorAll === 'function') {
      return context.el;
    }

    if (context.$el && context.$el[0] && typeof context.$el[0].querySelectorAll === 'function') {
      return context.$el[0];
    }

    return document;
  }

  function init(context) {
    var root = getRoot(context);

    root
      .querySelectorAll('.acf-field[data-name="full_name"].vf-person-record-select')
      .forEach(setupField);
  }

  window.acf.addAction('ready', init);
  window.acf.addAction('append', init);
})(window.jQuery);
