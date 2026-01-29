import React from 'react';
import { motion } from 'framer-motion';
import { Building, Users, Award, Heart } from 'lucide-react';

const About = () => {
  return (
    <div className="min-h-screen transition-colors duration-300">
      {/* Hero Section */}
      <section className="relative py-20 bg-primary-600 dark:bg-secondary-900 overflow-hidden transition-colors duration-300">
        <div className="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-800 dark:from-secondary-900 dark:to-primary-900/50 opacity-90"></div>
        <div className="container mx-auto px-4 relative z-10 text-center text-white">
          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="text-4xl md:text-5xl font-bold mb-6"
          >
            About CityCare Hospital
          </motion.h1>
          <p className="text-xl max-w-2xl mx-auto text-primary-50 dark:text-secondary-300">
            Leading the way in medical excellence with compassion and innovation since 2005.
          </p>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="py-20 bg-white dark:bg-secondary-950 transition-colors duration-300">
        <div className="container mx-auto px-4">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <motion.div
              initial={{ opacity: 0, x: -20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
            >
              <h2 className="text-3xl font-bold text-secondary-900 dark:text-white mb-6">Our Mission</h2>
              <p className="text-secondary-600 dark:text-secondary-400 text-lg leading-relaxed mb-8">
                To provide accessible, high-quality healthcare services that meet the evolving needs of our community. We are dedicated to patient-centered care, clinical excellence, and the continuous improvement of human health.
              </p>
              <div className="grid grid-cols-2 gap-6">
                <div className="bg-secondary-50 dark:bg-secondary-900 p-6 rounded-xl border border-secondary-100 dark:border-secondary-800">
                  <Heart className="w-8 h-8 text-primary-600 dark:text-primary-400 mb-4" />
                  <h3 className="font-bold text-secondary-900 dark:text-white mb-2">Compassion</h3>
                  <p className="text-sm text-secondary-500 dark:text-secondary-400">Treating every patient with dignity and kindness.</p>
                </div>
                <div className="bg-secondary-50 dark:bg-secondary-900 p-6 rounded-xl border border-secondary-100 dark:border-secondary-800">
                  <Award className="w-8 h-8 text-primary-600 dark:text-primary-400 mb-4" />
                  <h3 className="font-bold text-secondary-900 dark:text-white mb-2">Excellence</h3>
                  <p className="text-sm text-secondary-500 dark:text-secondary-400">Striving for the highest standards in all we do.</p>
                </div>
              </div>
            </motion.div>
            <motion.div
              initial={{ opacity: 0, x: 20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              className="relative"
            >
              <div className="aspect-video bg-secondary-100 dark:bg-secondary-900 rounded-2xl overflow-hidden border border-secondary-200 dark:border-secondary-800">
                 {/* Placeholder for an image */}
                 <div className="w-full h-full flex items-center justify-center text-secondary-400 dark:text-secondary-500">
                    <Building className="w-20 h-20 opacity-20" />
                 </div>
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* Stats */}
      <section className="py-16 bg-secondary-50 dark:bg-secondary-900 border-y border-secondary-200 dark:border-secondary-800 transition-colors duration-300">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            {[
              { label: 'Years of Service', value: '18+' },
              { label: 'Certified Doctors', value: '150+' },
              { label: 'Happy Patients', value: '50k+' },
              { label: 'Hospital Beds', value: '300+' },
            ].map((stat, i) => (
              <div key={i}>
                <div className="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">{stat.value}</div>
                <div className="text-secondary-500 dark:text-secondary-400 font-medium">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default About;
