/**
 * Return Nunjucks environment object
 */
import nunjucks from '../utils/nunjucks-slim';
import SpacelessExtension from '../utils/nunjucks-spaceless';

const useNunjucks = () => {
  // initialise on first request to ensure precompiled templates exist
  if (!window.vfNunjucks) {
    const env = new nunjucks.Environment(null, {
      lstripBlocks: true,
      trimBlocks: true,
      autoescape: false
    });
    env.addExtension('spaceless', new SpacelessExtension());
    window.vfNunjucks = env;
  }
  return window.vfNunjucks;
};

export default useNunjucks;
