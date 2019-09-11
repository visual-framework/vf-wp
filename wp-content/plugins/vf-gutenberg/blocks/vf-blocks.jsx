/**!
 * VF Gutenberg plugin blocks
 * https://visual-framework.github.io/vf-core/
 */
import {useVF} from './hooks';
import * as example from './vf-example';
import * as latest from './vf-latest-posts';

const {registerBlockType} = wp.blocks;
const {plugins} = useVF();

if (plugins.indexOf(example.settings.name) > -1) {
  registerBlockType(example.settings.name, example.settings);
}

if (plugins.indexOf(latest.settings.name) > -1) {
  registerBlockType(latest.settings.name, latest.settings);
}
