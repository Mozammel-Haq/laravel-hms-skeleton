import React, { useState, useEffect } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import { Search, MapPin, Star, GraduationCap, Calendar } from 'lucide-react';
import Button from '../../components/common/Button';
import axios from 'axios';
import API_ENDPOINTS from '../../services/endpoints';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const Doctors = () => {
  const [searchParams] = useSearchParams();
  const initialSpecialty = searchParams.get('specialty') || '';
  const [loading, setLoading] = useState(true);
  const [doctors, setDoctors] = useState([]);
  const [clinics, setClinics] = useState([]);
  const [departments, setDepartments] = useState([]);

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSpecialty, setSelectedSpecialty] = useState(initialSpecialty);

  // 🔹 additional filters (logic only)
  const [selectedScheduleDay, setSelectedScheduleDay] = useState('');
  const [selectedScheduleDate, setSelectedScheduleDate] = useState('');

  const [selectedClinicId, setSelectedClinicId] = useState('');
  const [selectedDepartmentId, setSelectedDepartmentId] = useState('');
  const [minExperience, setMinExperience] = useState('');
  const [minPopularity, setMinPopularity] = useState('');
  const [showAdvanced, setShowAdvanced] = useState(false);

  const getDoctors = async () => {
    const baseURL = import.meta.env.VITE_API_BASE_URL
    // console.log(`${API_ENDPOINTS.PUBLIC.DOCTORS}`)
    try {
      setLoading(true);
      const response = await axios.get(`${baseURL}${API_ENDPOINTS.PUBLIC.DOCTORS}`);
      console.log(response);
      const { doctors, clinics, departments } = response.data;

      const normalizedDoctors = doctors.map(doc => {
        const education = doc.educations?.length
          ? `${doc.educations[0].degree}, ${doc.educations[0].institution}`
          : 'Not specified';

        const primaryClinic = doc.clinics?.[0];

        // schedule summary
        const scheduleDays = (doc.schedules || [])
          .map(s => s.day_of_week)
          .filter(Boolean);

        const scheduleDates = (doc.schedules || [])
          .map(s => s.schedule_date)
          .filter(Boolean);

        const uniqueDays = [...new Set(scheduleDays)];
        const uniqueDates = [...new Set(scheduleDates)];

        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        const availabilityText =
          uniqueDays.length || uniqueDates.length
            ? `${uniqueDays.map(d => dayNames[d]).join(', ')}${uniqueDates.length ? ` (${uniqueDates.sort().join(', ')})` : ''}`
            : 'No Schedule';

        return {
          id: doc.id,
          name: `Dr. ${doc.user.name}`,
          specialty: doc.department?.name || doc.specialization,
          image: doc.profile_photo
            ? `${import.meta.env.VITE_BACKEND_BASE_URL}/${doc.profile_photo}`
            : '/default-doctor.png',
          rating: doc.appointments_count || 0,
          reviews: 'Appointments',
          education,
          room: doc.consultation_room_number || 'Not assigned',
          floor: doc.consultation_floor || 'Not assigned',
          location: primaryClinic
            ? `${primaryClinic.city}, ${primaryClinic.country}`
            : 'Clinic not assigned',
          availability: availabilityText,
          consultationFee: doc.consultation_fee,
          clinics: doc.clinics || [],
          department: doc.department || null,
          experience: doc.experience_years || 0,
          schedules: doc.schedules || []
        };
      });

      setDoctors(normalizedDoctors);
      setClinics(clinics);
      setDepartments(departments);
    } catch (error) {
      console.error('Error fetching doctors:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    getDoctors();
  }, []);

  const specialties = [
    'All',
    ...new Set(doctors.map(d => d.specialty).filter(Boolean))
  ];

  const scheduleDays = [
    ...new Set(
      doctors
        .flatMap(doc => doc.schedules || [])
        .map(s => s.day_of_week)
        .filter(Boolean)
    ),
  ];

  const scheduleDates = [
    ...new Set(
      doctors
        .flatMap(doc => doc.schedules || [])
        .map(s => s.schedule_date)
        .filter(Boolean)
    ),
  ];

  const filteredDoctors = doctors.filter(doc => {
    const matchesSearch =
      doc.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      doc.specialty.toLowerCase().includes(searchTerm.toLowerCase());

    const matchesSpecialty =
      selectedSpecialty === '' ||
      selectedSpecialty === 'All' ||
      doc.specialty === selectedSpecialty;

    const matchesClinic =
      selectedClinicId === '' ||
      doc.clinics.some(c => String(c.id) === String(selectedClinicId));

    const matchesDepartment =
      selectedDepartmentId === '' ||
      String(doc.department?.id) === String(selectedDepartmentId);

    const matchesExperience =
      minExperience === '' || doc.experience >= Number(minExperience);

    const matchesPopularity =
      minPopularity === '' || doc.rating >= Number(minPopularity);

    const matchesScheduleDay =
      selectedScheduleDay === '' ||
      doc.schedules?.some(
        s => String(s.day_of_week) === String(selectedScheduleDay)
      );

    const matchesScheduleDate =
      selectedScheduleDate === '' ||
      doc.schedules?.some(
        s => s.schedule_date === selectedScheduleDate
      );

    return (
      matchesSearch &&
      matchesSpecialty &&
      matchesClinic &&
      matchesDepartment &&
      matchesExperience &&
      matchesPopularity &&
      matchesScheduleDay &&
      matchesScheduleDate
    );
  });

  useEffect(() => {
    if (initialSpecialty && initialSpecialty !== selectedSpecialty) {
      setSelectedSpecialty(initialSpecialty);
    }
  }, [initialSpecialty, selectedSpecialty]);

  return (
    <div className="pt-20 min-h-screen bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300">
      <div className="bg-white dark:bg-secondary-950 border-b border-secondary-200 dark:border-secondary-800 relative overflow-hidden">
        <div className="absolute top-0 right-0 w-96 h-96 bg-primary-600/10 dark:bg-primary-900/20 rounded-full blur-3xl opacity-30 -mr-20 -mt-20"></div>
        <div className="absolute bottom-0 left-0 w-80 h-80 bg-primary-600/10 dark:bg-primary-900/20 rounded-full blur-3xl opacity-20 -ml-20 -mb-20"></div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center animate-fade-in-up relative z-10">
          <h1 className="text-3xl font-bold text-secondary-900 dark:text-white sm:text-4xl">
            Find a Doctor
          </h1>
          <p className="mt-3 max-w-2xl mx-auto text-secondary-600 dark:text-secondary-400 text-lg">
            Search our directory of experienced medical professionals.
          </p>

          <div className="mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {/* Search + Filters */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">

              {/* Search */}
              <div className="relative col-span-1 lg:col-span-2">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Search className="h-5 w-5 text-secondary-500" />
                </div>
                <input
                  type="text"
                  className="block w-full pl-10 pr-3 py-3 border border-secondary-200 dark:border-secondary-700 rounded-lg leading-5 bg-white dark:bg-secondary-900 placeholder-secondary-500 text-secondary-900 dark:text-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 sm:text-sm transition-all"
                  placeholder="Search by doctor name..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                />
              </div>

              {/* Specialty */}
              <div>
                <select
                  value={selectedSpecialty}
                  onChange={(e) => setSelectedSpecialty(e.target.value)}
                  className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all"
                >
                  <option value="">All Specialties</option>
                  {specialties.filter(s => s !== 'All').map(s => (
                    <option key={s} value={s}>
                      {s}
                    </option>
                  ))}
                </select>
              </div>

            </div>

            {/* Advanced Filters Toggle - Single Row */}
            <div className="mt-4 flex items-center gap-3">
              <span className="text-secondary-600 dark:text-secondary-400 text-sm">
                Advanced filters
              </span>
              <button
                className="text-primary-600 dark:text-primary-400 hover:underline text-sm"
                onClick={() => setShowAdvanced(!showAdvanced)}
              >
                {showAdvanced ? 'Hide' : 'Show'}
              </button>
            </div>

            {/* Advanced Filters */}
            {showAdvanced && (
              <div className="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {/* Clinic */}
                <select
                  value={selectedClinicId}
                  onChange={(e) => setSelectedClinicId(e.target.value)}
                  className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg"
                >
                  <option value="">All Clinics</option>
                  {clinics.map(clinic => (
                    <option key={clinic.id} value={clinic.id}>
                      {clinic.name}
                    </option>
                  ))}
                </select>

                {/* Department */}
                <select
                  value={selectedDepartmentId}
                  onChange={(e) => setSelectedDepartmentId(e.target.value)}
                  className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg"
                >
                  <option value="">All Departments</option>
                  {departments.map(dept => (
                    <option key={dept.id} value={dept.id}>
                      {dept.name}
                    </option>
                  ))}
                </select>

                {/* Experience */}
                <input
                  type="number"
                  min="0"
                  placeholder="Min Experience"
                  value={minExperience}
                  onChange={(e) => setMinExperience(e.target.value)}
                  className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg"
                />

                {/* Popularity */}
                <input
                  type="number"
                  min="0"
                  placeholder="Min Appointments"
                  value={minPopularity}
                  onChange={(e) => setMinPopularity(e.target.value)}
                  className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg"
                />

                {/* Schedule Day */}
                <div className="min-w-[200px]">
                  <select
                    value={selectedScheduleDay}
                    onChange={(e) => setSelectedScheduleDay(e.target.value)}
                    className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg"
                  >
                    <option value="">All Days</option>
                    {scheduleDays.map(day => (
                      <option key={day} value={day}>
                        {['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][day]}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Schedule Date */}
                <div className="min-w-[200px]">
                  <select
                    value={selectedScheduleDate}
                    onChange={(e) => setSelectedScheduleDate(e.target.value)}
                    className="block w-full px-3 py-3 border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white rounded-lg"
                  >
                    <option value="">All Dates</option>
                    {scheduleDates.map(date => (
                      <option key={date} value={date}>
                        {date}
                      </option>
                    ))}
                  </select>
                </div>

              </div>
            )}
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {loading ? (
  <MedicalLoader />
) : filteredDoctors.length > 0 ? (
  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            {filteredDoctors.map((doc, index) => (
              <div
                key={doc.id}
                className="bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden hover:border-primary-500/50 transition-all duration-300 flex flex-col group animate-fade-in"
                style={{ animationDelay: `${index * 50}ms` }}
              >
                <div className="p-6 flex-grow">
                  <div className="flex items-start justify-between mb-4">
                    <img
                      src={doc.image}
                      alt={doc.name}
                      className="w-20 h-20 rounded-full object-cover border-4 border-secondary-50 dark:border-secondary-800"
                    />
                    <div className="flex flex-col items-end">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300">
                        {doc.specialty}
                      </span>
                      <div className="flex items-center mt-2 text-yellow-500 text-sm font-bold">
                        <Star className="w-4 h-4 fill-current mr-1" />
                        {doc.rating}
                        <span className="text-secondary-500 dark:text-secondary-400 font-normal ml-1">
                          ({doc.reviews})
                        </span>
                      </div>
                    </div>
                  </div>

                  <h3 className="text-xl font-bold text-secondary-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <Link to={`/doctors/${doc.id}`}>{doc.name}</Link>
                  </h3>

                  <div className="space-y-2 mt-3">
                    <p className="text-sm text-secondary-600 dark:text-secondary-400 flex items-center">
                      <GraduationCap className="w-4 h-4 mr-2" />
                      {doc.education}
                    </p>
                    <p className="text-sm text-secondary-600 dark:text-secondary-400 flex items-center">
                      <MapPin className="w-4 h-4 mr-2" />
                      {doc.location} - Room: ({doc.room}), Floor: {doc.floor}
                    </p>
                    <p className="text-sm text-secondary-600 dark:text-secondary-400 flex items-center">
                      <Calendar className="w-4 h-4 mr-2" />
                      Avail: {doc.availability}
                    </p>
                  </div>
                </div>

                <div className="bg-secondary-50 dark:bg-secondary-800/50 p-4 border-t border-secondary-200 dark:border-secondary-800">
                  <Link to="/portal/appointments/book" className="w-full">
                    <Button size="sm" className="w-full shadow-none">
                      Book Appointment
                    </Button>
                  </Link>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center py-20">
            <p className="text-secondary-500 dark:text-secondary-400 text-lg">
              No doctors found matching your criteria.
            </p>
            <button
              onClick={() => {
                setSearchTerm('');
                setSelectedSpecialty('');
                setSelectedClinicId('');
                setSelectedDepartmentId('');
                setMinExperience('');
                setMinPopularity('');
                setSelectedScheduleDay('');
                setSelectedScheduleDate('');
              }}
              className="mt-4 text-primary-600 dark:text-primary-400 hover:underline"
            >
              Clear filters
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Doctors;
