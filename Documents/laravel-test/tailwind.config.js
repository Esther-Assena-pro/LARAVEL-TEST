module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: '#1E40AF',
        sky: '#38BDF8',
        background: {
          DEFAULT: '#FFFFFF',
          dark: '#0F172A',
        },
        text: {
          DEFAULT: '#1E293B',
          dark: '#F1F5F9',
        },
        card: {
          DEFAULT: '#F8FAFC',
          dark: '#1E293B',
        },
        border: {
          DEFAULT: '#E2E8F0',
          dark: '#334155',
        },
        accent: {
          DEFAULT: '#10B981',
          dark: '#34D399',
        },
        error: {
          DEFAULT: '#EF4444',
          dark: '#F87171',
        },
        navText: {
          DEFAULT: '#1E40AF',
          dark: '#38BDF8',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
