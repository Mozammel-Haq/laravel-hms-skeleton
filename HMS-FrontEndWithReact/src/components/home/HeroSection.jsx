import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import {
    ArrowRight,
    Calendar,
    Phone,
    Activity,
    HeartPulse,
    Stethoscope,
    Star,
    Users,
    Truck,
    CheckCircle,
    Clock,
    Shield,
    Sparkles,
    TrendingUp,
    Award,
    MessageCircle,
    Video,
} from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

// Static 3D Card Illustration Component
const GraphicalIllustration = ({ type }) => {
    const renderContent = () => {
        switch (type) {
            case "booking":
                return (
                    <div className="relative w-full h-full flex items-center justify-center">
                        {/* Main Card */}
                        <div className="relative w-full max-w-md">
                            {/* Background Glow */}
                            <div className="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-teal-600/20 rounded-3xl blur-3xl scale-110"></div>

                            {/* Main Calendar Card */}
                            <div className="relative bg-white dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-3xl p-8 transition-all duration-300 hover:shadow-xl hover:scale-[1.01]">
                                {/* Header */}
                                <div className="flex items-center justify-between mb-6">
                                    <div className="flex items-center gap-3">
                                        <div className="bg-primary-600 p-3 rounded-xl">
                                            <Calendar className="w-6 h-6 text-white" />
                                        </div>
                                        <div>
                                            <h3 className="text-secondary-900 dark:text-white font-semibold text-lg">
                                                Book Appointment
                                            </h3>
                                            <p className="text-secondary-500 dark:text-secondary-400 text-sm">
                                                Select your preferred time
                                            </p>
                                        </div>
                                    </div>
                                    <Sparkles className="w-5 h-5 text-yellow-400" />
                                </div>

                                {/* Calendar Grid */}
                                <div className="bg-secondary-50 dark:bg-secondary-900/50 rounded-2xl p-4 mb-4">
                                    <div className="grid grid-cols-7 gap-2 mb-3">
                                        {[
                                            "S",
                                            "M",
                                            "T",
                                            "W",
                                            "T",
                                            "F",
                                            "S",
                                        ].map((day, i) => (
                                            <div
                                                key={i}
                                                className="text-center text-secondary-400 dark:text-secondary-500 text-xs font-medium"
                                            >
                                                {day}
                                            </div>
                                        ))}
                                    </div>
                                    <div className="grid grid-cols-7 gap-2">
                                        {[...Array(28)].map((_, i) => {
                                            const isSelected = i === 15;
                                            const isAvailable = [
                                                8, 9, 10, 15, 16, 17, 22, 23,
                                            ].includes(i);
                                            return (
                                                <div
                                                    key={i}
                                                    className={`aspect-square rounded-lg flex items-center justify-center text-sm transition-colors duration-200
                            ${
                                isSelected
                                    ? "bg-primary-600 text-white font-bold shadow-lg shadow-primary-900/20"
                                    : isAvailable
                                      ? "bg-secondary-200 dark:bg-secondary-600 text-secondary-700 dark:text-white hover:bg-primary-100 dark:hover:bg-primary-900/30 cursor-pointer"
                                      : "text-secondary-300 dark:text-secondary-700"
                            }`}
                                                >
                                                    {i + 1}
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>

                                {/* Time Slots */}
                                <div className="flex gap-2 flex-wrap">
                                    {[
                                        "9:00 AM",
                                        "11:00 AM",
                                        "2:00 PM",
                                        "4:00 PM",
                                    ].map((time, i) => (
                                        <div
                                            key={i}
                                            className={`px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 cursor-pointer ${
                                                i === 1
                                                    ? "bg-primary-600 text-white shadow-lg shadow-primary-900/20"
                                                    : "bg-secondary-100 dark:bg-secondary-700 text-secondary-600 dark:text-secondary-300 hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400"
                                            }`}
                                        >
                                            {time}
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* Static Stats */}
                            <div className="absolute -top-4 -right-4 bg-white dark:bg-secondary-800 p-3 rounded-2xl flex items-center gap-2 border border-secondary-100 dark:border-secondary-700">
                                <CheckCircle className="w-4 h-4 text-green-500" />
                                <span className="text-sm font-semibold text-secondary-700 dark:text-secondary-200">
                                    Available Today
                                </span>
                            </div>

                            <div className="absolute -bottom-4 -left-4 bg-purple-500 text-white px-4 py-2 rounded-xl font-semibold text-sm flex items-center gap-2">
                                <Clock className="w-4 h-4" />
                                Fast Booking
                            </div>
                        </div>
                    </div>
                );

            case "doctors":
                return (
                    <div className="relative w-full h-full flex items-center justify-center">
                        {/* Background Glow */}
                        <div className="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-teal-600/20 rounded-full blur-3xl scale-[1.0001]"></div>

                        <div className="relative w-full max-w-md">
                            {/* Main Doctor Cards Stack */}
                            <div className="relative h-96 group/stack">
                                {[
                                    {
                                        name: "Dr Rashed",
                                        specialty: "Cardiology",
                                        rating: 4.9,
                                        patients: "2.5k",
                                        color: "bg-primary-600",
                                        icon: HeartPulse,
                                    },
                                    {
                                        name: "Dr. Jubayer",
                                        specialty: "Neurology",
                                        rating: 4.8,
                                        patients: "1.8k",
                                        color: "bg-teal-600",
                                        icon: Activity,
                                    },
                                    {
                                        name: "Dr. Harun-or-Rashid",
                                        specialty: "Pediatrics",
                                        rating: 5.0,
                                        patients: "3.2k",
                                        color: "bg-indigo-600",
                                        icon: Stethoscope,
                                    },
                                ].map((doctor, i) => (
                                    <div
                                        key={i}
                                        className="absolute inset-x-0 bg-white dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-3xl p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-500/30 !opacity-100"
                                        style={{
                                            top: `${i * 80}px`,
                                            zIndex: i + 1,
                                            transform: `scale(${1 - i * 0.01}) translateY(${i * 10}px)`,
                                        }}
                                        onMouseEnter={(e) =>
                                            (e.currentTarget.style.zIndex =
                                                "50")
                                        }
                                        onMouseLeave={(e) =>
                                            (e.currentTarget.style.zIndex =
                                                String(i + 1))
                                        }
                                    >
                                        <div className="flex items-start gap-4">
                                            {/* Avatar */}
                                            <div
                                                className={`${doctor.color} p-4 rounded-2xl`}
                                            >
                                                <doctor.icon className="w-8 h-8 text-white" />
                                            </div>

                                            {/* Info */}
                                            <div className="flex-1">
                                                <h4 className="text-secondary-900 dark:text-white font-bold text-lg mb-1">
                                                    {doctor.name}
                                                </h4>
                                                <p className="text-secondary-500 dark:text-secondary-400 text-sm mb-3">
                                                    {doctor.specialty}
                                                </p>

                                                {/* Stats */}
                                                <div className="flex items-center gap-4">
                                                    <div className="flex items-center gap-1">
                                                        <Star className="w-4 h-4 text-yellow-400 fill-yellow-400" />
                                                        <span className="text-secondary-900 dark:text-white font-semibold text-sm">
                                                            {doctor.rating}
                                                        </span>
                                                    </div>
                                                    <div className="text-secondary-500 dark:text-secondary-400 text-sm">
                                                        {doctor.patients}{" "}
                                                        patients
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Action Buttons */}
                                            <div className="flex flex-col gap-2">
                                                <div className="bg-secondary-100 dark:bg-secondary-600 p-2 rounded-lg">
                                                    <MessageCircle className="w-4 h-4 text-secondary-600 dark:text-secondary-200" />
                                                </div>
                                                <div className="bg-secondary-100 dark:bg-secondary-600 p-2 rounded-lg">
                                                    <Video className="w-4 h-4 text-secondary-600 dark:text-secondary-200" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Static Badges */}
                            <div className="absolute -top-6 -right-6 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 z-10">
                                <Award className="w-5 h-5" />
                                Top Rated
                            </div>

                            <div className="absolute -bottom-6 -left-6 bg-white dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 px-4 py-2 rounded-xl flex items-center gap-2">
                                <Users className="w-5 h-5 text-blue-400" />
                                <div>
                                    <div className="text-secondary-900 dark:text-white font-bold text-lg">
                                        50+
                                    </div>
                                    <div className="text-secondary-500 dark:text-secondary-400 text-xs">
                                        Specialists
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                );

            case "emergency":
                return (
                    <div className="relative w-full h-full flex items-center justify-center">
                        {/* Background Glow */}
                        <div className="absolute inset-0 bg-gradient-to-br from-rose-600/10 to-red-700/10 rounded-full blur-3xl"></div>

                        <div className="relative w-full max-w-md">
                            {/* Emergency Dashboard */}
                            <div className="bg-white dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 rounded-3xl p-8 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                                {/* Header */}
                                <div className="flex items-center justify-between mb-6">
                                    <div className="flex items-center gap-3">
                                        <div className="relative">
                                            <div className="bg-rose-600 p-4 rounded-2xl shadow-lg shadow-rose-600/20">
                                                <Truck className="w-8 h-8 text-white" />
                                            </div>
                                        </div>
                                        <div>
                                            <h3 className="text-secondary-900 dark:text-white font-bold text-xl">
                                                Emergency Care
                                            </h3>
                                            <p className="text-green-500 dark:text-green-400 font-semibold text-sm flex items-center gap-1">
                                                <div className="w-2 h-2 bg-green-500 dark:bg-green-400 rounded-full animate-pulse" />
                                                Available 24/7
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {/* Quick Stats Grid */}
                                <div className="grid grid-cols-2 gap-3 mb-6">
                                    {[
                                        {
                                            label: "Response Time",
                                            value: "<5 min",
                                            icon: Clock,
                                            color: "text-teal-500 dark:text-teal-400",
                                        },
                                        {
                                            label: "Ambulances",
                                            value: "12",
                                            icon: Truck,
                                            color: "text-rose-500 dark:text-rose-400",
                                        },
                                        {
                                            label: "Success Rate",
                                            value: "99.8%",
                                            icon: TrendingUp,
                                            color: "text-green-500 dark:text-green-400",
                                        },
                                        {
                                            label: "On Duty",
                                            value: "24/7",
                                            icon: Shield,
                                            color: "text-purple-500 dark:text-purple-400",
                                        },
                                    ].map((stat, i) => (
                                        <div
                                            key={i}
                                            className="bg-secondary-50 dark:bg-secondary-700/50 rounded-2xl p-4 cursor-pointer transition-all duration-300 hover:bg-white dark:hover:bg-secondary-600 hover:shadow-lg hover:-translate-y-1 group"
                                        >
                                            <stat.icon
                                                className={`w-6 h-6 mb-2 ${stat.color} transition-transform duration-300 group-hover:scale-110`}
                                            />
                                            <div className="text-secondary-900 dark:text-white font-bold text-2xl mb-1">
                                                {stat.value}
                                            </div>
                                            <div className="text-secondary-500 dark:text-secondary-400 text-xs">
                                                {stat.label}
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* Emergency Button */}
                                <div className="w-full bg-gradient-to-r from-rose-500 to-red-600 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-3 cursor-pointer transition-all duration-300 hover:shadow-xl hover:shadow-rose-600/30 hover:scale-[1.02] active:scale-95">
                                    <Phone className="w-5 h-5 animate-bounce" />
                                    Call Emergency: 911
                                </div>
                            </div>

                            {/* Static Indicator */}
                            <div className="absolute -top-4 -right-4 bg-gradient-to-r from-green-400 to-emerald-500 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-green-500/20 hover:scale-110 transition-transform cursor-default">
                                <HeartPulse className="w-4 h-4" />
                                Live Support
                            </div>
                        </div>
                    </div>
                );

            default:
                return null;
        }
    };

    return (
        <div className="relative w-full h-full flex items-center justify-center p-4">
            <div className="w-full h-full flex items-center justify-center">
                {renderContent()}
            </div>
        </div>
    );
};

// Button Component
const Button = ({
    children,
    variant = "primary",
    size = "md",
    className = "",
    ...props
}) => {
    const baseStyles =
        "font-semibold rounded-xl transition-all duration-300 flex items-center justify-center";
    const variants = {
        primary: "bg-primary-600 text-white hover:bg-primary-700",
        outline:
            "border-2 border-secondary-900 text-secondary-900 hover:bg-secondary-900 hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-secondary-900",
    };
    const sizes = {
        md: "px-6 py-3",
        xl: "px-8 py-4 text-lg",
    };

    return (
        <button
            className={`${baseStyles} ${variants[variant]} ${sizes[size]} ${className}`}
            {...props}
        >
            {children}
        </button>
    );
};

const slides = [
    {
        id: 1,
        illustrationType: "booking",
        title: "Advanced Healthcare for Your Family",
        subtitle:
            "Experience world-class medical care with our integrated digital ecosystem. Expert doctors, state-of-the-art facilities, and compassionate care.",
        ctaPrimary: {
            text: "Book Appointment",
            icon: Calendar,
            path: "/portal/login",
        },
        ctaSecondary: {
            text: "Our Services",
            icon: ArrowRight,
            path: "/services",
        },
    },
    {
        id: 2,
        illustrationType: "doctors",
        title: "Expert Doctors You Can Trust",
        subtitle:
            "Connect with top specialists across various disciplines. Your health is our priority, and we are here to support you every step of the way.",
        ctaPrimary: { text: "Find a Doctor", icon: Activity, path: "/doctors" },
        ctaSecondary: { text: "Contact Us", icon: Phone, path: "/contact" },
    },
    {
        id: 3,
        illustrationType: "emergency",
        title: "24/7 Emergency Care & Support",
        subtitle:
            "We utilize the latest diagnostic and treatment technologies to ensure accurate results and the best possible outcomes for our patients.",
        ctaPrimary: { text: "Emergency Help", icon: Phone, path: "/contact" },
        ctaSecondary: {
            text: "Patient Portal",
            icon: ArrowRight,
            path: "/portal/login",
        },
    },
];

const HeroSection = () => {
    const [[page, direction], setPage] = useState([0, 0]);
    const [isPaused, setIsPaused] = useState(false);

    const slideIndex = ((page % slides.length) + slides.length) % slides.length;

    useEffect(() => {
        if (isPaused) return;
        const timer = setInterval(() => {
            paginate(1);
        }, 6000);
        return () => clearInterval(timer);
    }, [isPaused, page]);

    const paginate = (newDirection) => {
        setPage([page + newDirection, newDirection]);
    };

    const handleDragEnd = (event, info) => {
        const swipeThreshold = 10;
        if (info.offset.x < -swipeThreshold) {
            paginate(1);
        } else if (info.offset.x > swipeThreshold) {
            paginate(-1);
        }
    };

    const variants = {
        enter: (direction) => ({
            x: direction > 0 ? "100%" : "-100%",
            opacity: 0,
        }),
        center: {
            x: 0,
            opacity: 1,
        },
        exit: (direction) => ({
            x: direction < 0 ? "100%" : "-100%",
            opacity: 0,
        }),
    };

    return (
        <section
            className="relative min-h-screen flex flex-col overflow-hidden transition-colors duration-300 bg-gradient-to-br from-secondary-50 via-white to-secondary-100/50 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950"
            onMouseEnter={() => setIsPaused(true)}
            onMouseLeave={() => setIsPaused(false)}
        >
            {/* Animated Background Grid */}
            <div className="absolute inset-0 opacity-10 dark:opacity-20 pointer-events-none">
                <div
                    className="absolute inset-0"
                    style={{
                        backgroundImage:
                            "linear-gradient(var(--tw-gradient-stops))",
                    }}
                >
                    <div className="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.1)_1px,transparent_1px)] dark:bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:50px_50px]"></div>
                </div>
            </div>

            <AnimatePresence initial={false} custom={direction}>
                <motion.div
                    key={page}
                    custom={direction}
                    variants={variants}
                    initial="enter"
                    animate="center"
                    exit="exit"
                    transition={{
                        x: { type: "spring", stiffness: 300, damping: 30 },
                        opacity: { duration: 0.3 },
                    }}
                    className="absolute inset-0 w-full h-full"
                    drag="x"
                    dragConstraints={{ left: 0, right: 0 }}
                    onDragEnd={handleDragEnd}
                >
                    <div className="w-full h-full flex items-center justify-center px-4 sm:px-6 xl:px-8 py-20 xl:py-0 overflow-y-auto xl:overflow-hidden">
                        <div className="max-w-7xl mx-auto w-full grid grid-cols-1 xl:grid-cols-2 gap-12 items-center min-h-min xl:h-full mt-16 xl:mt-0">
                            {/* Content Column */}
                            <div className="flex flex-col justify-center z-20 text-center xl:text-left order-1 xl:order-1 pt-40 xl:pt-0">
                                <motion.div
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: 0.2 }}
                                >
                                    <h1 className="text-4xl sm:text-5xl xl:text-6xl font-bold text-secondary-900 dark:text-white mb-6 leading-tight">
                                        {slides[slideIndex].title}
                                    </h1>

                                    <p className="text-lg sm:text-xl text-secondary-600 dark:text-secondary-300 mb-8 leading-relaxed max-w-2xl mx-auto xl:mx-0">
                                        {slides[slideIndex].subtitle}
                                    </p>

                                    <div className="flex flex-col sm:flex-row gap-4 justify-center xl:justify-start">
                                        <Link
                                            to={
                                                slides[slideIndex].ctaPrimary
                                                    .path
                                            }
                                            className="w-full sm:w-auto"
                                        >
                                            <Button className="w-full sm:w-auto px-6 py-3 text-sm sm:text-base sm:px-8 sm:py-4 sm:text-lg hover:-translate-y-1">
                                                {React.createElement(
                                                    slides[slideIndex]
                                                        .ctaPrimary.icon,
                                                    {
                                                        className:
                                                            "w-5 h-5 mr-2",
                                                    },
                                                )}
                                                {
                                                    slides[slideIndex]
                                                        .ctaPrimary.text
                                                }
                                            </Button>
                                        </Link>
                                        <Link
                                            to={
                                                slides[slideIndex].ctaSecondary
                                                    .path
                                            }
                                            className="w-full sm:w-auto"
                                        >
                                            <Button
                                                variant="outline"
                                                className="w-full sm:w-auto px-6 py-3 text-sm sm:text-base sm:px-8 sm:py-4 sm:text-lg hover:-translate-y-1"
                                            >
                                                {
                                                    slides[slideIndex]
                                                        .ctaSecondary.text
                                                }
                                                {React.createElement(
                                                    slides[slideIndex]
                                                        .ctaSecondary.icon,
                                                    {
                                                        className:
                                                            "w-5 h-5 ml-2",
                                                    },
                                                )}
                                            </Button>
                                        </Link>
                                    </div>
                                </motion.div>
                            </div>

                            {/* Illustration Column */}
                            <div className="relative h-[400px] sm:h-[500px] xl:h-[600px] flex items-center justify-center order-2 xl:order-2 pb-10 xl:pb-0 mt-8 xl:mt-0">
                                <GraphicalIllustration
                                    type={slides[slideIndex].illustrationType}
                                />
                            </div>
                        </div>
                    </div>
                </motion.div>
            </AnimatePresence>

            {/* Slide Indicators */}
            <div className="absolute bottom-8 left-0 right-0 z-30 flex justify-center gap-3">
                {slides.map((_, index) => (
                    <button
                        key={index}
                        onClick={() =>
                            setPage([
                                page + (index - slideIndex),
                                index - slideIndex,
                            ])
                        }
                        className={`h-3 rounded-full transition-all duration-300 ${
                            index === slideIndex
                                ? "w-8 bg-primary-600"
                                : "w-3 bg-secondary-300 dark:bg-white/50 hover:bg-secondary-400 dark:hover:bg-white/80"
                        }`}
                        aria-label={`Go to slide ${index + 1}`}
                    />
                ))}
            </div>
        </section>
    );
};

export default HeroSection;
