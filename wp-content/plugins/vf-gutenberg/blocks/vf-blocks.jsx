/**!
 * VF Gutenberg blocks
 * https://visual-framework.github.io/vf-core/
 */
import {useVFGutenberg} from './hooks';

// import Visual Framework core component settings
import vfButton from './vf-core/vf-button';

// import VF Plugin block settings
import vfDataResources from './plugins/vf-data-resources';
import vfExample from './plugins/vf-example';
import vfFactoid from './plugins/vf-factoid';
import vfGroupHeader from './plugins/vf-group-header';
import vfJobs from './plugins/vf-jobs';
import vfLatestPosts from './plugins/vf-latest-posts';
import vfMembers from './plugins/vf-members';
import vfPublicationsGroupEBI from './plugins/vf-publications-group-ebi';
import vfPublications from './plugins/vf-publications';

const {registerBlockType} = wp.blocks;

// register core components
registerBlockType(vfButton.name, vfButton);

// register plugins if corresponding WordPress plugin is activated
const pluginBlocks = [
  vfDataResources,
  vfExample,
  vfFactoid,
  vfGroupHeader,
  vfJobs,
  vfLatestPosts,
  vfMembers,
  vfPublicationsGroupEBI,
  vfPublications
];

const {plugins: pluginData} = useVFGutenberg();
pluginBlocks.forEach(settings => {
  if (Object.keys(pluginData).indexOf(settings.name) > -1) {
    registerBlockType(settings.name, settings);
  }
});
