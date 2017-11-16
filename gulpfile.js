var gulp = require ('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var gp_rename = require("gulp-rename");
var gutil = require('gulp-util');
var sass = require('gulp-sass');
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
});

gulp.task('default', ['scripts','styles']);