/**!
 * VF Gutenberg blocks
 */
import {registerBlockType} from '@wordpress/blocks';
import useVFGutenberg from './hooks/use-vf-gutenberg';
import useVFPluginSettings from './hooks/use-vf-plugin-settings';

// Import Visual Framework core component settings
import vfBadge from './vf-core/vf-badge';
import vfButton from './vf-core/vf-button';
// import vfBox from './vf-core/vf-box';
// import vfLede from './vf-core/vf-lede';

// Register core blocks
// registerBlockType(vfButton.name, vfButton);
// registerBlockType(vfBox.name, vfBox);
// registerBlockType(vfLede.name, vfLede);
registerBlockType(vfBadge.name, vfBadge);
registerBlockType(vfButton.name, vfButton);

// Register VF Plugin blocks from "localized" global settings
const {plugins} = useVFGutenberg();
for (const [key, plugin] of Object.entries(plugins)) {
  const settings = useVFPluginSettings(key, plugin.title);
  registerBlockType(key, settings);
}
