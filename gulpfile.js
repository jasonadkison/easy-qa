var gulp = require('gulp');
var del = require('del');
var es = require('event-stream');
var merge = require('merge-stream');
var runSequence = require('run-sequence');
var $ = require('gulp-load-plugins')({
	pattern: ['gulp-*', 'gulp.*', 'main-bower-files'],
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
	php: '/'
};

/* ===== Begin Main Tasks ===== */

// compiles new build from source
gulp.task('build', ['reset', 'css', 'js']);

// creates new dist by copying and compiles the build
gulp.task('dist', ['build'], function() {
	runSequence(
		'dist-create',
		'dist-css'
	);
});

// watches source
gulp.task('watch', function() {
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
	del([paths.build + sources.all, paths.dist + sources.all], cb);
});

// empties the build/dist directorys and makes dist a copy of src
gulp.task('reset', ['erase'], function() {
	return gulp.src(paths.source + sources.all)
		.pipe(gulp.dest(paths.build));
});

// copies all php files to the build dir
gulp.task('php', ['reset'], function() {
	return gulp.src([paths.source + sources.php])
		.pipe(gulp.dest(paths.build + destinations.php))
		.pipe($.livereload());
});

// builds stylesheets to the build directory
gulp.task('css', ['reset'], function() {
	var bowerCss = gulp.src($.mainBowerFiles())
		.pipe($.scopeCss('.easy-qa'))
		.pipe(gulp.dest(paths.build + destinations.css));

	var scss = gulp.src([paths.source + sources.sass])
		.pipe($.sass({outputStyle: 'nested', sourceComments: true}))
		.pipe($.autoprefixer({
			browsers: ['> 1%'],
			cascade: false
		}))
		.pipe(gulp.dest(paths.build + destinations.css))
		.pipe($.livereload());

	return merge(bowerCss, scss);
});

// builds js
gulp.task('js', ['reset'], function(cb) {
	return gulp.src([paths.source + sources.js])
		.pipe(gulp.dest(paths.build + destinations.js))
		.pipe($.livereload());
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