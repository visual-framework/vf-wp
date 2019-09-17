/**!
 * VF Gutenberg blocks
 */
import {useVFGutenberg, useVFPluginSettings} from './hooks';

// Import Visual Framework core component settings
import vfButton from './vf-core/vf-button';

const {registerBlockType} = wp.blocks;

// Register core blocks
registerBlockType(vfButton.name, vfButton);

// Register VF Plugin blocks from "localized" global settings
const {plugins} = useVFGutenberg();
for (const [key, plugin] of Object.entries(plugins)) {
  const settings = useVFPluginSettings(key, plugin.title);
  registerBlockType(key, settings);
}
