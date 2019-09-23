const fs = require('fs');
const path = require('path');
const pump = require('pump');
const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass');
const webpack = require('webpack');
const chalk = require('chalk');

/**
 * Setup configuration paths
 */
const config = {
  content_path: path.resolve(__dirname, 'wp-content')
};

config.theme_path = path.resolve(config.content_path, 'themes/vf-wp');
config.plugin_path = path.resolve(config.content_path, 'plugins');
config.assets_path = path.resolve(config.theme_path, 'assets');

config.content_glob = path.join(config.content_path, '**/*');
config.style_path = path.join(config.theme_path, 'style.css');
config.sass_glob = [path.join(config.assets_path, 'scss/**/*.scss')];

const js_glob = path.join(config.assets_path, 'js/*.js');
config.js_glob = [js_glob, '!' + js_glob.replace(/\.js$/, '.min.js')];

config.vf_blocks_glob = [
  path.resolve(config.plugin_path, 'vf-gutenberg/blocks/**/*.{js,jsx}')
];

/**
 * Return a Webpack callback function for the Node API to handle errors
 * and resolve a Gulp task promise
 */
const handleWebpack = (resolve, reject) => (err, stats) => {
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

/**
 * Compile and prefix Sass
 * TODO: remove VF includes - not necessary?
 */
// const vf_path = path.join(__dirname, '../vf-core/');
gulp.task('css', callback => {
  pump(
    [
      gulp.src(config.sass_glob),
      sass({
        outputStyle: 'expanded'
        // includePaths: [
        //   vf_path,
        //   path.join(vf_path, 'assets/scss'),
        //   path.join(vf_path, 'components/utilities'),
        //   path.join(vf_path, 'components/elements'),
        //   path.join(vf_path, 'components/blocks'),
        //   path.join(vf_path, 'components/containers'),
        //   path.join(vf_path, 'components/grids')
        // ]
      }),
      autoprefixer({
        grid: true,
        remove: false
      }),
      gulp.dest(path.join(config.assets_path, 'css'))
    ],
    callback
  );
});

/**
 * Compress and minify JavaScript from theme directory
 */

const vfwpWebpack = require('./vf-wp.webpack.js');

gulp.task('js', callback => {
  return new Promise((resolve, reject) => {
    webpack(
      vfwpWebpack({}, {mode: 'production'}),
      handleWebpack(resolve, reject)
    );
  });
});

/**
 * Build VF Gutenberg blocks plugin
 */

const vfGutenbergWebpack = require('./vf-gutenberg.webpack.js');

gulp.task('vf-blocks', () => {
  return new Promise((resolve, reject) => {
    webpack(
      [
        vfGutenbergWebpack({}, {mode: 'development'}),
        vfGutenbergWebpack({}, {mode: 'production'})
      ],
      handleWebpack(resolve, reject)
    );
  });
});

/**
 * Watch tasks
 */
gulp.task('watch-css', () => gulp.watch(config.sass_glob, gulp.series('css')));
gulp.task('watch-js', () => gulp.watch(config.js_glob, gulp.series('js')));
gulp.task('watch-blocks', () =>
  gulp.watch(config.vf_blocks_glob, gulp.series('vf-blocks'))
);
gulp.task('watch', gulp.parallel('watch-css', 'watch-js', 'watch-blocks'));

/**
 * Default task
 */
gulp.task(
  'default',
  gulp.series(gulp.parallel('css', 'js', 'vf-blocks'), 'watch')
);
