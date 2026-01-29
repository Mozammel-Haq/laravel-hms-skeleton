import React, { useState } from 'react';
import { ChevronLeft, ChevronRight, Quote } from 'lucide-react';

const TestimonialsCarousel = () => {
  const testimonials = [
    {
      id: 1,
      text: "The care I received at CityCare was exceptional. The doctors listened to my concerns and the nursing staff was incredibly kind. I felt safe and well-cared for throughout my entire stay.",
      author: "Sarah Mitchell",
      role: "Cardiology Patient",
      image: "https://randomuser.me/api/portraits/women/12.jpg"
    },
    {
      id: 2,
      text: "I was nervous about my surgery, but Dr. Ross explained everything clearly. The recovery facilities are top-notch and the follow-up care has been thorough. Highly recommended.",
      author: "James Wilson",
      role: "Orthopedic Surgery",
      image: "https://randomuser.me/api/portraits/men/45.jpg"
    },
    {
      id: 3,
      text: "Pediatric care here is amazing. They made my daughter feel so comfortable during her treatment. The environment is very child-friendly and the staff is patient.",
      author: "Emily Rodriguez",
      role: "Mother of Patient",
      image: "https://randomuser.me/api/portraits/women/67.jpg"
    }
  ];

  const [activeIndex, setActiveIndex] = useState(0);

  const next = () => {
    setActiveIndex((prev) => (prev + 1) % testimonials.length);
  };

  const prev = () => {
    setActiveIndex((prev) => (prev - 1 + testimonials.length) % testimonials.length);
  };

  return (
    <section className="py-24 bg-secondary-50 dark:bg-secondary-950 relative overflow-hidden border-b border-secondary-200 dark:border-secondary-800 transition-colors duration-300">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-16">
          <h2 className="text-3xl font-bold text-secondary-900 dark:text-white">Patient Stories</h2>
          <div className="w-24 h-1 bg-primary-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <div className="relative max-w-4xl mx-auto">
          {/* Main Card */}
          <div className="bg-white dark:bg-secondary-900 rounded-2xl p-8 md:p-12 border border-secondary-200 dark:border-secondary-800 relative transition-colors duration-300">
            <Quote className="absolute top-8 left-8 w-12 h-12 text-primary-100 dark:text-primary-900/40" />

            <div className="relative z-10 flex flex-col items-center text-center">
              <div className="w-20 h-20 rounded-full overflow-hidden border-4 border-secondary-100 dark:border-secondary-800 mb-6 shadow-none">
                <img
                  src={testimonials[activeIndex].image}
                  alt={testimonials[activeIndex].author}
                  className="w-full h-full object-cover"
                />
              </div>

              <p className="text-xl md:text-2xl text-secondary-600 dark:text-secondary-300 font-medium leading-relaxed italic mb-8">
                "{testimonials[activeIndex].text}"
              </p>

              <div>
                <h4 className="text-lg font-bold text-secondary-900 dark:text-white">{testimonials[activeIndex].author}</h4>
                <p className="text-primary-600 dark:text-primary-500">{testimonials[activeIndex].role}</p>
              </div>
            </div>
          </div>

          {/* Navigation - Absolute Positioned */}
          <button
            onClick={prev}
            className="absolute top-1/2 -left-4 md:-left-16 transform -translate-y-1/2 w-12 h-12 bg-white dark:bg-secondary-900 rounded-full border border-secondary-200 dark:border-secondary-800 flex items-center justify-center text-secondary-400 hover:text-primary-600 dark:hover:text-white hover:border-primary-500 hover:bg-secondary-50 dark:hover:bg-primary-600 transition-all"
          >
            <ChevronLeft className="w-6 h-6" />
          </button>

          <button
            onClick={next}
            className="absolute top-1/2 -right-4 md:-right-16 transform -translate-y-1/2 w-12 h-12 bg-white dark:bg-secondary-900 rounded-full border border-secondary-200 dark:border-secondary-800 flex items-center justify-center text-secondary-400 hover:text-primary-600 dark:hover:text-white hover:border-primary-500 hover:bg-secondary-50 dark:hover:bg-primary-600 transition-all"
          >
            <ChevronRight className="w-6 h-6" />
          </button>

          {/* Dots */}
          <div className="flex justify-center gap-2 mt-8">
            {testimonials.map((_, idx) => (
              <button
                key={idx}
                onClick={() => setActiveIndex(idx)}
                className={`w-2.5 h-2.5 rounded-full transition-all ${
                  idx === activeIndex ? 'bg-primary-600 w-8' : 'bg-secondary-300 dark:bg-secondary-600 hover:bg-primary-500'
                }`}
              />
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default TestimonialsCarousel;
