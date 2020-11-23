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
window.addEventListener('message', ({data}) => {
  if (data !== Object(data) || !/^vfwp_/.test(data.id)) {
    return;
  }
  const iframe = document.getElementById(data.id);
  if (!iframe || !iframe.vfActive) {
    return;
  }
  window.requestAnimationFrame(() => {
    iframe.style.height = `${data.height}px`;
  });
});
