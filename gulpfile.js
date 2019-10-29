/**
 * VF-WP Gulp
 */
const gulp = require('gulp');
const {blocksTask, blocksGlob} = require('./gulp-tasks/vf-gutenberg');
require('./gulp-tasks/vf-core');

/**
 * Load tasks
 */

gulp.task('vf-gutenberg', blocksTask);

/**
 * Watch tasks
 */

gulp.task('vf-gutenberg-watch', () =>
  gulp.watch(blocksGlob, gulp.series('vf-gutenberg'))
);

gulp.task('watch', gulp.parallel('vf-gutenberg-watch', 'vf-watch'));

/**
 * Build tasks
 */
gulp.task(
  'build',
  gulp.series(
    'vf-wp-clean:assets',
    'vf-css:generate-component-css',
    gulp.parallel('vf-gutenberg', 'vf-css', 'vf-scripts'),
    'vf-component-assets'
  )
);

/**
 * Default task
 */
gulp.task('default', gulp.series('build', 'watch'));
