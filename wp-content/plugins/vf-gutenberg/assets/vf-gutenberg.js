(() => {
  const waitForSelector = (selector, parent, callback) => {
    const existing = parent.querySelector(selector);
    if (existing) {
      callback(existing);
      return;
    }

    const observer = new MutationObserver((records, observer) => {
      for (const record of records) {
        for (const node of record.addedNodes) {
          if (node.nodeType === 1) {
            if (node.matches(selector)) {
              callback(node);
              return;
            }
            const target = node.querySelector(selector);
            if (target) {
              callback(target);
              return;
            }
          }
        }
        // if (record.type === 'childList') {
        //   const target = parent.querySelector(selector);
        //   if (target) {
        //     // observer.disconnect();
        //     callback(target);
        //     // return;
        //   }
        // }
      }
    });
    observer.observe(parent, {childList: true, subtree: true});
  };

  // Handle the `is_container` ACF field toggle
  const updateContainer = (acfId, isContainer) => {
    try {
      const block = findBlockByAcfId(acfId);
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

  const editorDocumentMap = new WeakMap();
  const wrapperMap = new WeakMap();
  const messageWindowMap = new WeakMap();

  const getEditorIframe = () => {
    return document.querySelector(
      [
        'iframe[name="editor-canvas"]',
        'iframe.block-editor-iframe__html',
        'iframe[title="Editor canvas"]',
        'iframe[title="Editor Canvas"]'
      ].join(',')
    );
  };

  const forEachEditorIframe = (callback) => {
    document
      .querySelectorAll(
        [
          'iframe[name="editor-canvas"]',
          'iframe.block-editor-iframe__html',
          'iframe[title="Editor canvas"]',
          'iframe[title="Editor Canvas"]'
        ].join(',')
      )
      .forEach(callback);
  };

  const getEditorDocuments = () => {
    const documents = [document];
    const iframe = getEditorIframe();

    if (iframe && iframe.contentDocument) {
      documents.push(iframe.contentDocument);
    }

    return documents;
  };

  const findBlockByAcfId = (acfId) => {
    for (const doc of getEditorDocuments()) {
      const block = doc.querySelector(`.wp-block[data-block*="${acfId}"]`);

      if (block) {
        return block;
      }
    }

    return null;
  };

  const handleResizeMessage = (doc, data) => {
    if (data !== Object(data) || !/^(vfblock_|vfwp_)/.test(data.id)) {
      return;
    }
    const iframe = doc.getElementById(data.id);
    if (iframe) {
      iframe.style.height = `${data.height}px`;
    }
  };

  const listenForResizeMessages = (win, doc) => {
    if (!win || !doc || messageWindowMap.has(win)) {
      return;
    }

    messageWindowMap.set(win, true);
    win.addEventListener('message', ({data}) => {
      handleResizeMessage(doc, data);
    });
  };

  // postMessage from iframe to resize height
  listenForResizeMessages(window, document);

  const iframes = new WeakMap();

  const getPreviewRoot = (node) => {
    return [...node.querySelectorAll('.vf-block')].find((preview) => {
      return preview.closest('.wp-block[data-type^="acf/vf"]') === node;
    });
  };

  const getDirectChild = (node, tagName) => {
    return [...node.children].find((child) => child.tagName === tagName) || null;
  };

  const getRelatedBlock = (node) => {
    if (!node || node.nodeType !== 1) {
      return null;
    }
    if (node.matches('.wp-block[data-type^="acf/vf"]')) {
      return node;
    }
    return node.closest('.wp-block[data-type^="acf/vf"]');
  };

  const renderBlock = (node) => {
    const block = getPreviewRoot(node);
    if (!block) {
      return;
    }

    const existingIframe = getDirectChild(block, 'IFRAME');
    const template = getDirectChild(block, 'TEMPLATE');
    if (!template) {
      if (existingIframe) {
        return;
      }
      requestAnimationFrame(() => {
        renderBlock(node);
      });
      return;
    }

    if (
      existingIframe &&
      existingIframe.dataset.srcdoc === template.innerHTML &&
      existingIframe.vfActive
    ) {
      return;
    }

    if (existingIframe) {
      existingIframe.remove();
    }

    const iframe = node.ownerDocument.createElement('iframe');
    iframe.id = getId();
    iframe.classList.add('vf-block__iframe');
    iframe.style.overflow = 'hidden';
    iframe.scrolling = 'no';
    iframe.dataset.srcdoc = template.innerHTML;

    const resizeIframeToContent = () => {
      const doc = iframe.contentWindow && iframe.contentWindow.document;
      const render = doc && doc.getElementById(iframe.id);
      if (!render) {
        return;
      }

      const renderRect = render.getBoundingClientRect();
      let height = Math.max(
        renderRect.height,
        render.scrollHeight,
        render.offsetHeight
      );

      // Include positioned or floated children that do not increase the
      // wrapper's normal-flow height.
      Array.prototype.forEach.call(render.children, (child) => {
        const childRect = child.getBoundingClientRect();
        height = Math.max(height, childRect.bottom - renderRect.top);
      });

      if (height > 0) {
        iframe.style.height = `${Math.ceil(height)}px`;
      }
    };

    const observeIframeContent = (render) => {
      const iframeWindow = iframe.contentWindow;
      if (iframeWindow && 'ResizeObserver' in iframeWindow) {
        const resizeObserver = new iframeWindow.ResizeObserver(
          resizeIframeToContent
        );
        resizeObserver.observe(render);
        Array.prototype.forEach.call(render.children, (child) => {
          resizeObserver.observe(child);
        });
        iframe.vfResizeObserver = resizeObserver;
      }

      Array.prototype.forEach.call(render.querySelectorAll('img'), (image) => {
        if (!image.complete) {
          image.addEventListener('load', resizeIframeToContent, {once: true});
          image.addEventListener('error', resizeIframeToContent, {once: true});
        }
      });

      resizeIframeToContent();
      [50, 100, 500, 1000, 2000].forEach((delay) => {
        iframeWindow.setTimeout(resizeIframeToContent, delay);
      });
      if (iframeWindow.document.fonts && iframeWindow.document.fonts.ready) {
        iframeWindow.document.fonts.ready.then(resizeIframeToContent);
      }
    };

    const prepareIframeRender = () => {
      const doc = iframe.contentWindow && iframe.contentWindow.document;
      if (!doc || !doc.body || iframe.vfActive) {
        return;
      }
      if (!doc.getElementById('vf-block-render-js')) {
        return;
      }

      const render = doc.createElement('div');
      render.id = iframe.id;
      render.classList.add('vf-block-render');
      render.innerHTML = doc.body.innerHTML;
      doc.body.innerHTML = '';
      doc.body.appendChild(render);
      iframe.vfActive = true;
      observeIframeContent(render);
    };

    iframe.addEventListener('load', prepareIframeRender);
    iframe.srcdoc = template.innerHTML;

    block.appendChild(iframe);
    requestAnimationFrame(prepareIframeRender);
    iframes.set(node, iframe);

    if (template.hasAttribute('data-is-container')) {
      updateContainer(node?.dataset?.block, true);
    }
  };

  const observer = new MutationObserver((records, observer) => {
    const newBlocks = [];
    for (const record of records) {
      for (const node of record.addedNodes) {
        if (node.nodeType !== 1) {
          continue;
        }
        const block = getRelatedBlock(node);
        if (block) {
          newBlocks.push(block);
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

  const initEditorDocument = (doc, win) => {
    if (!doc || editorDocumentMap.has(doc)) {
      return;
    }

    editorDocumentMap.set(doc, true);
    listenForResizeMessages(win, doc);

    waitForSelector('.editor-styles-wrapper', doc, (wrapper) => {
    // Handle the `is_container` ACF field toggle
    if (window.acf && !initEditorDocument.initACF) {
      initEditorDocument.initACF = true;
      acf.addAction('append_field/name=is_container', (field) => {
        field.on('change', 'input[type="checkbox"]', (ev) => {
          const acfId = ev.target.id
            .replace(/^acf-/, '')
            .replace(/-field_.+$/, '');
          updateContainer(acfId, field.val());
        });
      });
    }

    // Skip if already initialised
    if (wrapperMap.has(wrapper)) {
      return;
    }
    wrapperMap.set(wrapper, true);

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
  });
  };

  const initEditorIframe = (iframe) => {
    if (!iframe) {
      return;
    }

    const init = () => {
      try {
        initEditorDocument(iframe.contentDocument, iframe.contentWindow);
      } catch {
        // Ignore cross-origin or unavailable editor iframes.
      }
    };

    iframe.addEventListener('load', init);
    init();
  };

  const initAvailableEditors = () => {
    initEditorDocument(document, window);
    forEachEditorIframe(initEditorIframe);
  };

  initAvailableEditors();

  const editorIframeObserver = new MutationObserver(initAvailableEditors);
  editorIframeObserver.observe(document, {
    childList: true,
    subtree: true
  });
})();
