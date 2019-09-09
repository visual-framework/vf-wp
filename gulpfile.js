const fs = require('fs');
const path = require('path');
const pump = require('pump');
const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const chalk = require('chalk');

const config = {
  content_path: path.resolve(__dirname, 'wp-content')
};

config.theme_path = path.resolve(config.content_path, 'themes/vf-wp');
config.assets_path = path.resolve(config.theme_path, 'assets');

// Setup more paths
config.content_glob = path.join(config.content_path, '**/*');
config.style_path = path.join(config.theme_path, 'style.css');
config.sass_glob = [path.join(config.assets_path, 'scss/**/*.scss')];

const js_glob = path.join(config.assets_path, 'js/*.js');
config.js_glob = [js_glob, '!' + js_glob.replace(/\.js$/, '.min.js')];

// const vf_path = path.join(__dirname, '../vf-core/');

// Compile and prefix Sass
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

// Compress and minify JavaScript
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

// Copy the NPM package version into the theme stylesheet
// WordPress uses the theme style.css front comment
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

// Watch
gulp.task('watch-css', () => gulp.watch(config.sass_glob, gulp.series('css')));
gulp.task('watch-js', () => gulp.watch(config.js_glob, gulp.series('js')));

gulp.task('watch', gulp.parallel('watch-css', 'watch-js'));

// Default
gulp.task('default', gulp.series(gulp.parallel('css', 'js'), 'watch'));
