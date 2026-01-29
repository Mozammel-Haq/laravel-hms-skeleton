import React, { useState } from 'react';
import { Plus, Minus } from 'lucide-react';
import { Link } from 'react-router-dom';

const faqs = [
  {
    question: "How do I book an appointment?",
    answer: "You can book an appointment easily through our Patient Portal. Simply log in, choose your preferred doctor and time slot, and confirm your booking. Alternatively, you can call our reception desk."
  },
  {
    question: "What insurance plans do you accept?",
    answer: "We accept most major insurance plans. Please contact our billing department or check our insurance partners list on the patient portal for specific details regarding your coverage."
  },
  {
    question: "Do you offer emergency services?",
    answer: "Yes, our Emergency Department is open 24/7, 365 days a year. We are equipped to handle all types of medical emergencies with a dedicated team of trauma specialists."
  },
  {
    question: "How can I access my medical records?",
    answer: "Your medical records are securely stored and accessible via the Patient Portal. You can view your history, prescriptions, and lab results anytime after logging in."
  },
  {
    question: "What are your visiting hours?",
    answer: "General visiting hours are from 10:00 AM to 8:00 PM daily. However, hours may vary for specific units like ICU. Please check with the specific department for their current policy."
  }
];

const FAQSection = () => {
  const [openIndex, setOpenIndex] = useState(0);

  return (
    <section id="faq" className="py-20 bg-gradient-to-br from-secondary-50 via-white to-secondary-100/50 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950 border-b border-secondary-200 dark:border-secondary-800 transition-colors duration-300">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col lg:flex-row gap-12 lg:gap-24">

          {/* Header */}
          <div className="lg:w-1/3">
            <span className="text-primary-600 dark:text-primary-400 font-bold tracking-wider uppercase text-sm">Common Questions</span>
            <h2 className="text-3xl md:text-4xl font-bold text-secondary-900 dark:text-white mt-2 mb-6">
              Frequently Asked Questions
            </h2>
            <p className="text-secondary-600 dark:text-secondary-400 text-lg mb-8">
              Find answers to the most common questions about our services, appointments, and facilities. Can't find what you're looking for?
            </p>
            <Link to="/contact" className="inline-flex items-center text-primary-600 dark:text-primary-400 font-bold hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
              Contact Support <span className="ml-2">→</span>
            </Link>
          </div>

          {/* Accordion */}
          <div className="lg:w-2/3">
            <div className="space-y-4">
              {faqs.map((faq, index) => (
                <div
                  key={index}
                  className={`border rounded-xl transition-all duration-300 ${
                    openIndex === index
                      ? 'border-primary-500/50 bg-secondary-50 dark:bg-secondary-800'
                      : 'border-secondary-200 dark:border-secondary-800 bg-white dark:bg-secondary-900 hover:border-primary-500/30'
                  }`}
                >
                  <button
                    onClick={() => setOpenIndex(openIndex === index ? -1 : index)}
                    className="w-full flex items-center justify-between p-6 text-left focus:outline-none"
                  >
                    <span className={`text-lg font-bold ${
                      openIndex === index ? 'text-primary-600 dark:text-primary-400' : 'text-secondary-900 dark:text-white'
                    }`}>
                      {faq.question}
                    </span>
                    <span className={`flex-shrink-0 ml-4 w-8 h-8 rounded-full flex items-center justify-center border transition-colors ${
                      openIndex === index
                        ? 'bg-primary-600 border-primary-600 text-white'
                        : 'bg-transparent border-secondary-300 dark:border-secondary-700 text-secondary-400'
                    }`}>
                      {openIndex === index ? <Minus className="w-4 h-4" /> : <Plus className="w-4 h-4" />}
                    </span>
                  </button>
                  <div
                    className={`overflow-hidden transition-all duration-300 ease-in-out ${
                      openIndex === index ? 'max-h-48 opacity-100' : 'max-h-0 opacity-0'
                    }`}
                  >
                    <div className="p-6 pt-0 text-secondary-600 dark:text-secondary-400 leading-relaxed">
                      {faq.answer}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

        </div>
      </div>
    </section>
  );
};

export default FAQSection;
