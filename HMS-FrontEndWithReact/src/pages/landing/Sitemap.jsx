import React from 'react';
import { Link } from 'react-router-dom';

const Sitemap = () => {
  const links = [
    { title: 'Main', items: [
      { name: 'Home', path: '/' },
      { name: 'About Us', path: '/about' },
      { name: 'Contact', path: '/contact' },
    ]},
    { title: 'Services', items: [
      { name: 'All Services', path: '/services' },
      { name: 'Insurance', path: '/insurance' },
      { name: 'Doctors', path: '/doctors' },
    ]},
    { title: 'Resources', items: [
      { name: 'News & Media', path: '/news-media' },
      { name: 'Careers', path: '/careers' },
      { name: 'Privacy Policy', path: '/privacy' },
      { name: 'Terms of Service', path: '/terms' },
    ]},
    { title: 'Patient Portal', items: [
      { name: 'Login', path: '/portal/login' },
      { name: 'Dashboard', path: '/portal/dashboard' },
    ]},
  ];

  return (
    <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 py-20 transition-colors duration-300">
      <div className="container mx-auto px-4 max-w-4xl">
        <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mb-12 text-center">Sitemap</h1>
        <div className="grid md:grid-cols-2 gap-12">
          {links.map((section) => (
            <div key={section.title}>
              <h2 className="text-2xl font-bold text-primary-600 dark:text-primary-400 mb-6">{section.title}</h2>
              <ul className="space-y-3">
                {section.items.map((link) => (
                  <li key={link.name}>
                    <Link to={link.path} className="text-secondary-600 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                      {link.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Sitemap;
