import React, { useState, useEffect } from 'react';
import { Bell, Lock, Eye, Moon, Globe, User, Save, Camera, Mail, Phone, MapPin, Droplet, Calendar } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';
import { useAuth } from '../../context/AuthContext';
import { useUI } from '../../context/UIContext';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import Button from '../../components/common/Button';

const Settings = () => {
  const { theme, toggleTheme } = useTheme();
  const { user, login } = useAuth(); // login is used to update user context
  const { addToast } = useUI();
  
  const [activeTab, setActiveTab] = useState('profile');
  const [loading, setLoading] = useState(false);
  
  // Profile Form State
  const [profileData, setProfileData] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    blood_group: '',
    date_of_birth: '',
  });

  // Password Form State
  const [passwordData, setPasswordData] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
  });

  // Notification Settings State
  const [notifications, setNotifications] = useState({
    email: true,
    sms: false,
    app: true,
  });

  useEffect(() => {
    if (user) {
      setProfileData({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        address: user.address || '',
        blood_group: user.blood_group || '',
        date_of_birth: user.date_of_birth ? user.date_of_birth.split('T')[0] : '',
      });
    }
  }, [user]);

  const handleProfileUpdate = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      // Create FormData to handle potential file uploads in future
      // For now, we send JSON is fine too, but controller supports file
      // If we use FormData, we must not set Content-Type manually, axios does it
      // But for simple text, JSON is easier. Let's try JSON first as controller validates standard fields.
      // Wait, PatientProfileController expects standard request inputs.
      
      const response = await api.put(API_ENDPOINTS.PATIENT.UPDATE_PROFILE(user.id), profileData);
      
      addToast('success', 'Profile updated successfully');
      // Update local user context if possible, or trigger a reload
      // Assuming login() can be used to set user data or we need a specific setUser
      // For now, just toast.
      
    } catch (error) {
      console.error('Profile update failed:', error);
      addToast('error', error.response?.data?.message || 'Failed to update profile');
    } finally {
      setLoading(false);
    }
  };

  const handlePasswordChange = async (e) => {
    e.preventDefault();
    if (passwordData.new_password !== passwordData.new_password_confirmation) {
      addToast('error', 'New passwords do not match');
      return;
    }
    
    setLoading(true);
    try {
      await api.post(API_ENDPOINTS.PATIENT.CHANGE_PASSWORD, {
        current_password: passwordData.current_password,
        new_password: passwordData.new_password,
      });
      
      addToast('success', 'Password changed successfully');
      setPasswordData({
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
      });
    } catch (error) {
      console.error('Password change failed:', error);
      addToast('error', error.response?.data?.message || 'Failed to change password');
    } finally {
      setLoading(false);
    }
  };

  const tabs = [
    { id: 'profile', label: 'Profile', icon: User },
    { id: 'security', label: 'Security', icon: Lock },
    { id: 'appearance', label: 'Appearance', icon: Moon },
    { id: 'notifications', label: 'Notifications', icon: Bell },
  ];

  return (
    <div className="space-y-6">
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Settings</h1>
        <p className="text-secondary-500 dark:text-secondary-400">Manage your preferences and account settings.</p>
      </div>

      <div className="flex flex-col lg:flex-row gap-6">
        {/* Sidebar */}
        <div className="w-full lg:w-64 flex-shrink-0 space-y-2">
          {tabs.map((tab) => {
            const Icon = tab.icon;
            return (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all ${
                  activeTab === tab.id
                    ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 font-medium'
                    : 'text-secondary-600 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-800'
                }`}
              >
                <Icon className="w-5 h-5" />
                {tab.label}
              </button>
            );
          })}
        </div>

        {/* Content Area */}
        <div className="flex-1 bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800 p-6">
          
          {/* Profile Settings */}
          {activeTab === 'profile' && (
            <form onSubmit={handleProfileUpdate} className="space-y-6">
              <h2 className="text-lg font-bold text-secondary-900 dark:text-white mb-4">Personal Information</h2>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Full Name</label>
                  <div className="relative">
                    <User className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="text"
                      value={profileData.name}
                      onChange={(e) => setProfileData({...profileData, name: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Email Address</label>
                  <div className="relative">
                    <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="email"
                      value={profileData.email}
                      onChange={(e) => setProfileData({...profileData, email: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Phone Number</label>
                  <div className="relative">
                    <Phone className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="tel"
                      value={profileData.phone}
                      onChange={(e) => setProfileData({...profileData, phone: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Date of Birth</label>
                  <div className="relative">
                    <Calendar className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="date"
                      value={profileData.date_of_birth}
                      onChange={(e) => setProfileData({...profileData, date_of_birth: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Blood Group</label>
                  <div className="relative">
                    <Droplet className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <select
                      value={profileData.blood_group}
                      onChange={(e) => setProfileData({...profileData, blood_group: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500 appearance-none"
                    >
                      <option value="">Select Blood Group</option>
                      <option value="A+">A+</option>
                      <option value="A-">A-</option>
                      <option value="B+">B+</option>
                      <option value="B-">B-</option>
                      <option value="AB+">AB+</option>
                      <option value="AB-">AB-</option>
                      <option value="O+">O+</option>
                      <option value="O-">O-</option>
                    </select>
                  </div>
                </div>

                <div className="space-y-2 md:col-span-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Address</label>
                  <div className="relative">
                    <MapPin className="absolute left-3 top-3 w-5 h-5 text-secondary-400" />
                    <textarea
                      value={profileData.address}
                      onChange={(e) => setProfileData({...profileData, address: e.target.value})}
                      rows="3"
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                    ></textarea>
                  </div>
                </div>
              </div>

              <div className="flex justify-end pt-4">
                <Button type="submit" isLoading={loading} leftIcon={<Save className="w-4 h-4" />}>
                  Save Changes
                </Button>
              </div>
            </form>
          )}

          {/* Security Settings */}
          {activeTab === 'security' && (
            <form onSubmit={handlePasswordChange} className="space-y-6">
              <h2 className="text-lg font-bold text-secondary-900 dark:text-white mb-4">Change Password</h2>
              
              <div className="space-y-4 max-w-md">
                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Current Password</label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="password"
                      value={passwordData.current_password}
                      onChange={(e) => setPasswordData({...passwordData, current_password: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                      required
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">New Password</label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="password"
                      value={passwordData.new_password}
                      onChange={(e) => setPasswordData({...passwordData, new_password: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                      required
                      minLength={8}
                    />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="text-sm font-medium text-secondary-700 dark:text-secondary-300">Confirm New Password</label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                    <input
                      type="password"
                      value={passwordData.new_password_confirmation}
                      onChange={(e) => setPasswordData({...passwordData, new_password_confirmation: e.target.value})}
                      className="w-full pl-10 pr-4 py-2 border border-secondary-200 dark:border-secondary-700 rounded-lg bg-transparent text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                      required
                      minLength={8}
                    />
                  </div>
                </div>
              </div>

              <div className="flex justify-end pt-4">
                <Button type="submit" isLoading={loading} leftIcon={<Save className="w-4 h-4" />}>
                  Update Password
                </Button>
              </div>
            </form>
          )}

          {/* Appearance Settings */}
          {activeTab === 'appearance' && (
            <div className="space-y-6">
              <h2 className="text-lg font-bold text-secondary-900 dark:text-white mb-4">Appearance</h2>
              
              <div className="flex items-center justify-between p-4 bg-secondary-50 dark:bg-secondary-800/50 rounded-lg">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-white dark:bg-secondary-800 rounded-lg shadow-sm">
                    <Moon className="w-5 h-5 text-primary-600 dark:text-primary-400" />
                  </div>
                  <div>
                    <div className="font-medium text-secondary-900 dark:text-white">Dark Mode</div>
                    <div className="text-sm text-secondary-500 dark:text-secondary-400">Toggle between light and dark themes</div>
                  </div>
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
          )}

          {/* Notification Settings */}
          {activeTab === 'notifications' && (
            <div className="space-y-6">
              <h2 className="text-lg font-bold text-secondary-900 dark:text-white mb-4">Notification Preferences</h2>
              
              <div className="space-y-4">
                {Object.entries(notifications).map(([key, value]) => (
                  <div key={key} className="flex items-center justify-between p-4 bg-secondary-50 dark:bg-secondary-800/50 rounded-lg">
                    <div className="flex items-center gap-3">
                      <div className="p-2 bg-white dark:bg-secondary-800 rounded-lg shadow-sm">
                        <Bell className="w-5 h-5 text-primary-600 dark:text-primary-400" />
                      </div>
                      <div>
                        <div className="capitalize font-medium text-secondary-900 dark:text-white">{key} Notifications</div>
                        <div className="text-sm text-secondary-500 dark:text-secondary-400">Receive updates via {key}</div>
                      </div>
                    </div>
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
          )}

        </div>
      </div>
    </div>
  );
};

export default Settings;
