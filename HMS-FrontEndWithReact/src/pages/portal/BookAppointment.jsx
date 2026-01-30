import React, { useState, useEffect, useRef } from "react";
import { useForm } from "react-hook-form";
import { useNavigate, useLocation } from "react-router-dom";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import {
    Calendar as CalendarIcon,
    Clock,
    User,
    Info,
    CheckCircle,
    AlertCircle,
} from "lucide-react";
import {
    format,
    addDays,
    startOfToday,
    parseISO,
    subDays,
    isAfter,
} from "date-fns";
import Button from "../../components/common/Button";
import { useUI } from "../../context/UIContext";
import { useClinic } from "../../context/ClinicContext";
import api from "../../services/api";
import API_ENDPOINTS from "../../services/endpoints";
import { useAuth } from "../../context/AuthContext";

// Schema
const bookingSchema = z.object({
    department: z.string().min(1, "Please select a department"),
    doctor: z.string().min(1, "Please select a doctor"),
    date: z.string().min(1, "Please select a date"),
    time: z.string().min(1, "Please select a time slot"),
    type: z.enum(["new", "follow_up"], {
        required_error: "Please select appointment type",
    }),
    visitMode: z.enum(["in_person", "online"], {
        required_error: "Please select visit mode",
    }),
    reason: z.string().min(5, "Please provide a brief reason for visit"),
});

const BookAppointment = () => {
    const location = useLocation();
    const prefill = location.state?.prefill;
    const isReschedule = Boolean(prefill?.appointmentId);
    const [step, setStep] = useState(1);
    const [selectedDate, setSelectedDate] = useState(
        format(startOfToday(), "yyyy-MM-dd"),
    );
    const navigate = useNavigate();
    const { addToast } = useUI();
    const { activeClinic } = useClinic();
    const { user } = useAuth();
    const prefAppliedRef = useRef(false);
    // Data States
    const [doctors, setDoctors] = useState([]);
    const [departments, setDepartments] = useState([]);
    const [availableSlots, setAvailableSlots] = useState([]);
    const [isLoadingDoctors, setIsLoadingDoctors] = useState(false);
    const [isLoadingSlots, setIsLoadingSlots] = useState(false);
    const [isCheckingHistory, setIsCheckingHistory] = useState(false);
    const [fetchError, setFetchError] = useState(null);

    const {
        register,
        handleSubmit,
        watch,
        setValue,
        resetField,
        formState: { errors, isSubmitting },
    } = useForm({
        resolver: zodResolver(bookingSchema),
        defaultValues: {
            date: format(startOfToday(), "yyyy-MM-dd"),
            type: "new",
            visitMode: "in_person",
        },
    });

    const selectedDoctor = watch("doctor");
    const selectedDepartment = watch("department");
    const selectedTime = watch("time");

    // --- 1. Fetch Doctors & Departments ---
    useEffect(() => {
        const fetchDoctors = async () => {
            if (!activeClinic?.id) return;
            setIsLoadingDoctors(true);
            setFetchError(null);
            try {
                const response = await api.get(API_ENDPOINTS.PATIENT.DOCTORS);
                const { departments: depts = [] } = response.data;
                const { doctors: docs = [] } = response.data.clinics?.[0] || {};
                setDoctors(docs);
                setDepartments(depts);
            } catch (error) {
                setFetchError("Failed to load doctors. Please try again.");
                addToast("error", "Could not load doctors list.");
            } finally {
                setIsLoadingDoctors(false);
            }
        };
        fetchDoctors();
    }, [activeClinic?.id, addToast]);

    // --- 2. Filter Doctors by Selected Department ---
    const filteredDoctors = doctors.filter((doc) => {
        if (!selectedDepartment) return true;

        // Find the selected department object to get its name
        const selectedDeptObj = departments.find(
            (d) => String(d.id) === String(selectedDepartment),
        );

        // Match by ID (same clinic) OR Name (cross-clinic shared doctor)
        const matchesId =
            String(doc.primary_department_id) === String(selectedDepartment);
        const matchesName =
            selectedDeptObj && doc.department?.name === selectedDeptObj.name;

        return matchesId || matchesName;
    });

    // Keep doctor selection if it belongs to the selected department; otherwise clear

    // --- 3. Fetch Time Slots ---
    useEffect(() => {
        const fetchSlots = async () => {
            if (!selectedDoctor || !selectedDate) {
                setAvailableSlots([]);
                return;
            }
            setIsLoadingSlots(true);
            try {
                const response = await api.get(
                    API_ENDPOINTS.PATIENT.APPOINTMENT_SLOTS,
                    {
                        params: {
                            doctor_id: selectedDoctor,
                            date: selectedDate,
                            clinic_id: activeClinic?.id,
                        },
                    },
                );
                const slots = response.data.slots || response.data || [];
                setAvailableSlots(slots);
            } catch (error) {
                setAvailableSlots([]);
            } finally {
                setIsLoadingSlots(false);
            }
        };
        fetchSlots();
    }, [selectedDoctor, selectedDate, activeClinic?.id]);

    // --- 3.5 Validate Selected Time when Slots Change ---
    useEffect(() => {
        if (!selectedTime || isLoadingSlots) return;

        // If slots are loaded and selected time is not in them, clear it
        // We check both exact match and normalized match to be safe
        const normalizeTime = (t) => t?.substring(0, 5);
        const exists = availableSlots.some(
            (s) =>
                s.start === selectedTime ||
                normalizeTime(s.start) === normalizeTime(selectedTime),
        );

        if (availableSlots.length > 0 && !exists) {
            setValue("time", "");
        }
    }, [availableSlots, selectedTime, isLoadingSlots, setValue]);

    // --- 4. Auto-detect Appointment Type (New vs Follow-up) ---
    useEffect(() => {
        const checkHistory = async () => {
            if (!selectedDoctor) return;
            setIsCheckingHistory(true);
            try {
                const response = await api.get(
                    API_ENDPOINTS.PATIENT.APPOINTMENTS,
                );
                const appointments =
                    response.data.appointments || response.data || [];
                const twoWeeksAgo = subDays(new Date(), 14);
                const recentVisit = appointments.find((apt) => {
                    const aptDate = parseISO(apt.appointment_date || apt.date);
                    const aptDoctorId = apt.doctor_id || apt.doctor?.id;
                    return (
                        String(aptDoctorId) === String(selectedDoctor) &&
                        isAfter(aptDate, twoWeeksAgo) &&
                        apt.status === "completed"
                    );
                });
                if (recentVisit) {
                    setValue("type", "follow_up");
                } else {
                    setValue("type", "new");
                }
            } catch (error) {
                setValue("type", "new");
            } finally {
                setIsCheckingHistory(false);
            }
        };
        checkHistory();
    }, [selectedDoctor, setValue]);

    useEffect(() => {
        if (!prefill || prefAppliedRef.current) return;
        if (!departments.length || !doctors.length) return;
        if (prefill.departmentId)
            setValue("department", String(prefill.departmentId));
        if (prefill.doctorId) setValue("doctor", String(prefill.doctorId));
        if (prefill.date) {
            const formattedDate = format(new Date(prefill.date), "yyyy-MM-dd");
            setSelectedDate(formattedDate);
            setValue("date", formattedDate);
        }
        if (prefill.visitMode) setValue("visitMode", prefill.visitMode);
        prefAppliedRef.current = true;
        setStep(2);
    }, [prefill, departments, doctors, setValue]);

    useEffect(() => {
        if (!prefill || !availableSlots.length) return;
        // Normalize time formats (HH:mm:ss -> HH:mm)
        const normalizeTime = (t) => t?.substring(0, 5);
        const prefillTime = normalizeTime(prefill.time);

        const exists = availableSlots.some(
            (s) => normalizeTime(s.start) === prefillTime,
        );
        if (exists)
            setValue(
                "time",
                availableSlots.find(
                    (s) => normalizeTime(s.start) === prefillTime,
                )?.start,
            );
    }, [availableSlots, prefill, setValue]);

    useEffect(() => {
        // Wait for doctors to load
        if (doctors.length === 0) return;

        if (!selectedDoctor) return;

        const doc = getDoctorDetails(selectedDoctor);

        // Find the selected department object
        const selectedDeptObj = departments.find(
            (d) => String(d.id) === String(selectedDepartment),
        );

        // Check match
        const matchesId =
            doc &&
            String(doc.primary_department_id) === String(selectedDepartment);
        const matchesName =
            doc &&
            selectedDeptObj &&
            doc.department?.name === selectedDeptObj.name;

        // If doctor not found in list or department mismatch, clear selection
        if (!doc || (selectedDepartment && !matchesId && !matchesName)) {
            setValue("doctor", "");
        }
    }, [selectedDepartment, setValue, doctors, selectedDoctor, departments]);

    // --- 5. Submit Appointment ---
    const onSubmit = async (data) => {
        try {
            if (isReschedule) {
                // Handle Reschedule Request
                await api.post(API_ENDPOINTS.PATIENT.APPOINTMENT_REQUESTS, {
                    appointment_id: prefill.appointmentId,
                    type: "reschedule",
                    reason: data.reason,
                    desired_date: data.date,
                    desired_time: data.time?.substring(0, 5),
                });
                addToast(
                    "success",
                    "Reschedule request submitted successfully!",
                );
            } else {
                // Handle New Booking
                const selectedSlot = availableSlots.find(
                    (s) => s.start === data.time,
                );
                await api.post(API_ENDPOINTS.PATIENT.BOOK_APPOINTMENT, {
                    doctor_id: data.doctor,
                    patient_id: user?.id,
                    appointment_date: data.date,
                    start_time: data.time,
                    end_time: selectedSlot?.end,
                    appointment_type: data.visitMode, // 'in_person' or 'online'
                    reason_for_visit: `[${data.type === "new" ? "New Consultation" : "Follow-up Visit"}] ${data.reason}`,
                    department_id: data.department,
                    clinic_id: activeClinic?.id,
                    booking_source: "online",
                });
                addToast("success", "Appointment booked successfully!");
            }

            navigate("/portal/appointments");
        } catch (error) {
            console.error("Booking failed:", error);
            const msg =
                error.response?.data?.message || "Failed to process request";
            addToast("error", msg);
        }
    };

    // Generate next 14 days for date picker
    const dates = Array.from({ length: 14 }, (_, i) => {
        const date = addDays(startOfToday(), i);
        return {
            value: format(date, "yyyy-MM-dd"),
            label: format(date, "EEE, MMM d"),
            day: format(date, "d"),
            weekday: format(date, "EEE"),
        };
    });

    // Helper to get doctor details safely
    const getDoctorDetails = (id) => {
        return doctors.find((d) => String(d.id) === String(id));
    };

    return (
        <div className="max-w-4xl mx-auto">
            <div className="mb-8">
                <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">
                    Book New Appointment
                </h1>
                <p className="text-secondary-500 dark:text-secondary-400 mt-1">
                    Schedule a visit with our specialists at{" "}
                    {activeClinic?.name || "our clinic"}.
                </p>
            </div>

            <div className="bg-white dark:bg-secondary-900 rounded-lg border border-secondary-200 dark:border-secondary-800 overflow-hidden">
                {/* Progress Steps */}
                <div className="bg-secondary-50 dark:bg-secondary-900 border-b border-secondary-200 dark:border-secondary-800 p-4">
                    <div className="flex items-center justify-center space-x-4">
                        <div
                            className={`flex items-center ${step >= 1 ? "text-primary-600 dark:text-primary-400 font-bold" : "text-secondary-500"}`}
                        >
                            <div
                                className={`w-8 h-8 rounded-full flex items-center justify-center mr-2 border-2 ${step >= 1 ? "bg-primary-600 text-white border-primary-600" : "bg-secondary-200 dark:bg-secondary-800 border-secondary-300 dark:border-secondary-800"}`}
                            >
                                1
                            </div>
                            <span className="hidden sm:inline">
                                Specialty & Doctor
                            </span>
                        </div>
                        <div
                            className={`h-1 w-8 sm:w-12 ${step >= 2 ? "bg-primary-500/50" : "bg-secondary-200 dark:bg-secondary-700"}`}
                        ></div>
                        <div
                            className={`flex items-center ${step >= 2 ? "text-primary-600 dark:text-primary-400 font-bold" : "text-secondary-500"}`}
                        >
                            <div
                                className={`w-8 h-8 rounded-full flex items-center justify-center mr-2 border-2 ${step >= 2 ? "bg-primary-600 text-white border-primary-600" : "bg-secondary-200 dark:bg-secondary-800 border-secondary-300 dark:border-secondary-800"}`}
                            >
                                2
                            </div>
                            <span className="hidden sm:inline">
                                Date & Time
                            </span>
                        </div>
                        <div
                            className={`h-1 w-8 sm:w-12 ${step >= 3 ? "bg-primary-500/50" : "bg-secondary-200 dark:bg-secondary-700"}`}
                        ></div>
                        <div
                            className={`flex items-center ${step >= 3 ? "text-primary-600 dark:text-primary-400 font-bold" : "text-secondary-500"}`}
                        >
                            <div
                                className={`w-8 h-8 rounded-full flex items-center justify-center mr-2 border-2 ${step >= 3 ? "bg-primary-600 text-white border-primary-600" : "bg-secondary-200 dark:bg-secondary-800 border-secondary-300 dark:border-secondary-800"}`}
                            >
                                3
                            </div>
                            <span className="hidden sm:inline">Confirm</span>
                        </div>
                    </div>
                </div>

                <form onSubmit={handleSubmit(onSubmit)} className="p-6 sm:p-8">
                    {/* STEP 1: Department & Doctor */}
                    {step === 1 && (
                        <div className="space-y-6">
                            {fetchError && (
                                <div className="p-4 bg-red-50 text-red-700 rounded-md flex items-center">
                                    <AlertCircle className="w-5 h-5 mr-2" />{" "}
                                    {fetchError}
                                </div>
                            )}

                            {/* Department Selection */}
                            <div>
                                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">
                                    Select Department
                                </label>
                                <select
                                    {...register("department")}
                                    className="block w-full rounded-md border-secondary-300 dark:border-secondary-800 bg-white dark:bg-secondary-800 text-secondary-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 py-2.5 px-3 border"
                                    disabled={isLoadingDoctors}
                                >
                                    <option
                                        value=""
                                        className="bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white"
                                    >
                                        {isLoadingDoctors
                                            ? "Loading..."
                                            : "-- Choose Department --"}
                                    </option>
                                    {departments.map((dept) => (
                                        <option
                                            key={dept.id}
                                            value={dept.id}
                                            className="bg-white dark:bg-secondary-900 text-secondary-900 dark:text-white"
                                        >
                                            {dept.name}
                                        </option>
                                    ))}
                                </select>
                                {errors.department && (
                                    <p className="mt-1 text-sm text-red-500 dark:text-red-400">
                                        {errors.department.message}
                                    </p>
                                )}
                            </div>

                            {/* Doctor Selection */}
                            <div>
                                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">
                                    Select Doctor
                                </label>

                                {isLoadingDoctors ? (
                                    <div className="text-center py-8 text-secondary-500">
                                        Loading doctors...
                                    </div>
                                ) : filteredDoctors.length === 0 ? (
                                    <div className="text-center py-8 text-secondary-500 border rounded-lg border-dashed">
                                        {selectedDepartment
                                            ? "No doctors available in this department."
                                            : "Please select a department first."}
                                    </div>
                                ) : (
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {filteredDoctors.map((doc) => (
                                            <div
                                                key={doc.id}
                                                className={`relative rounded-lg border p-4 cursor-pointer hover:border-primary-500/50 transition-all ${
                                                    String(selectedDoctor) ===
                                                    String(doc.id)
                                                        ? "border-primary-500 bg-primary-50 dark:bg-primary-500/10 ring-1 ring-primary-500/50"
                                                        : "border-secondary-200 dark:border-secondary-800 bg-white dark:bg-secondary-800"
                                                }`}
                                                onClick={() =>
                                                    setValue(
                                                        "doctor",
                                                        String(doc.id),
                                                    )
                                                }
                                            >
                                                <div className="flex items-center space-x-4">
                                                    <div className="h-12 w-12 rounded-full bg-secondary-100 dark:bg-secondary-700 flex items-center justify-center text-secondary-500 dark:text-secondary-400 overflow-hidden">
                                                        {doc.profile_photo ? (
                                                            <img
                                                                src={`${import.meta.env.VITE_BACKEND_BASE_URL}/${doc.profile_photo}`}
                                                                alt={
                                                                    doc.user
                                                                        .name
                                                                }
                                                                className="w-full h-full object-cover"
                                                            />
                                                        ) : (
                                                            <User className="w-6 h-6" />
                                                        )}
                                                    </div>
                                                    <div className="flex-1">
                                                        <h3 className="font-bold text-secondary-900 dark:text-white">
                                                            Dr.{" "}
                                                            {doc.user?.name ||
                                                                doc.name}
                                                        </h3>
                                                        <p className="text-sm text-secondary-500 dark:text-secondary-400 mb-1">
                                                            {doc.department
                                                                ?.name ||
                                                                (Array.isArray(
                                                                    doc.specialization,
                                                                )
                                                                    ? doc.specialization.join(
                                                                          ", ",
                                                                      )
                                                                    : doc.specialization)}
                                                        </p>

                                                        <div className="text-xs text-secondary-500 space-y-1">
                                                            <div className="flex items-center gap-2">
                                                                <span className="font-medium text-secondary-700 dark:text-secondary-300">
                                                                    Room:
                                                                </span>
                                                                {doc.consultation_room_number ||
                                                                    "N/A"}{" "}
                                                                (
                                                                {doc.consultation_floor ||
                                                                    "GF"}
                                                                )
                                                            </div>

                                                            <div className="flex items-center gap-2 mt-1">
                                                                <span className="font-medium text-secondary-700 dark:text-secondary-300">
                                                                    Fee:
                                                                </span>
                                                                {doc.follow_up_fee &&
                                                                doc.follow_up_fee <
                                                                    doc.consultation_fee ? (
                                                                    <div className="flex items-center gap-2">
                                                                        <span className="line-through text-secondary-400 text-[10px]">
                                                                            $
                                                                            {
                                                                                doc.consultation_fee
                                                                            }
                                                                        </span>
                                                                        <span className="text-green-600 font-bold">
                                                                            $
                                                                            {
                                                                                doc.follow_up_fee
                                                                            }
                                                                        </span>
                                                                    </div>
                                                                ) : (
                                                                    <span className="font-semibold text-secondary-900 dark:text-white">
                                                                        $
                                                                        {doc.consultation_fee ||
                                                                            "0"}
                                                                    </span>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {String(selectedDoctor) ===
                                                    String(doc.id) && (
                                                    <div className="absolute top-2 right-2 text-primary-600 dark:text-primary-500">
                                                        <CheckCircle className="w-5 h-5" />
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                )}
                                {errors.doctor && (
                                    <p className="mt-1 text-sm text-red-500 dark:text-red-400">
                                        {errors.doctor.message}
                                    </p>
                                )}
                                <input type="hidden" {...register("doctor")} />
                            </div>

                            <div className="flex justify-end pt-4">
                                <Button
                                    onClick={(e) => {
                                        e.preventDefault();
                                        if (
                                            watch("department") &&
                                            watch("doctor")
                                        )
                                            setStep(2);
                                        else
                                            addToast(
                                                "error",
                                                "Please select department and doctor",
                                            );
                                    }}
                                    disabled={isLoadingDoctors}
                                >
                                    Next Step
                                </Button>
                            </div>
                        </div>
                    )}

                    {/* STEP 2: Date & Time */}
                    {step === 2 && (
                        <div className="space-y-8">
                            {/* Date Selection */}
                            <div>
                                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-3">
                                    Select Date
                                </label>
                                <div className="flex overflow-x-auto pb-4 gap-3 no-scrollbar">
                                    {dates.map((d) => (
                                        <div
                                            key={d.value}
                                            onClick={() => {
                                                setSelectedDate(d.value);
                                                setValue("date", d.value);
                                            }}
                                            className={`flex-shrink-0 w-16 h-20 rounded-lg border flex flex-col items-center justify-center cursor-pointer transition-all ${
                                                selectedDate === d.value
                                                    ? "bg-primary-600 text-white border-primary-600 transform scale-105"
                                                    : "bg-white dark:bg-secondary-800 text-secondary-500 dark:text-secondary-400 border-secondary-200 dark:border-secondary-800 hover:border-primary-500/50 hover:bg-secondary-50 dark:hover:bg-secondary-700"
                                            }`}
                                        >
                                            <span className="text-xs font-medium">
                                                {d.weekday}
                                            </span>
                                            <span className="text-xl font-bold">
                                                {d.day}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                                <input type="hidden" {...register("date")} />
                            </div>

                            {/* Time Slots */}
                            <div>
                                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-3">
                                    Select Time Slot
                                </label>

                                {isLoadingSlots ? (
                                    <div className="py-4 text-center text-secondary-500">
                                        Checking availability...
                                    </div>
                                ) : availableSlots.length === 0 ? (
                                    <div className="py-4 text-center text-orange-500 border border-orange-200 rounded-md bg-orange-50 dark:bg-orange-900/20">
                                        No available slots for this date. Please
                                        try another date.
                                    </div>
                                ) : (
                                    <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                        {availableSlots.map((slot) => (
                                            <button
                                                key={slot.start}
                                                type="button"
                                                onClick={() =>
                                                    setValue("time", slot.start)
                                                }
                                                className={`py-2 px-2 text-sm rounded-md border transition-colors ${
                                                    selectedTime === slot.start
                                                        ? "bg-primary-600 text-white border-primary-600"
                                                        : "bg-white dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 border-secondary-200 dark:border-secondary-700 hover:border-primary-500 hover:text-primary-600"
                                                }`}
                                            >
                                                {slot.label}
                                            </button>
                                        ))}
                                    </div>
                                )}

                                {errors.time && (
                                    <p className="mt-1 text-sm text-red-500 dark:text-red-400">
                                        {errors.time.message}
                                    </p>
                                )}
                            </div>

                            <div className="flex justify-between pt-4">
                                <Button
                                    variant="outline"
                                    onClick={() => setStep(1)}
                                >
                                    Back
                                </Button>
                                <Button
                                    onClick={(e) => {
                                        e.preventDefault();
                                        if (watch("date") && watch("time"))
                                            setStep(3);
                                        else
                                            addToast(
                                                "error",
                                                "Please select date and time",
                                            );
                                    }}
                                    disabled={
                                        isLoadingSlots ||
                                        availableSlots.length === 0
                                    }
                                >
                                    Next Step
                                </Button>
                            </div>
                        </div>
                    )}

                    {/* STEP 3: Confirm */}
                    {step === 3 && (
                        <div className="space-y-6">
                            <div className="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-500/20 rounded-lg p-6">
                                <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-4">
                                    Confirm Appointment Details
                                </h3>

                                {/* Appointment Type Info (Auto-detected) */}
                                <div className="mb-6 p-4 bg-white dark:bg-secondary-800 rounded-md border border-secondary-200 dark:border-secondary-700">
                                    <div className="flex items-center justify-between mb-2">
                                        <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300">
                                            Appointment Type
                                        </label>
                                        {isCheckingHistory && (
                                            <span className="text-xs text-primary-500 animate-pulse">
                                                Verifying history...
                                            </span>
                                        )}
                                    </div>

                                    <div
                                        className={`flex items-center justify-between p-3 rounded-lg border ${
                                            watch("type") === "follow_up"
                                                ? "bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800"
                                                : "bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-800"
                                        }`}
                                    >
                                        <div className="flex items-center space-x-3">
                                            <div
                                                className={`p-2 rounded-full ${
                                                    watch("type") ===
                                                    "follow_up"
                                                        ? "bg-green-100 text-green-600"
                                                        : "bg-primary-100 text-primary-600"
                                                }`}
                                            >
                                                {watch("type") ===
                                                "follow_up" ? (
                                                    <Clock className="w-5 h-5" />
                                                ) : (
                                                    <CheckCircle className="w-5 h-5" />
                                                )}
                                            </div>
                                            <div>
                                                <p className="font-bold text-secondary-900 dark:text-white">
                                                    {watch("type") === "new"
                                                        ? "New Consultation"
                                                        : "Follow-up Visit"}
                                                </p>
                                                <p className="text-xs text-secondary-500 dark:text-secondary-400">
                                                    {watch("type") === "new"
                                                        ? "Standard consultation fee applies."
                                                        : "Discounted follow-up fee applied based on your recent visit."}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            className={`text-lg font-bold ${
                                                watch("type") === "follow_up"
                                                    ? "text-green-600"
                                                    : "text-primary-600"
                                            }`}
                                        >
                                            $
                                            {watch("type") === "new"
                                                ? getDoctorDetails(
                                                      watch("doctor"),
                                                  )?.consultation_fee || 0
                                                : getDoctorDetails(
                                                      watch("doctor"),
                                                  )?.follow_up_fee ||
                                                  getDoctorDetails(
                                                      watch("doctor"),
                                                  )?.consultation_fee ||
                                                  0}
                                        </span>
                                    </div>
                                    <input
                                        type="hidden"
                                        {...register("type")}
                                    />
                                </div>

                                <div className="space-y-3">
                                    <div className="flex justify-between">
                                        <span className="text-secondary-600 dark:text-secondary-400">
                                            Doctor:
                                        </span>
                                        <span className="font-medium text-secondary-900 dark:text-white">
                                            Dr.{" "}
                                            {getDoctorDetails(watch("doctor"))
                                                ?.user?.name ||
                                                getDoctorDetails(
                                                    watch("doctor"),
                                                )?.name}
                                        </span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-secondary-600 dark:text-secondary-400">
                                            Date & Time:
                                        </span>
                                        <span className="font-medium text-secondary-900 dark:text-white">
                                            {watch("date")} at {watch("time")}
                                        </span>
                                    </div>
                                    <div className="flex justify-between border-t border-secondary-200 dark:border-secondary-700 pt-2 mt-2">
                                        <span className="text-secondary-900 dark:text-white font-bold">
                                            Estimated Fee:
                                        </span>
                                        <span className="font-bold text-primary-600 dark:text-primary-400 text-lg">
                                            $
                                            {watch("type") === "new"
                                                ? getDoctorDetails(
                                                      watch("doctor"),
                                                  )?.consultation_fee || 0
                                                : getDoctorDetails(
                                                      watch("doctor"),
                                                  )?.follow_up_fee ||
                                                  getDoctorDetails(
                                                      watch("doctor"),
                                                  )?.consultation_fee ||
                                                  0}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">
                                    Reason for Visit
                                </label>
                                <textarea
                                    {...register("reason")}
                                    rows="3"
                                    className="block w-full rounded-md border-secondary-300 dark:border-secondary-800 bg-white dark:bg-secondary-800 text-secondary-900 dark:text-white focus:border-primary-500 focus:ring-primary-500 p-3 border"
                                    placeholder="Please describe your symptoms or reason for appointment..."
                                ></textarea>
                                {errors.reason && (
                                    <p className="mt-1 text-sm text-red-500 dark:text-red-400">
                                        {errors.reason.message}
                                    </p>
                                )}
                            </div>

                            <div className="flex justify-between pt-4">
                                <Button
                                    variant="outline"
                                    onClick={() => setStep(2)}
                                >
                                    Back
                                </Button>
                                <Button type="submit" isLoading={isSubmitting}>
                                    Confirm Booking
                                </Button>
                            </div>
                        </div>
                    )}
                </form>
            </div>
        </div>
    );
};

export default BookAppointment;
