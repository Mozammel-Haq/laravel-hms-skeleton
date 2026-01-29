import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { MapPin, Phone, Clock, Navigation } from 'lucide-react';
import Button from '../../components/common/Button';
import axios from 'axios';
import API_ENDPOINTS from '../../services/endpoints';
import MedicalLoader from '../../components/loaders/MedicalLoader';



const Locations = () => {
  const [loading, setLoading] = useState(true);
  const [locations, setLocations] = useState([]);
  const [activeMapQuery, setActiveMapQuery] = useState("");
  
  const handleGetDirections = (query) => {
    setActiveMapQuery(query);
    setTimeout(() => {
      const mapSection = document.getElementById('map-section');
      if (mapSection) {
        mapSection.scrollIntoView({ behavior: 'smooth' });
      }
    }, 100);
  };

  const getLocations = async () => {
    try {
      setLoading(true);
      const res = await axios.get(
        `${import.meta.env.VITE_API_BASE_URL}${API_ENDPOINTS.PUBLIC.CLINICS}`
      );

      const normalizedLocations = res.data.clinics.map((clinic) => ({
        id: clinic.id,
        name: clinic.name,
        type: "Clinic", 
        address: `${clinic.address_line_1}, ${clinic.city}`,
        mapQuery: encodeURIComponent(`${clinic.address_line_1}, ${clinic.city}`),
        phone: clinic.phone,
        hours: `${clinic.opening_time} - ${clinic.closing_time}`,
        image: clinic.images?.length
          ? `${import.meta.env.VITE_BACKEND_BASE_URL}/storage/${clinic.images[0].image_path}`
          : "https://images.unsplash.com/photo-1587351021759-3e566b9af923",
      }));

      setLocations(normalizedLocations);
      // Set first location as default if available
      if (normalizedLocations.length > 0) {
        setActiveMapQuery(normalizedLocations[0].mapQuery);
      }
    } catch (error) {
      console.error('Error fetching Clinics:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    getLocations();
  }, []);

  const selectedMapUrl = activeMapQuery 
    ? `https://maps.google.com/maps?q=${activeMapQuery}&t=&z=13&ie=UTF8&iwloc=&output=embed`
    : null;

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center pt-20">
        <MedicalLoader text="Loading locations..." />
      </div>
    );
  }
  
  return (
    <div className="pt-24 pb-20 bg-secondary-50 dark:bg-secondary-950 min-h-screen transition-colors duration-300">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up">
          <span className="text-primary-600 dark:text-primary-400 font-bold tracking-wider uppercase text-sm">Our Network</span>
          <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mt-2 mb-4">Find a Location Near You</h1>
          <p className="text-secondary-600 dark:text-secondary-400 text-lg">
            With multiple centers across the city, world-class healthcare is always within reach.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {locations.map((loc) => (
            <div
              key={loc.id}
              onClick={() => setActiveMapQuery(loc.mapQuery)}
              className="group border border-secondary-200 dark:border-secondary-800 rounded-2xl overflow-hidden hover:border-primary-500/50 transition-all duration-300 bg-white dark:bg-secondary-900 cursor-pointer"
            >
              {/* Image Container */}
              <div className="h-48 overflow-hidden relative">
                <div className="absolute inset-0 bg-secondary-900/10 dark:bg-secondary-950/20 group-hover:bg-transparent transition-colors z-10"></div>
                <img
                  src={loc.image}
                  alt={loc.name}
                  className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                />
                <div className="absolute top-4 right-4 z-20 bg-white/90 dark:bg-secondary-950/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary-600 dark:text-primary-400 border border-primary-500/30">
                  {loc.type}
                </div>
              </div>

              {/* Content */}
              <div className="p-6">
                <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-4 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                  <Link to={`/locations/${loc.id}`}>
                    {loc.name}
                  </Link>
                </h3>

                <div className="space-y-3 mb-6">
                  <div className="flex items-start gap-3 text-secondary-600 dark:text-secondary-400">
                    <MapPin className="w-5 h-5 text-primary-600 dark:text-primary-500 shrink-0 mt-0.5" />
                    <span className="text-sm">{loc.address}</span>
                  </div>
                  <div className="flex items-center gap-3 text-secondary-600 dark:text-secondary-400">
                    <Phone className="w-5 h-5 text-primary-600 dark:text-primary-500 shrink-0" />
                    <span className="text-sm font-medium">{loc.phone}</span>
                  </div>
                  <div className="flex items-center gap-3 text-secondary-600 dark:text-secondary-400">
                    <Clock className="w-5 h-5 text-primary-600 dark:text-primary-500 shrink-0" />
                    <span className="text-sm">{loc.hours}</span>
                  </div>
                </div>

                <Button 
                  onClick={() => handleGetDirections(loc.mapQuery)}
                  variant="outline" 
                  className="w-full border-secondary-200 dark:border-secondary-800 text-secondary-600 dark:text-secondary-300 hover:border-primary-500 hover:text-white hover:bg-primary-600 dark:hover:bg-secondary-800"
                >
                  <Navigation className="w-4 h-4 mr-2" />
                  View on Map
                </Button>
              </div>
            </div>
          ))}
        </div>

        {/* Interactive Map Section */}
        <div id="map-section" className="mt-20 rounded-2xl overflow-hidden border border-secondary-200 dark:border-secondary-800 h-[500px] relative bg-secondary-100 dark:bg-secondary-900 shadow-lg animate-fade-in">
             {selectedMapUrl ? (
               <iframe
                 width="100%"
                 height="100%"
                 frameBorder="0"
                 scrolling="no"
                 marginHeight="0"
                 marginWidth="0"
                 src={selectedMapUrl}
                 title="Clinic Location Map"
                 className="w-full h-full"
               ></iframe>
             ) : (
               <div className="flex flex-col items-center justify-center h-full text-center p-8">
                 <MapPin className="w-16 h-16 text-secondary-300 dark:text-secondary-700 mb-4" />
                 <h3 className="text-xl font-bold text-secondary-500 dark:text-secondary-400">Select a location to view on map</h3>
               </div>
             )}
        </div>

      </div>
    </div>
  );
};

export default Locations;
