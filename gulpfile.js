const fs = require('fs');
const path = require('path');
const pump = require('pump');
const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const webpack = require('webpack');
const chalk = require('chalk');

// -----------------------------------------------------------------------------
// Configuration
// -----------------------------------------------------------------------------

// Pull in some optional configuration from the package.json file, a la:
// "vfConfig": {
//   "vfName": "My Component Library",
//   "vfNameSpace": "myco-",
//   "vfComponentPath": "./src/components",
//   "vfComponentDirectories": [
//      "vf-core-components",
//      "../node_modules/your-optional-collection-of-dependencies"
//     NOTE: Don't forget to symlink: `cd components` `ln -s ../node_modules/your-optional-collection-of-dependencies`
//    ],
//   "vfBuildDestination": "./build",
//   "vfThemePath": "@frctl/mandelbrot"
// },
// all settings are optional
// todo: this could/should become a JS module
const config = JSON.parse(fs.readFileSync('./package.json'));
const vfCoreConfig = JSON.parse(fs.readFileSync(require.resolve('@visual-framework/vf-core/package.json')));
config.vfConfig = config.vfConfig || [];
global.vfName = config.vfConfig.vfName || "Visual Framework 2.0";
global.vfNamespace = config.vfConfig.vfNamespace || "vf-";
global.vfComponentPath = config.vfConfig.vfComponentPath || path.resolve('.', __dirname + '/components');
global.vfBuildDestination = config.vfConfig.vfBuildDestination || __dirname + '/temp/build-files';
global.vfThemePath = config.vfConfig.vfThemePath || './tools/vf-frctl-theme';
global.vfVersion = vfCoreConfig.version || 'not-specified';
const componentPath = path.resolve('.', global.vfComponentPath).replace(/\\/g, '/');
const componentDirectories = config.vfConfig.vfComponentDirectories || ['vf-core-components'];
const buildDestionation = path.resolve('.', global.vfBuildDestination).replace(/\\/g, '/');

// Tasks to build/run vf-core component system
require('./node_modules/\@visual-framework/vf-core/tools/gulp-tasks/_gulp_rollup.js')(gulp, path, componentPath, componentDirectories, buildDestionation);

/**
 * Setup configuration paths
 */
config.content_path = path.resolve(__dirname, 'wp-content');

config.theme_path = path.resolve(config.content_path, 'themes/vf-wp');
config.plugin_path = path.resolve(config.content_path, 'plugins');
config.assets_path = path.resolve(config.theme_path, 'assets');

config.content_glob = path.join(config.content_path, '**/*');

const js_glob = path.join(config.assets_path, 'js/*.js');
config.js_glob = [js_glob, '!' + js_glob.replace(/\.js$/, '.min.js')];

/**
 * Webpack configuration for VF Gutenberg plugin blocks
 */

config.vf_blocks_glob = [
  path.resolve(config.plugin_path, 'vf-gutenberg/blocks/**/*.{js,jsx}')
];

config.vf_blocks_webpack = mode => ({
  mode: mode,
  entry: path.resolve(config.plugin_path, 'vf-gutenberg/blocks/vf-blocks.jsx'),
  output: {
    path: path.resolve(config.plugin_path, 'vf-gutenberg/assets'),
    filename: `vf-blocks${mode === 'production' ? '.min' : ''}.js`
  },
  externals: {
    wp: 'wp',
    '@wordpress': 'wp',
    '@wordpress/blocks': 'wp.blocks',
    '@wordpress/block-editor': 'wp.blockEditor',
    '@wordpress/components': 'wp.components',
    '@wordpress/compose': 'wp.compose',
    '@wordpress/element': 'wp.element',
    '@wordpress/i18n': 'wp.i18n',
    react: 'React',
    'react-dom': 'ReactDOM'
  },
  module: {
    rules: [
      {
        test: /\.jsx$/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              [
                '@babel/preset-env',
                {
                  debug: false, // mode === 'development',
                  useBuiltIns: 'usage',
                  corejs: 3,
                  targets: {
                    browsers: ['> 1%']
                  }
                }
              ],
              [
                '@babel/preset-react',
                {
                  pragma: 'wp.element.createElement'
                }
              ]
            ],
            plugins: []
          }
        }
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.jsx', '.json']
  }
});

/**
 * Compress and minify JavaScript from theme directory
 */
gulp.task('js', callback => {
  pump(
    [
      gulp.src(config.js_glob),
      rename({
        suffix: '.min'
      }),
      babel({
        presets: ['@babel/preset-env']
      }),
      uglify({
        output: {
          comments: /^!/
        }
      }),
      gulp.dest(path.join(config.theme_path, 'assets/js'))
    ],
    callback
  );
});

/**
 * Copy the NPM package version into the theme stylesheet
 * WordPress uses the theme style.css front comment
 */
gulp.task('update-theme-version', callback => {
  console.log(chalk.blue('Updating theme version to match package...'));
  if (!fs.existsSync(config.style_path)) {
    console.log(chalk.red('Cannot find theme stylesheet.'));
    return;
  }
  const pkg = JSON.parse(fs.readFileSync('package.json', 'utf8'));
  const style = fs.readFileSync(config.style_path).toString();
  fs.writeFileSync(
    config.style_path,
    style.replace(/^(Version:\s+)[\d]\.[\d]\.[\d]/im, `$1${pkg.version}`)
  );
  callback();
});

/**
 * Build VF Gutenberg blocks plugin
 */
gulp.task('vf-blocks', () => {
  return new Promise((resolve, reject) => {
    webpack(
      [
        config.vf_blocks_webpack('development'),
        config.vf_blocks_webpack('production')
      ],
      (err, stats) => {
        if (err) {
          return reject(err);
        }
        if (stats.hasErrors()) {
          if (Array.isArray(stats.stats)) {
            stats.stats.forEach(({compilation}) => {
              console.log(chalk.red(compilation.errors.join('\n')));
            });
          } else {
            console.log(chalk.red(stats.compilation.errors.join('\n')));
          }
          return reject(new Error());
        }
        resolve();
      }
    );
  });
});

/**
 * Watch tasks
 */
gulp.task('watch-js', () => gulp.watch(config.js_glob, gulp.series('js')));
gulp.task('watch-blocks', () =>
  gulp.watch(config.vf_blocks_glob, gulp.series('vf-blocks'))
);
gulp.task('watch', gulp.parallel('watch-js', 'watch-blocks', 'vf-watch'));

/**
 * Build task
 */
gulp.task(
  'build',
  'vf-css:generate-component-css',
  gulp.parallel('js', 'vf-css', 'vf-scripts', 'vf-blocks'),
  'vf-component-assets'
);

/**
 * Default task
 */
gulp.task(
  'default',
  gulp.series(gulp.parallel('js', 'vf-css', 'vf-scripts', 'vf-blocks', 'vf-component-assets', 'vf-css:generate-component-css'), 'watch')
);
