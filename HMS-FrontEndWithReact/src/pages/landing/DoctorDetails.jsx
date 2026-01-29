import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import {
  MapPin,
  Star,
  GraduationCap,
  Calendar,
  Clock,
  Phone,
  Mail,
  Award,
  BookOpen
} from 'lucide-react';
import Button from '../../components/common/Button';
import axios from 'axios';
import API_ENDPOINTS from '../../services/endpoints';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const DoctorDetails = () => {
  const { id } = useParams();
  const baseURL = import.meta.env.VITE_API_BASE_URL;

  const [doctor, setDoctor] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchDoctor = async () => {
      try {
        const response = await axios.get(
          `${baseURL}${API_ENDPOINTS.PUBLIC.DOCTORS}/${id}`
        );

        const doc = response.data;
        const parseSpecializations = (specialization) => {
  if (!Array.isArray(specialization) || !specialization.length) return [];

  try {
    const parsed = JSON.parse(specialization[0]); // string → array
    return parsed[0]
      .split(',')
      .map(s => s.trim())
      .filter(Boolean);
  } catch {
    return [];
  }
};

const specializations = parseSpecializations(doc.specialization);

        const education = doc.educations?.length
          ? `${doc.educations[0].degree}, ${doc.educations[0].institution}`
          : 'Not specified';

        const primaryClinic = doc.clinics?.[0];

        const scheduleDays = (doc.schedules || [])
          .map(s => s.day_of_week)
          .filter(Boolean);

        const scheduleDates = (doc.schedules || [])
          .map(s => s.schedule_date)
          .filter(Boolean);

        const uniqueDays = [...new Set(scheduleDays)];
        const uniqueDates = [...new Set(scheduleDates)];

        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        const availability =
          uniqueDays.length || uniqueDates.length
            ? `${uniqueDays.map(d => dayNames[d]).join(', ')}${
                uniqueDates.length ? ` (${uniqueDates.join(', ')})` : ''
              }`
            : 'No schedule available';

        setDoctor({
          id: doc.id,
          name: `Dr. ${doc.user?.name || 'Unknown'}`,
          specializations,
          education,
          experience: `${doc.experience_years || 0} Years`,
          rating: doc.appointments_count || 0,
          reviews: 'Appointments',
          image: doc.profile_photo
            ? `${import.meta.env.VITE_BACKEND_BASE_URL}/${doc.profile_photo}`
            : '/default-doctor.png',
          biography: doc.biography || 'No biography provided.',
          availability,
          location: primaryClinic
            ? `${primaryClinic.city}, ${primaryClinic.country} – Room (${doc.consultation_room_number}), Floor ${doc.consultation_floor}`
            : 'Clinic not assigned',
          languages: ['English'], // backend does not provide this yet
          expertise: Array.isArray(doc.specialization)
            ? doc.specialization
            : []
        });
      } catch (error) {
        console.error('Error loading doctor:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchDoctor();
  }, [id, baseURL]);

  if (loading) {
  return (
    <div className="pt-20 min-h-screen bg-secondary-50 dark:bg-secondary-950 flex items-center justify-center transition-colors duration-300">
      <MedicalLoader text="Loading doctor profile..." />
    </div>
  );
}


  if (!doctor) {
    return (
      <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 flex flex-col items-center justify-center pt-20 transition-colors duration-300">
        <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">
          Doctor Not Found
        </h2>
        <Link to="/doctors">
          <Button>Back to Doctors Directory</Button>
        </Link>
      </div>
    );
  }

  return (
    <div className="pt-20 min-h-screen bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300">
      {/* Profile Header */}
      <div className="bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-800">
       <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
          <div className="flex flex-col md:flex-row gap-5 md:gap-8 items-start">
            {/* Image */}
            <div className="w-full md:w-64 flex-shrink-0">
              <div className="aspect-square rounded-2xl overflow-hidden shadow-sm border-4 border-white dark:border-secondary-800">
                <img
                  src={doctor.image}
                  alt={doctor.name}
                  className="w-full h-full object-cover"
                />
              </div>
            </div>

            {/* Info */}
            <div className="flex-grow">
              <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                  <h1 className="text-3xl font-bold text-secondary-900 dark:text-white">
                    {doctor.name}
                  </h1>
                  <div className="flex flex-wrap items-center gap-2 mt-2">
  {doctor.specializations.slice(0, 2).map((spec, index) => (
    <span
      key={index}
      className="px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-sm font-medium"
    >
      {spec}
    </span>
  ))}

  {doctor.specializations.length > 2 && (
    <span className="px-3 py-1 rounded-full bg-secondary-200 dark:bg-secondary-700 text-sm font-medium cursor-default">
      +{doctor.specializations.length - 2}
    </span>
  )}
</div>

                </div>
                <div className="flex items-center bg-yellow-100 dark:bg-yellow-900/30 px-3 py-1 rounded-full">
                  <Star className="w-5 h-5 text-yellow-500 fill-current" />
                  <span className="ml-1 font-bold text-secondary-900 dark:text-white">
                    {doctor.rating}
                  </span>
                  <span className="ml-1 text-sm text-secondary-600 dark:text-secondary-400">
                    ({doctor.reviews})
                  </span>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div className="flex items-center text-secondary-600 dark:text-secondary-400">
                  <GraduationCap className="w-5 h-5 mr-3 text-primary-500" />
                  <span>{doctor.education}</span>
                </div>
                <div className="flex items-center text-secondary-600 dark:text-secondary-400">
                  <Award className="w-5 h-5 mr-3 text-primary-500" />
                  <span>{doctor.experience} Experience</span>
                </div>
                <div className="flex items-center text-secondary-600 dark:text-secondary-400">
                  <MapPin className="w-5 h-5 mr-3 text-primary-500" />
                  <span>{doctor.location}</span>
                </div>
                <div className="flex items-center text-secondary-600 dark:text-secondary-400">
                  <BookOpen className="w-5 h-5 mr-3 text-primary-500" />
                  <span>Languages: {doctor.languages.join(', ')}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Left Column */}
          <div className="lg:col-span-2 space-y-8">
            <div className="bg-white dark:bg-secondary-900 rounded-xl p-6 shadow-sm border border-secondary-200 dark:border-secondary-800">
              <h2 className="text-xl font-bold text-secondary-900 dark:text-white mb-4">
                About {doctor.name}
              </h2>
              <p className="text-secondary-600 dark:text-secondary-400 leading-relaxed">
                {doctor.biography}
              </p>
            </div>

            <div className="bg-white dark:bg-secondary-900 rounded-xl p-6 shadow-sm border border-secondary-200 dark:border-secondary-800">
              <h2 className="text-xl font-bold text-secondary-900 dark:text-white mb-4">
                Areas of Expertise
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                {doctor.specializations.map((item, index) => (
                  <div
                    key={index}
                    className="flex items-center text-secondary-700 dark:text-secondary-300"
                  >
                    <div className="w-2 h-2 rounded-full bg-primary-500 mr-3"></div>
                    {item}
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Right Column */}
          <div className="lg:col-span-1 space-y-6">
            <div className="bg-white dark:bg-secondary-900 rounded-xl p-6 shadow-sm border border-secondary-200 dark:border-secondary-800 sticky top-24">
              <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-4">
                Book Appointment
              </h3>

              <div className="space-y-4 mb-6">
                <div className="flex items-start">
                  <Calendar className="w-5 h-5 text-primary-600 mt-0.5 mr-3" />
                  <div>
                    <p className="font-medium text-secondary-900 dark:text-white">
                      Availability
                    </p>
                    <p className="text-sm text-secondary-600 dark:text-secondary-400">
                      {doctor.availability}
                    </p>
                  </div>
                </div>
                <div className="flex items-start">
                  <Clock className="w-5 h-5 text-primary-600 mt-0.5 mr-3" />
                  <div>
                    <p className="font-medium text-secondary-900 dark:text-white">
                      Timings
                    </p>
                    <p className="text-sm text-secondary-600 dark:text-secondary-400">
                      9:00 AM – 5:00 PM
                    </p>
                  </div>
                </div>
              </div>

              <Link to="/portal/appointments/book" className="block">
                <Button className="w-full justify-center">
                  Schedule Consultation
                </Button>
              </Link>

              <div className="mt-6 pt-6 border-t border-secondary-200 dark:border-secondary-800">
                <p className="text-sm text-center text-secondary-500 dark:text-secondary-400 mb-4">
                  Need help booking?
                </p>
                <div className="flex justify-center gap-4">
                  <button className="p-2 rounded-full bg-secondary-100 dark:bg-secondary-800">
                    <Phone className="w-5 h-5" />
                  </button>
                  <button className="p-2 rounded-full bg-secondary-100 dark:bg-secondary-800">
                    <Mail className="w-5 h-5" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DoctorDetails;
