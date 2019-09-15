const mix = require('laravel-mix')

mix
  .webpackConfig({
    output: {
      chunkFilename: 'js/chunks/[name].js',
    },
    resolve: {
      extensions: ['vue', 'js'],
      alias: {
        '@': __dirname + '/resources/js',
      },
    },
  })
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('postcss-nesting'),
  ])
  .babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
  })
  .js('resources/js/app.js', 'public/js')
