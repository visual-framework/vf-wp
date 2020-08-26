const fs = require('fs');
const path = require('path');
const gulp = require('gulp');
const del = require('del');

// Pull in optional configuration from the package.json file, a la:
const {componentPath, componentDirectories, buildDestionation} = require('@visual-framework/vf-config');

// Tasks to build/run vf-core component system
require('../node_modules/@visual-framework/vf-core/gulp-tasks/_gulp_rollup.js')(
  gulp,
  path,
  componentPath,
  componentDirectories,
  buildDestionation
);

gulp.task('vf-wp-clean:assets', function(){
  return del([buildDestionation+'/assets/**'], {force:true});
});
