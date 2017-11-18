var gulp = require ('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var gp_rename = require("gulp-rename");
var gutil = require('gulp-util');
var sass = require('gulp-sass');
<<<<<<< HEAD
var importCss = require('gulp-import-css');

gulp.task('styles', function() {
    return gulp.src([
            './assets/src/css/**/*.scss'
        ])
        .pipe(sass({includePaths: ['./assets/src/css/partials']}).on('error', sass.logError))
        .pipe(importCss())
        .pipe(gulp.dest('./assets/dist/css'));
});

gulp.task('scripts', function(){
    return gulp.src([
        './bower_components/jquery/dist/jquery.js', 
        './bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
        './assets/src/js/scripts.js',
        //'./assets/src/js/menu.js',
        ])
        .pipe(concat('concat.js'))
        .pipe(gulp.dest('./assets/dist/js'))
        .pipe(gp_rename('scripts.js'))
        .pipe(uglify().on('error', gutil.log))
        .pipe(gulp.dest('./assets/dist/js'));
});

gulp.task('watch', function (){
    gulp.watch('./assets/src/css/**/*.scss', ['styles']);
    gulp.watch('./assets/src/js/*.js', ['scripts']);
=======

gulp.task('styles', function() {
    return gulp.src('./styles/**/*.scss')
    	.pipe(sass({includePaths: ['./styles/partials']}).on('error', sass.logError))
        .pipe(gulp.dest('./dist/css'));
});

gulp.task('scripts', function(){
	return gulp.src([
		'./bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
		'./bower_components/swiper/dist/js/swiper.jquery.js',
		'./scripts/modernizr.custom.js',
		'./scripts/main.js',
		'./scripts/skip-link-focus-fix.js',
		'./scripts/menu.js',
		
		])
		.pipe(concat('concat.js'))
		.pipe(gulp.dest('./dist/js'))
		.pipe(gp_rename('scripts.js'))
		.pipe(uglify().on('error', gutil.log))
		.pipe(gulp.dest('./dist/js'));
});

gulp.task('watch', function (){
	gulp.watch('./styles/**/*.scss', ['styles']);
	gulp.watch('./scripts/*.js', ['scripts']);
>>>>>>> d696b30e8eeb0904b9ed24eee61b2f82b219861d
});

gulp.task('default', ['scripts','styles']);