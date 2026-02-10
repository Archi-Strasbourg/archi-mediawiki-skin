/* jshint node: true */

// Grab our gulp packages
var gulp  = require('gulp'),
    sass = require('gulp-sass')(require('sass'));
    autoprefixer = require('gulp-autoprefixer'),
    jshint = require('gulp-jshint'),
    concat = require('gulp-concat'),
    plumber = require('gulp-plumber'),
    babel = require('gulp-babel');


// Compile Sass, Autoprefix and minify
gulp.task('styles', function() {
    return gulp.src('./resources/scss/**/*.scss')
        .pipe(plumber(function(error) {
            console.error(error.message);
            this.emit('end');
        }))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(gulp.dest('./dist/css/'));
});
    
// JSHint, concat, and minify JavaScript
gulp.task('site-js', function() {
  return gulp.src([	
	  
           // Grab your custom scripts
  		  './resources/js/*.js'
  		  
  ])
    .pipe(plumber())
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('./dist/js'));
});    

// JSHint, concat, and minify Foundation JavaScript
gulp.task('foundation-js', function() {
  return gulp.src([	

  		  // Foundation core - needed if you want to use any of the components below
          './vendor/foundation-sites/js/foundation.core.js',
          './vendor/foundation-sites/js/foundation.util.*.js',
          
          // Pick the components you need in your project
          './vendor/foundation-sites/js/foundation.abide.js',
          './vendor/foundation-sites/js/foundation.accordion.js',
          './vendor/foundation-sites/js/foundation.accordionMenu.js',
          './vendor/foundation-sites/js/foundation.drilldown.js',
          './vendor/foundation-sites/js/foundation.dropdown.js',
          './vendor/foundation-sites/js/foundation.dropdownMenu.js',
          './vendor/foundation-sites/js/foundation.equalizer.js',
          './vendor/foundation-sites/js/foundation.interchange.js',
          './vendor/foundation-sites/js/foundation.magellan.js',
          './vendor/foundation-sites/js/foundation.offcanvas.js',
          './vendor/foundation-sites/js/foundation.orbit.js',
          './vendor/foundation-sites/js/foundation.responsiveMenu.js',
          './vendor/foundation-sites/js/foundation.responsiveToggle.js',
          './vendor/foundation-sites/js/foundation.reveal.js',
          './vendor/foundation-sites/js/foundation.slider.js',
          './vendor/foundation-sites/js/foundation.sticky.js',
          './vendor/foundation-sites/js/foundation.tabs.js',
          './vendor/foundation-sites/js/foundation.toggler.js',
          './vendor/foundation-sites/js/foundation.tooltip.js',
  ])
	.pipe(babel({
		presets: ['@babel/preset-env'],
	    compact: true
	}))
    .pipe(concat('foundation.js'))
    .pipe(gulp.dest('./dist/js'));
}); 

// Install vendor JS deps
gulp.task('js-deps', function(){
  return gulp.src([ 
    
        // Grab your dependecies scripts
        './vendor/jquery/dist/*.js',
        
  ])
    .pipe(gulp.dest('./dist/js'));
});




// Watch files for changes (without Browser-Sync)
// Watch files for changes (Gulp 4 version)
gulp.task('watch', function() {

    // Watch .scss files
    gulp.watch([
        './resources/scss/**/*.scss',
        './vendor/foundation-sites/scss/**/*.scss'
    ], gulp.series('styles'));

    // Watch site-js files
    gulp.watch('./resources/js/**/*.js', gulp.series('site-js'));

    // Watch foundation-js files
    gulp.watch('./vendor/foundation-sites/js/*.js', gulp.series('foundation-js'));

});


// Run styles, site-js and foundation-js
gulp.task('default', gulp.series('styles', 'site-js', 'foundation-js', 'js-deps', function (done) {
    done();
}));
