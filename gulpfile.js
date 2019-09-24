/**
 * VF-WP Gulp
 */
const gulp = require('gulp');

const {cssTask, cssGlob} = require('./gulp-tasks/vf-wp-css');
const {jsTask, jsGlob} = require('./gulp-tasks/vf-wp');
const {blocksTask, blocksGlob} = require('./gulp-tasks/vf-gutenberg');
const {nunjucksTask} = require('./gulp-tasks/vf-nunjucks');

/**
 * Load tasks
 */
gulp.task('css', cssTask);
gulp.task('js', jsTask);
gulp.task('blocks', blocksTask);
gulp.task('nunjucks', nunjucksTask);

/**
 * Watch tasks
 */
gulp.task('watch-css', () => gulp.watch(cssGlob, gulp.series('css')));
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
