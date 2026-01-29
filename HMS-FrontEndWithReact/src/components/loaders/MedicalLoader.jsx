import React from 'react';
import { HeartPulse } from 'lucide-react';

const MedicalLoader = ({ text = 'Loading doctors...' }) => {
  return (
    <div className="flex flex-col items-center justify-center py-24">
      {/* Icon */}
      <div className="relative">
        <HeartPulse className="w-16 h-16 text-primary-600 animate-pulse" />

        {/* ECG Line */}
        <div className="absolute -bottom-4 left-1/2 -translate-x-1/2 w-24 h-1 overflow-hidden">
          <div className="w-full h-full bg-gradient-to-r from-transparent via-primary-500 to-transparent animate-ecg"></div>
        </div>
      </div>

      {/* Text */}
      <p className="mt-8 text-secondary-600 dark:text-secondary-400 text-sm tracking-wide">
        {text}
      </p>
    </div>
  );
};

export default MedicalLoader;
