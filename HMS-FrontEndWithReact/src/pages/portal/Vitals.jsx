import React, { useState, useEffect } from 'react';
import { Activity, Heart, Thermometer, Droplet, Weight, Wind, TrendingUp, TrendingDown, ArrowRight, Search } from 'lucide-react';
import Button from '../../components/common/Button';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const Vitals = () => {
  const { user } = useAuth();
  const { activeClinicId } = useClinic();
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [data, setData] = useState({ cards: [], history: [] });

  useEffect(() => {
    const fetchVitals = async () => {
      setLoading(true);
      try {
        const response = await api.get(API_ENDPOINTS.PATIENT.VITALS);
        setData(response.data);
      } catch (error) {
        console.error('Failed to fetch vitals', error);
      } finally {
        setLoading(false);
      }
    };

    if (user && activeClinicId) {
        fetchVitals();
    }
  }, [user, activeClinicId]);

  const filteredHistory = data.history.filter(record => {
    if (!searchTerm) return true;
    const term = searchTerm.toLowerCase();
    return (
        record.date?.toLowerCase().includes(term) ||
        record.heartRate?.toString().includes(term) ||
        record.bp?.toString().includes(term) ||
        record.temp?.toString().includes(term) ||
        record.weight?.toString().includes(term)
    );
  });

  const getIcon = (title) => {
      switch(title) {
          case 'Heart Rate': return Heart;
          case 'Blood Pressure': return Activity;
          case 'Body Temperature': return Thermometer;
          case 'Blood Oxygen': return Wind;
          case 'Weight': return Weight;
          case 'Blood Glucose': return Droplet;
          default: return Activity;
      }
  };

  const getColor = (title) => {
      switch(title) {
          case 'Heart Rate': return { color: 'text-primary-600 dark:text-primary-400', bg: 'bg-primary-500/10' };
          case 'Blood Pressure': return { color: 'text-teal-600 dark:text-teal-400', bg: 'bg-teal-500/10' };
          case 'Body Temperature': return { color: 'text-emerald-600 dark:text-emerald-400', bg: 'bg-emerald-500/10' };
          case 'Blood Oxygen': return { color: 'text-cyan-600 dark:text-cyan-400', bg: 'bg-cyan-500/10' };
          case 'Weight': return { color: 'text-secondary-600 dark:text-secondary-400', bg: 'bg-secondary-500/10' };
          default: return { color: 'text-secondary-600 dark:text-secondary-400', bg: 'bg-secondary-500/10' };
      }
  };

  if (loading) {
      return (
        <div className="flex justify-center items-center h-96">
            <MedicalLoader />
        </div>
      );
  }

  return (
    <div className="space-y-8">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Health Vitals</h1>
          <p className="text-secondary-500 dark:text-secondary-400">Monitor your key health metrics over time</p>
        </div>
        <Button>
          Log New Vitals
        </Button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {data.cards.map((vital, index) => {
            const Icon = getIcon(vital.title);
            const style = getColor(vital.title);
            return (
          <div key={index} className="bg-white dark:bg-secondary-900 rounded-2xl p-6 border border-secondary-200 dark:border-secondary-800 hover:border-primary-500/30 transition-colors">
            <div className="flex justify-between items-start mb-4">
              <div className={`p-3 rounded-xl ${style.bg} ${style.color} ${!style.bg.includes('border') ? 'border border-transparent' : ''}`}>
                <Icon className="w-6 h-6" />
              </div>
              <span className={`px-2 py-1 rounded text-xs font-bold uppercase ${
                vital.status === 'Normal'
                  ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/20 dark:text-primary-400 border border-primary-200 dark:border-primary-500/20'
                  : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20'
              }`}>
                {vital.status}
              </span>
            </div>

            <h3 className="text-secondary-500 dark:text-secondary-400 font-medium text-sm mb-1">{vital.title}</h3>
            <div className="flex items-baseline gap-2 mb-4">
              <span className="text-3xl font-bold text-secondary-900 dark:text-white">{vital.value}</span>
              <span className="text-secondary-500 dark:text-secondary-400 text-sm">{vital.unit}</span>
            </div>

            <div className="flex items-center text-sm">
              {vital.trendData?.trend === 'up' && <TrendingUp className="w-4 h-4 text-green-500 mr-1" />}
              {vital.trendData?.trend === 'down' && <TrendingDown className="w-4 h-4 text-rose-500 mr-1" />}
              {vital.trendData?.trend === 'stable' && <Activity className="w-4 h-4 text-secondary-400 mr-1" />}

              <span className={`font-medium ${
                vital.trendData?.trend === 'up' ? 'text-green-500' :
                vital.trendData?.trend === 'down' ? 'text-rose-500' : 'text-secondary-400'
              }`}>
                {vital.trendData?.change}
              </span>
              <span className="text-secondary-400 ml-1">vs last month</span>
            </div>
          </div>
        );
        })}
      </div>

      {/* Recent History */}
      <div className="bg-white dark:bg-secondary-900 rounded-2xl border border-secondary-200 dark:border-secondary-800 overflow-hidden">
        <div className="p-6 border-b border-secondary-200 dark:border-secondary-800 flex justify-between items-center">
          <h2 className="text-lg font-bold text-secondary-900 dark:text-white">Recent History</h2>
          <Button variant="ghost" size="sm">View All <ArrowRight className="w-4 h-4 ml-1" /></Button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-secondary-50 dark:bg-secondary-900/50 text-secondary-500 dark:text-secondary-400">
              <tr>
                <th className="px-6 py-4 font-semibold">Date</th>
                <th className="px-6 py-4 font-semibold">Heart Rate</th>
                <th className="px-6 py-4 font-semibold">Blood Pressure</th>
                <th className="px-6 py-4 font-semibold">Temperature</th>
                <th className="px-6 py-4 font-semibold">Weight</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-secondary-200 dark:divide-secondary-800">
              {filteredHistory.length === 0 ? (
                 <tr>
                     <td colSpan="5" className="px-6 py-4 text-center text-secondary-500">No history available</td>
                 </tr>
              ) : (
                filteredHistory.map((record, i) => (
                <tr key={i} className="hover:bg-secondary-50 dark:hover:bg-secondary-700 transition-colors">
                  <td className="px-6 py-4 text-secondary-900 dark:text-white">{record.date}</td>
                  <td className="px-6 py-4 text-secondary-600 dark:text-secondary-300">{record.heartRate}</td>
                  <td className="px-6 py-4 text-secondary-600 dark:text-secondary-300">{record.bp}</td>
                  <td className="px-6 py-4 text-secondary-600 dark:text-secondary-300">{record.temp}</td>
                  <td className="px-6 py-4 text-secondary-600 dark:text-secondary-300">{record.weight}</td>
                </tr>
              ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Vitals;
