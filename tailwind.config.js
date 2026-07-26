/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: '#412D15',
        ink: '#1F150C',
        cream: '#E1DCC9',
      },
    },
  },
  plugins: [],
}
