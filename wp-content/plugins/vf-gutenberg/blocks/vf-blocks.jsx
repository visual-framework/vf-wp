/**!
 * VF Gutenberg plugin blocks
 * https://visual-framework.github.io/vf-core/
 */
import {useVF} from './hooks';
import * as latest from './latest-posts';

const {registerBlockType} = wp.blocks;
const {plugins} = useVF();

if (plugins.indexOf(latest.settings.name) > -1) {
  registerBlockType(latest.settings.name, latest.settings);
}
