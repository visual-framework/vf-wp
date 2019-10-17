/**
 * VF Gutenberg plugin JavaScript
 */
const path = require('path');
const chalk = require('chalk');
const webpack = require('webpack');

const contentPath = path.resolve(__dirname, '../wp-content');
const pluginPath = path.join(contentPath, 'plugins');
const assetsPath = path.join(contentPath, 'themes/vf-wp/assets');
const blocksGlob = [path.join(pluginPath, 'vf-gutenberg/blocks/**/*.{js,jsx}')];

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

const conf = require('./vf-gutenberg.webpack.js');

const task = callback => {
  return new Promise((resolve, reject) => {
    webpack(
      [conf({}, {mode: 'development'}), conf({}, {mode: 'production'})],
      handle(resolve, reject)
    );
  });
};
task.description = 'Compile vf-gutenberg plugin React';

module.exports = {
  blocksTask: task,
  blocksGlob
};
