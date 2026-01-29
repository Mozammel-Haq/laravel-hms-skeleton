import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { MapPin, Phone, Clock, Navigation, CheckCircle2, X } from 'lucide-react';
import Button from '../../components/common/Button';
import axios from 'axios';
import API_ENDPOINTS from '../../services/endpoints';
import MedicalLoader from '../../components/loaders/MedicalLoader';

const LocationDetails = () => {
  const [loading, setLoading] = useState(true);
  const { id } = useParams();
  const [selectedImage, setSelectedImage] = useState(null);

  const [location, setLocation] = useState(null);
  // console.log("hello")
  const getLocationDetails = async () => {
  try {
    setLoading(true);
    const res = await axios.get(
      `${import.meta.env.VITE_API_BASE_URL}${API_ENDPOINTS.PUBLIC.CLINIC_DETAILS(id)}`
    );

    const clinic = res.data.clinic;

    const normalizedLocation = {
      id: clinic.id,
      name: clinic.name,
      type: "Clinic", // or based on code/status later
      address: `${clinic.address_line_1}, ${clinic.city}`,
      phone: clinic.phone,
      hours: `${clinic.opening_time.slice(0, 5)} - ${clinic.closing_time.slice(0, 5)}`,
      description: clinic.about,
      services: clinic.services || [],
      image: clinic.images?.length
        ? `${import.meta.env.VITE_BACKEND_BASE_URL}/storage/${clinic.images[0].image_path}`
        : "https://images.unsplash.com/photo-1587351021759-3e566b9af923",
      gallery: clinic.images?.length
        ? clinic.images.map(
            (img) =>
              `${import.meta.env.VITE_BACKEND_BASE_URL}/storage/${img.image_path}`
          )
        : [],
      mapLink: `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
        clinic.address_line_1
      )}`,
    };

    setLocation(normalizedLocation);
  } catch (error) {
    console.error('Error fetching clinic details:', error);
  } finally {
    setLoading(false);
  }
};

  useEffect(() => {
    getLocationDetails();
  }, [id]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center pt-20">
        <MedicalLoader text="Loading location details..." />
      </div>
    );
  }
  
  if (!location) {
    return (
      <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 flex flex-col items-center justify-center pt-20 transition-colors duration-300">
        <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Location Not Found</h2>
        <Link to="/locations">
          <Button>Back to Locations</Button>
        </Link>
      </div>
    );
  }

  return (
    <div className="pt-20 min-h-screen bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300">
      {/* Hero Section with Image */}
      <div className="relative h-[300px] md:h-[400px]">
        <img 
          src={location.image} 
          alt={location.name} 
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-secondary-900/90 via-secondary-900/50 to-transparent"></div>
        <div className="absolute bottom-0 left-0 right-0 p-8 md:p-12">
          <div className="max-w-7xl mx-auto">
            <span className="inline-block px-4 py-1 rounded-full bg-primary-600 text-white text-sm font-bold tracking-wide mb-4">
              {location.type}
            </span>
            <h1 className="text-4xl md:text-5xl font-bold text-white mb-4">{location.name}</h1>
            <div className="flex flex-col md:flex-row gap-6 text-white/90">
              <div className="flex items-center gap-2">
                <MapPin className="w-5 h-5 text-primary-400" />
                <span>{location.address}</span>
              </div>
              <div className="flex items-center gap-2">
                <Clock className="w-5 h-5 text-primary-400" />
                <span>{location.hours}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
          {/* Main Content */}
          <div className="lg:col-span-2 space-y-12">
            
            {/* About Section */}
            <section>
              <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6">About This Center</h2>
              <p className="text-lg text-secondary-600 dark:text-secondary-400 leading-relaxed">
                {location.description}
              </p>
            </section>

            {/* Services Section */}
            <section>
              <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6">Services Available</h2>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {location.services.map((service, index) => (
                  <div key={index} className="flex items-center p-4 bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 shadow-sm">
                    <CheckCircle2 className="w-5 h-5 text-primary-600 dark:text-primary-400 mr-3" />
                    <span className="font-medium text-secondary-900 dark:text-white">{service}</span>
                  </div>
                ))}
              </div>
            </section>

            {/* Gallery Section */}
            <section>
              <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6">Facility Gallery</h2>
              <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                {location.gallery.map((img, index) => (
                  <div 
                    key={index} 
                    className={`relative rounded-xl overflow-hidden cursor-pointer group ${index === 0 ? 'col-span-2 row-span-2' : ''}`}
                    onClick={() => setSelectedImage(img)}
                  >
                    <div className="aspect-square w-full h-full">
                       <img 
                        src={img} 
                        alt={`Gallery ${index + 1}`} 
                        className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                      />
                      <div className="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-300"></div>
                    </div>
                  </div>
                ))}
              </div>
            </section>
          </div>

          {/* Sidebar */}
          <div className="lg:col-span-1">
            <div className="bg-white dark:bg-secondary-900 rounded-2xl p-8 shadow-sm border border-secondary-200 dark:border-secondary-800 sticky top-24">
              <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-6">Contact Information</h3>
              
              <div className="space-y-6">
                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center shrink-0">
                    <Phone className="w-5 h-5 text-primary-600 dark:text-primary-400" />
                  </div>
                  <div>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 font-medium uppercase tracking-wide">Phone</p>
                    <p className="text-lg font-semibold text-secondary-900 dark:text-white mt-1">{location.phone}</p>
                    <p className="text-sm text-secondary-500 mt-1">24/7 Hotline</p>
                  </div>
                </div>

                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center shrink-0">
                    <MapPin className="w-5 h-5 text-primary-600 dark:text-primary-400" />
                  </div>
                  <div>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 font-medium uppercase tracking-wide">Address</p>
                    <p className="text-base font-medium text-secondary-900 dark:text-white mt-1">{location.address}</p>
                  </div>
                </div>

                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center shrink-0">
                    <Clock className="w-5 h-5 text-primary-600 dark:text-primary-400" />
                  </div>
                  <div>
                    <p className="text-sm text-secondary-500 dark:text-secondary-400 font-medium uppercase tracking-wide">Hours</p>
                    <p className="text-base font-medium text-secondary-900 dark:text-white mt-1">{location.hours}</p>
                  </div>
                </div>

                <hr className="border-secondary-200 dark:border-secondary-800 my-6" />

                <a href={location.mapLink} target="_blank" rel="noopener noreferrer" className="block">
                  <Button className="w-full justify-center">
                    <Navigation className="w-4 h-4 mr-2" />
                    Get Directions
                  </Button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Lightbox Modal */}
      {selectedImage && (
        <div className="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4" onClick={() => setSelectedImage(null)}>
          <button 
            className="absolute top-4 right-4 text-white hover:text-primary-400 transition-colors"
            onClick={() => setSelectedImage(null)}
          >
            <X className="w-8 h-8" />
          </button>
          <img 
            src={selectedImage} 
            alt="Gallery Preview" 
            className="max-w-full max-h-[90vh] object-contain rounded-lg animate-fade-in"
            onClick={(e) => e.stopPropagation()}
          />
        </div>
      )}
    </div>
  );
};

export default LocationDetails;
