/**
 * VF Gutenberg plugin JavaScript
 */
const path = require('path');
const chalk = require('chalk');
const rollup = require('rollup');

const {babel} = require('@rollup/plugin-babel');
const commonjs = require('@rollup/plugin-commonjs');
const {nodeResolve} = require('@rollup/plugin-node-resolve');
const replace = require('@rollup/plugin-replace');
const {terser} = require('rollup-plugin-terser');

const contentPath = path.resolve(__dirname, '../wp-content');
const pluginPath = path.join(contentPath, 'plugins');
const blocksGlob = [path.join(pluginPath, 'vf-gutenberg/blocks/**/*.{js,jsx}')];

const globals = {
  wp: 'wp',
  '@wordpress': 'wp',
  '@wordpress/blocks': 'wp.blocks',
  '@wordpress/block-editor': 'wp.blockEditor',
  '@wordpress/components': 'wp.components',
  '@wordpress/compose': 'wp.compose',
  '@wordpress/data': 'wp.data',
  '@wordpress/element': 'wp.element',
  '@wordpress/hooks': 'wp.hooks',
  '@wordpress/i18n': 'wp.i18n',
  react: 'React',
  'react-dom': 'ReactDOM'
};

const inputOptions = ({NODE_ENV}) => {
  const isDev = NODE_ENV === 'development';
  const isProd = !isDev;
  return {
    input: path.resolve(pluginPath, 'vf-gutenberg/blocks/vf-blocks.jsx'),
    external: Object.keys(globals),
    plugins: [
      replace({
        'process.env.NODE_ENV': NODE_ENV
      }),
      nodeResolve({
        browser: true,
        extensions: ['.js', '.jsx', '.json']
      }),
      commonjs(),
      babel({
        exclude: '**/node_modules/**',
        babelHelpers: 'runtime',
        plugins: ['@babel/plugin-transform-runtime'],
        presets: [
          [
            '@babel/preset-env',
            {
              debug: false,
              targets: '> 1%, not dead',
              useBuiltIns: 'usage',
              corejs: 3
            }
          ],
          [
            '@babel/preset-react',
            {
              pragma: 'wp.element.createElement'
            }
          ]
        ]
      }),
      isProd && terser()
    ]
  };
};

const task = async () => {
  const devBundle = await rollup.rollup(
    inputOptions({NODE_ENV: 'development'})
  );

  const prodBundle = await rollup.rollup(
    inputOptions({NODE_ENV: 'production'})
  );

  return Promise.all([
    devBundle.write({
      file: path.resolve(pluginPath, `vf-gutenberg/assets/vf-blocks.js`),
      format: 'umd',
      globals
    }),
    prodBundle.write({
      file: path.resolve(pluginPath, `vf-gutenberg/assets/vf-blocks.min.js`),
      format: 'umd',
      globals
    })
  ]);
};
task.description = 'Compile vf-gutenberg plugin React';

module.exports = {
  blocksTask: task,
  blocksGlob
};
