import React from 'react';
import { useParams, Link } from 'react-router-dom';
import { Heart, Brain, Bone, Baby, Eye, Activity, User, Thermometer, ArrowLeft, CheckCircle } from 'lucide-react';
import Button from '../../components/common/Button';

const ServiceDetail = () => {
  const { id } = useParams();

  // Mock data - in a real app this would come from an API or central config
  const services = {
    cardiology: {
      title: "Cardiology",
      icon: Heart,
      color: "bg-rose-500 dark:bg-rose-600",
      description: "Our Cardiology department offers comprehensive heart care services, from preventive screening to advanced interventional procedures.",
      features: [
        "24/7 Cardiac Emergency Care",
        "Non-invasive Cardiac Diagnostics",
        "Interventional Cardiology",
        "Cardiac Rehabilitation Program",
        "Heart Failure Clinic"
      ],
      doctors: ["Dr. Sarah Ahmed"]
    },
    neurology: {
      title: "Neurology",
      icon: Brain,
      color: "bg-purple-500",
      description: "We provide expert care for disorders of the brain, spine, and nervous system using state-of-the-art diagnostic and treatment technologies.",
      features: [
        "Stroke Unit",
        "Epilepsy Management",
        "Movement Disorders Clinic",
        "Neurophysiology Lab",
        "Headache & Migraine Clinic"
      ],
      doctors: ["Dr. David Kim"]
    },
    orthopedics: {
      title: "Orthopedics",
      icon: Bone,
      color: "bg-blue-500",
      description: "Our Orthopedics team specializes in the diagnosis and treatment of bone, joint, and muscle conditions, helping you regain mobility.",
      features: [
        "Joint Replacement Surgery",
        "Sports Medicine",
        "Spine Surgery",
        "Trauma & Fracture Care",
        "Pediatric Orthopedics"
      ],
      doctors: ["Dr. Michael Ross"]
    },
    pediatrics: {
      title: "Pediatrics",
      icon: Baby,
      color: "bg-emerald-500 dark:bg-emerald-600",
      description: "We offer specialized medical care for infants, children, and adolescents in a warm, child-friendly environment.",
      features: [
        "Well-baby Clinic",
        "Pediatric Intensive Care Unit (PICU)",
        "Vaccination Center",
        "Child Development Services",
        "Pediatric Surgery"
      ],
      doctors: ["Dr. Jessica Lee"]
    },
    ophthalmology: {
      title: "Ophthalmology",
      icon: Eye,
      color: "bg-yellow-500",
      description: "Complete eye care services ranging from routine vision exams to complex surgical procedures for vision correction.",
      features: [
        "Cataract Surgery",
        "Glaucoma Management",
        "Retina Services",
        "Pediatric Ophthalmology",
        "Laser Vision Correction"
      ],
      doctors: []
    },
    "emergency-care": {
      title: "Emergency Care",
      icon: Activity,
      color: "bg-red-600 dark:bg-red-700",
      description: "Our Emergency Department is staffed 24/7 by trauma specialists to handle critical medical situations with speed and precision.",
      features: [
        "24/7 Trauma Center",
        "Rapid Response Team",
        "Advanced Life Support Ambulances",
        "Dedicated Pediatric Emergency",
        "Stroke & Cardiac Pathways"
      ],
      doctors: []
    },
    "general-medicine": {
      title: "General Medicine",
      icon: User,
      color: "bg-primary-500 dark:bg-primary-600",
      description: "Comprehensive primary care services for preventive health, chronic disease management, and general wellness for adults and families.",
      features: [
        "Preventive Health Screenings",
        "Chronic Disease Management",
        "Immunizations",
        "Wellness Counseling",
        "Routine Check-ups"
      ],
      doctors: ["Dr. Rahim Khan"]
    },
    "internal-medicine": {
      title: "Internal Medicine",
      icon: Thermometer,
      color: "bg-teal-500 dark:bg-teal-600",
      description: "Expert diagnosis, treatment, and prevention of adult diseases with a focus on complex medical conditions and multi-system diseases.",
      features: [
        "Complex Diagnosis",
        "Geriatric Care",
        "Infectious Disease Management",
        "Endocrinology Support",
        "Rheumatology Services"
      ],
      doctors: []
    }
  };

  const service = services[id] || {
    title: "Service Not Found",
    description: "The service you are looking for does not exist.",
    features: [],
    color: "bg-secondary-500",
    icon: Activity
  };

  const Icon = service.icon;

  return (
    <div className="pt-20 min-h-screen bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300 max-w-7xl mx-auto">
      {/* Hero */}
      <div className=" border-b border-secondary-200 dark:border-secondary-800 py-12">
        <div className="container mx-auto px-4">
          <Link to="/services" className="inline-flex items-center text-secondary-500 hover:text-primary-600 mb-6 transition-colors">
            <ArrowLeft className="w-4 h-4 mr-2" />
            Back to Services
          </Link>
          <div className="flex items-center gap-6">
            <div className={`${service.color} w-16 h-16 rounded-2xl flex items-center justify-center`}>
              <Icon className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-3xl font-bold text-secondary-900 dark:text-white">{service.title}</h1>
              <p className="text-secondary-600 dark:text-secondary-400 mt-2 max-w-2xl">{service.description}</p>
            </div>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto px-4 py-12">
        <div className="grid md:grid-cols-3 gap-8">
          {/* Main Info */}
          <div className="md:col-span-2 space-y-8">
            <div className="bg-white dark:bg-secondary-900 rounded-xl p-8 border border-secondary-200 dark:border-secondary-800">
              <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-6">Key Features</h2>
              <div className="grid sm:grid-cols-2 gap-4">
                {service.features.map((feature, index) => (
                  <div key={index} className="flex items-start gap-3">
                    <CheckCircle className="w-5 h-5 text-primary-600 shrink-0 mt-0.5" />
                    <span className="text-secondary-700 dark:text-secondary-300">{feature}</span>
                  </div>
                ))}
              </div>
            </div>

            <div className="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-8 border border-primary-100 dark:border-primary-500/20">
              <h2 className="text-2xl font-bold text-secondary-900 dark:text-white mb-4">Why Choose Us?</h2>
              <p className="text-secondary-700 dark:text-secondary-300 leading-relaxed">
                Our {service.title} department is equipped with the latest medical technology and staffed by experienced professionals dedicated to your well-being. We prioritize patient comfort and successful outcomes in every treatment plan.
              </p>
            </div>
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            <div className="bg-white dark:bg-secondary-900 rounded-xl p-6 border border-secondary-200 dark:border-secondary-800">
              <h3 className="font-bold text-secondary-900 dark:text-white mb-4">Book an Appointment</h3>
              <p className="text-sm text-secondary-500 dark:text-secondary-400 mb-6">
                Schedule a consultation with our {service.title} specialists today.
              </p>
              <Link to="/portal/login">
                <Button className="w-full">Book Now</Button>
              </Link>
            </div>

            {service.doctors && service.doctors.length > 0 && (
              <div className="bg-white dark:bg-secondary-900 rounded-xl p-6 border border-secondary-200 dark:border-secondary-800">
                <h3 className="font-bold text-secondary-900 dark:text-white mb-4">Our Specialists</h3>
                <div className="space-y-4">
                  {service.doctors.map((doctor, idx) => (
                    <div key={idx} className="flex items-center gap-3 pb-3 border-b border-secondary-100 dark:border-secondary-800 last:border-0 last:pb-0">
                      <div className="w-10 h-10 bg-secondary-200 dark:bg-secondary-800 rounded-full flex items-center justify-center">
                        <User className="w-5 h-5 text-secondary-500" />
                      </div>
                      <div>
                        <div className="font-medium text-secondary-900 dark:text-white">{doctor}</div>
                        <div className="text-xs text-secondary-500 dark:text-secondary-400">{service.title}</div>
                      </div>
                    </div>
                  ))}
                </div>
                <Link to={`/doctors?specialty=${service.title}`} className="block mt-4 text-center text-sm text-primary-600 hover:underline">
                  View All Doctors
                </Link>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default ServiceDetail;
