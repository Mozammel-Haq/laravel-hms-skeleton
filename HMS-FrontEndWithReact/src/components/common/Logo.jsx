import React from 'react';

const Logo = ({ className = "", iconSize = "w-10 h-10", textSize = "text-xl", subTextSize = "text-[10px]" }) => {
  return (
    <div className={`flex items-center gap-2.5 group ${className}`}>
      {/* Logo Icon */}
      <div className={`${iconSize} relative flex items-center justify-center`}>
        {/* Main Background Shape */}
        <div className="absolute inset-0 bg-gradient-to-tr from-primary-700 to-primary-500 rounded-xl shadow-lg shadow-primary-900/20"></div>

        {/* SVG Icon */}
        <svg
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          className="w-3/5 h-3/5 relative z-10 text-white"
        >
          <path
            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
            fill="currentColor"
            className="opacity-20"
          />
          <path
            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <path
            d="M12 5.5V11M9 8.5h6"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      </div>

      {/* Text Content */}
      <div className="flex flex-col">
        <span className={`${textSize} font-bold text-secondary-900 dark:text-white leading-none tracking-tight transition-colors`}>
          CityCare
        </span>
        <span className={`${subTextSize} text-primary-600 dark:text-primary-400 font-bold tracking-[0.2em] uppercase mt-0.5 group-hover:text-primary-700 dark:group-hover:text-white transition-colors`}>
          Medical
        </span>
      </div>
    </div>
  );
};

export default Logo;
