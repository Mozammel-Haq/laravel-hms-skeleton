import React, { useEffect, useState, useRef } from 'react';
import { Users, Building, Activity, Award } from 'lucide-react';

const StatsSection = () => {
  const [isVisible, setIsVisible] = useState(false);
  const sectionRef = useRef(null);

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
        }
      },
      { threshold: 0.1 }
    );
    if (sectionRef.current) observer.observe(sectionRef.current);
    return () => observer.disconnect();
  }, []);

  const stats = [
    { icon: Users, value: 50000, label: "Patients Recovered", suffix: "+" },
    { icon: Building, value: 12, label: "Specialized Clinics", suffix: "" },
    { icon: Activity, value: 150, label: "Expert Doctors", suffix: "+" },
    { icon: Award, value: 25, label: "Years of Excellence", suffix: "+" },
  ];

  return (
    <section ref={sectionRef} className="py-20 bg-gradient-to-br from-secondary-50 via-white to-secondary-100/50 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950 relative overflow-hidden border-b border-secondary-100 dark:border-secondary-800 transition-colors duration-300">
      {/* Decorative Background Pattern */}
      <div className="absolute inset-0 opacity-20 pointer-events-none">
        <div className="absolute -top-24 -right-24 w-96 h-96 bg-primary-100 dark:bg-secondary-800 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-24 -left-24 w-96 h-96 bg-primary-200 dark:bg-secondary-800 rounded-full blur-3xl"></div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
          {stats.map((stat, index) => (
            <StatItem
              key={index}
              icon={stat.icon}
              target={stat.value}
              label={stat.label}
              suffix={stat.suffix}
              isVisible={isVisible}
              delay={index * 100}
            />
          ))}
        </div>
      </div>
    </section>
  );
};

const StatItem = ({ icon: Icon, target, label, suffix, isVisible, delay }) => {
  const [count, setCount] = useState(0);

  useEffect(() => {
    if (!isVisible) return;

    let start = 0;
    const duration = 2000; // 2 seconds
    const increment = target / (duration / 16); // 60fps

    const timer = setInterval(() => {
      start += increment;
      if (start >= target) {
        setCount(target);
        clearInterval(timer);
      } else {
        setCount(Math.floor(start));
      }
    }, 16);

    return () => clearInterval(timer);
  }, [isVisible, target]);

  return (
    <div
      className={`text-center transition-all duration-700 transform ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'}`}
      style={{ transitionDelay: `${delay}ms` }}
    >
      <div className="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-primary-50 dark:bg-secondary-800 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-secondary-700">
        <Icon className="w-8 h-8" />
      </div>
      <div className="text-4xl md:text-5xl font-bold text-secondary-900 dark:text-white mb-2 font-sans">
        {count.toLocaleString()}{suffix}
      </div>
      <div className="text-sm md:text-base font-medium text-secondary-500 dark:text-secondary-400 uppercase tracking-wide">
        {label}
      </div>
    </div>
  );
};

export default StatsSection;
