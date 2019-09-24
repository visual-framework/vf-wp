/**
 * Return Nunjucks environment object
 */
import nunjucks from '../utils/nunjucks-slim';
import SpacelessExtension from '../utils/nunjucks-spaceless';

const env = new nunjucks.Environment(null, {
  lstripBlocks: true,
  trimBlocks: true,
  autoescape: false
});

env.addExtension('spaceless', new SpacelessExtension());

const useNunjucks = () => env;

export default useNunjucks;
