import React, { useState, useEffect } from 'react';
import { Bed, User, Calendar, FileText, Activity, Clock, CreditCard, LogOut, CheckCircle, X } from 'lucide-react';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import { useUI } from '../../context/UIContext';
import MedicalLoader from '../../components/loaders/MedicalLoader';
import { useNavigate } from 'react-router-dom';

const MyStay = () => {
  const { user } = useAuth();
  const { activeClinicId } = useClinic();
  const { addToast } = useUI();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [admission, setAdmission] = useState(null);
  const [rounds, setRounds] = useState([]);
  const [billing, setBilling] = useState(null);
  const [showDepositModal, setShowDepositModal] = useState(false);
  const [depositAmount, setDepositAmount] = useState('');
  const [selectedGateway, setSelectedGateway] = useState('stripe');
  const [processingPayment, setProcessingPayment] = useState(false);

  const fetchData = async () => {
    setLoading(true);
    try {
      const [admissionRes, roundsRes, billingRes] = await Promise.all([
        api.get(API_ENDPOINTS.PATIENT.IPD_ADMISSION),
        api.get(API_ENDPOINTS.PATIENT.IPD_ROUNDS),
        api.get(API_ENDPOINTS.PATIENT.IPD_BILLING)
      ]);

      setAdmission(admissionRes.data.admission);
      setRounds(roundsRes.data.rounds || []);
      setBilling(billingRes.data.billing);
    } catch (error) {
      console.error('Failed to fetch IPD data', error);
      // Don't toast error here as user might simply not be admitted
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (user && activeClinicId) {
        fetchData();
    }
  }, [user, activeClinicId]);

  const handlePay = () => {
      if (billing?.invoice_id) {
          // Navigate to invoices page for final bill
          navigate('/portal/invoices');
      } else {
          // Open deposit modal for running bill
          setDepositAmount(billing?.due_amount ? String(billing.due_amount) : '');
          setShowDepositModal(true);
      }
  };

  const handleDepositSubmit = async (e) => {
      e.preventDefault();
      if (!depositAmount || Number(depositAmount) <= 0) {
          addToast('Please enter a valid amount', 'error');
          return;
      }

      setProcessingPayment(true);
      try {
          const response = await api.post(API_ENDPOINTS.PATIENT.IPD_DEPOSIT, {
              amount: depositAmount,
              gateway: selectedGateway,
              redirect_url: window.location.href
          });

          if (response.data.payment_url) {
              window.location.href = response.data.payment_url;
          } else {
              addToast('Failed to initiate payment', 'error');
              setProcessingPayment(false);
          }
      } catch (error) {
          console.error('Deposit error:', error);
          addToast(error.response?.data?.message || 'Failed to initiate deposit', 'error');
          setProcessingPayment(false);
      }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <MedicalLoader />
      </div>
    );
  }

  if (!admission) {
    return (
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">My Stay</h1>
          <p className="text-secondary-500 dark:text-secondary-400">Current inpatient admission details</p>
        </div>
        <div className="text-center py-12 bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800">
          <Bed className="w-12 h-12 text-secondary-300 mx-auto mb-3" />
          <h3 className="text-lg font-medium text-secondary-900 dark:text-white">Not Currently Admitted</h3>
          <p className="text-secondary-500 dark:text-secondary-400 mt-1">You do not have an active inpatient admission record.</p>
        </div>
      </div>
    );
  }

  const isDischarged = admission.status === 'discharged';

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">My Stay</h1>
        <p className="text-secondary-500 dark:text-secondary-400">
            {isDischarged ? 'Past admission details' : 'Current inpatient admission details'}
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Admission Info Card */}
        <div className="lg:col-span-2 space-y-6">
          <div className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800 p-6">
            <div className="flex justify-between items-start mb-4">
                <h2 className="text-lg font-bold text-secondary-900 dark:text-white flex items-center gap-2">
                <Bed className="w-5 h-5 text-primary-600" />
                Admission Details
                </h2>
                <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider ${
                    isDischarged
                    ? 'bg-secondary-100 text-secondary-700 dark:bg-secondary-800 dark:text-secondary-300'
                    : 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400'
                }`}>
                    {admission.status}
                </span>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="space-y-1">
                <span className="text-sm text-secondary-500 dark:text-secondary-400">Admission Date</span>
                <div className="font-medium text-secondary-900 dark:text-white flex items-center gap-2">
                  <Calendar className="w-4 h-4 text-secondary-400" />
                  {admission.admission_date}
                </div>
              </div>

              <div className="space-y-1">
                <span className="text-sm text-secondary-500 dark:text-secondary-400">Attending Doctor</span>
                <div className="font-medium text-secondary-900 dark:text-white flex items-center gap-2">
                  <User className="w-4 h-4 text-secondary-400" />
                  {admission.doctor}
                </div>
              </div>

              {admission.discharge_date && (
                  <div className="space-y-1">
                    <span className="text-sm text-secondary-500 dark:text-secondary-400">Discharge Date</span>
                    <div className="font-medium text-secondary-900 dark:text-white flex items-center gap-2">
                      <LogOut className="w-4 h-4 text-secondary-400" />
                      {admission.discharge_date}
                    </div>
                  </div>
              )}

              <div className="md:col-span-2 space-y-1">
                 <span className="text-sm text-secondary-500 dark:text-secondary-400">Reason for Admission</span>
                 <div className="p-3 bg-secondary-50 dark:bg-secondary-800/50 rounded-lg text-secondary-700 dark:text-secondary-300">
                   {admission.reason}
                 </div>
              </div>
            </div>

            <div className="mt-6 pt-6 border-t border-secondary-200 dark:border-secondary-800">
              <h3 className="text-sm font-semibold text-secondary-900 dark:text-white mb-3">Location</h3>
              <div className="grid grid-cols-3 gap-4">
                <div className="p-4 bg-primary-50 dark:bg-primary-900/10 rounded-lg text-center border border-primary-100 dark:border-primary-900/20">
                  <div className="text-xs text-primary-600 dark:text-primary-400 uppercase font-bold tracking-wider mb-1">Ward</div>
                  <div className="font-bold text-secondary-900 dark:text-white">{admission.ward}</div>
                </div>
                <div className="p-4 bg-primary-50 dark:bg-primary-900/10 rounded-lg text-center border border-primary-100 dark:border-primary-900/20">
                  <div className="text-xs text-primary-600 dark:text-primary-400 uppercase font-bold tracking-wider mb-1">Room</div>
                  <div className="font-bold text-secondary-900 dark:text-white">{admission.room}</div>
                </div>
                <div className="p-4 bg-primary-50 dark:bg-primary-900/10 rounded-lg text-center border border-primary-100 dark:border-primary-900/20">
                  <div className="text-xs text-primary-600 dark:text-primary-400 uppercase font-bold tracking-wider mb-1">Bed</div>
                  <div className="font-bold text-secondary-900 dark:text-white">{admission.bed}</div>
                </div>
              </div>
            </div>
          </div>

          {/* Billing Card */}
          {billing && (
              <div className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800 p-6">
                  <h2 className="text-lg font-bold text-secondary-900 dark:text-white mb-4 flex items-center gap-2">
                      <CreditCard className="w-5 h-5 text-primary-600" />
                      {billing.is_final ? 'Final Invoice Summary' : 'Running Bill Estimate'}
                  </h2>
                  <div className="space-y-3">
                      <div className="flex justify-between text-sm">
                          <span className="text-secondary-500 dark:text-secondary-400">Total Services</span>
                          <span className="font-medium text-secondary-900 dark:text-white">{billing.currency} {billing.total_services}</span>
                      </div>
                      <div className="flex justify-between text-sm">
                          <span className="text-secondary-500 dark:text-secondary-400">Room Rent</span>
                          <span className="font-medium text-secondary-900 dark:text-white">{billing.currency} {billing.total_room_rent}</span>
                      </div>
                      {Number(billing.total_admission_fees) > 0 && (
                          <div className="flex justify-between text-sm">
                              <span className="text-secondary-500 dark:text-secondary-400">Admission Fees</span>
                              <span className="font-medium text-secondary-900 dark:text-white">{billing.currency} {billing.total_admission_fees}</span>
                          </div>
                      )}
                      <div className="pt-2 border-t border-secondary-100 dark:border-secondary-800 flex justify-between font-bold">
                          <span className="text-secondary-700 dark:text-secondary-300">
                              {billing.is_final ? 'Total Invoice Amount' : 'Total Estimated'}
                          </span>
                          <span className="text-secondary-900 dark:text-white">{billing.currency} {billing.total_estimated_bill}</span>
                      </div>
                      <div className="flex justify-between text-sm text-green-600 dark:text-green-400">
                          <span>{billing.is_final ? 'Total Paid' : 'Deposited'}</span>
                          <span>- {billing.currency} {billing.paid_amount}</span>
                      </div>
                      <div className="pt-2 border-t border-secondary-100 dark:border-secondary-800 flex justify-between font-bold text-lg">
                          <span className="text-primary-600 dark:text-primary-400">Due Amount</span>
                          <span className="text-primary-600 dark:text-primary-400">{billing.currency} {billing.due_amount}</span>
                      </div>

                      {/* Pay Button */}
                      {Number(billing.due_amount) > 0 && (
                          <div className="pt-4">
                              <button
                                  onClick={handlePay}
                                  className="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2 shadow-lg shadow-primary-600/20"
                              >
                                  <CreditCard className="w-4 h-4" />
                                  {billing.is_final ? 'Pay Invoice Now' : 'Add Deposit'}
                              </button>
                              {!billing.is_final && (
                                  <p className="text-xs text-center text-secondary-500 mt-2">
                                      Note: Online deposits are currently unavailable. Please visit the counter.
                                  </p>
                              )}
                          </div>
                      )}

                      {Number(billing.due_amount) <= 0 && billing.is_final && (
                          <div className="pt-4 flex items-center justify-center gap-2 text-green-600 font-bold bg-green-50 dark:bg-green-900/10 p-3 rounded-lg">
                              <CheckCircle className="w-5 h-5" />
                              Fully Paid
                          </div>
                      )}
                  </div>
              </div>
          )}
        </div>

        {/* Rounds Timeline */}
        <div className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800 p-6 h-fit">
          <h2 className="text-lg font-bold text-secondary-900 dark:text-white mb-4 flex items-center gap-2">
            <Activity className="w-5 h-5 text-primary-600" />
            Doctor Rounds
          </h2>

          {rounds.length === 0 ? (
            <div className="text-center py-8 text-secondary-500 dark:text-secondary-400">
              No rounds recorded yet.
            </div>
          ) : (
            <div className="space-y-6 relative before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-secondary-200 dark:before:bg-secondary-800">
              {rounds.map((round) => (
                <div key={round.id} className="relative pl-8">
                  <div className="absolute left-0 top-1.5 w-4.5 h-4.5 rounded-full bg-primary-100 border-2 border-primary-600 dark:border-primary-400 z-10"></div>
                  <div className="space-y-1">
                    <div className="flex items-center gap-2 text-xs text-secondary-500 dark:text-secondary-400 font-medium uppercase tracking-wider">
                      <Clock className="w-3 h-3" />
                      {round.date}
                    </div>
                    <div className="font-semibold text-secondary-900 dark:text-white">
                      Dr. {round.doctor}
                    </div>
                    <p className="text-sm text-secondary-600 dark:text-secondary-300 bg-secondary-50 dark:bg-secondary-800/50 p-3 rounded-lg mt-2">
                      {round.notes}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Deposit Modal */}
      {showDepositModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
          <div className="bg-white dark:bg-secondary-900 rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
            <div className="flex justify-between items-center p-4 border-b border-secondary-200 dark:border-secondary-800">
              <h3 className="text-lg font-bold text-secondary-900 dark:text-white">Make a Deposit</h3>
              <button
                onClick={() => setShowDepositModal(false)}
                className="p-1 hover:bg-secondary-100 dark:hover:bg-secondary-800 rounded-full text-secondary-500 transition-colors"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleDepositSubmit} className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">
                  Amount ({billing?.currency})
                </label>
                <input
                  type="number"
                  min="1"
                  step="0.01"
                  value={depositAmount}
                  onChange={(e) => setDepositAmount(e.target.value)}
                  className="w-full px-4 py-2 rounded-lg border border-secondary-300 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-secondary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all"
                  placeholder="Enter amount"
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">
                  Payment Method
                </label>
                <div className="grid grid-cols-2 gap-3">
                  <button
                    type="button"
                    onClick={() => setSelectedGateway('stripe')}
                    className={`flex flex-col items-center justify-center p-3 rounded-lg border-2 transition-all ${
                      selectedGateway === 'stripe'
                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400'
                        : 'border-secondary-200 dark:border-secondary-700 hover:border-secondary-300 dark:hover:border-secondary-600'
                    }`}
                  >
                    <span className="font-bold">Stripe</span>
                    <span className="text-xs text-secondary-500">Credit/Debit Card</span>
                  </button>
                  <button
                    type="button"
                    onClick={() => setSelectedGateway('sslcommerz')}
                    className={`flex flex-col items-center justify-center p-3 rounded-lg border-2 transition-all ${
                      selectedGateway === 'sslcommerz'
                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400'
                        : 'border-secondary-200 dark:border-secondary-700 hover:border-secondary-300 dark:hover:border-secondary-600'
                    }`}
                  >
                    <span className="font-bold">SSLCommerz</span>
                    <span className="text-xs text-secondary-500">Mobile Banking</span>
                  </button>
                </div>
              </div>

              <div className="pt-2">
                <button
                  type="submit"
                  disabled={processingPayment}
                  className="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                >
                  {processingPayment ? (
                    <>
                      <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                      Processing...
                    </>
                  ) : (
                    <>
                      <CreditCard className="w-4 h-4" />
                      Pay Now
                    </>
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default MyStay;
