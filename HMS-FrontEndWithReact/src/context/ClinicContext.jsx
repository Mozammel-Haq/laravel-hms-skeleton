import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import PropTypes from 'prop-types';
import api from '../services/api';
import { API_ENDPOINTS } from '../services/endpoints';
import { useAuth } from './AuthContext';
import { useUI } from './UIContext';

const ClinicContext = createContext();

export const useClinic = () => {
  const context = useContext(ClinicContext);
  if (!context) {
    throw new Error('useClinic must be used within a ClinicProvider');
  }
  return context;
};

export const ClinicProvider = ({ children }) => {
  const { isAuthenticated, token } = useAuth();
  const { addToast, setIsLoading } = useUI();

  const [clinics, setClinics] = useState([]);
  const [activeClinic, setActiveClinic] = useState(null);
  const [activeClinicId, setActiveClinicId] = useState(localStorage.getItem('activeClinicId'));

  // Fetch clinics when authenticated
  useEffect(() => {
    if (isAuthenticated) {
      fetchClinics();
    } else {
      setClinics([]);
      setActiveClinic(null);
      setActiveClinicId(null);
    }
  }, [isAuthenticated]);

  // Update active clinic object when clinics or ID changes
  useEffect(() => {
    if (activeClinicId && clinics.length > 0) {
      const clinic = clinics.find(c => c.id === parseInt(activeClinicId));
      if (clinic) {
        setActiveClinic(clinic);
      }
    }
  }, [activeClinicId, clinics]);

  const fetchClinics = async () => {
    try {
      // INTEGRATION: Use the correct endpoint from endpoints.js
      // const response = await api.get(API_ENDPOINTS.PATIENT.AVAILABLE_CLINICS);
      const response = await api.get(`${API_ENDPOINTS.PATIENT.AVAILABLE_CLINICS}`); 
      setClinics(response.data.clinics || []);
    } catch (error) {
      console.error('Failed to fetch clinics', error);
      // Don't toast here to avoid spam on login if it fails silently
    }
  };

  const switchClinic = useCallback(async (clinicId) => {
    setIsLoading(true);
    try {
      // Logic from guide: Clear cached data, reset state
      // In a real app with React Query, we'd invalidate queries.
      // Here we just update the state and storage.

      localStorage.setItem('activeClinicId', clinicId);
      setActiveClinicId(clinicId);

      addToast('success', 'Clinic switched successfully');

      // Ideally we might want to redirect to dashboard if deep in a page
      // window.location.href = '/portal/dashboard';
    } catch (error) {
      console.error('Failed to switch clinic', error);
      addToast('error', 'Failed to switch clinic');
    } finally {
      setIsLoading(false);
    }
  }, [addToast, setIsLoading]);

  const value = {
    clinics,
    activeClinic,
    activeClinicId,
    switchClinic,
    refreshClinics: fetchClinics,
  };

  return <ClinicContext.Provider value={value}>{children}</ClinicContext.Provider>;
};

ClinicProvider.propTypes = {
  children: PropTypes.node.isRequired,
};
