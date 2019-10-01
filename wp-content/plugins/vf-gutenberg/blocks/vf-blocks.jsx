/**!
 * VF Gutenberg blocks
 */
import {registerBlockType} from '@wordpress/blocks';
import useVFGutenberg from './hooks/use-vf-gutenberg';
import useVFPluginSettings from './hooks/use-vf-plugin-settings';

// Import Visual Framework core component settings
import vfBadge from './vf-core/vf-badge';
import vfBlockquote from './vf-core/vf-blockquote';
import vfBox from './vf-core/vf-box';
import vfButton from './vf-core/vf-button';
import vfDivider from './vf-core/vf-divider';
import vfLede from './vf-core/vf-lede';
// import vfSummary from './vf-core/vf-summary';

// Register VF Core blocks
[
  // Elements
  vfBadge,
  vfBlockquote,
  vfButton,
  vfDivider,
  // Blocks
  vfBox,
  vfLede,
  // vfSummary
].forEach(settings => registerBlockType(settings.name, settings));

// Register VF Plugin blocks from "localized" global settings
const {plugins} = useVFGutenberg();
for (const [key, plugin] of Object.entries(plugins)) {
  const settings = useVFPluginSettings(key, plugin.title);
  registerBlockType(key, settings);
}
