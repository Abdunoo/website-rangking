import { js, inProduction, version } from 'laravel-mix';

js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

if (inProduction()) {
    version();
}
