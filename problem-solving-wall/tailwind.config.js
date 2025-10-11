// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/**/*.vue'
  ],
  safelist: [
    // ✅ grid layouts
    'grid', 'grid-cols-1', 'grid-cols-2', 'grid-cols-3',
    'sm:grid-cols-2','md:grid-cols-2','lg:grid-cols-3',

    // ✅ positioning / floating
    'fixed','bottom-6','right-6','z-50',

    // ✅ height utilities
    'h-screen', 'h-full', 'min-h-screen', 'min-h-full', 'flex-1',

    // ✅ arbitrary heights we rely on
    'h-[calc(100vh-4rem)]',

    // ✅ debug borders (so we can test visually)
    'border-4','border-red-500','border-blue-500','border-green-500','border-purple-500'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [forms],
}
