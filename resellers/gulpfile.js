var elixir = require('laravel-elixir');
var inky = require('inky');
var gulp = require('gulp');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
});

elixir(function(mix) {
    mix.task('inky');
});

gulp.task("inky", function() {
  gulp.src('resources/views/emails/rawemail.html')
  .pipe(inky())
  .pipe(gulp.dest('resources/views/emails/compiled'));
});
