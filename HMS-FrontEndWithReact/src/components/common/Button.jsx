import React from 'react';
import PropTypes from 'prop-types';
import { Loader2 } from 'lucide-react';
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

const variants = {
  primary: 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-sm shadow-primary-900/20',
  secondary: 'bg-secondary-200 dark:bg-secondary-700 text-secondary-900 dark:text-white hover:bg-secondary-300 dark:hover:bg-secondary-800 focus:ring-secondary-500',
  outline: 'border-2 border-primary-600 dark:border-primary-500 text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-500/10 focus:ring-primary-500',
  ghost: 'text-primary-600 dark:text-primary-400 hover:bg-secondary-100 dark:hover:bg-white/5 focus:ring-primary-500',
  danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm shadow-red-900/20',
};

const sizes = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-base',
  lg: 'px-6 py-3 text-lg',
};

const Button = React.forwardRef(({
  className,
  variant = 'primary',
  size = 'md',
  isLoading = false,
  disabled = false,
  children,
  type = 'button',
  leftIcon,
  rightIcon,
  ...props
}, ref) => {
  return (
    <button
      ref={ref}
      type={type}
      disabled={disabled || isLoading}
      className={twMerge(
        'inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-secondary-900 disabled:opacity-50 disabled:pointer-events-none',
        variants[variant],
        sizes[size],
        className
      )}
      {...props}
    >
      {isLoading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
      {!isLoading && leftIcon && <span className="mr-2">{leftIcon}</span>}
      {children}
      {!isLoading && rightIcon && <span className="ml-2">{rightIcon}</span>}
    </button>
  );
});

Button.displayName = 'Button';

Button.propTypes = {
  className: PropTypes.string,
  variant: PropTypes.oneOf(['primary', 'secondary', 'outline', 'ghost', 'danger']),
  size: PropTypes.oneOf(['sm', 'md', 'lg']),
  isLoading: PropTypes.bool,
  disabled: PropTypes.bool,
  children: PropTypes.node,
  type: PropTypes.oneOf(['button', 'submit', 'reset']),
  leftIcon: PropTypes.node,
  rightIcon: PropTypes.node,
};

export default Button;
