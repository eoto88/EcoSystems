var gulp = require('gulp'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    sourcemaps = require('gulp-sourcemaps'),
    clean = require('gulp-clean');

gulp.task('default', ['clean', 'compress']);

gulp.task('compress', function (cb) {
    return gulp.src([
            'assets/js/main.js',
            'assets/js/ES.js',
            'assets/js/widget.js'
        ])
        .pipe(sourcemaps.init())
        .pipe(concat('concat.js'))
        .pipe(gulp.dest('build/js'))
        .pipe(rename('uglify.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('build/js'));
});

gulp.task('clean', function () {
    return gulp.src('build', {read: false})
        .pipe(clean());
});