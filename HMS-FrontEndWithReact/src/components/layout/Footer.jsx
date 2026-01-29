import React from 'react';
import { Link } from 'react-router-dom';
import { Stethoscope, MapPin, Phone, Mail, Clock, ArrowRight, Facebook, Twitter, Linkedin, Instagram } from 'lucide-react';
import Logo from '../common/Logo';

const Footer = () => {
  return (
    <footer className="bg-secondary-50 dark:bg-secondary-950 text-secondary-600 dark:text-secondary-400 pt-16 pb-8 border-t border-secondary-200 dark:border-white/5 transition-colors duration-300">
      <div className="max-w-7xl mx-auto px-2 sm:px-4 lg:px-0">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          {/* Brand Column */}
          <div className="space-y-6">
            <Link to="/" className="inline-block">
              <Logo />
            </Link>
            <p className="text-secondary-600 dark:text-secondary-400 text-sm leading-relaxed">
              Providing world-class healthcare with a compassionate touch. Our board-certified specialists are dedicated to your well-being.
            </p>
            <div className="flex gap-4 pt-2">
              {[Facebook, Twitter, Linkedin, Instagram].map((Icon, i) => (
                <a key={i} href="https://example.com" target="_blank" rel="noopener noreferrer" className="w-8 h-8 rounded-full bg-white dark:bg-white/5 flex items-center justify-center hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-all duration-300 border border-secondary-200 dark:border-white/10 hover:border-primary-600 dark:hover:border-primary-600 shadow-sm">
                  <Icon className="w-4 h-4 text-secondary-500 dark:text-secondary-400 group-hover:text-white" />
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-sm font-bold uppercase tracking-wider text-secondary-900 dark:text-secondary-200 mb-6">Quick Links</h4>
            <ul className="space-y-3">
              {[
                { label: 'About Us', path: '/about' },
                { label: 'Find a Doctor', path: '/doctors' },
                { label: 'Our Services', path: '/services' },
                { label: 'Locations', path: '/locations' },
                { label: 'Careers', path: '/careers' },
                { label: 'Blog & News', path: '/blog' }
              ].map((item) => (
                <li key={item.label}>
                  <Link to={item.path} className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 hover:pl-2 transition-all text-sm flex items-center gap-2">
                    <span className="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
                    {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Patient Resources */}
          <div>
            <h4 className="text-sm font-bold uppercase tracking-wider text-secondary-900 dark:text-secondary-200 mb-6">Patient Center</h4>
            <ul className="space-y-3">
              {[
                { label: 'Patient Portal Login', path: '/portal/login' },
                { label: 'Book Appointment', path: '/portal/login' },
                { label: 'Insurance Info', path: '/insurance' },
                { label: 'Pay Bill Online', path: '/insurance' },
                { label: 'Medical Records', path: '/portal/history' },
              ].map((item) => (
                <li key={item.label}>
                  <Link to={item.path} className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">
                    {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h4 className="text-sm font-bold uppercase tracking-wider text-secondary-900 dark:text-secondary-200 mb-6">Contact Us</h4>
            <ul className="space-y-4">
              <li className="flex items-start gap-3 text-secondary-600 dark:text-secondary-400 text-sm">
                <MapPin className="w-5 h-5 text-primary-500 shrink-0 mt-0.5" />
                <span>123 Medical Center Dr.<br />Dhaka, Bangladesh 1205</span>
              </li>
              <li className="flex items-center gap-3 text-secondary-600 dark:text-secondary-400 text-sm">
                <Phone className="w-5 h-5 text-primary-500 shrink-0" />
                <a href="tel:+8801234567890" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">+880 1234 567 890</a>
              </li>
              <li className="flex items-center gap-3 text-secondary-600 dark:text-secondary-400 text-sm">
                <Mail className="w-5 h-5 text-primary-500 shrink-0" />
                <a href="mailto:info@citycare.com" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">info@citycare.com</a>
              </li>
              <li className="flex items-start gap-3 text-secondary-900 dark:text-secondary-300 text-sm bg-white dark:bg-white/5 p-3 rounded-lg border border-secondary-200 dark:border-white/10 shadow-sm backdrop-blur-sm">
                <Clock className="w-5 h-5 text-yellow-500 shrink-0 mt-0.5" />
                <div>
                  <span className="block font-medium">Emergency Dept.</span>
                  <span className="text-xs opacity-60">Open 24/7, 365 days</span>
                </div>
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="pt-8 border-t border-secondary-200 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
          <p className="text-xs text-secondary-500 dark:text-secondary-500">
            &copy; {new Date().getFullYear()} CityCare Medical Center. All rights reserved.
          </p>
          <div className="flex gap-6 text-xs text-secondary-500 dark:text-secondary-500">
            <Link to="/privacy" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Privacy Policy</Link>
            <Link to="/terms" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Terms of Service</Link>
            <Link to="/sitemap" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Sitemap</Link>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
