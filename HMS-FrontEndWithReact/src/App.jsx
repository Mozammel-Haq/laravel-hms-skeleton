import React from 'react';
import { Routes, Route, Navigate, Link } from 'react-router-dom';
import { ThemeProvider } from './context/ThemeContext';
import ToastContainer from './components/common/ToastContainer';
import PublicLayout from './components/layout/PublicLayout';
import ProtectedLayout from './components/layout/ProtectedLayout';
import LandingHome from './pages/landing/Home';
import Services from './pages/landing/Services';
import Doctors from './pages/landing/Doctors';
import DoctorDetails from './pages/landing/DoctorDetails';
import Locations from './pages/landing/Locations';
import LocationDetails from './pages/landing/LocationDetails';
import Contact from './pages/landing/Contact';
import About from './pages/landing/About';
import Careers from './pages/landing/Careers';
import NewsMedia from './pages/landing/NewsMedia';
import Blog from './pages/landing/Blog';
import Insurance from './pages/landing/Insurance';
import Privacy from './pages/landing/Privacy';
import Terms from './pages/landing/Terms';
import Sitemap from './pages/landing/Sitemap';
import PortalLogin from './pages/portal/Login';
import Dashboard from './pages/portal/Dashboard';
import Appointments from './pages/portal/Appointments';
import BookAppointment from './pages/portal/BookAppointment';
import MedicalHistory from './pages/portal/MedicalHistory';
import Prescriptions from './pages/portal/Prescriptions';
import LabResults from './pages/portal/LabResults';
import Profile from './pages/portal/Profile';
import Settings from './pages/portal/Settings';
import Vitals from './pages/portal/Vitals';
import ForgotPassword from './pages/portal/ForgotPassword';
import ServiceDetail from './pages/landing/ServiceDetail';
import BlogPost from './pages/landing/BlogPost';

// Placeholder components for routes not yet implemented
const NotFound = () => (
  <div className="min-h-screen bg-secondary-50 dark:bg-secondary-950 flex flex-col items-center justify-center p-10 text-center transition-colors duration-300">
    <h1 className="text-6xl font-bold text-primary-600 mb-4">404</h1>
    <p className="text-xl text-secondary-900 dark:text-white mb-8">Page Not Found</p>
    <Link to="/" className="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline">Return Home</Link>
  </div>
);

function App() {
  return (
    <ThemeProvider>
      <ToastContainer />
      <Routes>
        {/* Public Routes */}
        <Route element={<PublicLayout />}>
          <Route path="/" element={<LandingHome />} />
          <Route path="/services" element={<Services />} />
          <Route path="/services/:id" element={<ServiceDetail />} />
          <Route path="/doctors" element={<Doctors />} />
          <Route path="/doctors/:id" element={<DoctorDetails />} />
          <Route path="/locations" element={<Locations />} />
          <Route path="/locations/:id" element={<LocationDetails />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/about" element={<About />} />
          <Route path="/careers" element={<Careers />} />
          <Route path="/news-media" element={<NewsMedia />} />
          <Route path="/blog" element={<Blog />} />
          <Route path="/blog/:id" element={<BlogPost />} />
          <Route path="/insurance" element={<Insurance />} />
          <Route path="/privacy" element={<Privacy />} />
          <Route path="/terms" element={<Terms />} />
          <Route path="/sitemap" element={<Sitemap />} />
          <Route path="/portal/login" element={<PortalLogin />} />
          <Route path="/forgot-password" element={<ForgotPassword />} />
        </Route>

        {/* Protected Portal Routes */}
        <Route path="/portal" element={<ProtectedLayout />}>
          <Route index element={<Navigate to="/portal/dashboard" replace />} />
          <Route path="dashboard" element={<Dashboard />} />
          <Route path="appointments" element={<Appointments />} />
          <Route path="appointments/book" element={<BookAppointment />} />
          <Route path="history" element={<MedicalHistory />} />
          <Route path="prescriptions" element={<Prescriptions />} />
          <Route path="lab-results" element={<LabResults />} />
          <Route path="profile" element={<Profile />} />
          <Route path="settings" element={<Settings />} />
          <Route path="vitals" element={<Vitals />} />
        </Route>

        {/* Catch all */}
        <Route path="*" element={<NotFound />} />
      </Routes>
    </ThemeProvider>
  );
}

export default App;
