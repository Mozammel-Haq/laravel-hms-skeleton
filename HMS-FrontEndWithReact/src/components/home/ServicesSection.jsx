import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import {
  HeartPulse, Brain, Baby, Stethoscope, Eye, Smile, Bone, ArrowRight,
  Microscope, Pill, Bed, Syringe, Ambulance, UserPlus
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

const services = [
  { id: 1, name: 'Cardiology', icon: HeartPulse, desc: 'Expert heart care and diagnostics.', color: 'text-rose-600 dark:text-rose-400', bg: 'bg-rose-100 dark:bg-rose-900/30' },
  { id: 2, name: 'Neurology', icon: Brain, desc: 'Advanced brain and nerve treatments.', color: 'text-indigo-600 dark:text-indigo-400', bg: 'bg-indigo-100 dark:bg-indigo-900/30' },
  { id: 3, name: 'Pediatrics', icon: Baby, desc: 'Compassionate care for your children.', color: 'text-sky-600 dark:text-sky-400', bg: 'bg-sky-100 dark:bg-sky-900/30' },
  { id: 4, name: 'General Medicine', icon: Stethoscope, desc: 'Primary care for all ages.', color: 'text-primary-600 dark:text-primary-400', bg: 'bg-primary-100 dark:bg-primary-900/30' },
  { id: 5, name: 'Ophthalmology', icon: Eye, desc: 'Comprehensive eye care services.', color: 'text-amber-600 dark:text-amber-400', bg: 'bg-amber-100 dark:bg-amber-900/30' },
  { id: 6, name: 'Laboratory', icon: Microscope, desc: '24/7 Advanced Pathology Labs.', color: 'text-emerald-600 dark:text-emerald-400', bg: 'bg-emerald-100 dark:bg-emerald-900/30' },
  { id: 7, name: 'Dental Care', icon: Smile, desc: 'Complete oral health solutions.', color: 'text-teal-600 dark:text-teal-400', bg: 'bg-teal-100 dark:bg-teal-900/30' },
  { id: 8, name: 'Orthopedics', icon: Bone, desc: 'Bone and joint specialists.', color: 'text-orange-600 dark:text-orange-400', bg: 'bg-orange-100 dark:bg-orange-900/30' },
  { id: 9, name: 'Pharmacy', icon: Pill, desc: 'In-house 24/7 Pharmacy.', color: 'text-purple-600 dark:text-purple-400', bg: 'bg-purple-100 dark:bg-purple-900/30' },
  { id: 10, name: 'IPD Services', icon: Bed, desc: 'Comfortable In-Patient admissions.', color: 'text-blue-600 dark:text-blue-400', bg: 'bg-blue-100 dark:bg-blue-900/30' },
  { id: 11, name: 'Vaccination', icon: Syringe, desc: 'Immunization for all age groups.', color: 'text-pink-600 dark:text-pink-400', bg: 'bg-pink-100 dark:bg-pink-900/30' },
  { id: 12, name: 'Emergency', icon: Ambulance, desc: '24/7 Trauma & Emergency Care.', color: 'text-red-600 dark:text-red-400', bg: 'bg-red-100 dark:bg-red-900/30' },
];

const ServicesSection = () => {
  const [activeService, setActiveService] = useState(services[0]);

  return (
    <section className="py-20 bg-gradient-to-br from-secondary-50 via-white to-secondary-100/50 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950 transition-colors duration-300 overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-3xl mx-auto mb-16">
          <span className="text-primary-600 dark:text-primary-400 font-bold tracking-wider uppercase text-sm">Comprehensive Care</span>
          <h2 className="text-3xl md:text-4xl font-bold text-secondary-900 dark:text-white mt-2 mb-4">Our Medical Services</h2>
          <p className="text-secondary-600 dark:text-secondary-400 text-lg">
            From specialized departments to essential facilities like Pharmacy and Lab, we provide complete healthcare solutions.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Interactive Circular UI */}
          <div className="relative w-full aspect-square max-w-[600px] mx-auto order-2 lg:order-1">
            {/* Background Glow */}
            <div className="absolute inset-0 rounded-full bg-gradient-to-br from-primary-100/30 via-secondary-50/40 to-primary-50/30 dark:from-primary-900/20 dark:via-secondary-900/30 dark:to-primary-900/20 blur-3xl" />
            
            {/* Main Container */}
            <div className="absolute inset-0">
              {/* Stationary Outer Circle - Reference for icon positions */}
              <svg className="absolute inset-0 w-full h-full" style={{ transform: 'rotate(-90deg)' }}>
                <circle
                  cx="50%"
                  cy="50%"
                  r="45%"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeDasharray="8 8"
                  className="text-secondary-200 dark:text-secondary-700"
                />
              </svg>

              {/* Animated Decorative Circles */}
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[88%] h-[88%] rounded-full border border-dashed border-primary-200/40 dark:border-primary-800/40 animate-[spin_120s_linear_infinite]" />
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70%] h-[70%] rounded-full border border-secondary-100/60 dark:border-secondary-800/60" />
              
              {/* Inner Glow */}
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[55%] h-[55%] rounded-full bg-gradient-to-br from-primary-100/40 to-secondary-100/40 dark:from-primary-900/30 dark:to-secondary-900/30 blur-2xl" />

              {/* Central Info Card */}
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20 w-[48%] h-[48%] min-w-[200px] min-h-[200px] rounded-full bg-white dark:bg-secondary-800 shadow-2xl flex flex-col items-center justify-center p-6 text-center border-4 border-secondary-100 dark:border-secondary-700 transition-all duration-300">
                <AnimatePresence mode="wait">
                  <motion.div
                    key={activeService.id}
                    initial={{ opacity: 0, scale: 0.8, rotateY: -90 }}
                    animate={{ opacity: 1, scale: 1, rotateY: 0 }}
                    exit={{ opacity: 0, scale: 0.8, rotateY: 90 }}
                    transition={{ duration: 0.3, type: "spring" }}
                    className="flex flex-col items-center w-full"
                  >
                    <div className={`p-4 rounded-full mb-3 ${activeService.bg}`}>
                      <activeService.icon className={`w-10 h-10 ${activeService.color}`} />
                    </div>
                    <h3 className="text-lg sm:text-xl font-bold text-secondary-900 dark:text-white mb-2">{activeService.name}</h3>
                    <p className="text-xs sm:text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed mb-3 line-clamp-2">
                      {activeService.desc}
                    </p>
                    <Link to="/services" className="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
                      Learn More <ArrowRight className="w-3 h-3" />
                    </Link>
                  </motion.div>
                </AnimatePresence>
              </div>

              {/* Orbiting Service Icons - Circulating on the border */}
              {services.map((service, index) => {
                const total = services.length;
                const baseAngle = (index / total) * 360;
                
                return (
                  <motion.div
                    key={service.id}
                    className="absolute top-1/2 left-1/2"
                    style={{
                      width: '90%',
                      height: '90%',
                      marginLeft: '-45%',
                      marginTop: '-45%',
                    }}
                    animate={{
                      rotate: [baseAngle, baseAngle + 360],
                    }}
                    transition={{
                      duration: 60,
                      repeat: Infinity,
                      ease: "linear",
                    }}
                  >
                    <motion.button
                      className={`absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full shadow-xl flex items-center justify-center transition-all duration-300 z-10
                        ${activeService.id === service.id 
                          ? 'bg-gradient-to-br from-primary-500 to-primary-600 text-white ring-4 ring-primary-200 dark:ring-primary-800 scale-110 shadow-primary-500/50' 
                          : 'bg-white dark:bg-secondary-800 text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-400 hover:shadow-2xl border-2 border-secondary-100 dark:border-secondary-700'}`}
                      onClick={() => setActiveService(service)}
                      whileHover={{ scale: 1.2 }}
                      whileTap={{ scale: 0.95 }}
                      initial={{ opacity: 0, scale: 0 }}
                      animate={{ 
                        opacity: 1, 
                        scale: 1,
                      }}
                      transition={{ 
                        delay: index * 0.05,
                        type: "spring",
                        stiffness: 260,
                        damping: 20,
                      }}
                      style={{
                        rotate: `${-(baseAngle)}deg`, // Keep icons upright
                      }}
                    >
                      <motion.div
                        animate={{
                          rotate: [0, -360],
                        }}
                        transition={{
                          duration: 60,
                          repeat: Infinity,
                          ease: "linear",
                        }}
                      >
                        <service.icon className="w-7 h-7" />
                      </motion.div>
                    </motion.button>
                  </motion.div>
                );
              })}
            </div>
          </div>

          {/* Grid List for Mobile / Detailed View */}
          <div className="order-1 lg:order-2">
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
              {services.slice(0, 9).map((service) => (
                <div 
                  key={service.id}
                  onClick={() => setActiveService(service)}
                  className={`p-4 rounded-xl border transition-all duration-200 cursor-pointer flex flex-col items-center text-center gap-2
                    ${activeService.id === service.id
                      ? 'bg-primary-50 dark:bg-primary-900/10 border-primary-200 dark:border-primary-800'
                      : 'bg-white dark:bg-secondary-800 border-secondary-100 dark:border-secondary-700 hover:border-primary-200 dark:hover:border-primary-700'
                    }`}
                >
                  <service.icon className={`w-6 h-6 ${service.color}`} />
                  <span className={`text-sm font-medium ${activeService.id === service.id ? 'text-primary-700 dark:text-primary-300' : 'text-secondary-700 dark:text-secondary-300'}`}>
                    {service.name}
                  </span>
                </div>
              ))}
              
              <Link to="/services" className="col-span-2 sm:col-span-3 mt-4">
                <div className="p-4 rounded-xl border border-dashed border-primary-300 dark:border-primary-700 bg-primary-50/50 dark:bg-primary-900/10 flex items-center justify-center gap-2 text-primary-600 dark:text-primary-400 font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                  <UserPlus className="w-5 h-5" />
                  View All Departments & Services
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default ServicesSection;