/**!
 * VF Gutenberg plugin blocks
 * https://visual-framework.github.io/vf-core/
 */
import * as button from './button';
import * as latest from './latest-posts';

const {registerBlockType} = wp.blocks;

// registerBlockType('vf/button', button.settings);
registerBlockType(latest.settings.name, latest.settings);
