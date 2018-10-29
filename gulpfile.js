var gulp = require('gulp'),
    sass = require('gulp-sass');
    sourcemap = require('gulp-sourcemaps');


/* sass compiler */
function compileSass(src, dest){
    return function(){
        gulp.src(src)
            .pipe(sourcemap.init())
            .pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
            .pipe(sourcemap.write('map'))
            .pipe(gulp.dest(dest))
    }
}

// compile bootstrap
gulp.task('bs', compileSass('bootstrap/bootstrap.scss','public/assets/css/'));
gulp.task('style', compileSass('style/style.scss','public/assets/css/'));

// default gulp task
gulp.task('default',['bs', 'style'], function(){
    gulp.watch('bootstrap/*.scss', ['bs']);
    gulp.watch('style/**/*.scss', ['style']);
});