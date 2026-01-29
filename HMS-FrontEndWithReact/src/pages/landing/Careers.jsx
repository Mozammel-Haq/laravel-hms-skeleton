import React from 'react';
import { Briefcase, MapPin, Clock } from 'lucide-react';
import Button from '../../components/common/Button';

const Careers = () => {
  const jobs = [
    { title: 'Senior Nurse', department: 'Emergency', location: 'Dhaka, Bangladesh', type: 'Full-time' },
    { title: 'Cardiologist', department: 'Cardiology', location: 'Dhaka, Bangladesh', type: 'Full-time' },
    { title: 'Medical Receptionist', department: 'Administration', location: 'Dhaka, Bangladesh', type: 'Part-time' },
  ];

  return (
    <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 py-20 transition-colors duration-300">
      <div className="container mx-auto px-4">
        <div className="text-center mb-16">
          <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mb-4">Join Our Team</h1>
          <p className="text-secondary-600 dark:text-secondary-400 max-w-2xl mx-auto">
            Build a rewarding career at CityCare Hospital. We are always looking for passionate professionals to join our mission of providing exceptional healthcare.
          </p>
        </div>

        <div className="max-w-4xl mx-auto space-y-6">
          {jobs.map((job, index) => (
            <div key={index} className="bg-white dark:bg-secondary-900 p-6 rounded-xl border border-secondary-100 dark:border-secondary-800 flex flex-col md:flex-row justify-between items-center gap-6 hover:border-primary-500 transition-colors">
              <div>
                <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-2">{job.title}</h3>
                <div className="flex flex-wrap gap-4 text-sm text-secondary-500 dark:text-secondary-400">
                  <span className="flex items-center gap-1"><Briefcase className="w-4 h-4" /> {job.department}</span>
                  <span className="flex items-center gap-1"><MapPin className="w-4 h-4" /> {job.location}</span>
                  <span className="flex items-center gap-1"><Clock className="w-4 h-4" /> {job.type}</span>
                </div>
              </div>
              <Button variant="outline" className="shrink-0">Apply Now</Button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Careers;
