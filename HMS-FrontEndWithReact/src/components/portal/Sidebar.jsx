import React from 'react';
import { NavLink, Link, useLocation } from 'react-router-dom';
import {
  LayoutDashboard,
  Calendar,
  FileText,
  Activity,
  Pill,
  TestTube,
  User,
  Settings,
  LogOut,
  X
} from 'lucide-react';
import { useUI } from '../../context/UIContext';
import { useAuth } from '../../context/AuthContext';
import { clsx } from 'clsx';

const Sidebar = () => {
  const { isSidebarOpen, closeSidebar } = useUI();
  const { logout } = useAuth();
  const location = useLocation();

  const links = [
    { name: 'Dashboard', path: '/portal/dashboard', icon: LayoutDashboard },
    { name: 'Appointments', path: '/portal/appointments', icon: Calendar },
    { name: 'Medical History', path: '/portal/history', icon: FileText },
    { name: 'Vitals', path: '/portal/vitals', icon: Activity },
    { name: 'Prescriptions', path: '/portal/prescriptions', icon: Pill },
    { name: 'Lab Results', path: '/portal/lab-results', icon: TestTube },
    { name: 'Profile', path: '/portal/profile', icon: User },
    { name: 'Settings', path: '/portal/settings', icon: Settings },
  ];

  const isActive = (path) => {
    return location.pathname.startsWith(path);
  };

  return (
    <>
      {/* Mobile Backdrop */}
      {isSidebarOpen && (
        <div
          className="fixed inset-0 bg-secondary-950/80 backdrop-blur-sm z-40 lg:hidden"
          onClick={closeSidebar}
        />
      )}

      {/* Sidebar Container */}
      <aside
        className={clsx(
          "fixed top-0 left-0 z-50 h-screen w-64 bg-white dark:bg-secondary-900 border-r border-secondary-200 dark:border-white/10 transition-all duration-300 ease-in-out lg:translate-x-0 lg:static",
          isSidebarOpen ? "translate-x-0" : "-translate-x-full"
        )}
      >
        <div className="flex flex-col h-full">
          {/* Header */}
          <div className="h-16 flex items-center px-6 border-b border-secondary-200 dark:border-white/10">
            <Link to="/" className="flex items-center space-x-2">
              <div className="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                CC
              </div>
              <span className="text-lg font-bold text-secondary-900 dark:text-white">Patient Portal</span>
            </Link>
            <button
              onClick={closeSidebar}
              className="ml-auto lg:hidden text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Navigation */}
          <nav className="flex-1 overflow-y-auto py-6 px-3 space-y-1 scrollbar-thin scrollbar-thumb-secondary-200 dark:scrollbar-thumb-secondary-800">
            {links.map((link) => {
              const Icon = link.icon;
              return (
                <NavLink
                  key={link.path}
                  to={link.path}
                  className={({ isActive }) => clsx(
                    "flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200",
                    isActive
                      ? "bg-primary-50 dark:bg-primary-600/10 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-500/20"
                      : "text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 hover:text-secondary-900 dark:hover:text-white border border-transparent"
                  )}
                  onClick={() => window.innerWidth < 1024 && closeSidebar()}
                >
                  <Icon className={clsx("w-5 h-5 mr-3", isActive(link.path) ? "text-primary-600 dark:text-primary-400" : "text-secondary-500 dark:text-secondary-500 group-hover:text-secondary-900 dark:group-hover:text-secondary-300")} />
                  {link.name}
                </NavLink>
              );
            })}
          </nav>

          {/* Footer */}
          <div className="p-4 border-t border-secondary-200 dark:border-secondary-800">
            <button
              onClick={logout}
              className="flex items-center w-full px-3 py-2.5 text-sm font-medium text-secondary-600 dark:text-secondary-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 transition-colors border border-transparent hover:border-red-200 dark:hover:border-red-500/20"
            >
              <LogOut className="w-5 h-5 mr-3 text-secondary-500 dark:text-secondary-500 group-hover:text-red-600 dark:group-hover:text-red-400" />
              Sign Out
            </button>
          </div>
        </div>
      </aside>
    </>
  );
};

export default Sidebar;
