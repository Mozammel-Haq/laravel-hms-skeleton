import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Mail, Phone, MapPin, Send, CheckCircle, Clock, Loader2 } from 'lucide-react';
import Button from '../../components/common/Button';

// Validation Schema
const contactSchema = z.object({
  name: z.string().min(2, 'Name must be at least 2 characters'),
  email: z.string().email('Invalid email address'),
  phone: z.string().min(10, 'Phone number must be at least 10 digits'),
  subject: z.string().min(5, 'Subject must be at least 5 characters'),
  message: z.string().min(20, 'Message must be at least 20 characters'),
});

const Contact = () => {
  const [isSubmitted, setIsSubmitted] = useState(false);

  const { register, handleSubmit, formState: { errors, isSubmitting }, reset } = useForm({
    resolver: zodResolver(contactSchema),
  });

  const onSubmit = async (data) => {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    console.log(data);
    setIsSubmitted(true);
    reset();
  };

  return (
    <div className="pt-24 pb-20 bg-secondary-50 dark:bg-secondary-950 min-h-screen transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up">
          <span className="text-primary-600 dark:text-primary-400 font-bold tracking-wider uppercase text-sm">Contact Us</span>
          <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mt-2 mb-4">Get in Touch</h1>
          <p className="text-secondary-600 dark:text-secondary-400 text-lg">
            Have questions or need assistance? Our team is here to help. Reach out to us via phone, email, or visit one of our centers.
          </p>
        </div>

        <div className="flex flex-col lg:flex-row gap-12 max-w-6xl mx-auto">

          {/* Contact Info */}
          <div className="lg:w-1/3 space-y-8 animate-fade-in delay-100">
            <div className="bg-white dark:bg-secondary-900 p-8 rounded-2xl border border-secondary-200 dark:border-secondary-700">
              <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-6">Contact Information</h3>

              <div className="space-y-6">
                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200 dark:border-primary-700/30">
                    <MapPin className="w-5 h-5" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-secondary-900 dark:text-white">Headquarters</h4>
                    <p className="text-secondary-600 dark:text-secondary-400 text-sm mt-1">
                      123 Medical Center Dr.<br />
                      Dhanmondi, Dhaka 1209
                    </p>
                  </div>
                </div>

                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200 dark:border-primary-700/30">
                    <Phone className="w-5 h-5" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-secondary-900 dark:text-white">Phone</h4>
                    <p className="text-secondary-600 dark:text-secondary-400 text-sm mt-1">
                      <a href="tel:+8801234567890" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">+880 1234 567 890</a>
                    </p>
                    <p className="text-secondary-500 dark:text-secondary-500 text-xs mt-1">Mon-Fri 8am-8pm</p>
                  </div>
                </div>

                <div className="flex items-start gap-4">
                  <div className="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200 dark:border-primary-700/30">
                    <Mail className="w-5 h-5" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-secondary-900 dark:text-white">Email</h4>
                    <p className="text-secondary-600 dark:text-secondary-400 text-sm mt-1">
                      <a href="mailto:info@citycare.com" className="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">info@citycare.com</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div className="bg-gradient-to-br from-primary-800 to-primary-700 dark:from-primary-900 dark:to-primary-800 p-8 rounded-2xl text-white relative overflow-hidden border border-white/10">
               <div className="absolute top-0 right-0 w-32 h-32 bg-primary-500 rounded-full blur-2xl opacity-30 -mr-10 -mt-10"></div>
               <div className="relative z-10">
                 <h3 className="text-xl font-bold mb-4 flex items-center gap-2">
                   <Clock className="w-5 h-5" /> Opening Hours
                 </h3>
                 <ul className="space-y-3 text-sm text-primary-50">
                   <li className="flex justify-between border-b border-primary-600/50 pb-2">
                     <span>Monday - Friday</span>
                     <span>8:00 AM - 10:00 PM</span>
                   </li>
                   <li className="flex justify-between border-b border-primary-600/50 pb-2">
                     <span>Saturday</span>
                     <span>9:00 AM - 8:00 PM</span>
                   </li>
                   <li className="flex justify-between">
                     <span>Sunday</span>
                     <span>10:00 AM - 6:00 PM</span>
                   </li>
                 </ul>
                 <div className="mt-6 pt-4 border-t border-primary-600/50">
                   <p className="text-xs text-primary-200">
                     * Emergency Department is open 24/7
                   </p>
                 </div>
               </div>
            </div>
          </div>

          {/* Contact Form */}
          <div className="lg:w-2/3 animate-fade-in delay-200">
            <div className="bg-white dark:bg-secondary-900 p-8 md:p-10 rounded-2xl border border-secondary-200 dark:border-secondary-700 h-full">
              {isSubmitted ? (
                <div className="flex flex-col items-center justify-center h-full text-center py-20">
                  <div className="w-20 h-20 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center text-green-600 dark:text-green-500 mb-6 animate-fade-in-up border border-green-200 dark:border-green-500/20">
                    <CheckCircle className="w-10 h-10" />
                  </div>
                  <h3 className="text-2xl font-bold text-secondary-900 dark:text-white mb-2">Message Sent!</h3>
                  <p className="text-secondary-600 dark:text-secondary-400 max-w-md mb-8">
                    Thank you for reaching out. We have received your message and will get back to you within 24 hours.
                  </p>
                  <Button onClick={() => setIsSubmitted(false)} variant="outline" className="border-secondary-300 dark:border-secondary-700 text-secondary-700 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-white hover:border-primary-500 hover:bg-secondary-50 dark:hover:bg-secondary-800">
                    Send Another Message
                  </Button>
                </div>
              ) : (
                <>
                  <h3 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6">Send us a Message</h3>
                  <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-2">
                        <label className="text-sm font-medium text-secondary-700 dark:text-secondary-400">Full Name</label>
                        <input
                          {...register('name')}
                          className={`w-full px-4 py-3 rounded-lg border ${errors.name ? 'border-red-500 focus:ring-red-500/20' : 'border-secondary-300 dark:border-secondary-700 focus:border-primary-500 focus:ring-primary-500/20'} outline-none focus:ring-4 transition-all bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:bg-white dark:focus:bg-secondary-950`}
                          placeholder="John Doe"
                        />
                        {errors.name && <p className="text-red-500 dark:text-red-400 text-xs">{errors.name.message}</p>}
                      </div>

                      <div className="space-y-2">
                        <label className="text-sm font-medium text-secondary-700 dark:text-secondary-400">Email Address</label>
                        <input
                          {...register('email')}
                          className={`w-full px-4 py-3 rounded-lg border ${errors.email ? 'border-red-500 focus:ring-red-500/20' : 'border-secondary-300 dark:border-secondary-700 focus:border-primary-500 focus:ring-primary-500/20'} outline-none focus:ring-4 transition-all bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:bg-white dark:focus:bg-secondary-950`}
                          placeholder="john@example.com"
                        />
                        {errors.email && <p className="text-red-500 dark:text-red-400 text-xs">{errors.email.message}</p>}
                      </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-2">
                        <label className="text-sm font-medium text-secondary-700 dark:text-secondary-400">Phone Number</label>
                        <input
                          {...register('phone')}
                          className={`w-full px-4 py-3 rounded-lg border ${errors.phone ? 'border-red-500 focus:ring-red-500/20' : 'border-secondary-300 dark:border-secondary-700 focus:border-primary-500 focus:ring-primary-500/20'} outline-none focus:ring-4 transition-all bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:bg-white dark:focus:bg-secondary-950`}
                          placeholder="+880 1234 567 890"
                        />
                        {errors.phone && <p className="text-red-500 dark:text-red-400 text-xs">{errors.phone.message}</p>}
                      </div>

                      <div className="space-y-2">
                        <label className="text-sm font-medium text-secondary-700 dark:text-secondary-400">Subject</label>
                        <input
                          {...register('subject')}
                          className={`w-full px-4 py-3 rounded-lg border ${errors.subject ? 'border-red-500 focus:ring-red-500/20' : 'border-secondary-300 dark:border-secondary-700 focus:border-primary-500 focus:ring-primary-500/20'} outline-none focus:ring-4 transition-all bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:bg-white dark:focus:bg-secondary-950`}
                          placeholder="Appointment Inquiry"
                        />
                        {errors.subject && <p className="text-red-500 dark:text-red-400 text-xs">{errors.subject.message}</p>}
                      </div>
                    </div>

                    <div className="space-y-2">
                      <label className="text-sm font-medium text-secondary-700 dark:text-secondary-400">Message</label>
                      <textarea
                        {...register('message')}
                        rows="5"
                        className={`w-full px-4 py-3 rounded-lg border ${errors.message ? 'border-red-500 focus:ring-red-500/20' : 'border-secondary-300 dark:border-secondary-700 focus:border-primary-500 focus:ring-primary-500/20'} outline-none focus:ring-4 transition-all bg-white dark:bg-secondary-950 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:bg-white dark:focus:bg-secondary-950 resize-none`}
                        placeholder="How can we help you?"
                      ></textarea>
                      {errors.message && <p className="text-red-500 dark:text-red-400 text-xs">{errors.message.message}</p>}
                    </div>

                    <div className="pt-2">
                      <Button type="submit" disabled={isSubmitting} className="w-full md:w-auto min-w-[160px] bg-primary-600 dark:bg-primary-600 text-white hover:bg-primary-700 dark:hover:bg-primary-700">
                        {isSubmitting ? (
                          <span className="flex items-center">
                            <Loader2 className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" />
                            Sending...
                          </span>
                        ) : (
                          <span className="flex items-center">
                            Send Message <Send className="w-4 h-4 ml-2" />
                          </span>
                        )}
                      </Button>
                    </div>
                  </form>
                </>
              )}
            </div>
          </div>

        </div>
      </div>
    </div>
  );
};

export default Contact;
