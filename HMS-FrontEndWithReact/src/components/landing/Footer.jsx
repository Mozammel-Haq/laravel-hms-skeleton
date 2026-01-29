import React from 'react';
import { Link } from 'react-router-dom';
import { Facebook, Twitter, Instagram, Linkedin, MapPin, Phone, Mail } from 'lucide-react';

const Footer = () => {
  return (
    <footer className="bg-secondary-50 dark:bg-secondary-950 text-secondary-600 dark:text-secondary-300 pt-16 pb-8 border-t border-secondary-200 dark:border-white/10 transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          {/* Brand Info */}
          <div>
            <div className="flex items-center space-x-2 mb-6">
              <div className="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md shadow-primary-600/20">
                H
              </div>
              <div className="flex flex-col">
                <span className="text-xl font-bold text-secondary-900 dark:text-white leading-tight">Dhanmondi</span>
                <span className="text-sm font-medium text-primary-600 dark:text-primary-400 leading-tight">CityCare Hospital</span>
              </div>
            </div>
            <p className="text-secondary-600 dark:text-secondary-400 text-sm leading-relaxed mb-6">
              Providing world-class healthcare with a personal touch. Our dedicated team of specialists is here to ensure your well-being.
            </p>
            <div className="flex space-x-4">
              <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" className="text-secondary-400 hover:text-primary-600 dark:hover:text-white transition-colors">
                <Facebook className="w-5 h-5" />
              </a>
              <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" className="text-secondary-400 hover:text-primary-600 dark:hover:text-white transition-colors">
                <Twitter className="w-5 h-5" />
              </a>
              <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" className="text-secondary-400 hover:text-primary-600 dark:hover:text-white transition-colors">
                <Instagram className="w-5 h-5" />
              </a>
              <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" className="text-secondary-400 hover:text-primary-600 dark:hover:text-white transition-colors">
                <Linkedin className="w-5 h-5" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-6">Quick Links</h3>
            <ul className="space-y-3">
              <li>
                <Link to="/about" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">About Us</Link>
              </li>
              <li>
                <Link to="/services" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Our Services</Link>
              </li>
              <li>
                <Link to="/doctors" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Find a Doctor</Link>
              </li>
              <li>
                <Link to="/portal/login" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Patient Portal</Link>
              </li>
              <li>
                <Link to="/careers" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Careers</Link>
              </li>
            </ul>
          </div>

          {/* Services */}
          <div>
            <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-6">Our Services</h3>
            <ul className="space-y-3">
              <li>
                <Link to="/services/cardiology" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Cardiology</Link>
              </li>
              <li>
                <Link to="/services/orthopedics" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Orthopedics</Link>
              </li>
              <li>
                <Link to="/services/neurology" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Neurology</Link>
              </li>
              <li>
                <Link to="/services/pediatrics" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Pediatrics</Link>
              </li>
              <li>
                <Link to="/services/emergency-care" className="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-sm">Emergency Care</Link>
              </li>
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-6">Contact Us</h3>
            <ul className="space-y-4">
              <li className="flex items-start text-secondary-600 dark:text-secondary-400">
                <MapPin className="w-5 h-5 mr-3 mt-0.5 shrink-0 text-primary-600 dark:text-primary-400" />
                <span className="text-sm">72 Satmasjid Road, Dhanmondi,<br />Dhaka 1209, Bangladesh</span>
              </li>
              <li className="flex items-center text-secondary-600 dark:text-secondary-400">
                <Phone className="w-5 h-5 mr-3 shrink-0 text-primary-600 dark:text-primary-400" />
                <span className="text-sm">+880 1234 567890</span>
              </li>
              <li className="flex items-center text-secondary-600 dark:text-secondary-400">
                <Mail className="w-5 h-5 mr-3 shrink-0 text-primary-600 dark:text-primary-400" />
                <span className="text-sm">info@dhanmondicitycare.com</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="border-t border-secondary-200 dark:border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center">
          <p className="text-secondary-500 text-sm text-center md:text-left mb-4 md:mb-0">
            &copy; {new Date().getFullYear()} Dhanmondi CityCare Hospital. All rights reserved.
          </p>
          <div className="flex space-x-6">
            <Link to="/privacy" className="text-secondary-500 hover:text-primary-600 dark:hover:text-white text-sm transition-colors">Privacy Policy</Link>
            <Link to="/terms" className="text-secondary-500 hover:text-primary-600 dark:hover:text-white text-sm transition-colors">Terms of Service</Link>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
