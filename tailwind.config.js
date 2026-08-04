/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.php",
    "./public/**/*.html",
    "./frontend/**/*.php",
    "./backend/**/*.php"
  ],
  safelist: [
    // Primary brand color hover/focus/active variants used across the frontend
    'hover:bg-primary-hover',
    'hover:bg-primary-light',
    'hover:text-primary',
    'focus:ring-primary',
    'focus:border-primary',
    'focus:border-transparent',
    'group-hover:bg-primary',
    'group-hover:bg-primary/10',
    'active:scale-95',
    'hover:shadow-md',
    'hover:opacity-100',
    // Disabled states
    'disabled:opacity-50',
    'disabled:cursor-not-allowed',
    // Ring variants for form focus
    'ring-4',
    'ring-[#EEF2FF]',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      fontSize: {
        'h1': ['72px', { lineHeight: '60px', fontWeight: '700' }],
        'h2': ['48px', { lineHeight: '50px', fontWeight: '700' }],
        'h3': ['36px', { lineHeight: '38px', fontWeight: '600' }],
        'h4': ['18px', { lineHeight: '22px', fontWeight: '500' }],
        'paragraph': ['14px', { lineHeight: '24px' }],
      },
      colors: {
        primary: {
          DEFAULT: '#4F47E6',
          hover: '#4338CA',
          light: '#EEF2FF',
          text: '#191C1D'
        },
        secondary: {
          text: '#464555',
          DEFAULT: '#FFFFFF',
        },
        mainbg: '#F8F9FA',
        brandBorder: '#E2E8F0',
        error: '#EF4444',
        success: '#22C55E',
        warning: '#F59E0B'
      }
    },
  },
  plugins: [],
}
