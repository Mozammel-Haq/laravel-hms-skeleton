import React from 'react';
import { ShieldCheck, CreditCard, FileText } from 'lucide-react';

const Insurance = () => {
  return (
    <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 py-20 transition-colors duration-300">
      <div className="container mx-auto px-4 max-w-5xl">
        <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mb-8 text-center">Insurance & Billing</h1>

        <div className="grid md:grid-cols-2 gap-8 mb-12">
          <div className="bg-white dark:bg-secondary-900 p-8 rounded-2xl border border-secondary-100 dark:border-secondary-800">
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6 flex items-center gap-3">
              <ShieldCheck className="w-8 h-8 text-primary-600" />
              Accepted Insurance Plans
            </h2>
            <ul className="space-y-4 text-secondary-600 dark:text-secondary-300">
              {['Blue Cross Blue Shield', 'Aetna', 'Cigna', 'UnitedHealthcare', 'Medicare / Medicaid', 'Humana'].map((plan) => (
                <li key={plan} className="flex items-center gap-2">
                  <span className="w-2 h-2 rounded-full bg-primary-500"></span>
                  {plan}
                </li>
              ))}
            </ul>
          </div>

          <div className="bg-white dark:bg-secondary-900 p-8 rounded-2xl border border-secondary-100 dark:border-secondary-800">
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6 flex items-center gap-3">
              <CreditCard className="w-8 h-8 text-primary-600" />
              Payment Options
            </h2>
            <p className="text-secondary-600 dark:text-secondary-300 mb-6">
              We accept various payment methods for co-pays and non-covered services:
            </p>
            <ul className="space-y-4 text-secondary-600 dark:text-secondary-300 mb-6">
              <li>• Cash / Check</li>
              <li>• Major Credit Cards (Visa, MasterCard, Amex)</li>
              <li>• Online Payment Portal</li>
            </ul>
            <p className="text-sm text-secondary-500 dark:text-secondary-400">
              For billing inquiries, please call our billing department at +880 1234 567 890.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Insurance;
