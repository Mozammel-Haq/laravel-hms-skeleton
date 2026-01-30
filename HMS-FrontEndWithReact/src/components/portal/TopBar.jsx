import React, { useState, useEffect, useRef } from 'react';
import { Menu, Bell, ChevronDown, Check, X, Trash2 } from 'lucide-react';
import { useUI } from '../../context/UIContext';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import ThemeToggle from '../common/ThemeToggle';
import api from '../../services/api';
import API_ENDPOINTS from '../../services/endpoints';
import { Link } from 'react-router-dom';

const TopBar = () => {
  const { toggleSidebar } = useUI();
  const { user } = useAuth();
  const { clinics, activeClinic, switchClinic } = useClinic();

  // Notification State
  const [notifications, setNotifications] = useState([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [showNotifications, setShowNotifications] = useState(false);
  const notificationRef = useRef(null);

  // Fetch Notifications
  const fetchNotifications = async () => {
    try {
      const response = await api.get(API_ENDPOINTS.PATIENT.NOTIFICATIONS);
      setNotifications(response.data.notifications || []);
      setUnreadCount(response.data.unread_count || 0);
    } catch (error) {
      console.error("Failed to fetch notifications:", error);
    }
  };

  useEffect(() => {
    if (user) {
      fetchNotifications();
      // Poll every 30 seconds
      const interval = setInterval(fetchNotifications, 30000);
      return () => clearInterval(interval);
    }
  }, [user]);

  // Handle click outside to close dropdown
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (notificationRef.current && !notificationRef.current.contains(event.target)) {
        setShowNotifications(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const markAsRead = async (id) => {
    try {
      await api.post(API_ENDPOINTS.PATIENT.MARK_NOTIFICATION_READ(id));
      // Optimistic update
      if (id === 'all') {
        setNotifications(prev => prev.map(n => ({ ...n, read_at: new Date().toISOString() })));
        setUnreadCount(0);
      } else {
        setNotifications(prev => prev.map(n => n.id === id ? ({ ...n, read_at: new Date().toISOString() }) : n));
        setUnreadCount(prev => Math.max(0, prev - 1));
      }
    } catch (error) {
      console.error("Failed to mark as read:", error);
    }
  };

  const deleteNotification = async (id, e) => {
    e.stopPropagation();
    try {
      await api.delete(API_ENDPOINTS.PATIENT.DELETE_NOTIFICATION(id));
      if (id === 'all') {
        setNotifications([]);
        setUnreadCount(0);
      } else {
        setNotifications(prev => prev.filter(n => n.id !== id));
        const wasUnread = notifications.find(n => n.id === id)?.read_at === null;
        if (wasUnread) setUnreadCount(prev => Math.max(0, prev - 1));
      }
    } catch (error) {
      console.error("Failed to delete notification:", error);
    }
  };

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
            <div className="absolute top-full left-0 w-64 bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 py-1 hidden group-hover:block z-50 shadow-lg">
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
        <div className="relative" ref={notificationRef}>
          <button
            className="p-2 text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors relative"
            onClick={() => setShowNotifications(!showNotifications)}
          >
            <Bell className="w-5 h-5" />
            {unreadCount > 0 && (
              <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            )}
          </button>

          {/* Notification Dropdown */}
          {showNotifications && (
            <div className="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 shadow-xl z-50 overflow-hidden">
              <div className="p-3 border-b border-secondary-200 dark:border-secondary-800 flex justify-between items-center bg-secondary-50 dark:bg-secondary-800/50">
                <h3 className="font-semibold text-secondary-900 dark:text-white text-sm">Notifications</h3>
                <div className="flex gap-2">
                  {unreadCount > 0 && (
                    <button
                      onClick={() => markAsRead('all')}
                      className="text-xs text-primary-600 dark:text-primary-400 hover:underline flex items-center"
                    >
                      <Check className="w-3 h-3 mr-1" /> Mark all read
                    </button>
                  )}
                  {notifications.length > 0 && (
                    <button
                      onClick={(e) => deleteNotification('all', e)}
                      className="text-xs text-red-600 dark:text-red-400 hover:underline flex items-center"
                    >
                      <Trash2 className="w-3 h-3 mr-1" /> Clear all
                    </button>
                  )}
                </div>
              </div>

              <div className="max-h-[400px] overflow-y-auto">
                {notifications.length === 0 ? (
                  <div className="p-8 text-center text-secondary-500 dark:text-secondary-400 text-sm">
                    No notifications yet.
                  </div>
                ) : (
                  notifications.map((notification) => (
                    <div
                      key={notification.id}
                      className={`p-4 border-b border-secondary-100 dark:border-secondary-800 hover:bg-secondary-50 dark:hover:bg-secondary-800/50 transition-colors ${!notification.read_at ? 'bg-primary-50/30 dark:bg-primary-900/10' : ''}`}
                    >
                      <div className="flex justify-between items-start gap-3">
                        <div className="flex-1">
                          <p className="text-sm font-semibold text-secondary-900 dark:text-white mb-1">
                            {notification.data?.title || 'Notification'}
                          </p>
                          <p className="text-xs text-secondary-600 dark:text-secondary-300 mb-2">
                            {notification.data?.message}
                          </p>
                          <div className="flex items-center justify-between">
                            <span className="text-[10px] text-secondary-400">{notification.created_at}</span>
                            {notification.data?.link && (
                              <Link
                                to={notification.data.link}
                                className="text-[10px] font-medium text-primary-600 dark:text-primary-400 hover:underline"
                                onClick={() => {
                                  setShowNotifications(false);
                                  if (!notification.read_at) markAsRead(notification.id);
                                }}
                              >
                                View Details
                              </Link>
                            )}
                          </div>
                        </div>
                        <div className="flex flex-col gap-1">
                          {!notification.read_at && (
                            <button
                              onClick={() => markAsRead(notification.id)}
                              className="text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400"
                              title="Mark as read"
                            >
                              <div className="w-2 h-2 rounded-full bg-primary-500"></div>
                            </button>
                          )}
                          <button
                             onClick={(e) => deleteNotification(notification.id, e)}
                             className="text-secondary-400 hover:text-red-600 dark:hover:text-red-400"
                             title="Delete"
                           >
                             <X className="w-4 h-4" />
                           </button>
                        </div>
                      </div>
                    </div>
                  ))
                )}
              </div>
            </div>
          )}
        </div>

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
