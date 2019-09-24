const path = require('path');
const nunjucks = require('nunjucks');

const contentPath = path.resolve(__dirname, '../wp-content');
const pluginPath = path.join(contentPath, 'plugins');

const SpacelessExtension = require(path.join(
  pluginPath,
  '/vf-gutenberg/blocks/utils/nunjucks-spaceless'
));

const env = new nunjucks.Environment(null, {
  lstripBlocks: true,
  trimBlocks: true,
  autoescape: false
});

env.addExtension('spaceless', new SpacelessExtension());

const task = () => {
  return new Promise((resolve, reject) => {
    console.log(env);
    resolve();
  });
};
task.description = 'Precompile vf-core Nunjucks templates';

module.exports = {
  nunjucksTask: task
};
