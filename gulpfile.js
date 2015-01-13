var gulp = require('gulp');
var sass = require('gulp-sass');
var del = require('del');
var watch = require('gulp-watch');

var paths = {
	build: './build',
	dist: './dist',
	source: './src'
};

var sources = {
	all: '/**/*',
	sass: '/public/scss/**/*.scss',
	js: '/public/js/**/*.js',
	php: '/**/*.php'
};

var destinations = {
	css: '/public/css',
	js: '/public/js',
	php: '/'
};

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
		.pipe(gulp.dest(paths.build + destinations.php));
});

// builds sass to the build directory
gulp.task('sass', ['reset'], function() {
	return gulp.src([paths.source + sources.sass])
		.pipe(sass())
		.pipe(gulp.dest(paths.build + destinations.css));
});

// builds js
gulp.task('js', ['reset'], function(cb) {
	return gulp.src([paths.source + sources.js])
		.pipe(gulp.dest(paths.build + destinations.js));
});

// builds src to the build directory
gulp.task('build', ['reset', 'sass', 'js']);

// copies the current build to the dist directory
gulp.task('dist', ['build'], function() {
	return gulp.src(paths.build + sources.all)
		.pipe(gulp.dest(paths.dist));
});

// watch
gulp.task('watch', function() {
	gulp.watch(paths.source + sources.sass, ['build']);
	gulp.watch(paths.source + sources.js, ['build']);
	gulp.watch(paths.source + sources.php, ['build']);
});

// default
gulp.task('default', ['watch'], function() {
	
});