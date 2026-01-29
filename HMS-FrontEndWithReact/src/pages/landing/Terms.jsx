import React from 'react';
import { FileText, CheckCircle, AlertCircle } from 'lucide-react';

const Terms = () => {
  return (
    <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 py-20 transition-colors duration-300">
      <div className="container mx-auto px-4 max-w-4xl">
        <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mb-8">Terms of Service</h1>

        <div className="bg-white dark:bg-secondary-900 p-8 rounded-2xl border border-secondary-100 dark:border-secondary-800 space-y-8 text-secondary-600 dark:text-secondary-300">
          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Agreement to Terms</h2>
            <p className="leading-relaxed">
              By accessing our website and using our services, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.
            </p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Use License</h2>
            <p className="leading-relaxed mb-4">
              Permission is granted to temporarily download one copy of the materials (information or software) on CityCare Hospital's website for personal, non-commercial transitory viewing only.
            </p>
            <ul className="list-disc pl-5 space-y-2">
              <li>You may not modify or copy the materials.</li>
              <li>You may not use the materials for any commercial purpose.</li>
              <li>You may not attempt to decompile or reverse engineer any software contained on the website.</li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Disclaimer</h2>
            <p className="leading-relaxed">
              The materials on CityCare Hospital's website are provided on an 'as is' basis. While we strive to provide accurate medical information, content on this site is for informational purposes only and is not a substitute for professional medical advice, diagnosis, or treatment.
            </p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Limitations</h2>
            <p className="leading-relaxed">
              In no event shall CityCare Hospital or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on CityCare Hospital's website.
            </p>
          </section>
        </div>
      </div>
    </div>
  );
};

export default Terms;
