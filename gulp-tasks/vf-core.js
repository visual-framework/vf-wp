const fs = require('fs');
const path = require('path');
const gulp = require('gulp');

// Pull in optional configuration from the package.json file, a la:
const {componentPath, componentDirectories, buildDestionation} = require('@visual-framework/vf-config');

let vfCoreTasksLoaded = false;

async function loadVfCoreTasks() {
  if (vfCoreTasksLoaded) return;
  const vfCoreRollupTasks = await import('@visual-framework/vf-core/gulp-tasks/_gulp_rollup.mjs');
  const registerTasks = vfCoreRollupTasks.default || vfCoreRollupTasks;

  registerTasks(
    gulp,
    path,
    componentPath,
    componentDirectories,
    buildDestionation
  );

  vfCoreTasksLoaded = true;
}

function proxyToVfCoreTask(taskName) {
  return async function proxyTask(done) {
    await loadVfCoreTasks();
    return gulp.series(taskName)(done);
  };
}

// Pre-register task names so gulpfile.js can reference them before ESM loading.
gulp.task('vf-clean', proxyToVfCoreTask('vf-clean'));
gulp.task('vf-css:generate-component-css', proxyToVfCoreTask('vf-css:generate-component-css'));
gulp.task('vf-css', proxyToVfCoreTask('vf-css'));
gulp.task('vf-scripts', proxyToVfCoreTask('vf-scripts'));
gulp.task('vf-component-assets', proxyToVfCoreTask('vf-component-assets'));
gulp.task('vf-css:production', proxyToVfCoreTask('vf-css:production'));
gulp.task('vf-watch', proxyToVfCoreTask('vf-watch'));

gulp.task('vf-wp-clean:assets', async function(){
  const {deleteAsync} = await import('del');
  return deleteAsync([buildDestionation+'/assets/**'], {force:true});
});
