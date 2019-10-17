const fs = require('fs');
const path = require('path');
const gulp = require('gulp');
const del = require('del');

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
const vfCoreConfig = JSON.parse(
  fs.readFileSync(require.resolve('@visual-framework/vf-core/package.json'))
);
config.vfConfig = config.vfConfig || [];
global.vfName = config.vfConfig.vfName || 'Visual Framework 2.0';
global.vfNamespace = config.vfConfig.vfNamespace || 'vf-';
global.vfComponentPath =
  config.vfConfig.vfComponentPath ||
  path.resolve('.', __dirname + '/components');
global.vfBuildDestination =
  config.vfConfig.vfBuildDestination || __dirname + '/temp/build-files';
global.vfThemePath = config.vfConfig.vfThemePath || './tools/vf-frctl-theme';
global.vfVersion = vfCoreConfig.version || 'not-specified';
const componentPath = path
  .resolve('.', global.vfComponentPath)
  .replace(/\\/g, '/');
const componentDirectories = config.vfConfig.vfComponentDirectories || [
  'vf-core-components'
];
const buildDestionation = path
  .resolve('.', global.vfBuildDestination)
  .replace(/\\/g, '/');

// Tasks to build/run vf-core component system
require('../node_modules/@visual-framework/vf-core/tools/gulp-tasks/_gulp_rollup.js')(
  gulp,
  path,
  componentPath,
  componentDirectories,
  buildDestionation
);

gulp.task('vf-wp-clean:assets', function(){
  return del([buildDestionation+'/assets/**'], {force:true});
});
