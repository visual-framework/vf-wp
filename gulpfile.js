const fs = require('fs');
const path = require('path');
const pump = require('pump');
const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass');
const webpack = require('webpack');
const chalk = require('chalk');

/**
 * File paths
 */
const contentPath = path.resolve(__dirname, 'wp-content');
const themePath = path.join(contentPath, 'themes/vf-wp');
const pluginPath = path.join(contentPath, 'plugins');
const assetsPath = path.join(themePath, 'assets');

/**
 * Glob paths
 */
const contentGlob = path.join(contentPath, '**/*');
const sassGlob = [path.join(assetsPath, 'scss/**/*.scss')];
const jsGlob = (jsGlob => [jsGlob, '!' + jsGlob.replace(/\.js$/, '.min.js')])(
  path.join(assetsPath, 'js/*.js')
);
const blocksGlob = [path.join(pluginPath, 'vf-gutenberg/blocks/**/*.{js,jsx}')];

/**
 * VF-WP theme Sass
 */
const cssTask = callback => {
  pump(
    [
      gulp.src(sassGlob),
      sass({
        outputStyle: 'expanded'
      }),
      autoprefixer({
        grid: true,
        remove: false
      }),
      gulp.dest(path.join(assetsPath, 'css'))
    ],
    callback
  );
};
cssTask.description = 'Compile vf-wp theme Sass';
gulp.task('css', cssTask);

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
 * VF-WP theme JavaScript
 */
const vfwpWebpack = require('./vf-wp.webpack.js');
const jsTask = callback => {
  return new Promise((resolve, reject) => {
    webpack(
      vfwpWebpack({}, {mode: 'production'}),
      handleWebpack(resolve, reject)
    );
  });
};
jsTask.description = 'Compile vf-wp theme JavaScript';
gulp.task('js', jsTask);

/**
 * VF Gutenberg plugin JavaScript
 */
const vfGutenbergWebpack = require('./vf-gutenberg.webpack.js');
const blockTask = () => {
  return new Promise((resolve, reject) => {
    webpack(
      [
        vfGutenbergWebpack({}, {mode: 'development'}),
        vfGutenbergWebpack({}, {mode: 'production'})
      ],
      handleWebpack(resolve, reject)
    );
  });
};
blockTask.description = 'Compile vf-gutenberg plugin React';
gulp.task('blocks', blockTask);

/**
 * Watch tasks
 */
gulp.task('watch-css', () => gulp.watch(sassGlob, gulp.series('css')));

gulp.task('watch-js', () => gulp.watch(jsGlob, gulp.series('js')));

gulp.task('watch-blocks', () => gulp.watch(blocksGlob, gulp.series('blocks')));

gulp.task('watch', gulp.parallel('watch-css', 'watch-js', 'watch-blocks'));

/**
 * Default task
 */
gulp.task(
  'default',
  gulp.series(gulp.parallel('css', 'js', 'blocks'), 'watch')
);
