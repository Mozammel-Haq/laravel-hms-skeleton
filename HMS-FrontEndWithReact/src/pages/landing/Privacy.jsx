import React from 'react';
import { Shield, Lock, FileText } from 'lucide-react';

const Privacy = () => {
  return (
    <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 py-20 transition-colors duration-300">
      <div className="container mx-auto px-4 max-w-4xl">
        <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mb-8">Privacy Policy</h1>

        <div className="bg-white dark:bg-secondary-900 p-8 rounded-2xl border border-secondary-100 dark:border-secondary-800 space-y-8 text-secondary-600 dark:text-secondary-300">
          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4 flex items-center gap-2">
              <Shield className="w-6 h-6 text-primary-600" />
              Data Protection
            </h2>
            <p className="leading-relaxed">
              At CityCare Hospital, we take your privacy seriously. This policy outlines how we collect, use, and protect your personal and medical information in compliance with HIPAA and local regulations.
            </p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Information We Collect</h2>
            <ul className="list-disc pl-5 space-y-2">
              <li>Personal identification information (Name, email address, phone number, etc.)</li>
              <li>Medical history and health records</li>
              <li>Insurance and payment information</li>
              <li>Appointment scheduling details</li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">How We Use Your Information</h2>
            <p className="leading-relaxed">
              We use your information to provide medical services, process payments, communicate with you about appointments, and improve our healthcare delivery. We do not sell your personal data to third parties.
            </p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Security Measures</h2>
            <p className="leading-relaxed">
              We employ industry-standard encryption and security protocols to safeguard your data against unauthorized access, alteration, disclosure, or destruction.
            </p>
          </section>

          <div className="pt-8 border-t border-secondary-200 dark:border-secondary-800 text-sm text-secondary-500">
            Last updated: January 2026
          </div>
        </div>
      </div>
    </div>
  );
};

export default Privacy;
