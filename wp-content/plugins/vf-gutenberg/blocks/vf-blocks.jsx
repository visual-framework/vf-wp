/**!
 * VF Gutenberg blocks
 */
import {registerBlockType} from '@wordpress/blocks';
import useVFGutenberg from './hooks/use-vf-gutenberg';
import useVFSettings from './hooks/use-vf-settings';

// Import Visual Framework core component settings
// import vfButton from './vf-core/vf-button';
// import vfBox from './vf-core/vf-box';
// import vfLede from './vf-core/vf-lede';

// Register core blocks
// registerBlockType(vfButton.name, vfButton);
// registerBlockType(vfBox.name, vfBox);
// registerBlockType(vfLede.name, vfLede);

// Register VF Plugin blocks from "localized" global settings
const {plugins} = useVFGutenberg();
for (const [key, plugin] of Object.entries(plugins)) {
  const settings = useVFSettings(key, plugin.title);
  registerBlockType(key, settings);
}
