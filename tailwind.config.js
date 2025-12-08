/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Filament/**/*.php',
    './resources/views/filament/pages/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {
        fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
        },
        boxShadow: {
            'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
        }
    },
  },
  plugins: [],
}
