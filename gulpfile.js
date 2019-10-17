/**
 * VF-WP Gulp
 */
const gulp = require('gulp');

const {jsTask, jsGlob} = require('./gulp-tasks/vf-wp');
const {blocksTask, blocksGlob} = require('./gulp-tasks/vf-gutenberg');
require('./gulp-tasks/vf-core');

/**
 * Load tasks
 */

gulp.task('js', jsTask);
gulp.task('blocks', blocksTask);

/**
 * Watch tasks
 */

gulp.task('watch-js', () => gulp.watch(jsGlob, gulp.series('js')));
gulp.task('watch-blocks', () => gulp.watch(blocksGlob, gulp.series('blocks')));
gulp.task('watch', gulp.parallel('watch-js', 'watch-blocks', 'vf-watch'));

/**
 * Build tasks
 */
gulp.task(
  'build',
  gulp.series(
    'vf-css:generate-component-css',
    gulp.parallel('js', 'blocks', 'vf-css', 'vf-scripts'),
    'vf-component-assets'
  )
);

/**
 * Default task
 */
gulp.task('default', gulp.series(gulp.parallel('build'), 'watch'));
