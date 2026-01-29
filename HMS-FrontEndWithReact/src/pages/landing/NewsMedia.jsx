import React from 'react';

const NewsMedia = () => {
  const news = [
    { title: 'CityCare Opens New Cardiology Wing', date: 'Jan 15, 2026', excerpt: 'Expanding our services to better serve the community with state-of-the-art heart care facilities.' },
    { title: 'Top Doctors Join Our Neurology Department', date: 'Jan 10, 2026', excerpt: 'Welcoming world-renowned specialists to our growing team of experts.' },
    { title: 'Free Health Camp Success', date: 'Dec 20, 2025', excerpt: 'Over 500 patients served in our recent community outreach program.' },
  ];

  return (
    <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 py-20 transition-colors duration-300">
      <div className="container mx-auto px-4">
        <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mb-12 text-center">News & Media</h1>
        <div className="grid md:grid-cols-3 gap-8">
          {news.map((item, index) => (
            <div key={index} className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-100 dark:border-secondary-800 overflow-hidden transition-shadow hover:border-primary-500/50">
              <div className="h-48 bg-secondary-200 dark:bg-secondary-800"></div>
              <div className="p-6">
                <div className="text-sm text-primary-600 dark:text-primary-400 font-medium mb-2">{item.date}</div>
                <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-3">{item.title}</h3>
                <p className="text-secondary-600 dark:text-secondary-400">{item.excerpt}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default NewsMedia;
