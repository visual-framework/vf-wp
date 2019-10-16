/**!
 * VF Gutenberg blocks
 */
import {registerBlockType} from '@wordpress/blocks';
import useVFGutenberg from './hooks/use-vf-gutenberg';
import useVFPluginSettings from './hooks/use-vf-plugin-settings';

// Import Visual Framework core component settings
import vfActivityItem from './vf-core/vf-activity-item';
import vfActivityList from './vf-core/vf-activity-list';
import vfBadge from './vf-core/vf-badge';
import vfBlockquote from './vf-core/vf-blockquote';
import vfBox from './vf-core/vf-box';
import vfBreadcrumbsItem from './vf-core/vf-breadcrumbs-item';
import vfBreadcrumbs from './vf-core/vf-breadcrumbs';
import vfButton from './vf-core/vf-button';
import vfDivider from './vf-core/vf-divider';
import vfGridColumn from './vf-core/vf-grid-column';
import vfEMBLGrid from './vf-core/vf-embl-grid';
import vfGrid from './vf-core/vf-grid';
import vfLede from './vf-core/vf-lede';
// import vfSummary from './vf-core/vf-summary';

// Register VF Core blocks
[
  // Grid
  vfGridColumn,
  vfEMBLGrid,
  vfGrid,
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
  vfLede
  // vfSummary
].forEach(settings => registerBlockType(settings.name, settings));

// Register VF Plugin blocks from "localized" global settings
const {plugins} = useVFGutenberg();
for (const [name, plugin] of Object.entries(plugins)) {
  const settings = useVFPluginSettings({name, title: plugin.title});
  registerBlockType(name, settings);
}
