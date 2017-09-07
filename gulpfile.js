// Grab our gulp packages
var gulp  = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    cleanCSS = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    jshint = require('gulp-jshint'),
    stylish = require('jshint-stylish'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    plumber = require('gulp-plumber'),
    bower = require('gulp-bower'),
    babel = require('gulp-babel');


// Compile Sass, Autoprefix and minify
gulp.task('styles', function() {
    return gulp.src('./resources/scss/**/*.scss')
        .pipe(plumber(function(error) {
            gutil.log(gutil.colors.red(error.message));
            this.emit('end');
        }))
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions', 'iOS 8'],
            cascade: false
        }))
        .pipe(gulp.dest('./dist/css/'))
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
    .pipe(gulp.dest('./dist/js'))
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
		presets: ['es2015'],
	    compact: true
	}))
    .pipe(concat('foundation.js'))
    .pipe(gulp.dest('./dist/js'))
}); 

// Install vendor JS deps
gulp.task('js-deps', function(){
  return gulp.src([ 
    
        // Grab your dependecies scripts
        './vendor/jquery/dist/*.js',
        
  ])
    .pipe(gulp.dest('./dist/js'));
});

// Update Foundation with Bower and save to /vendor
gulp.task('bower', function() {
  return bower({ cmd: 'update'})
    .pipe(gulp.dest('vendor/'))
});  




// Watch files for changes (without Browser-Sync)
gulp.task('watch', function() {

  // Watch .scss files
  gulp.watch('./resources/scss/**/*.scss', ['styles']);
  gulp.watch('./vendor/foundation-sites/scss/**/*.scss', ['styles']);

  // Watch site-js files
  gulp.watch('./resources/js/**/*.js', ['site-js']);
  
  // Watch foundation-js files
  gulp.watch('./vendor/foundation-sites/js/*.js', ['foundation-js']);

}); 


// Run styles, site-js and foundation-js
gulp.task('default', function() {
  gulp.start('styles', 'site-js','foundation-js', 'js-deps');
});
