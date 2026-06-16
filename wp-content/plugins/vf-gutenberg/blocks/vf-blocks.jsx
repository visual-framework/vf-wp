/**!
 * VF Gutenberg blocks
 */
import {registerBlockType} from '@wordpress/blocks';
import useVFGutenberg from './hooks/use-vf-gutenberg';

// Import Visual Framework core component settings
import vfBlockquote from './vf-core/vf-blockquote';
import vfBreadcrumbsItem from './vf-core/vf-breadcrumbs-item';
import vfBreadcrumbs from './vf-core/vf-breadcrumbs';

import vfCluster from './vf-core/vf-cluster';
import vfEmbed from './vf-core/vf-embed';
import vfGridColumn from './vf-core/vf-grid-column';
import vfEMBLGrid from './vf-core/vf-embl-grid';
import vfGrid from './vf-core/vf-grid';
import vfTabsSection from './vf-core/vf-tabs-section';
import vfTabs from './vf-core/vf-tabs';

// Import deprecated blocks replaced by ACF counterparts
import vfActivityItem from './vf-core/deprecated/vf-activity-item';
import vfActivityList from './vf-core/deprecated/vf-activity-list';
import vfBadge from './vf-core/deprecated/vf-badge';
import vfBox from './vf-core/deprecated/vf-box';
import vfButton from './vf-core/deprecated/vf-button';
import vfDivider from './vf-core/deprecated/vf-divider';
import vfLede from './vf-core/deprecated/vf-lede';

// Get "localized" global script settings
const {coreOptin} = useVFGutenberg();

// Register VF Core blocks
if (parseInt(coreOptin) === 1) {
  const coreBlocks = [
    //Tabs
    vfTabsSection,
    vfTabs,
    // Grid
    vfGridColumn,
    vfEMBLGrid,
    vfGrid,
    // Inner Blocks
    vfCluster,
    // Elements
    vfBadge,
    vfBlockquote,
    vfButton,
    vfDivider,
    // Blocks
    vfActivityItem,
    vfActivityList,
    vfBox,
    vfBreadcrumbsItem,
    vfBreadcrumbs,
    vfLede,
    vfEmbed
  ];
  coreBlocks.forEach(settings => registerBlockType(settings.name, settings));
}

// Register experimental preview block
import vfPlugin from './vf-core/vf-plugin';
registerBlockType('vf/plugin', vfPlugin);

// Handle iframe preview resizing globally
// TODO: remove necessity from `useVFIFrame`
const editorIframeSelector = [
  'iframe[name="editor-canvas"]',
  'iframe.block-editor-iframe__html',
  'iframe[title="Editor canvas"]',
  'iframe[title="Editor Canvas"]'
].join(',');

const resizePreviewIframe = (doc, data) => {
  if (data !== Object(data) || !/^vfwp_/.test(data.id)) {
    return;
  }
  const iframe = doc.getElementById(data.id);
  if (!iframe || !iframe.vfActive) {
    return;
  }
  const targetWindow = doc.defaultView || window;
  targetWindow.requestAnimationFrame(() => {
    iframe.style.height = `${data.height}px`;
  });
};

const resizeMessageWindows = new WeakSet();
const listenForResizeMessages = (win, doc) => {
  if (!win || !doc || resizeMessageWindows.has(win)) {
    return;
  }
  resizeMessageWindows.add(win);
  win.addEventListener('message', ({data}) => resizePreviewIframe(doc, data));
};

const initResizeMessages = () => {
  listenForResizeMessages(window, document);
  document.querySelectorAll(editorIframeSelector).forEach((iframe) => {
    try {
      listenForResizeMessages(iframe.contentWindow, iframe.contentDocument);
    } catch (err) {
      // Ignore editor iframes that are not ready yet.
    }
  });
};

initResizeMessages();
new MutationObserver(initResizeMessages).observe(document, {
  childList: true,
  subtree: true
});
