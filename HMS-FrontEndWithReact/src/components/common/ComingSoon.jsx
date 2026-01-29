import React from 'react';
import { Construction } from 'lucide-react';
import Button from './Button';
import { useNavigate } from 'react-router-dom';

const ComingSoon = ({ title = "Coming Soon", message = "We are working hard to bring you this feature." }) => {
  const navigate = useNavigate();

  return (
    <div className="min-h-[60vh] flex flex-col items-center justify-center p-8 text-center animate-fade-in-up transition-colors duration-300">
      <div className="w-24 h-24 bg-secondary-100 dark:bg-secondary-900 rounded-full flex items-center justify-center mb-6 border border-secondary-200 dark:border-white/10 shadow-2xl shadow-secondary-200/50 dark:shadow-black/20 relative">
        <div className="absolute inset-0 bg-primary-600/20 rounded-full blur-xl animate-pulse"></div>
        <Construction className="w-10 h-10 text-primary-500 relative z-10" />
      </div>

      <h2 className="text-3xl font-bold text-secondary-900 dark:text-white mb-4">{title}</h2>
      <p className="text-secondary-600 dark:text-secondary-400 text-lg max-w-md mb-8 leading-relaxed">
        {message}
      </p>

      <Button
        onClick={() => navigate(-1)}
        className="bg-secondary-200 dark:bg-white/5 text-secondary-900 dark:text-white border border-secondary-300 dark:border-white/10 hover:bg-secondary-300 dark:hover:bg-white/10 hover:border-primary-500/50 transition-all duration-300"
      >
        Go Back
      </Button>
    </div>
  );
};

export default ComingSoon;
