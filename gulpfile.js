var gulp = require('gulp');
var del = require('del');
var es = require('event-stream');
var merge = require('merge-stream');
var runSequence = require('run-sequence');
var $ = require('gulp-load-plugins')({
  pattern: ['gulp-*', 'gulp.*'],
  replaceString: /\bgulp[\-.]/
});

var paths = {
  build: './build',
  dist: './dist',
  source: './src'
};

var sources = {
  all: '/**/*',
  sass: '/public/scss/**/*.scss',
  css: '/public/css/**/*.css',
  js: '/public/js/**/*.js',
  php: '/**/*.php'
};

var destinations = {
  css: '/public/css',
  js: '/public/js',
  php: '/',
  fonts: '/public/fonts'
};

var bootstrapSass = {
  in: './node_modules/bootstrap-sass/'
};

var fonts = {
  in: [bootstrapSass.in + 'assets/fonts/**/*'],
  out: paths.build + destinations.fonts
}

/* ===== Begin Main Tasks ===== */

// compiles new build from source
gulp.task('build', ['reset'], function(callback) {
  runSequence('css', 'js', 'fonts', callback);
});

// creates new dist by copying and compiles the build
gulp.task('dist', ['build'], function(callback) {
  runSequence('dist-create', 'dist-css', callback);
});

// watches source
gulp.task('watch', ['build'], function() {
  $.livereload.listen();
  gulp.watch(paths.source + sources.sass, ['build']);
  gulp.watch(paths.source + sources.js, ['build']);
  gulp.watch(paths.source + sources.php, ['build']);
});

// default task
gulp.task('default', ['watch'], function() {

});

/* ===== End Main Tasks ===== */

/* ===== Begin Sub Tasks ===== */

// empty build/dist directories
gulp.task('erase', function(cb) {
  return del([paths.build + sources.all, paths.dist + sources.all], cb);
});

// empties the build/dist directories and makes dist a copy of src
gulp.task('reset', ['erase'], function() {
  return gulp.src(paths.source + sources.all)
    .pipe(gulp.dest(paths.build));
});

// copies all php files to the build dir
gulp.task('php', function() {
  return gulp.src([paths.source + sources.php])
    .pipe(gulp.dest(paths.build + destinations.php))
    .pipe($.livereload());
});

// builds stylesheets to the build directory
gulp.task('css', function() {
  return gulp.src([paths.source + sources.sass])
    .pipe($.sass({
      outputStyle: 'nested',
      errLogToConsole: true,
      sourceComments: true,
      includePaths: [bootstrapSass.in + 'assets/stylesheets']
    }))
    .pipe($.autoprefixer({
      browsers: ['> 1%'],
      cascade: false
    }))
    .pipe($.scopeCss('.easy-qa-plugin'))
    .pipe(gulp.dest(paths.build + destinations.css))
    .pipe($.livereload());
});

// builds js
gulp.task('js', function() {
  return gulp.src([paths.source + sources.js])
    .pipe(gulp.dest(paths.build + destinations.js))
    .pipe($.livereload());
});

gulp.task('fonts', function() {
  return gulp.src(fonts.in)
    .pipe(gulp.dest(fonts.out));
});

// copies the build as a new dist
gulp.task('dist-create', function() {
  return gulp.src(paths.build + sources.all).pipe(gulp.dest(paths.dist));
});

// builds the dist css
gulp.task('dist-css', function() {
  return gulp.src(paths.dist + sources.css)
    .pipe($.minifyCss({keepSpecialComments: false}))
    .pipe(gulp.dest(paths.dist + destinations.css));
});

/* ===== End Sub Tasks ===== */
