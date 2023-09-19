(() => {
  const waitForSelector = (selector, parent, callback) => {
    const observer = new MutationObserver((records, observer) => {
      for (const record of records) {
        if (record.type === 'childList') {
          const target = parent.querySelector(selector);
          if (target) {
            observer.disconnect();
            callback(target);
            return;
          }
        }
      }
    });
    observer.observe(parent, {childList: true, subtree: true});
  };

  // Handle the `is_container` ACF field toggle
  const updateContainer = (acfId, isContainer) => {
    try {
      const block = document.querySelector(`.wp-block[data-block*="${acfId}"]`);
      if (isContainer) {
        block.style.maxWidth = 'none';
      } else {
        block.style.removeProperty('max-width');
      }
    } catch {
      // Do nothing...
    }
  };

  // Generate unique ID for each iframe
  let id = 0;
  const getId = () => {
    return `vfblock_${++id}`;
  };

  // postMessage from iframe to resize height
  window.addEventListener('message', ({data}) => {
    if (data !== Object(data) || !/^vfblock_/.test(data.id)) {
      return;
    }
    const iframe = document.getElementById(data.id);
    if (iframe) {
      iframe.style.height = `${data.height}px`;
    }
  });

  const iframes = new WeakMap();

  const renderBlock = (node) => {
    if (node.querySelector('.vf-block > iframe')) {
      return;
    }
    const template = node.querySelector(`.vf-block > template`);
    if (!template) {
      requestAnimationFrame(() => {
        renderBlock(node);
      });
      return;
    }
    const iframe = document.createElement('iframe');
    iframe.id = getId();
    iframe.classList.add('vf-block__iframe');
    iframe.style.overflow = 'hidden';
    iframe.scrolling = 'no';
    iframe.srcdoc = template.innerHTML;

    iframe.addEventListener(
      'load',
      () => {
        const doc = iframe.contentWindow.document;
        const render = document.createElement('div');
        render.id = iframe.id;
        render.classList.add('vf-block-render');
        render.innerHTML = doc.body.innerHTML;
        doc.body.innerHTML = '';
        doc.body.appendChild(render);
        iframe.vfActive = true;
      },
      {once: true}
    );

    node.querySelector(`.vf-block`).appendChild(iframe);
    iframes.set(node, iframe);

    if (template.hasAttribute('data-is-container')) {
      updateContainer(node?.dataset?.block, true);
    }
  };

  const observer = new MutationObserver((records, observer) => {
    const newBlocks = [];
    for (const record of records) {
      for (const node of record.addedNodes) {
        if (!node.classList) {
          continue;
        }
        if (!node.classList.contains('wp-block')) {
          continue;
        }
        if (node?.dataset?.type?.startsWith('acf/vf')) {
          newBlocks.push(node);
          continue;
        }
        [...node.querySelectorAll('.wp-block[data-type^="acf/vf"]')].forEach(
          (subBlock) => {
            newBlocks.push(subBlock);
          }
        );
      }
      for (const node of record.removedNodes) {
        if (!node?.classList) {
          continue;
        }
        if (
          node.classList.contains('vf-block-preview') ||
          node.classList.contains('acf-block-preview')
        ) {
          const parent = record.target.closest(
            '.wp-block[data-type^="acf/vf"]'
          );
          if (parent) {
            newBlocks.push(parent);
          }
        }
      }
    }
    newBlocks.forEach((node) => {
      renderBlock(node);
    });
  });

  waitForSelector('.editor-styles-wrapper', document, (wrapper) => {
    // Render existing blocks on first load
    [...wrapper.querySelectorAll('.wp-block[data-type^="acf/vf"]')].forEach(
      (node) => {
        renderBlock(node);
      }
    );

    // Observe for new blocks
    observer.observe(wrapper, {
      childList: true,
      subtree: true
    });

    // Handle the `is_container` ACF field toggle
    if (window.acf) {
      acf.addAction('append_field/name=is_container', (field) => {
        field.on('change', 'input[type="checkbox"]', (ev) => {
          const acfId = ev.target.id
            .replace(/^acf-/, '')
            .replace(/-field_.+$/, '');
          updateContainer(acfId, field.val());
        });
      });
    }
  });
})();
