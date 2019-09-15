module.exports = {
  theme: {
    customForms: theme => ({
      default: {
        'input, textarea, select, multiselect, checkbox, radio': {
          '&:focus': {
            boxShadow: theme('boxShadow.default'),
            borderColor: theme('colors.black'),
          },
        },
      },
    }),
    extend: {
      fontFamily: {
        sans: 'Inter, sans-serif',
      },
    },
  },
  variants: {},
  plugins: [require('@tailwindcss/custom-forms')],
}
