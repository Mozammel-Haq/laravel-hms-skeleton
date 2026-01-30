import React, { useEffect, useState } from 'react';
import { User, Mail, Phone, MapPin, Calendar, Shield, Edit2, Camera, Save } from 'lucide-react';
import Button from '../../components/common/Button';
import { useAuth } from '../../context/AuthContext';
import { formatDate } from 'date-fns';
import API_ENDPOINTS from '../../services/endpoints';
import api from '../../services/api';
import { useUI } from '../../context/UIContext';

const Profile = () => {
  const [isEditing, setIsEditing] = useState(false);
  const { addToast } = useUI();
  const [user, setUser] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    dob: '',
    bloodGroup: '',
    avatar: null
  });
  const [passwordData, setPasswordData] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  });
  const [avatarFile, setAvatarFile] = useState(null);
  const [avatarPreview, setAvatarPreview] = useState(null); // NEW: Separate preview state
  const { user: authUser, setUser: setAuthUser } = useAuth();
  // toast for success and error messages

  useEffect(() => {
    if (authUser) {
      setUser({
        name: authUser.name || '',
        patient_code: authUser.patient_code || '',
        email: authUser.email || '',
        phone: authUser.phone || '',
        address: authUser.address || '',
        dob: formatDate(new Date(authUser.date_of_birth), 'yyyy-MM-dd') || '',
        bloodGroup: authUser.blood_group || '',
        avatar: authUser.profile_photo || null
      });
      setAvatarPreview(null); // Reset preview when authUser changes
    }
  }, [authUser]);

  const handleChange = (e) => {
    setUser({ ...user, [e.target.name]: e.target.value });
  };

  const handlePasswordChange = (e) => {
    setPasswordData({ ...passwordData, [e.target.name]: e.target.value });
  };

  const handleAvatarChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setAvatarFile(file);
      setAvatarPreview(URL.createObjectURL(file)); // Store blob URL separately
    }
  };

  const handleSave = async () => {
    try {
      const formData = new FormData();

      // Append all fields (Laravel will handle empty values)
      formData.append('name', user.name || '');
      formData.append('email', user.email || '');
      formData.append('phone', user.phone || '');
      formData.append('address', user.address || '');
      formData.append('date_of_birth', user.dob || '');
      formData.append('blood_group', user.bloodGroup || '');

      // Append password fields only if they are filled
      if (passwordData.current_password) {
        formData.append('current_password', passwordData.current_password);
      }
      if (passwordData.new_password) {
        formData.append('new_password', passwordData.new_password);
      }
      if (passwordData.new_password_confirmation) {
        formData.append('new_password_confirmation', passwordData.new_password_confirmation);
      }

      if (avatarFile) {
        formData.append('profile_photo', avatarFile);
      }

      // Laravel doesn't support PUT with multipart/form-data well
      // So we use POST with _method override
      formData.append('_method', 'PUT');

      const response = await api.post(API_ENDPOINTS.PATIENT.UPDATE_PROFILE(authUser.id), formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      addToast('success', 'Profile updated successfully');

      if (response.status === 200) {
        // Update authUser with the data from response (response.data.data contains the patient object)
        const updatedPatient = response.data.data;
        setAuthUser(updatedPatient);
        setIsEditing(false);
        setAvatarFile(null);
        setAvatarPreview(null); // Clear preview after save
        setPasswordData({ current_password: '', new_password: '', new_password_confirmation: '' }); // Reset password fields
      }
    } catch (error) {
      addToast('error', 'Failed to update profile');
      console.error('Failed to update profile:', error);
      if (error.response) {
        console.error('Error details:', error.response.data);
      }
    }
  };

  // Helper function to get the correct avatar URL
  const getAvatarUrl = () => {
    if (avatarPreview) {
      // If there's a preview (user just uploaded), use the blob URL
      return avatarPreview;
    } else if (user.avatar) {
      // Otherwise use the server URL
      return `${import.meta.env.VITE_BACKEND_BASE_URL}/${user.avatar}`;
    }
    return null;
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">My Profile</h1>
          <p className="text-secondary-500 dark:text-secondary-400">Manage your personal information</p>
        </div>
        <Button
          variant={isEditing ? 'primary' : 'outline'}
          onClick={isEditing ? handleSave : () => setIsEditing(true)}
        >
          {isEditing ? <Save className="w-4 h-4 mr-2" /> : <Edit2 className="w-4 h-4 mr-2" />}
          {isEditing ? 'Save Changes' : 'Edit Profile'}
        </Button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Profile Card */}
        <div className="bg-white dark:bg-secondary-900 rounded-2xl border border-secondary-200 dark:border-secondary-800 p-6 text-center">
          <div className="relative inline-block mb-4">
            <div className="w-32 h-32 rounded-full bg-primary-100 dark:bg-primary-500/10 flex items-center justify-center text-4xl font-bold text-primary-600 dark:text-primary-400 overflow-hidden border-4 border-white dark:border-secondary-800 shadow-none">
              {getAvatarUrl() ? (
                <img
                  src={getAvatarUrl()}
                  alt={user.name}
                  className="w-full h-full object-cover"
                />
              ) : (
                user.name.charAt(0)
              )}
            </div>
            {isEditing && (
              <label
                htmlFor="avatarUpload"
                className="absolute bottom-0 right-0 bg-primary-600 hover:bg-primary-700 text-white p-2 rounded-full cursor-pointer"
              >
                <Camera className="w-4 h-4" />
                <input
                  id="avatarUpload"
                  type="file"
                  accept="image/*"
                  onChange={handleAvatarChange}
                  className="hidden"
                />
              </label>
            )}
          </div>
          <h2 className="text-xl font-bold text-secondary-900 dark:text-white mb-1">{user.name}</h2>
          <p className="text-secondary-500 dark:text-secondary-400 mb-4">Patient ID: #{user.patient_code}</p>
          <div className="inline-flex items-center px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-500/10 text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-500/20 text-sm font-medium">
            <Shield className="w-4 h-4 mr-2" />
            Verified Patient
          </div>
        </div>

        {/* Personal Info */}
        <div className="lg:col-span-2 bg-white dark:bg-secondary-900 rounded-2xl border border-secondary-200 dark:border-secondary-800 p-6">
          <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-6">Personal Information</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Full Name</label>
              <div className="relative">
                <User className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="text"
                  name="name"
                  value={user.name}
                  onChange={handleChange}
                  disabled={!isEditing}
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white placeholder:text-secondary-400"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Email Address</label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="email"
                  name="email"
                  value={user.email}
                  onChange={handleChange}
                  disabled={!isEditing}
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white placeholder:text-secondary-400"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Phone Number</label>
              <div className="relative">
                <Phone className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="tel"
                  name="phone"
                  value={user.phone}
                  onChange={handleChange}
                  disabled={!isEditing}
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Date of Birth</label>
              <div className="relative">
                <Calendar className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="date"
                  name="dob"
                  value={user.dob}
                  onChange={handleChange}
                  disabled={!isEditing}
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white"
                />
              </div>
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Address</label>
              <div className="relative">
                <MapPin className="absolute left-3 top-3 w-5 h-5 text-secondary-400" />
                <textarea
                  name="address"
                  value={user.address}
                  onChange={handleChange}
                  disabled={!isEditing}
                  rows="3"
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white resize-none placeholder:text-secondary-400"
                />
              </div>
            </div>
          </div>
        </div>

        {/* Security Settings */}
        <div className="lg:col-span-3 bg-white dark:bg-secondary-900 rounded-2xl border border-secondary-200 dark:border-secondary-800 p-6">
          <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-6">Security Settings</h3>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Current Password</label>
              <div className="relative">
                <Shield className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="password"
                  name="current_password"
                  value={passwordData.current_password}
                  onChange={handlePasswordChange}
                  disabled={!isEditing}
                  placeholder="Enter current password"
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white placeholder:text-secondary-400"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">New Password</label>
              <div className="relative">
                <Shield className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="password"
                  name="new_password"
                  value={passwordData.new_password}
                  onChange={handlePasswordChange}
                  disabled={!isEditing}
                  placeholder="Enter new password"
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white placeholder:text-secondary-400"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Confirm New Password</label>
              <div className="relative">
                <Shield className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
                <input
                  type="password"
                  name="new_password_confirmation"
                  value={passwordData.new_password_confirmation}
                  onChange={handlePasswordChange}
                  disabled={!isEditing}
                  placeholder="Confirm new password"
                  className="w-full pl-10 pr-4 py-2 bg-secondary-50 dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:opacity-60 disabled:cursor-not-allowed text-secondary-900 dark:text-white placeholder:text-secondary-400"
                />
              </div>
            </div>
          </div>
          <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-4">
            Leave password fields empty if you don't want to change your password.
          </p>
        </div>
      </div>
    </div>
  );
};

export default Profile;
