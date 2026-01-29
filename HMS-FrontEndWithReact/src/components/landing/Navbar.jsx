import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, Phone, UserCircle } from 'lucide-react';
import Button from '../common/Button';
import ThemeToggle from '../common/ThemeToggle';
import { clsx } from 'clsx';

const Navbar = () => {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const location = useLocation();

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'About', path: '/about' },
    { name: 'Services', path: '/services' },
    { name: 'Doctors', path: '/doctors' },
    { name: 'Locations', path: '/locations' },
    { name: 'Contact', path: '/contact' },
  ];

  const isActive = (path) => {
    return location.pathname === path;
  };

  return (
    <header className="bg-white dark:bg-secondary-950 border-b border-secondary-200 dark:border-white/10 sticky top-0 z-50 transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-20">
          {/* Logo */}
          <Link to="/" className="flex items-center space-x-2">
            <div className="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md shadow-primary-600/20">
              H
            </div>
            <div className="flex flex-col md:hidden min-[1100px]:flex">
              <span className="text-xl font-bold text-secondary-900 dark:text-white leading-tight">Dhanmondi</span>
              <span className="text-sm font-medium text-primary-600 dark:text-primary-400 leading-tight">CityCare Hospital</span>
            </div>
          </Link>

          {/* Desktop Navigation */}
          <nav className="hidden min-[1100px]:flex items-center space-x-8">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.path}
                className={clsx(
                  'text-sm font-medium transition-colors hover:text-primary-600 dark:hover:text-primary-400',
                  isActive(link.path) ? 'text-primary-600 dark:text-primary-400 font-bold' : 'text-secondary-600 dark:text-secondary-300'
                )}
              >
                {link.name}
              </Link>
            ))}
          </nav>

          {/* Desktop Actions */}
          <div className="hidden min-[1400px]:flex items-center space-x-4">
            <ThemeToggle />
            <div className="flex items-center text-primary-600 dark:text-primary-400 font-medium">
              <Phone className="w-4 h-4 mr-2" />
              <span>+880 1234 567890</span>
            </div>
            <Link to="/portal">
              <Button variant="primary" size="sm" leftIcon={<UserCircle className="w-4 h-4" />}>
                Patient Portal
              </Button>
            </Link>
          </div>

          {/* Mobile Menu Button */}
          <div className="min-[1400px]:hidden flex items-center gap-4">
            <ThemeToggle />
            <button
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="text-secondary-600 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none"
            >
              {isMobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Menu */}
      {isMobileMenuOpen && (
        <div className="min-[1400px]:hidden bg-white dark:bg-secondary-900 border-t border-secondary-200 dark:border-white/10 py-4 shadow-xl">
          <div className="container mx-auto px-4 flex flex-col space-y-4">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.path}
                className={clsx(
                  'block py-2 text-base font-medium',
                  isActive(link.path) ? 'text-primary-600 dark:text-primary-400' : 'text-secondary-600 dark:text-secondary-300'
                )}
                onClick={() => setIsMobileMenuOpen(false)}
              >
                {link.name}
              </Link>
            ))}
            <div className="pt-4 border-t border-secondary-200 dark:border-white/10 flex flex-col space-y-4">
              <div className="flex items-center text-primary-600 dark:text-primary-400 font-medium">
                <Phone className="w-4 h-4 mr-2" />
                <span>+880 1234 567890</span>
              </div>
              <Link to="/portal/login" onClick={() => setIsMobileMenuOpen(false)}>
                <Button variant="primary" className="w-full" leftIcon={<UserCircle className="w-4 h-4" />}>
                  Patient Portal Login
                </Button>
              </Link>
            </div>
          </div>
        </div>
      )}
    </header>
  );
};

export default Navbar;