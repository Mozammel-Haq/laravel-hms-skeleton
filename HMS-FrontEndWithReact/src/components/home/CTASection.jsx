import React from 'react';
import { Link } from 'react-router-dom';
import { Calendar, Phone, Shield, Clock, CheckCircle } from 'lucide-react';
import Button from '../common/Button';

const CTASection = () => {
  return (
    <section className="py-20 bg-primary-700 dark:bg-secondary-900 text-white relative overflow-hidden border-b border-white/5 transition-colors duration-300">
      {/* Abstract Background Elements */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-white/10 dark:bg-primary-600/20 rounded-full blur-3xl opacity-50 -mr-20 -mt-20"></div>
      <div className="absolute bottom-0 left-0 w-80 h-80 bg-white/10 dark:bg-primary-600/20 rounded-full blur-3xl opacity-30 -ml-20 -mb-20"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="flex flex-col lg:flex-row items-center justify-between gap-10">

          <div className="lg:max-w-2xl text-center lg:text-left">
            <h2 className="text-3xl md:text-4xl font-bold mb-6 text-white">
              Ready to Prioritize Your Health?
            </h2>
            <p className="text-primary-100 dark:text-secondary-300 text-lg md:text-xl leading-relaxed mb-8">
              Book an appointment with our specialists today and experience healthcare reimagined. We are here to support you every step of the way.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <Link to="/portal/login">
                <Button size="lg" className="bg-white text-primary-700 hover:bg-secondary-100 dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700 border-transparent w-full sm:w-auto">
                  <Calendar className="w-5 h-5 mr-2" />
                  Book Appointment
                </Button>
              </Link>
              <a href="tel:+8801234567890">
                <Button variant="outline" size="lg" className="border-white/20 text-white hover:bg-white/10 w-full sm:w-auto">
                  <Phone className="w-5 h-5 mr-2" />
                  Call Now
                </Button>
              </a>
            </div>
          </div>

          {/* Right Side - Static Elements (No Card Effect) */}
          <div className="hidden lg:grid grid-cols-2 gap-8 w-full max-w-md">
            <div className="flex flex-col items-start p-2">
              <Clock className="w-10 h-10 text-primary-200 dark:text-primary-400 mb-4" />
              <div className="text-white font-bold text-xl mb-1">24/7 Support</div>
              <div className="text-primary-100 dark:text-secondary-400 text-sm">Always available for you</div>
            </div>
            <div className="flex flex-col items-start p-2">
              <Shield className="w-10 h-10 text-primary-200 dark:text-primary-400 mb-4" />
              <div className="text-white font-bold text-xl mb-1">Secure Care</div>
              <div className="text-primary-100 dark:text-secondary-400 text-sm">Your health is protected</div>
            </div>
            <div className="flex flex-col items-start p-2">
              <CheckCircle className="w-10 h-10 text-primary-200 dark:text-emerald-400 mb-4" />
              <div className="text-white font-bold text-xl mb-1">Top Rated</div>
              <div className="text-primary-100 dark:text-secondary-400 text-sm">98% Patient Satisfaction</div>
            </div>
            <div className="flex flex-col items-start p-2">
               <div className="text-4xl font-bold mb-1 text-white">15+</div>
               <div className="text-primary-100 dark:text-secondary-400 text-sm font-medium">Years of Experience</div>
            </div>
          </div>

        </div>
      </div>
    </section>
  );
};

export default CTASection;
