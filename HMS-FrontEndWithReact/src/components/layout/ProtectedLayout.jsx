import React, { useEffect } from 'react';
import { Outlet, Navigate, useLocation } from 'react-router-dom';
import Sidebar from '../portal/Sidebar';
import TopBar from '../portal/TopBar';
import { useAuth } from '../../context/AuthContext';
import { Loader } from 'lucide-react';

const ProtectedLayout = () => {
  const { isAuthenticated, isLoading } = useAuth();
  const location = useLocation();

  if (isLoading) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-secondary-950">
        <Loader className="w-8 h-8 text-primary-500 animate-spin" />
      </div>
    );
  }

  if (!isAuthenticated) {
    return <Navigate to="/portal/login" state={{ from: location }} replace />;
  }

  return (
    <div className="flex h-screen overflow-hidden bg-secondary-50 dark:bg-secondary-950">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <TopBar />
        <main className="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin scrollbar-thumb-secondary-200 dark:scrollbar-thumb-secondary-800 scrollbar-track-transparent">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default ProtectedLayout;
