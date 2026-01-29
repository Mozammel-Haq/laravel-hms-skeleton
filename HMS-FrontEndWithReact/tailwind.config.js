/** @type {import('tailwindcss').Config} */
export default {
  darkMode: "class", // Explicitly use class strategy for better compatibility
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: "#059669", // Healing Green 600 (Vitality)
          50: "#ecfdf5", // Mint
          100: "#d1fae5",
          200: "#a7f3d0", // Soft Mint
          300: "#6ee7b7",
          400: "#34d399",
          500: "#10b981",
          600: "#059669",
          700: "#047857",
          800: "#065f46",
          900: "#064e3b", // Forest Green
          950: "#022c22", // Deep Forest
        },
        secondary: {
          DEFAULT: "#64748b",
          50: "#f8fafc",
          100: "#f1f5f9",
          200: "#e2e8f0",
          300: "#cbd5e1",
          400: "#94a3b8",
          500: "#64748b",
          600: "#475569",
          700: "#334155",
          800: "#1e293b",
          900: "#0f172a",
          950: "#020617",
        },
      },
      fontFamily: {
        sans: ["Inter", "sans-serif"],
      },
      animation: {
        ecg: "ecg 0.3s linear infinite",
      },
      keyframes: {
        ecg: {
          "0%": { transform: "translateX(-100%)" },
          "100%": { transform: "translateX(100%)" },
        },
      },
    },
  },
  plugins: [],
};
