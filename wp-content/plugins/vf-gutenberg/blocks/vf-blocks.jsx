/**!
 * VF Gutenberg plugin blocks
 * https://visual-framework.github.io/vf-core/
 */
import * as button from './button';

const {registerBlockType} = wp.blocks;

registerBlockType('vf/button', button.settings);
