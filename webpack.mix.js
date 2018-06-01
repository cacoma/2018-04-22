let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// inserir para o sistema puxar o formato do jquery - Machado 22/04/2018 as 21:24hs
mix.autoload({
	jquery: ['$', 'window.jQuery', 'jQuery'],
	moment: 'moment'
});

//inserir para o sistema compilar os arquivos com jquery. inserir os arquivos dentro do html nao funciona - rcaziraghi 24042018
mix.js(['resources/assets/js/app.js' ,
 'resources/assets/js/javascript/js.js',
 'resources/assets/js/javascript/classes.js',
  'resources/assets/js/jquery/jquery.js'],
	 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.browserSync('https://cacoma.tk');

// mix.browserSync({
// 	proxy: "https://cacoma.tk",
// 	https: {
// 		key: "/var/www/html/privkey.pem",
// 		cert: "/var/www/html/fullchain.pem"
// 	}
// });
