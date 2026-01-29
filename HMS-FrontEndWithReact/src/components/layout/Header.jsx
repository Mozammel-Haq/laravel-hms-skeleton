import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, ChevronRight } from 'lucide-react';
import Button from '../common/Button';
import Logo from '../common/Logo';
import ThemeToggle from '../common/ThemeToggle';

const Header = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const location = useLocation();

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 20);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Close mobile menu on route change
  useEffect(() => {
    setIsOpen(false);
  }, [location]);

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Services', path: '/services' },
    { name: 'Doctors', path: '/doctors' },
    { name: 'Locations', path: '/locations' },
    { name: 'Blog', path: '/blog' },
    { name: 'Contact', path: '/contact' },
  ];

  return (
    <header
      className={`fixed w-full z-50 transition-all duration-300 ${
            scrolled
              ? 'bg-white dark:bg-secondary-950 backdrop-blur-md shadow-sm border-b border-secondary-200 dark:border-secondary-800 py-3'
              : 'bg-white dark:bg-secondary-950/90 backdrop-blur-sm border-b border-secondary-200 dark:border-secondary-800/50 py-3'
          }`}
    >
      <div className="max-w-7xl mx-auto px-4 md:px-4 lg:px-2">
        <div className="flex justify-between items-center">
          {/* Logo */}
          <Link to="/" className="block">
            <Logo textClassName="md:hidden min-[1050px]:flex" />
          </Link>

          {/* Desktop Nav */}
          <nav className="hidden min-[1050px]:flex items-center space-x-8">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.path}
                className={`text-sm font-medium transition-colors hover:text-primary-600 dark:hover:text-primary-400 ${
                  location.pathname === link.path
                    ? 'text-primary-600 dark:text-primary-400 font-bold'
                    : 'text-secondary-600 dark:text-secondary-300'
                }`}
              >
                {link.name}
              </Link>
            ))}
          </nav>

          {/* CTA Buttons & Theme Toggle */}
          <div className="hidden min-[1400px]:flex items-center space-x-4">
            <ThemeToggle />
            <Link to="/portal/login">
              <Button variant="ghost" size="sm" className="text-secondary-600 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-secondary-100 dark:hover:bg-white/5">
                Patient Portal
              </Button>
            </Link>
            <Link to="/portal/login">
              <Button size="sm" className="bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-900/20 border-transparent">
                Book Appointment
              </Button>
            </Link>
          </div>

          {/* Mobile Menu Button */}
          <div className="min-[1400px]:hidden flex items-center gap-4">
            <ThemeToggle />
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="p-2 rounded-md text-secondary-600 dark:text-secondary-300 hover:bg-secondary-100 dark:hover:bg-white/10 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
            >
              {isOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Menu */}
      {isOpen && (
        <div className="min-[1400px]:hidden absolute top-full left-0 w-full bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-white/10 animate-fade-in-up">
          <div className="px-4 py-6 space-y-4">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.path}
                className={`block px-4 py-3 rounded-lg text-base font-medium ${
                  location.pathname === link.path
                    ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                    : 'text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white'
                }`}
              >
                <div className="flex justify-between items-center">
                  {link.name}
                  <ChevronRight className="w-4 h-4 opacity-50" />
                </div>
              </Link>
            ))}
            <div className="pt-4 space-y-3 px-4 border-t border-secondary-200 dark:border-white/10 mt-4">
              <Link to="/portal/login" className="block">
                <Button variant="outline" className="w-full justify-center border-secondary-200 dark:border-white/20 text-secondary-600 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white">
                  Patient Portal
                </Button>
              </Link>
              <Link to="/portal/login" className="block">
                <Button className="w-full justify-center bg-primary-600 text-white shadow-lg hover:bg-primary-700">
                  Book Appointment
                </Button>
              </Link>
            </div>
          </div>
        </div>
      )}
    </header>
  );
};

export default Header;
