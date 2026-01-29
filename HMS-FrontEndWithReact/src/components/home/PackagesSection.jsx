import React from 'react';
import { Check, Shield, Zap, Heart } from 'lucide-react';
import Button from '../common/Button';
import { Link } from 'react-router-dom';

const packages = [
  {
    name: "Basic Checkup",
    price: "2,500 BDT",
    discount: "Save 15%",
    icon: Shield,
    color: "text-primary-400",
    bg: "bg-primary-500/10",
    features: [
      "General Physician Consultation",
      "CBC (Complete Blood Count)",
      "Blood Sugar (Fasting)",
      "Lipid Profile",
      "ECG",
      "BP & BMI Check"
    ],
    recommended: false
  },
  {
    name: "Comprehensive Care",
    price: "5,500 BDT",
    discount: "Save 25%",
    icon: Heart,
    color: "text-rose-400",
    bg: "bg-rose-500/10",
    features: [
      "All Basic Checkup Features",
      "Cardiology Consultation",
      "Liver Function Test",
      "Kidney Function Test",
      "Chest X-Ray",
      "Dietician Consultation"
    ],
    recommended: true
  },
  {
    name: "Executive Wellness",
    price: "8,500 BDT",
    discount: "Save 30%",
    icon: Zap,
    color: "text-amber-400",
    bg: "bg-amber-500/10",
    features: [
      "All Comprehensive Features",
      "Whole Body Checkup",
      "Vitamin D & B12 Screening",
      "Thyroid Profile",
      "Ultrasound (Whole Abdomen)",
      "Priority Reporting"
    ],
    recommended: false
  }
];

const PackagesSection = () => {
  return (
    <section className="py-20 bg-secondary-50 dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-800 transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up">
          <span className="text-primary-600 dark:text-primary-400 font-bold tracking-wider uppercase text-sm">Health Packages</span>
          <h2 className="text-3xl md:text-4xl font-bold text-secondary-900 dark:text-white mt-2 mb-4">
            Affordable Care Bundles
          </h2>
          <p className="text-secondary-600 dark:text-secondary-400 text-lg">
            Preventive health checkups designed for every age and lifestyle. Save more with our discounted bundles.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          {packages.map((pkg, index) => (
            <div
              key={index}
              className={`relative rounded-2xl border transition-all duration-300 flex flex-col group ${
                pkg.recommended
                  ? 'bg-white dark:bg-secondary-950 border-primary-500 scale-105 z-10'
                  : 'bg-white dark:bg-secondary-800 border-secondary-200 dark:border-secondary-800 hover:border-primary-500/50 hover:bg-secondary-50 dark:hover:bg-secondary-800'
              }`}
            >
              {pkg.recommended && (
                <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary-600 text-white px-4 py-1 rounded-full text-sm font-bold tracking-wide">
                  Recommended
                </div>
              )}

              <div className="p-8 border-b border-secondary-100 dark:border-secondary-800">
                <div className={`w-14 h-14 ${pkg.bg} ${pkg.color} rounded-xl flex items-center justify-center mb-6 ring-1 ring-inset ring-black/5 dark:ring-secondary-700`}>
                  <pkg.icon className="w-7 h-7" />
                </div>
                <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-2">{pkg.name}</h3>
                <div className="flex items-baseline gap-2 mb-2">
                  <span className="text-3xl font-bold text-secondary-900 dark:text-white">{pkg.price}</span>
                </div>
                <span className="inline-block bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-bold px-2 py-1 rounded border border-green-500/20">
                  {pkg.discount}
                </span>
              </div>

              <div className="p-8 flex-grow">
                <ul className="space-y-4">
                  {pkg.features.map((feature, idx) => (
                    <li key={idx} className="flex items-start gap-3 text-secondary-400 text-sm group-hover:text-secondary-300 transition-colors">
                      <Check className="w-5 h-5 text-primary-500 shrink-0" />
                      <span>{feature}</span>
                    </li>
                  ))}
                </ul>
              </div>

              <div className="p-8 pt-0">
                <Link to="/portal/login">
                  <Button
                    variant={pkg.recommended ? 'primary' : 'outline'}
                    className={`w-full justify-center ${!pkg.recommended && 'border-secondary-200 dark:border-secondary-800 text-secondary-600 dark:text-secondary-300 hover:border-primary-500 hover:text-white hover:bg-primary-600 dark:hover:bg-secondary-800'}`}
                  >
                    Book Now
                  </Button>
                </Link>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default PackagesSection;
