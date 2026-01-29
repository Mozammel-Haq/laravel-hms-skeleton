import React from 'react';
import { Sun, Moon } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';
import { motion } from 'framer-motion';

const ThemeToggle = ({ className = '' }) => {
  const { theme, toggleTheme } = useTheme();

  return (
    <button
      onClick={toggleTheme}
      className={`relative p-2 rounded-lg transition-colors hover:bg-secondary-100 dark:hover:bg-secondary-800 ${className}`}
      aria-label="Toggle Theme"
    >
      <div className="relative w-6 h-6">
        <motion.div
          initial={false}
          animate={{ scale: theme === 'dark' ? 1 : 0, rotate: theme === 'dark' ? 0 : 90 }}
          transition={{ duration: 0.2 }}
          className="absolute inset-0 flex items-center justify-center text-primary-400"
        >
          <Moon className="w-5 h-5" />
        </motion.div>
        <motion.div
          initial={false}
          animate={{ scale: theme === 'light' ? 1 : 0, rotate: theme === 'light' ? 0 : -90 }}
          transition={{ duration: 0.2 }}
          className="absolute inset-0 flex items-center justify-center text-orange-500"
        >
          <Sun className="w-5 h-5" />
        </motion.div>
      </div>
    </button>
  );
};

export default ThemeToggle;
