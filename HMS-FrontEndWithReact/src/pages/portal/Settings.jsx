import React, { useState } from 'react';
import { Bell, Lock, Eye, Moon, Globe } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';

const Settings = () => {
  const { theme, toggleTheme } = useTheme();
  const [notifications, setNotifications] = useState({
    email: true,
    sms: false,
    app: true,
  });

  return (
    <div className="space-y-6">
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Settings</h1>
        <p className="text-secondary-500 dark:text-secondary-400">Manage your preferences and account settings.</p>
      </div>

      <div className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800 overflow-hidden">
        <div className="p-6 border-b border-secondary-200 dark:border-secondary-800">
          <div className="flex items-center gap-3 mb-4">
            <Moon className="w-5 h-5 text-primary-600 dark:text-primary-400" />
            <h2 className="text-lg font-bold text-secondary-900 dark:text-white">Appearance</h2>
          </div>
          <div className="flex items-center justify-between">
            <div>
              <div className="font-medium text-secondary-900 dark:text-white">Dark Mode</div>
              <div className="text-sm text-secondary-500 dark:text-secondary-400">Toggle between light and dark themes</div>
            </div>
            <button
              onClick={toggleTheme}
              className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                theme === 'dark' ? 'bg-primary-600' : 'bg-secondary-200 dark:bg-secondary-700'
              }`}
            >
              <span className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                theme === 'dark' ? 'translate-x-6' : 'translate-x-1'
              }`} />
            </button>
          </div>
        </div>

        <div className="p-6 border-b border-secondary-200 dark:border-secondary-800">
          <div className="flex items-center gap-3 mb-4">
            <Bell className="w-5 h-5 text-primary-600 dark:text-primary-400" />
            <h2 className="text-lg font-bold text-secondary-900 dark:text-white">Notifications</h2>
          </div>
          <div className="space-y-4">
            {Object.entries(notifications).map(([key, value]) => (
              <div key={key} className="flex items-center justify-between">
                <div className="capitalize font-medium text-secondary-900 dark:text-white">{key} Notifications</div>
                <button
                  onClick={() => setNotifications(prev => ({ ...prev, [key]: !prev[key] }))}
                  className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                    value ? 'bg-primary-600' : 'bg-secondary-200 dark:bg-secondary-700'
                  }`}
                >
                  <span className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                    value ? 'translate-x-6' : 'translate-x-1'
                  }`} />
                </button>
              </div>
            ))}
          </div>
        </div>

        <div className="p-6">
          <div className="flex items-center gap-3 mb-4">
            <Lock className="w-5 h-5 text-primary-600 dark:text-primary-400" />
            <h2 className="text-lg font-bold text-secondary-900 dark:text-white">Security</h2>
          </div>
          <button className="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">Change Password</button>
        </div>
      </div>
    </div>
  );
};

export default Settings;
