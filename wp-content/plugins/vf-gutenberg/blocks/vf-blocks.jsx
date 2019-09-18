/**!
 * VF Gutenberg blocks
 */
import useVFGutenberg from './hooks/use-vf-gutenberg';
import useVFSettings from './hooks/use-vf-settings';

// Import Visual Framework core component settings
import vfButton from './vf-core/vf-button';
import vfBox from './vf-core/vf-box';

const {registerBlockType} = wp.blocks;

// Register core blocks
registerBlockType(vfButton.name, vfButton);
registerBlockType(vfBox.name, vfBox);

// Register VF Plugin blocks from "localized" global settings
const {plugins} = useVFGutenberg();
for (const [key, plugin] of Object.entries(plugins)) {
  const settings = useVFSettings(key, plugin.title);
  registerBlockType(key, settings);
}
