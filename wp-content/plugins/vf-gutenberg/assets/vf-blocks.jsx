/**
 * VF Gutenberg blocks
 * https://visual-framework.github.io/vf-core/
 */
import * as button from './blocks/vf-button';

const {registerBlockType} = wp.blocks;

registerBlockType('vf/button', button.settings);
