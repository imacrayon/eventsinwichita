const cssImport = require('postcss-import')
const cssNesting = require('postcss-nesting')
const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

mix
  .webpackConfig({
    output: {
      chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
    },
    resolve: {
      extensions: ['vue', 'js'],
      alias: {
        '@': __dirname + '/resources/js',
      },
    },
  })
  .postCss('resources/css/app.css', 'public/css', [
    cssImport(),
    cssNesting(),
    tailwindcss(),
  ])
  .js('resources/js/app.js', 'public/js')
  .sourceMaps()
  .version()
