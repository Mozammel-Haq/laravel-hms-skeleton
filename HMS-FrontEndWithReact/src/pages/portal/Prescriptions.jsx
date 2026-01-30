import React, { useState, useEffect } from 'react';
import { Pill, RefreshCw, AlertCircle, Clock, CheckCircle, Search, Printer } from 'lucide-react';
import Button from '../../components/common/Button';
import { useUI } from '../../context/UIContext';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const Prescriptions = () => {
  const [filter, setFilter] = useState('active');
  const [medications, setMedications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const { user } = useAuth();
  const { activeClinicId } = useClinic();
  const { addToast } = useUI();

  useEffect(() => {
    const fetchPrescriptions = async () => {
      setLoading(true);
      try {
        const response = await api.get(API_ENDPOINTS.PATIENT.PRESCRIPTIONS);
        setMedications(response.data.prescriptions || []);
      } catch (error) {
        console.error('Failed to fetch prescriptions', error);
        // addToast('error', 'Failed to load prescriptions');
      } finally {
        setLoading(false);
      }
    };

    if (user) {
        fetchPrescriptions();
    }
  }, [user, activeClinicId]);

  const filteredMeds = medications.filter(med => {
    if (filter === 'active' && med.status !== 'Active') return false;
    if (filter === 'history' && med.status === 'Active') return false;

    if (!searchTerm) return true;
    const term = searchTerm.toLowerCase();
    const name = med.name?.toLowerCase() || '';
    const doctor = med.prescribedBy?.toLowerCase() || '';
    return name.includes(term) || doctor.includes(term);
  });

  const handleRefillRequest = (medName) => {
    // Simulate API call
    addToast('success', `Refill request sent for ${medName}`);
  };

  if (loading) {
      return (
        <div className="flex justify-center items-center h-96">
            <MedicalLoader />
        </div>
      );
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Prescriptions</h1>
          <p className="text-secondary-500 dark:text-secondary-400 text-sm mt-1">Manage your active medications and view history.</p>
        </div>
      </div>

      {/* Tabs */}
      <div className="flex flex-col sm:flex-row justify-between items-end gap-4 border-b border-secondary-200 dark:border-secondary-800 pb-0">
        <nav className="-mb-px flex space-x-8">
          <button
            onClick={() => setFilter('active')}
            className={`whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors ${
              filter === 'active'
                ? 'border-primary-600 text-primary-600 dark:text-primary-400'
                : 'border-transparent text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white hover:border-secondary-300 dark:hover:border-secondary-700'
            }`}
          >
            Active Medications
          </button>
          <button
            onClick={() => setFilter('history')}
            className={`whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors ${
              filter === 'history'
                ? 'border-primary-600 text-primary-600 dark:text-primary-400'
                : 'border-transparent text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white hover:border-secondary-300 dark:hover:border-secondary-700'
            }`}
          >
            Medication History
          </button>
        </nav>

        <div className="relative w-full sm:w-64 mb-2">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search className="h-4 w-4 text-secondary-400" />
          </div>
          <input
            type="text"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            placeholder="Search medications..."
            className="block w-full pl-10 pr-3 py-2 border border-secondary-200 dark:border-secondary-800 rounded-md leading-5 bg-secondary-50 dark:bg-secondary-800 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 sm:text-sm"
          />
        </div>
      </div>

      {/* List */}
      <div className="space-y-4">
        {filteredMeds.length > 0 ? (
          filteredMeds.map((med) => (
            <div key={med.id} className="bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 p-6 flex flex-col md:flex-row gap-6">
              <div className="flex-shrink-0">
                <div className={`w-12 h-12 rounded-full flex items-center justify-center ${
                  med.status === 'Active' ? 'bg-primary-100 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-500/20' : 'bg-secondary-100 dark:bg-secondary-800 text-secondary-500 dark:text-secondary-400 border border-secondary-200 dark:border-secondary-800'
                }`}>
                  <Pill className="w-6 h-6" />
                </div>
              </div>

              <div className="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div className="md:col-span-2">
                  <div className="flex items-center gap-2">
                    <h3 className="text-lg font-bold text-secondary-900 dark:text-white">{med.name}</h3>
                    <span className="text-sm text-secondary-500 dark:text-secondary-400 bg-secondary-100 dark:bg-secondary-800 px-2 py-0.5 rounded-full border border-secondary-200 dark:border-secondary-800">{med.dosage}</span>
                  </div>
                  <p className="text-primary-600 dark:text-primary-400 font-medium mt-1">{med.frequency}</p>
                  <p className="text-sm text-secondary-500 dark:text-secondary-400 mt-2 italic">"{med.instructions}"</p>

                  <div className="mt-4 flex flex-wrap gap-4 text-sm text-secondary-500 dark:text-secondary-500">
                    <div className="flex items-center">
                      <Clock className="w-4 h-4 mr-1.5 text-secondary-400 dark:text-secondary-600" />
                      Started: {med.startDate}
                    </div>
                    <div className="flex items-center">
                      <CheckCircle className="w-4 h-4 mr-1.5 text-secondary-400 dark:text-secondary-600" />
                      Prescriber: {med.prescribedBy}
                    </div>
                  </div>
                </div>

                <div className="flex flex-col justify-between items-start md:items-end border-t md:border-t-0 md:border-l border-secondary-200 dark:border-secondary-800 pt-4 md:pt-0 md:pl-6">
                  <div className="mb-4 flex flex-col items-end gap-2 w-full">
                     <div className="flex items-center justify-between w-full md:justify-end gap-2">
                         {med.print_url && (
                              <a
                                  href={med.print_url}
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  className="p-1 text-secondary-500 hover:text-primary-600 dark:text-secondary-400 dark:hover:text-primary-400 transition-colors rounded hover:bg-secondary-100 dark:hover:bg-secondary-800"
                                  title="Print Prescription"
                              >
                                  <Printer className="w-5 h-5" />
                              </a>
                          )}
                         <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${
                            med.status === 'Active' ? 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 border-green-200 dark:border-green-500/20' : 'bg-secondary-100 dark:bg-secondary-800 text-secondary-600 dark:text-secondary-400 border-secondary-200 dark:border-secondary-800'
                          }`}>
                            {med.status}
                          </span>
                     </div>
                  </div>

                  {med.status === 'Active' && (
                    <div className="w-full text-right">
                       <p className="text-sm text-secondary-500 dark:text-secondary-400 mb-2">
                         Refills: <span className="font-bold text-secondary-900 dark:text-white">{med.refillsRemaining}</span>
                       </p>
                       <Button
                         size="sm"
                         variant="outline"
                         className="w-full md:w-auto"
                         disabled={med.refillsRemaining === 0}
                         leftIcon={<RefreshCw className="w-3 h-3" />}
                         onClick={() => handleRefillRequest(med.name)}
                       >
                         Request Refill
                       </Button>
                    </div>
                  )}
                </div>
              </div>
            </div>
          ))
        ) : (
          <div className="text-center py-12 bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 border-dashed">
            <Pill className="w-12 h-12 mx-auto text-secondary-400 dark:text-secondary-700 mb-3" />
            <h3 className="text-lg font-medium text-secondary-900 dark:text-white">No {filter} medications found</h3>
            <p className="text-secondary-500 dark:text-secondary-400 mt-1">Your prescription history will appear here.</p>
          </div>
        )}
      </div>

      <div className="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded-r-lg">
        <div className="flex">
          <div className="flex-shrink-0">
            <AlertCircle className="h-5 w-5 text-yellow-500" aria-hidden="true" />
          </div>
          <div className="ml-3">
            <p className="text-sm text-yellow-700 dark:text-yellow-400">
              For urgent medication issues or adverse reactions, please contact your doctor immediately or visit the nearest emergency room.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Prescriptions;
