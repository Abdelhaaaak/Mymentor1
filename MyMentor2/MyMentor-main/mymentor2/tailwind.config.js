// tailwind.config.js
const defaultTheme = require('tailwindcss/defaultTheme');
const forms        = require('@tailwindcss/forms');

module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      // ← your existing customizations
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },

      // ──────── ADD THESE ────────
      keyframes: {
        'gradient-pan': {
          '0%':   { backgroundPosition: '0% 50%' },
          '50%':  { backgroundPosition: '100% 50%' },
          '100%': { backgroundPosition: '0% 50%' },
        },
      },
      animation: {
        'gradient-pan': 'gradient-pan 10s ease infinite',
      },
      // ──────────────────────────

      // …any other extends you already have…
    },
  },

  plugins: [
    forms,
    // …other plugins
  ],
};
