import React, { useState, useEffect } from 'react';
import { Activity, AlertCircle, Scissors, FileText, Syringe, Clock } from 'lucide-react';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const MedicalHistory = () => {
  const { user } = useAuth();
  const { activeClinicId } = useClinic();
  const [loading, setLoading] = useState(true);
  const [data, setData] = useState({
      conditions: [],
      allergies: [],
      surgeries: [],
      immunizations: []
  });

  useEffect(() => {
    const fetchHistory = async () => {
      setLoading(true);
      try {
        const response = await api.get(API_ENDPOINTS.PATIENT.MEDICAL_HISTORY);
        setData(response.data);
      } catch (error) {
        console.error('Failed to fetch medical history', error);
      } finally {
        setLoading(false);
      }
    };

    if (user && activeClinicId) {
        fetchHistory();
    }
  }, [user, activeClinicId]);

  if (loading) {
      return (
        <div className="flex justify-center items-center h-96">
            <MedicalLoader />
        </div>
      );
  }

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Medical History</h1>
        <p className="text-secondary-500 dark:text-secondary-400 mt-1">Comprehensive view of your medical records and health profile.</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Chronic Conditions */}
        <div className="bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 overflow-hidden">
          <div className="bg-primary-50 dark:bg-primary-900/20 px-6 py-4 border-b border-secondary-200 dark:border-secondary-800 flex items-center">
            <Activity className="w-5 h-5 text-primary-600 dark:text-primary-400 mr-2" />
            <h2 className="font-bold text-primary-700 dark:text-primary-400">Chronic Conditions</h2>
          </div>
          <div className="divide-y divide-secondary-200 dark:divide-secondary-800">
            {data.conditions.length === 0 ? <div className="p-4 text-center text-secondary-500">No chronic conditions recorded</div> :
            data.conditions.map((item) => (
              <div key={item.id} className="p-4 hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors">
                <div className="flex justify-between items-start">
                  <div>
                    <h3 className="font-bold text-secondary-900 dark:text-white">{item.name}</h3>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Diagnosed: {item.diagnosed}</p>
                    <p className="text-xs text-secondary-400 dark:text-secondary-500 mt-0.5">By {item.doctor}</p>
                  </div>
                  <span className="bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-500/20 text-xs px-2.5 py-0.5 rounded-full font-medium">
                    {item.status}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Allergies */}
        <div className="bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 overflow-hidden">
          <div className="bg-red-50 dark:bg-red-900/20 px-6 py-4 border-b border-secondary-200 dark:border-secondary-800 flex items-center">
            <AlertCircle className="w-5 h-5 text-red-600 dark:text-red-400 mr-2" />
            <h2 className="font-bold text-red-700 dark:text-red-400">Allergies</h2>
          </div>
          <div className="divide-y divide-secondary-200 dark:divide-secondary-800">
            {(!data?.allergies || data.allergies.length === 0) ? <div className="p-4 text-center text-secondary-500">No allergies recorded</div> :
            data.allergies.map((item) => (
              <div key={item.id} className="p-4 hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors">
                <div className="flex justify-between items-start">
                  <div>
                    <h3 className="font-bold text-secondary-900 dark:text-white">{item.allergen}</h3>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Reaction: {item.reaction}</p>
                  </div>
                  <span className={`text-xs px-2.5 py-0.5 rounded-full font-medium border ${
                    item.severity === 'Critical' ? 'bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20' : 'bg-orange-100 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-500/20'
                  }`}>
                    {item.severity}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Surgeries & Procedures */}
        <div className="bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 overflow-hidden">
          <div className="bg-blue-50 dark:bg-blue-900/20 px-6 py-4 border-b border-secondary-200 dark:border-secondary-800 flex items-center">
            <Scissors className="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" />
            <h2 className="font-bold text-blue-700 dark:text-blue-400">Surgeries & Procedures</h2>
          </div>
          <div className="divide-y divide-secondary-200 dark:divide-secondary-800">
            {(!data?.surgeries || data.surgeries.length === 0) ? <div className="p-4 text-center text-secondary-500">No surgeries recorded</div> :
            data.surgeries.map((item) => (
              <div key={item.id} className="p-4 hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors">
                <div className="flex justify-between items-start">
                  <div>
                    <h3 className="font-bold text-secondary-900 dark:text-white">{item.procedure}</h3>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-1">{item.hospital}</p>
                  </div>
                  <div className="text-right">
                    <p className="text-sm font-medium text-secondary-700 dark:text-secondary-300">{item.date}</p>
                    <p className="text-xs text-secondary-400 dark:text-secondary-500 mt-0.5">{item.surgeon}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Immunizations */}
        <div className="bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 overflow-hidden">
          <div className="bg-purple-50 dark:bg-purple-900/20 px-6 py-4 border-b border-secondary-200 dark:border-secondary-800 flex items-center">
            <Syringe className="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" />
            <h2 className="font-bold text-purple-700 dark:text-purple-400">Immunizations</h2>
          </div>
          <div className="divide-y divide-secondary-200 dark:divide-secondary-800">
            {data.immunizations.length === 0 ? <div className="p-4 text-center text-secondary-500">No immunizations recorded</div> :
            data.immunizations.map((item) => (
              <div key={item.id} className="p-4 hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors">
                <div className="flex justify-between items-center">
                  <div>
                    <h3 className="font-bold text-secondary-900 dark:text-white">{item.vaccine}</h3>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-1">{item.provider}</p>
                  </div>
                  <div className="text-right">
                    <p className="text-sm font-medium text-secondary-700 dark:text-secondary-300">{item.date}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="bg-white dark:bg-secondary-900 border border-secondary-200 dark:border-secondary-800 rounded-lg p-4 flex items-start gap-3">
        <FileText className="w-5 h-5 text-secondary-400 dark:text-secondary-400 mt-0.5" />
        <div>
          <h4 className="font-medium text-secondary-900 dark:text-white">Need a copy of your records?</h4>
          <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-1">
            You can request a full download of your medical history for personal use or to share with another provider.
          </p>
          <button className="text-primary-600 dark:text-primary-400 text-sm font-medium hover:text-primary-700 dark:hover:text-primary-300 mt-2 transition-colors">
            Request Medical Records Download &rarr;
          </button>
        </div>
      </div>
    </div>
  );
};

export default MedicalHistory;
