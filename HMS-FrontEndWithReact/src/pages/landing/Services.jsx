import React from 'react';
import { Link } from 'react-router-dom';
import { Heart, Activity, User, Brain, Bone, Baby, Eye, Thermometer, FlaskConical, Pill, Bed, CheckCircle2 } from 'lucide-react';
import CTASection from '../../components/home/CTASection';

const Services = () => {
  const services = [
    {
      icon: <Heart className="w-8 h-8 text-white" />,
      title: "Cardiology",
      description: "Comprehensive heart care including diagnostics, interventional procedures, and rehabilitation.",
      color: "bg-rose-500 dark:bg-rose-600"
    },
    {
      icon: <Brain className="w-8 h-8 text-white" />,
      title: "Neurology",
      description: "Expert diagnosis and treatment for disorders of the nervous system, brain, and spine.",
      color: "bg-purple-500 dark:bg-purple-600"
    },
    {
      icon: <Bone className="w-8 h-8 text-white" />,
      title: "Orthopedics",
      description: "Advanced care for bone, joint, and muscle conditions, including joint replacement surgery.",
      color: "bg-blue-500 dark:bg-blue-600"
    },
    {
      icon: <Baby className="w-8 h-8 text-white" />,
      title: "Pediatrics",
      description: "Specialized medical care for infants, children, and adolescents in a child-friendly environment.",
      color: "bg-emerald-500 dark:bg-emerald-600"
    },
    {
      icon: <Eye className="w-8 h-8 text-white" />,
      title: "Ophthalmology",
      description: "Complete eye care services from routine exams to complex surgical procedures.",
      color: "bg-amber-500 dark:bg-amber-600"
    },
    {
      icon: <Activity className="w-8 h-8 text-white" />,
      title: "Emergency Care",
      description: "24/7 emergency services equipped to handle critical medical situations and trauma.",
      color: "bg-red-600 dark:bg-red-700"
    },
    {
      icon: <User className="w-8 h-8 text-white" />,
      title: "General Medicine",
      description: "Primary care services for preventive health, chronic disease management, and general wellness.",
      color: "bg-primary-500 dark:bg-primary-600"
    },
    {
      icon: <Thermometer className="w-8 h-8 text-white" />,
      title: "Internal Medicine",
      description: "Diagnosis, treatment, and prevention of adult diseases with a focus on complex conditions.",
      color: "bg-teal-500 dark:bg-teal-600"
    }
  ];

  const facilities = [
    {
      icon: <FlaskConical className="w-10 h-10 text-white" />,
      title: "Advanced Laboratory",
      description: "State-of-the-art diagnostic laboratory equipped with modern technology for accurate and timely results.",
      features: ["24/7 Pathology Services", "Molecular Diagnostics", "Microbiology & Serology", "Home Sample Collection"],
      color: "bg-indigo-500 dark:bg-indigo-600"
    },
    {
      icon: <Pill className="w-10 h-10 text-white" />,
      title: "24/7 Pharmacy",
      description: "Fully stocked in-house pharmacy providing a wide range of prescription medications and healthcare products.",
      features: ["Round-the-Clock Service", "Genuine Medicines", "Surgical Supplies", "Prescription Refills"],
      color: "bg-green-500 dark:bg-green-600"
    },
    {
      icon: <Bed className="w-10 h-10 text-white" />,
      title: "In-Patient Department (IPD)",
      description: "Comprehensive inpatient care with modern amenities ensuring patient comfort and rapid recovery.",
      features: ["Private & Semi-Private Rooms", "Intensive Care Units (ICU)", "24/7 Nursing Care", "Dietary Services"],
      color: "bg-cyan-500 dark:bg-cyan-600"
    }
  ];

  return (
    <div className="pt-20 min-h-screen transition-colors duration-300">
      {/* Hero Section */}
      <section className="bg-white dark:bg-secondary-950 py-16 sm:py-24 border-b border-secondary-200 dark:border-white/5 relative overflow-hidden transition-colors duration-300">
        {/* Background Gradients */}
        <div className="absolute top-0 right-0 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl opacity-30 -mr-20 -mt-20"></div>
        <div className="absolute bottom-0 left-0 w-80 h-80 bg-primary-600/10 rounded-full blur-3xl opacity-20 -ml-20 -mb-20"></div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fade-in-up relative z-10">
          <h1 className="text-4xl font-extrabold text-secondary-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
            Our Medical Services
          </h1>
          <p className="mt-5 max-w-xl mx-auto text-xl text-secondary-600 dark:text-secondary-400">
            We provide a wide range of specialized medical services to meet the healthcare needs of our community.
          </p>
        </div>
      </section>

      {/* Services Grid */}
      <section className="py-16 bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {services.map((service, index) => (
              <div
                key={index}
                className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-700 p-6 hover:border-primary-500/50 hover:bg-secondary-50 dark:hover:bg-secondary-900/80 transition-all duration-300 group animate-fade-in"
                style={{ animationDelay: `${index * 50}ms` }}
              >
                <div className={`${service.color} w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform`}>
                  {service.icon}
                </div>
                <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{service.title}</h3>
                <p className="text-secondary-600 dark:text-secondary-400 leading-relaxed mb-4">
                  {service.description}
                </p>
                <Link to={`/services/${service.title.toLowerCase().replace(/ /g, '-')}`} className="text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 inline-flex items-center group-hover:translate-x-1 transition-transform">
                  Learn More &rarr;
                </Link>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Core Facilities Section */}
      <section className="py-16 bg-white dark:bg-secondary-900 transition-colors duration-300">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16 animate-fade-in-up">
            <h2 className="text-3xl font-extrabold text-secondary-900 dark:text-white sm:text-4xl">
              24/7 Facilities & Infrastructure
            </h2>
            <p className="mt-4 max-w-2xl mx-auto text-xl text-secondary-600 dark:text-secondary-400">
              Supporting your healthcare journey with world-class infrastructure and round-the-clock services.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {facilities.map((facility, index) => (
              <div
                key={index}
                className="bg-secondary-50 dark:bg-secondary-950 rounded-2xl overflow-hidden hover:border-primary transition-all duration-300 group border border-secondary-200 dark:border-secondary-800 animate-fade-in"
                style={{ animationDelay: `${index * 150}ms` }}
              >
                <div className={`${facility.color} p-8 flex justify-center items-center`}>
                  <div className="transform group-hover:scale-110 transition-transform duration-300 drop-shadow-lg">
                    {facility.icon}
                  </div>
                </div>
                <div className="p-8">
                  <h3 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4 text-center">
                    {facility.title}
                  </h3>
                  <p className="text-secondary-600 dark:text-secondary-400 mb-6 text-center leading-relaxed">
                    {facility.description}
                  </p>
                  <ul className="space-y-3">
                    {facility.features.map((feature, idx) => (
                      <li key={idx} className="flex items-center text-secondary-700 dark:text-secondary-300">
                        <CheckCircle2 className="w-5 h-5 text-primary-600 dark:text-primary-400 mr-3 flex-shrink-0" />
                        <span className="font-medium">{feature}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <CTASection />
    </div>
  );
};

export default Services;
