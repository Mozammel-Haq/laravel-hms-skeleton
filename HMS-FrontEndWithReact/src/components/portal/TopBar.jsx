import React from 'react';
import { Menu, Bell, ChevronDown } from 'lucide-react';
import { useUI } from '../../context/UIContext';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import ThemeToggle from '../common/ThemeToggle';

const TopBar = () => {
  const { toggleSidebar } = useUI();
  const { user } = useAuth();
  const { clinics, activeClinic, switchClinic } = useClinic();

  return (
    <header className="h-16 bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-800 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-colors duration-300">
      <div className="flex items-center">
        <button
          onClick={toggleSidebar}
          className="lg:hidden p-2 -ml-2 text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white focus:outline-none"
        >
          <Menu className="w-6 h-6" />
        </button>

        {/* Clinic Selector */}
        {clinics.length > 0 && (
          <div className="ml-4 lg:ml-0 relative group">
            <button className="flex items-center space-x-2 text-sm font-medium text-secondary-600 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white focus:outline-none transition-colors">
              <span className="hidden sm:inline text-secondary-500 dark:text-secondary-400">Clinic:</span>
              <span className="font-bold text-primary-600 dark:text-primary-400">{activeClinic?.name || 'Select Clinic'}</span>
              <ChevronDown className="w-4 h-4 text-secondary-400 dark:text-secondary-500" />
            </button>

            {/* Dropdown */}
            <div className="absolute top-full left-0 w-64 bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 py-1 hidden group-hover:block z-50">
              {clinics.map((clinic) => (
                <button
                  key={clinic.id}
                  onClick={() => switchClinic(clinic.id)}
                  className={`block w-full text-left px-4 py-2 text-sm hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors ${
                    activeClinic?.id === clinic.id ? 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-500/10' : 'text-secondary-600 dark:text-secondary-300'
                  }`}
                >
                  {clinic.name}
                </button>
              ))}
            </div>
          </div>
        )}
      </div>

      <div className="flex items-center space-x-4">
        <ThemeToggle />
        {/* Notifications */}
        <button className="p-2 text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors relative">
          <Bell className="w-5 h-5" />
          <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        {/* User Profile */}
        <div className="flex items-center space-x-3 pl-4 border-l border-secondary-200 dark:border-secondary-800">
          <div className="hidden sm:flex flex-col items-end">
            <span className="text-sm font-medium text-secondary-900 dark:text-white">{user?.name || 'Guest User'}</span>
            <span className="text-xs font-semibold text-secondary-500 dark:text-secondary-400">{user?.patient_code || 'ID: ---'}</span>
          </div>
          <div className="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold border border-white/10">
            {user.profile_photo ? (
              <img src={`${import.meta.env.VITE_BACKEND_BASE_URL}/${user.profile_photo}`} alt="User Profile" className="h-8 w-8 rounded-full" />
            ) : (
              <span className="text-xs font-bold">{user?.name?.charAt(0).toUpperCase() || 'U'}</span>
            )}
          </div>
        </div>
      </div>
    </header>
  );
};

export default TopBar;
