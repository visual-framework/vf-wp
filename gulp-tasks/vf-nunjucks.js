const fs = require('fs');
const path = require('path');
const chalk = require('chalk');
const nunjucks = require('nunjucks');

const contentPath = path.resolve(__dirname, '../wp-content');
const pluginPath = path.join(contentPath, 'plugins');

const SpacelessExtension = require(path.join(
  pluginPath,
  '/vf-gutenberg/blocks/utils/nunjucks-spaceless'
));

const components = [
  // Elements
  ...['vf-badge', 'vf-blockquote', 'vf-button', 'vf-divider', 'vf-figure'],
  // Blocks
  ...['vf-activity-list', 'vf-box', 'vf-breadcrumbs', 'vf-lede', 'vf-summary']
];

const env = new nunjucks.Environment(null, {
  lstripBlocks: true,
  trimBlocks: true,
  autoescape: false
});

env.addExtension('spaceless', new SpacelessExtension());

const task = () => {
  return new Promise((resolve, reject) => {
    components.forEach(name => {
      const package = `@visual-framework/${name}`;
      const src = path.resolve(
        __dirname,
        `../node_modules/${package}/${name}.njk`
      );
      const dest = path.resolve(
        pluginPath,
        `vf-gutenberg/blocks/templates/${name}.js`
      );
      const front = `/**\n * Precompiled Nunjucks template: ${package}\n */\n`;
      if (!fs.existsSync(src)) {
        console.log(chalk.yellow(`Missing dependency: ${package}`));
        return;
      }
      console.log(chalk.green(`Precompiling: ${package}`));
      const js = (() => {
        try {
          return nunjucks.precompile(src, {
            env: env,
            name: name
          });
        } catch (err) {
          console.log(chalk.red(err));
        }
      })();
      if (!js) {
        return;
      }
      fs.writeFileSync(dest, front + js);
    });
    resolve();
  });
};
task.description = 'Precompile vf-core Nunjucks templates';

module.exports = {
  nunjucksTask: task
};
