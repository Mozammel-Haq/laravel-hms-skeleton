import React, { useState, useEffect } from 'react';
import { FileText, Download, Share2, Search, Filter, Beaker } from 'lucide-react';
import Button from '../../components/common/Button';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const LabResults = () => {
  const { user } = useAuth();
  const { activeClinicId } = useClinic();
  const [loading, setLoading] = useState(true);
  const [results, setResults] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    const fetchLabResults = async () => {
      setLoading(true);
      try {
        const response = await api.get(API_ENDPOINTS.PATIENT.LAB_RESULTS);
        setResults(response.data.results || []);
      } catch (error) {
        console.error('Failed to fetch lab results', error);
      } finally {
        setLoading(false);
      }
    };

    if (user && activeClinicId) {
        fetchLabResults();
    }
  }, [user, activeClinicId]);

  const getStatusColor = (status) => {
    switch (status) {
      case 'Normal': return 'bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-400';
      case 'Attention': return 'bg-amber-100 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400';
      case 'Critical': return 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400';
      default: return 'bg-secondary-100 text-secondary-700 dark:bg-secondary-800 dark:text-secondary-400';
    }
  };

  const filteredResults = results.filter(result =>
    result.testName.toLowerCase().includes(searchTerm.toLowerCase()) ||
    result.doctor.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Lab Results</h1>
          <p className="text-secondary-500 dark:text-secondary-400">View and download your laboratory test reports</p>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" size="sm">
            <Filter className="w-4 h-4 mr-2" />
            Filter
          </Button>
          <Button size="sm">
            <Download className="w-4 h-4 mr-2" />
            Download All
          </Button>
        </div>
      </div>

      {/* Search */}
      <div className="relative">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
        <input
          type="text"
          placeholder="Search by test name or doctor..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          className="w-full pl-10 pr-4 py-3 bg-white dark:bg-secondary-900 border border-secondary-200 dark:border-secondary-800 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-secondary-900 dark:text-white placeholder:text-secondary-400"
        />
      </div>

      {/* Results List */}
      {loading ? (
        <div className="flex justify-center items-center h-64">
          <MedicalLoader />
        </div>
      ) : filteredResults.length === 0 ? (
        <div className="text-center py-12 bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800">
          <Beaker className="w-12 h-12 text-secondary-300 mx-auto mb-3" />
          <h3 className="text-lg font-medium text-secondary-900 dark:text-white">No Lab Results Found</h3>
          <p className="text-secondary-500 dark:text-secondary-400 mt-1">You don't have any lab results yet.</p>
        </div>
      ) : (
      <div className="grid gap-4">
        {filteredResults.map((result) => (
          <div key={result.id} className="bg-white dark:bg-secondary-900 rounded-xl p-6 border border-secondary-200 dark:border-secondary-800 hover:border-primary-500/50 transition-colors group">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="flex items-start gap-4">
                <div className="p-3 rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform">
                  <Beaker className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="font-bold text-secondary-900 dark:text-white text-lg">{result.testName}</h3>
                  <div className="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                    <span>{result.doctor}</span>
                    <span className="hidden sm:inline">•</span>
                    <span>{result.date}</span>
                  </div>
                  {result.result && (
                      <div className="mt-2 text-sm text-secondary-600 dark:text-secondary-300">
                          Result: <span className="font-semibold">{result.result}</span>
                      </div>
                  )}
                </div>
              </div>

              <div className="flex items-center gap-4 self-end md:self-auto">
                <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ${getStatusColor(result.status)}`}>
                  {result.status}
                </span>
                <div className="flex gap-2">
                  <button className="p-2 text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" title="Share">
                    <Share2 className="w-5 h-5" />
                  </button>
                  {result.file && (
                      <a href={result.file} target="_blank" rel="noopener noreferrer" className="p-2 text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" title="Download PDF">
                        <Download className="w-5 h-5" />
                      </a>
                  )}
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
      )}
    </div>
  );
};

export default LabResults;
