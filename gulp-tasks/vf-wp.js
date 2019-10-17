/**
 * VF-WP theme JavaScript
 */
const path = require('path');
const chalk = require('chalk');
const webpack = require('webpack');

const contentPath = path.resolve(__dirname, '../wp-content');
const assetsPath = path.join(contentPath, 'themes/vf-wp/assets');

const jsGlob = (jsGlob => [jsGlob, '!' + jsGlob.replace(/\.js$/, '.min.js')])(
  path.join(assetsPath, 'js/*.js')
);

const handle = (resolve, reject) => (err, stats) => {
  if (err) {
    return reject(err.stack || err);
  }
  const info = stats.toJson();
  if (stats.hasErrors()) {
    console.log(chalk.red(info.errors));
  }
  if (stats.hasWarnings()) {
    console.log(chalk.blue(info.warnings));
  }
  resolve();
};

const conf = require('./vf-wp.webpack.js');

const task = callback => {
  return new Promise((resolve, reject) => {
    webpack(conf({}, {mode: 'production'}), handle(resolve, reject));
  });
};
task.description = 'Compile vf-wp theme JavaScript';

module.exports = {
  jsTask: task,
  jsGlob
};
