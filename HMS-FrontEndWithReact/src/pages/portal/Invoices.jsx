import React, { useState, useEffect } from 'react';
import { CreditCard, Download, Filter, Search } from 'lucide-react';
import Button from '../../components/common/Button';
import api from '../../services/api';
import { API_ENDPOINTS } from '../../services/endpoints';
import { useAuth } from '../../context/AuthContext';
import { useClinic } from '../../context/ClinicContext';
import { useUI } from '../../context/UIContext';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const Invoices = () => {
  const { user } = useAuth();
  const { activeClinicId } = useClinic();
  const { addToast } = useUI();
  const [loading, setLoading] = useState(true);
  const [invoices, setInvoices] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterOpen, setFilterOpen] = useState(false);
  const [statusFilter, setStatusFilter] = useState('All');
  const [processingPayment, setProcessingPayment] = useState(null);

  const fetchInvoices = async () => {
    setLoading(true);
    try {
      const response = await api.get(API_ENDPOINTS.PATIENT.INVOICES, {
          params: {
              status: statusFilter !== 'All' ? statusFilter : undefined
          }
      });
      setInvoices(response.data.invoices || []);
    } catch (error) {
      console.error('Failed to fetch invoices', error);
      addToast('error', 'Failed to load invoices.');
    } finally {
      setLoading(false);
    }
  };

  const handlePay = async (invoiceId, gateway) => {
    setProcessingPayment(invoiceId);
    try {
        const response = await api.post(API_ENDPOINTS.PATIENT.PAY_INVOICE(invoiceId), {
            gateway: gateway
        });

        if (response.data.payment_url) {
            window.location.href = response.data.payment_url;
        } else {
            addToast('error', 'Failed to initiate payment');
        }
    } catch (error) {
        console.error('Payment error', error);
        addToast('error', error.response?.data?.message || 'Payment initialization failed');
    } finally {
        setProcessingPayment(null);
    }
  };

  useEffect(() => {
    if (user && activeClinicId) {
        fetchInvoices();
    }
  }, [user, activeClinicId, statusFilter]);

  const getStatusColor = (status) => {
    switch (status) {
      case 'Paid': return 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400';
      case 'Partial': return 'bg-amber-100 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400';
      case 'Unpaid': return 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400';
      default: return 'bg-secondary-100 text-secondary-700 dark:bg-secondary-800 dark:text-secondary-400';
    }
  };

  // Client-side search since backend search wasn't explicitly added for invoices yet,
  // or we can rely on standard filtering. Let's do client side filtering for simple search.
  const filteredInvoices = invoices.filter(inv =>
    inv.invoice_number.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">Invoices</h1>
          <p className="text-secondary-500 dark:text-secondary-400">View and download your billing invoices</p>
        </div>
        <div className="flex gap-2">
          <Button variant={filterOpen ? 'primary' : 'outline'} size="sm" onClick={() => setFilterOpen(!filterOpen)}>
            <Filter className="w-4 h-4 mr-2" />
            Filter
          </Button>
        </div>
      </div>

      {/* Search and Filter */}
      <div className="space-y-4">
        <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-secondary-400" />
            <input
            type="text"
            placeholder="Search by invoice number..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full pl-10 pr-4 py-3 bg-white dark:bg-secondary-900 border border-secondary-200 dark:border-secondary-800 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-secondary-900 dark:text-white placeholder:text-secondary-400"
            />
        </div>

        {filterOpen && (
            <div className="flex flex-wrap gap-2 p-4 bg-white dark:bg-secondary-900 border border-secondary-200 dark:border-secondary-800 rounded-xl animate-in slide-in-from-top-2">
                {['All', 'Paid', 'Unpaid', 'Partial'].map((status) => (
                    <button
                        key={status}
                        onClick={() => setStatusFilter(status)}
                        className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                            statusFilter === status
                                ? 'bg-primary-500 text-white'
                                : 'bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 hover:bg-secondary-200 dark:hover:bg-secondary-700'
                        }`}
                    >
                        {status}
                    </button>
                ))}
            </div>
        )}
      </div>

      {/* Results List */}
      {loading ? (
        <div className="flex justify-center items-center h-64">
          <MedicalLoader />
        </div>
      ) : filteredInvoices.length === 0 ? (
        <div className="text-center py-12 bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-800">
          <CreditCard className="w-12 h-12 text-secondary-300 mx-auto mb-3" />
          <h3 className="text-lg font-medium text-secondary-900 dark:text-white">No Invoices Found</h3>
          <p className="text-secondary-500 dark:text-secondary-400 mt-1">You don't have any invoices yet.</p>
        </div>
      ) : (
      <div className="grid gap-4">
        {filteredInvoices.map((invoice) => (
          <div key={invoice.id} className="bg-white dark:bg-secondary-900 rounded-xl p-6 border border-secondary-200 dark:border-secondary-800 hover:border-primary-500/50 transition-colors group">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="flex items-start gap-4">
                <div className="p-3 rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform">
                  <CreditCard className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="font-bold text-secondary-900 dark:text-white text-lg">#{invoice.invoice_number}</h3>
                  <div className="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                    <span>{invoice.date}</span>
                    <span className="hidden sm:inline">•</span>
                    <span>{invoice.items_count} items</span>
                  </div>
                  <div className="mt-2 text-lg font-bold text-primary-600 dark:text-primary-400">
                      {invoice.status !== 'Paid' && invoice.due_amount < invoice.amount ? (
                          <div className="flex flex-col">
                              <span className="text-sm text-secondary-500 font-normal">Total: ${Number(invoice.amount).toFixed(2)}</span>
                              <span>Due: ${Number(invoice.due_amount).toFixed(2)}</span>
                          </div>
                      ) : (
                          <span>${Number(invoice.amount).toFixed(2)}</span>
                      )}
                  </div>
                </div>
              </div>

              <div className="flex items-center gap-4 self-end md:self-auto">
                <span className={`px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ${getStatusColor(invoice.status)}`}>
                  {invoice.status}
                </span>
                <div className="flex gap-2">
                  {invoice.status !== 'Paid' && (
                      <div className="flex gap-2">
                          <Button
                              size="sm"
                              onClick={() => handlePay(invoice.id, 'stripe')}
                              disabled={processingPayment === invoice.id}
                              className="bg-indigo-600 hover:bg-indigo-700 text-white border-none"
                              title="Pay with Stripe"
                          >
                              <CreditCard className="w-4 h-4 mr-2" />
                              Stripe
                          </Button>
                          <Button
                              size="sm"
                              onClick={() => handlePay(invoice.id, 'sslcommerz')}
                              disabled={processingPayment === invoice.id}
                              className="bg-orange-600 hover:bg-orange-700 text-white border-none"
                              title="Pay with SSLCommerz"
                          >
                              <CreditCard className="w-4 h-4 mr-2" />
                              SSLCommerz
                          </Button>
                      </div>
                  )}
                  {invoice.download_url && (
                      <a
                        href={invoice.download_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex items-center gap-2 px-4 py-2 bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                        title="Download Invoice"
                      >
                        <Download className="w-4 h-4" />
                        <span className="text-sm font-medium">Download</span>
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

export default Invoices;
