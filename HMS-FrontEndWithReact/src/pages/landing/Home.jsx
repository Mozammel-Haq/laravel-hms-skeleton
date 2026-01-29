import React from 'react';
import HeroSection from '../../components/home/HeroSection';
import StatsSection from '../../components/home/StatsSection';
import ServicesSection from '../../components/home/ServicesSection';
import PackagesSection from '../../components/home/PackagesSection';
import DoctorsCarousel from '../../components/home/DoctorsCarousel';
import TestimonialsCarousel from '../../components/home/TestimonialsCarousel';
import FAQSection from '../../components/home/FAQSection';
import CTASection from '../../components/home/CTASection';

const Home = () => {
  return (
    <div className="flex flex-col">
      <HeroSection />
      <StatsSection />
      <ServicesSection />
      <DoctorsCarousel />
      <PackagesSection />
      <TestimonialsCarousel />
      <FAQSection />
      <CTASection />
    </div>
  );
};

export default Home;
