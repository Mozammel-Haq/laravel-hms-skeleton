import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { Link } from 'react-router-dom';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { AlertCircle, ArrowLeft, Mail } from 'lucide-react';
import Button from '../../components/common/Button';
import Logo from '../../components/common/Logo';

const forgotPasswordSchema = z.object({
  email: z.string().email('Please enter a valid email address'),
});

const ForgotPassword = () => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSuccess, setIsSuccess] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(forgotPasswordSchema),
  });

  const onSubmit = async (data) => {
    setIsSubmitting(true);
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    console.log('Reset password for:', data.email);
    setIsSubmitting(false);
    setIsSuccess(true);
  };

  return (
    <div className="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden transition-colors duration-300">
      {/* Background Gradients */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl opacity-30 -mr-20 -mt-20 pointer-events-none"></div>
      <div className="absolute bottom-0 left-0 w-80 h-80 bg-teal-600/10 rounded-full blur-3xl opacity-20 -ml-20 -mb-20 pointer-events-none"></div>

      <div className="sm:mx-auto sm:w-full sm:max-w-md relative z-10 mt-3 flex flex-col items-center">
        <h2 className="text-center text-3xl font-bold tracking-tight text-secondary-900 dark:text-white mt-3">
          Reset your password
        </h2>
        <p className="mt-2 text-center text-sm text-secondary-600 dark:text-secondary-400">
          Enter your email address and we'll send you a link to reset your password.
        </p>
      </div>

      <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div className="bg-white dark:bg-secondary-900 py-8 px-4 sm:rounded-2xl sm:px-10 border border-secondary-200 dark:border-secondary-800 transition-colors duration-300">

          {isSuccess ? (
            <div className="text-center">
              <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                <Mail className="h-6 w-6 text-green-600 dark:text-green-400" />
              </div>
              <h3 className="text-lg font-medium text-secondary-900 dark:text-white">Check your email</h3>
              <p className="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                We've sent a password reset link to your email address.
              </p>
              <div className="mt-6">
                <Link to="/portal/login">
                  <Button className="w-full">Return to Sign in</Button>
                </Link>
              </div>
            </div>
          ) : (
            <form className="space-y-6" onSubmit={handleSubmit(onSubmit)}>
              {/* Email Field */}
              <div>
                <label htmlFor="email" className="block text-sm font-medium text-secondary-700 dark:text-secondary-300">
                  Email address
                </label>
                <div className="mt-1">
                  <input
                    id="email"
                    type="email"
                    autoComplete="email"
                    className={`block w-full appearance-none rounded-lg border px-3 py-2 placeholder-secondary-400 focus:outline-none sm:text-sm bg-secondary-50 dark:bg-secondary-950 text-secondary-900 dark:text-white transition-colors ${
                      errors.email
                        ? 'border-red-500/50 focus:border-red-500 focus:ring-red-500/20'
                        : 'border-secondary-200 dark:border-secondary-700 focus:border-primary-500 focus:ring-primary-500/20 focus:bg-white dark:focus:bg-secondary-950'
                    }`}
                    {...register('email')}
                  />
                  {errors.email && (
                    <p className="mt-2 text-sm text-red-500 dark:text-red-400 flex items-center">
                      <AlertCircle className="w-4 h-4 mr-1" />
                      {errors.email.message}
                    </p>
                  )}
                </div>
              </div>

              <div>
                <Button
                  type="submit"
                  variant="primary"
                  className="w-full justify-center py-3"
                  isLoading={isSubmitting}
                >
                  Send Reset Link
                </Button>
              </div>
            </form>
          )}

          <div className="mt-6">
            <div className="relative">
              <div className="absolute inset-0 flex items-center">
                <div className="w-full border-t border-secondary-200 dark:border-secondary-800" />
              </div>
              <div className="relative flex justify-center text-sm">
                <span className="bg-white dark:bg-secondary-900 px-2 text-secondary-500">
                  <Link to="/portal/login" className="flex items-center gap-1 font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500 transition-colors">
                    <ArrowLeft className="w-4 h-4" /> Back to Sign in
                  </Link>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ForgotPassword;
