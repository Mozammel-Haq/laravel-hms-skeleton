import React, { useRef, useEffect, useState } from 'react';
import { ChevronLeft, ChevronRight, Star, GraduationCap, Plus } from 'lucide-react';
import { Link } from 'react-router-dom';
import Button from '../common/Button';
import axios from 'axios';
import API_ENDPOINTS from '../../services/endpoints';
import MedicalLoader from '../loaders/MedicalLoader';

const DoctorsCarousel = () => {
  const scrollContainerRef = useRef(null);
  const [doctors, setDoctors] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchDoctors = async () => {
    const baseURL = import.meta.env.VITE_API_BASE_URL;

    try {
      setLoading(true);
      const res = await axios.get(`${baseURL}${API_ENDPOINTS.PUBLIC.DOCTORS}`);
      const apiDoctors = res.data.doctors || [];

      // 🔹 inline normalization (no separate normalizer)
      const normalized = apiDoctors.map(doc => {
        const education = doc.educations?.length
          ? doc.educations[0].degree
          : 'MBBS';

        return {
          id: doc.id,
          name: `Dr. ${doc.user?.name || 'Unknown'}`,
          specialty: doc.department?.name || doc.specialization || 'General',
          image: doc.profile_photo
            ? `${import.meta.env.VITE_BACKEND_BASE_URL}/${doc.profile_photo}`
            : '/default-doctor.png',
          qualification: education,
          rating: doc.appointments_count || 0,
        };
      });

      // 🔹 limit carousel items (adjust if needed)
      setDoctors(normalized.slice(0, 10));
    } catch (err) {
      console.error('Carousel doctor fetch failed:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDoctors();
  }, []);

  const scrollLeft = () => {
    scrollContainerRef.current?.scrollBy({ left: -350, behavior: 'smooth' });
  };

  const scrollRight = () => {
    scrollContainerRef.current?.scrollBy({ left: 350, behavior: 'smooth' });
  };

  return (
    <section className="py-20 bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300">
      <div className="max-w-7xl mx-auto px-4">

        {/* Header */}
        <div className="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
          <div className="max-w-2xl">
            <h2 className="text-3xl md:text-4xl font-bold text-secondary-900 dark:text-white mb-4">
              Meet Our Specialists
            </h2>
            <p className="text-xl text-secondary-600 dark:text-secondary-400">
              Our team of expert doctors is dedicated to providing you with the best possible care.
            </p>
          </div>

          <div className="flex items-center gap-4">
            <Button
              variant="outline"
              className="rounded-full w-12 h-12 p-0"
              onClick={scrollLeft}
            >
              <ChevronLeft className="w-6 h-6" />
            </Button>

            <Button
              variant="outline"
              className="rounded-full w-12 h-12 p-0"
              onClick={scrollRight}
            >
              <ChevronRight className="w-6 h-6" />
            </Button>

            <Link to="/doctors">
              <Button
                variant="primary"
                className="rounded-full w-12 h-12 p-0"
              >
                <Plus className="w-6 h-6" />
              </Button>
            </Link>
          </div>
        </div>

        {/* Content */}
        {loading ? (
          <MedicalLoader />
        ) : doctors.length === 0 ? (
          <p className="text-center text-secondary-500">No doctors available.</p>
        ) : (
          <div
            ref={scrollContainerRef}
            className="flex overflow-x-auto gap-8 pb-8 scrollbar-hide snap-x snap-mandatory"
            style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
          >
            {doctors.map(doctor => (
              <div
                key={doctor.id}
                className="min-w-[300px] md:min-w-[350px] bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden group hover:border-primary-500/50 transition-all snap-center"
              >
                <div className="relative h-64 overflow-hidden bg-secondary-100 dark:bg-secondary-700">
                  <img
                    src={doctor.image}
                    alt={doctor.name}
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-primary-900/60 to-transparent" />
                </div>

                <div className="p-6">
                  <div className="flex justify-between items-start mb-2">
                    <div>
                      <span className="text-xs font-bold text-primary-600 dark:text-primary-200 uppercase bg-primary-50 dark:bg-primary-900 rounded-full px-2 py-1">
                        {doctor.specialty}
                      </span>
                      <h3 className="text-xl font-bold mt-2 hover:text-primary-600 transition-colors duration-300">
                        <Link to={`/doctors/${doctor.id}`}>
                          {doctor.name}
                        </Link>
                      </h3>
                    </div>

                    <div className="flex items-center gap-1 text-yellow-500">
                      <Star className="w-4 h-4 fill-current" />
                      <span className="text-sm font-bold">
                        {doctor.rating}
                      </span>
                    </div>
                  </div>

                  <p className="text-sm text-secondary-600 flex items-center gap-2 mb-6">
                    <GraduationCap className="w-4 h-4" />
                    {doctor.qualification}
                  </p>

                  <Link to={`/doctors/${doctor.id}`}>
                    <Button variant="outline" className="w-full">
                      View Profile
                    </Button>
                  </Link>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </section>
  );
};

export default DoctorsCarousel;
