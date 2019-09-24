/**
 * VF-WP theme CSS/Sass
 */
const path = require('path');
const gulp = require('gulp');
const pump = require('pump');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');

const contentPath = path.resolve(__dirname, '../wp-content');
const assetsPath = path.join(contentPath, 'themes/vf-wp/assets');
const cssGlob = [path.join(assetsPath, 'scss/**/*.scss')];

const task = callback => {
  pump(
    [
      gulp.src(cssGlob),
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
task.description = 'Compile vf-wp theme Sass';

module.exports = {
  cssTask: task,
  cssGlob
};
