import React, { useCallback, useEffect, useRef, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import {
  Calendar,
  Clock,
  MapPin,
  Search,
  Filter,
  Plus,
  FileText,
  User,
  X,
  Activity,
  Pill,
  Stethoscope,
  AlertCircle,
  Video,
  CreditCard,
} from "lucide-react";
import Button from "../../components/common/Button";
import { useAuth } from "../../context/AuthContext";
import { useUI } from "../../context/UIContext";
import api from "../../services/api";
import API_ENDPOINTS from "../../services/endpoints";
import { useClinic } from "../../context/ClinicContext";
import MedicalLoader from "../../components/loaders/MedicalLoader";

const getYmdFromUnknown = (value) => {
  if (!value) return null;
  const str = String(value);
  const ymd = str.split("T")[0].split(" ")[0];
  if (!/^\d{4}-\d{2}-\d{2}$/.test(ymd)) return null;
  return ymd;
};

const getHmFromUnknownTime = (value) => {
  if (!value) return null;
  const str = String(value);
  const timePart = str.includes("T") ? str.split("T")[1] : str;
  const cleaned = timePart.split(".")[0].replace("Z", "").split(" ")[0];
  const [hh, mm] = cleaned.split(":");
  if (!hh || !mm) return null;
  return { hh, mm };
};

const buildLocalDateTime = (dateValue, timeValue) => {
  const ymd = getYmdFromUnknown(timeValue) ?? getYmdFromUnknown(dateValue);
  const hm = getHmFromUnknownTime(timeValue);
  if (!ymd || !hm) return null;

  const [y, m, d] = ymd.split("-").map((v) => parseInt(v, 10));
  const hour = parseInt(hm.hh, 10);
  const minute = parseInt(hm.mm, 10);

  const dt = new Date(y, m - 1, d, hour, minute, 0, 0);
  if (Number.isNaN(dt.getTime())) return null;
  return dt;
};

const formatTime12h = (dt) => {
  try {
    return new Intl.DateTimeFormat(undefined, {
      hour: "numeric",
      minute: "2-digit",
      hour12: true,
    }).format(dt);
  } catch (_e) {
    return "";
  }
};

const isAppointmentPassed = (apt) => {
  try {
    const status = apt.status?.toLowerCase().trim();
    const isActiveStatus = ["confirmed", "pending", "arrived"].includes(status);
    if (!isActiveStatus) return false;
    const startAt = buildLocalDateTime(apt.appointment_date, apt.start_time);
    const endAt = buildLocalDateTime(apt.appointment_date, apt.end_time);
    const now = new Date();
    if (endAt) return endAt < now;
    if (startAt) return startAt < now;
    return false;
  } catch {
    return false;
  }
};

const Appointments = () => {
  const navigate = useNavigate();
  const [filter, setFilter] = useState("upcoming");
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const { user } = useAuth();
  const {activeClinicId} = useClinic();
  const { addToast } = useUI();
  const [processingPayment, setProcessingPayment] = useState(null);

  const handlePay = async (invoiceId, gateway) => {
    setProcessingPayment(invoiceId);
    try {
        const response = await api.post(API_ENDPOINTS.PATIENT.PAY_INVOICE(invoiceId), {
            gateway: gateway
        });

        if (response.data.payment_url) {
            window.location.href = response.data.payment_url;
        } else {
            addToast('error', 'Failed to initiate payment');
        }
    } catch (error) {
        console.error('Payment error', error);
        addToast('error', error.response?.data?.message || 'Payment initialization failed');
    } finally {
        setProcessingPayment(null);
    }
  };

  // Summary Modal State
  const [isSummaryModalOpen, setIsSummaryModalOpen] = useState(false);
  const [summaryLoading, setSummaryLoading] = useState(false);
  const [summaryData, setSummaryData] = useState(null);

  // Request Modal State
  const [isRequestModalOpen, setIsRequestModalOpen] = useState(false);
  const [selectedAppointment, setSelectedAppointment] = useState(null);
  const [requestType, setRequestType] = useState(null); // 'cancel' or 'reschedule'
  const [requestReason, setRequestReason] = useState("");
  const [desiredDate, setDesiredDate] = useState("");
  const [desiredTime, setDesiredTime] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");

  const requestSeqRef = useRef(0);
  const abortControllerRef = useRef(null);

  const getAppointments = useCallback(async () => {
    if (!activeClinicId || !user?.id) {
      setAppointments([]);
      setLoading(false);
      return;
    }

    const requestSeq = ++requestSeqRef.current;
    if (abortControllerRef.current) {
      abortControllerRef.current.abort();
    }

    const controller = new AbortController();
    abortControllerRef.current = controller;

    try {
      setLoading(true);

      const statusParam = filter === "upcoming" ? "upcoming" : filter === "past" ? "past" : "all";

      const response = await api.get(API_ENDPOINTS.PATIENT.APPOINTMENTS, {
        params: {
          patient_id: user.id,
          search: searchTerm,
          status: statusParam,
        },
        signal: controller.signal,
      });

      if (controller.signal.aborted || requestSeq !== requestSeqRef.current) {
        return;
      }

      const serverAppointments = Array.isArray(response?.data?.appointments)
        ? response.data.appointments
        : [];

      const uniqueById = Array.from(
        new Map(serverAppointments.map((apt) => [apt?.id, apt])).values()
      ).filter((apt) => apt && apt.id != null);

      setAppointments(uniqueById);
    } catch (error) {
      if (controller.signal.aborted) {
        return;
      }
      console.error("Failed to fetch appointments", error);
      setAppointments([]);
    } finally {
      if (!controller.signal.aborted && requestSeq === requestSeqRef.current) {
        setLoading(false);
      }
    }
  }, [activeClinicId, filter, searchTerm, user?.id]);

  useEffect(() => {
    const delayMs = searchTerm ? 400 : 0;
    const timer = setTimeout(() => {
      getAppointments();
    }, delayMs);

    return () => clearTimeout(timer);
  }, [getAppointments, searchTerm]);

  useEffect(() => {
    return () => {
      if (abortControllerRef.current) {
        abortControllerRef.current.abort();
      }
    };
  }, []);

  const appointmentsToRender = Array.isArray(appointments) ? appointments : [];
  const filteredAppointments =
    filter === "upcoming"
      ? appointmentsToRender.filter((apt) => !isAppointmentPassed(apt))
      : appointmentsToRender;

  const getDateParts = (dateString) => {
    const d = new Date(dateString);
    return {
      year: d.getFullYear(),
      month: d.toLocaleString("en-US", { month: "short" }),
      day: d.getDate(),
    };
  };

  const getSpecializations = (doctor) => {
    try {
      if (!doctor?.specialization) return "";

      // If it's already an array (e.g. from JSON cast in Laravel)
      if (Array.isArray(doctor.specialization)) {
        const first = doctor.specialization[0];
        // Check if nested array like [["Mental Health", "Psychiatry"]]
        if (Array.isArray(first)) {
            return first.slice(0, 2).join(", ");
        }
        // Flat array like ["Mental Health", "Psychiatry"]
        return doctor.specialization.slice(0, 2).join(", ");
      }

      // Fallback for string format
      const raw = doctor.specialization[0];
      if (!raw) return "";
      const parsed = JSON.parse(raw);
      return parsed.slice(0, 2).join(", ");
    } catch {
      return "";
    }
  };

  const openRequestModal = (appointment, type) => {
    setSelectedAppointment(appointment);
    setRequestType(type);
    setIsRequestModalOpen(true);
    setRequestReason("");
    setDesiredDate("");
    setDesiredTime("");
  };

  const closeRequestModal = () => {
    setIsRequestModalOpen(false);
    setSelectedAppointment(null);
    setRequestType(null);
  };

  const openSummaryModal = async (appointment) => {
    setSelectedAppointment(appointment);
    setIsSummaryModalOpen(true);
    setSummaryLoading(true);
    try {
        const response = await api.get(API_ENDPOINTS.PATIENT.APPOINTMENT_DETAILS(appointment.id));
        setSummaryData(response.data);
    } catch (error) {
        console.error("Failed to fetch appointment details", error);
        // Fallback to basic data if API fails or not implemented yet
        setSummaryData({ appointment });
    } finally {
        setSummaryLoading(false);
    }
  };

  const closeSummaryModal = () => {
    setIsSummaryModalOpen(false);
    setSelectedAppointment(null);
    setSummaryData(null);
  };

  const handleSubmitRequest = async (e) => {
    e.preventDefault();
    if (!selectedAppointment) return;

    try {
      setSubmitting(true);
      await api.post(API_ENDPOINTS.PATIENT.APPOINTMENT_REQUESTS, {
        appointment_id: selectedAppointment.id,
        type: requestType,
        reason: requestReason,
        desired_date: requestType === 'reschedule' ? desiredDate : null,
        desired_time: requestType === 'reschedule' ? desiredTime : null,
      });

      alert("Request submitted successfully");
      closeRequestModal();
      getAppointments();
    } catch (error) {
      console.error("Failed to submit request", error);
      alert(error.response?.data?.message || "Failed to submit request");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-secondary-900 dark:text-white">
            My Appointments
          </h1>
          <p className="text-secondary-500 dark:text-secondary-400 text-sm mt-1">
            Manage your upcoming visits and view history.
          </p>
        </div>
        <Link to="/portal/appointments/book">
          <Button leftIcon={<Plus className="w-4 h-4" />}>
            Book Appointment
          </Button>
        </Link>
      </div>

      {/* Filters */}
      <div className="bg-white dark:bg-secondary-900 p-4 rounded-lg border border-secondary-200 dark:border-secondary-800 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div className="flex bg-secondary-100 dark:bg-secondary-800 rounded-lg p-1 border border-secondary-200 dark:border-secondary-800">
          <button
            onClick={() => setFilter("upcoming")}
            className={`px-4 py-2 text-sm font-medium rounded-md transition-all ${
              filter === "upcoming"
                ? "bg-white dark:bg-primary-600 text-primary-600 dark:text-white"
                : "text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-200 dark:hover:bg-secondary-700"
            }`}
          >
            Upcoming
          </button>
          <button
            onClick={() => setFilter("past")}
            className={`px-4 py-2 text-sm font-medium rounded-md transition-all ${
              filter === "past"
                ? "bg-white dark:bg-primary-600 text-primary-600 dark:text-white"
                : "text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-200 dark:hover:bg-secondary-700"
            }`}
          >
            Past History
          </button>
        </div>

        <div className="relative w-full sm:w-64">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search className="h-4 w-4 text-secondary-400 dark:text-secondary-500" />
          </div>
          <input
            type="text"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            placeholder="Search doctor, date..."
            className="block w-full pl-10 pr-3 py-2 border border-secondary-200 dark:border-secondary-800 rounded-md leading-5 bg-secondary-50 dark:bg-secondary-800 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 sm:text-sm"
          />
        </div>
      </div>


      {/* Appointments List */}

      <div className="space-y-3">
        {loading ? (
          <div className="flex items-center justify-center py-16">
            <MedicalLoader text="Loading appointments..." />
          </div>
        ) : filteredAppointments.length > 0 ? (
          filteredAppointments.map((apt) => {
            const { year, month, day } = getDateParts(apt.appointment_date);
            const status = apt.status?.toLowerCase().trim();
            const hasPendingRequest = apt.requests && apt.requests.length > 0;
            const pendingRequestType = hasPendingRequest ? apt.requests[0].type : null;
            const startAt = buildLocalDateTime(apt.appointment_date, apt.start_time);
            const endAt = buildLocalDateTime(apt.appointment_date, apt.end_time);
            const now = new Date();

            // Invoice Logic
            const invoice = apt.visit?.invoices?.length > 0 ? apt.visit.invoices[0] : null;
            const isUnpaid = invoice && ['unpaid', 'partial'].includes(invoice.status?.toLowerCase());

            // Telemedicine: Check if joinable (15 mins before start until end time)
            const isOnline = apt.appointment_type?.toLowerCase() === 'online' || !!apt.meeting_link;

            const joinStatus = (() => {
                if (!isOnline) return 'not_online';
                if (status !== 'confirmed' && status !== 'arrived' && status !== 'pending') return 'not_active';
                if (status === 'pending') return 'pending_confirmation';
                if (!startAt || !endAt) return 'error';

                if (now > endAt) return 'ended';
                if (!invoice) return 'awaiting_invoice';
                if (['unpaid', 'partial'].includes(invoice.status?.toLowerCase())) return 'payment_required';
                if (!apt.meeting_link) return 'no_link';

                const joinWindowOpensAt = new Date(startAt.getTime() - 15 * 60 * 1000);

                if (now < joinWindowOpensAt) return 'too_early';

                if (status !== 'confirmed' && status !== 'arrived') return 'pending_confirmation';

                return 'joinable';
            })();

            // Check if appointment time has passed
            const isActiveStatus = ["confirmed", "pending", "arrived"].includes(status);
            const isTimePassed =
              isActiveStatus &&
              ((endAt && endAt < now) || (!endAt && startAt && startAt < now));
            const canPay = !!isUnpaid && status !== "cancelled" && (!isOnline || !isTimePassed);

            return (
              <div
                key={apt.id}
                className={`group bg-white dark:bg-secondary-900 rounded-xl border overflow-hidden hover:shadow-sm transition-all duration-200 ${
                    isTimePassed
                    ? 'border-red-200 dark:border-red-900/50 hover:border-red-300 dark:hover:border-red-800'
                    : 'border-secondary-200 dark:border-secondary-700 hover:border-primary-400 dark:hover:border-primary-500'
                }`}
              >
                <div className="p-5 sm:p-6">
                  <div className="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    {/* Date Badge */}
                    <div className="flex-shrink-0">
                      <div className={`inline-flex sm:flex flex-col items-center justify-center bg-gradient-to-br rounded-2xl border-2 p-4 min-w-[88px] shadow-sm ${
                          isTimePassed
                          ? 'from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-red-200 dark:border-red-700'
                          : 'from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 border-primary-200 dark:border-primary-700'
                      }`}>
                        <span className={`text-xs font-bold uppercase tracking-wide ${
                            isTimePassed ? 'text-red-600 dark:text-red-400' : 'text-primary-600 dark:text-primary-400'
                        }`}>
                          {month}
                        </span>
                        <span className={`text-3xl font-bold leading-none my-1 ${
                            isTimePassed ? 'text-red-700 dark:text-red-300' : 'text-primary-700 dark:text-primary-300'
                        }`}>
                          {day}
                        </span>
                        <span className={`text-xs font-medium ${
                            isTimePassed ? 'text-red-600/70 dark:text-red-400/70' : 'text-primary-600/70 dark:text-primary-400/70'
                        }`}>
                          {year}
                        </span>
                      </div>
                    </div>

                    {/* Main Content */}
                    <div className="flex-1 min-w-0">
                      {/* Header */}
                      <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                        <div className="flex-1 min-w-0">
                          <div className="flex items-center gap-2 mb-2">
                            <h3 className="text-lg sm:text-xl font-bold text-secondary-900 dark:text-white truncate">
                              {apt.doctor?.user?.name}
                            </h3>
                            <span
                              className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border transition-colors ${
                                status === "confirmed"
                                  ? "bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-700"
                                  : status === "pending"
                                  ? "bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-700"
                                  : status === "arrived"
                                  ? "bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-700"
                                  : status === "completed"
                                  ? "bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-700"
                                  : "bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-700"
                              }`}
                            >
                              {apt.status}
                            </span>
                            {isTimePassed && (
                                <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-700">
                                    Passed
                                </span>
                            )}
                            {isUnpaid && (
                                <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700">
                                    Payment Due
                                </span>
                            )}
                          </div>

                          <div className="flex flex-wrap items-center gap-2 text-sm">
                            <span className="inline-flex items-center px-3 py-1 rounded-lg bg-secondary-100 dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 font-medium border border-secondary-200 dark:border-secondary-700">
                              {getSpecializations(apt.doctor)}
                            </span>
                            <span className="inline-flex items-center px-3 py-1 rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 font-medium border border-primary-200 dark:border-primary-700">
                              {apt.appointment_type}
                            </span>
                            {hasPendingRequest && (
                                <span className="inline-flex items-center px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-medium border border-amber-200 dark:border-amber-700">
                                  {pendingRequestType === 'cancel' ? 'Cancellation Pending' : 'Reschedule Pending'}
                                </span>
                            )}
                          </div>
                        </div>
                      </div>

                      {/* Details Grid */}
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <div className="flex items-start gap-3 p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-100 dark:border-secondary-700/50">
                          <div className="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-secondary-700 border border-secondary-200 dark:border-secondary-600">
                            <Clock className="w-4 h-4 text-primary-600 dark:text-primary-400" />
                          </div>
                          <div className="flex-1 min-w-0">
                            <p className="text-xs font-medium text-secondary-500 dark:text-secondary-400 mb-0.5">
                              Appointment Time
                            </p>
                            <p className="text-sm font-semibold text-secondary-900 dark:text-white">
                              {startAt && endAt
                                ? `${formatTime12h(startAt)} – ${formatTime12h(endAt)}`
                                : `${apt.start_time} – ${apt.end_time}`}
                            </p>
                          </div>
                        </div>

                        <div className="flex items-start gap-3 p-3 rounded-lg bg-secondary-50 dark:bg-secondary-800/50 border border-secondary-100 dark:border-secondary-700/50">
                          <div className="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-secondary-700 border border-secondary-200 dark:border-secondary-600">
                            <MapPin className="w-4 h-4 text-primary-600 dark:text-primary-400" />
                          </div>
                          <div className="flex-1 min-w-0">
                            <p className="text-xs font-medium text-secondary-500 dark:text-secondary-400 mb-0.5">
                              Location
                            </p>
                            <p className="text-sm font-semibold text-secondary-900 dark:text-white">
                              Room {apt.doctor?.consultation_room_number}, Floor {apt.doctor?.consultation_floor}
                            </p>
                          </div>
                        </div>
                      </div>

                      {isTimePassed && (
                        <div className="mb-4 p-3 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 rounded-lg flex items-center gap-3">
                            <AlertCircle className="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" />
                            <div className="text-sm text-red-700 dark:text-red-300">
                                <span className="font-semibold">Missed Appointment?</span> The scheduled time for this appointment has passed. Please reschedule or book a new appointment.
                            </div>
                        </div>
                      )}

                      {/* Actions */}
                      <div className="flex flex-wrap gap-2 pt-3 border-t border-secondary-100 dark:border-secondary-800">
                        {/* Payment Actions */}
                        {canPay && (
                             <div className="flex flex-wrap items-center gap-2 w-full sm:w-auto mb-2 sm:mb-0 mr-2">
                                 <Button
                                     size="sm"
                                     onClick={() => handlePay(invoice.id, 'stripe')}
                                     disabled={processingPayment === invoice.id}
                                     className="flex-1 sm:flex-none bg-indigo-600 hover:bg-indigo-700 text-white border-none"
                                     title="Pay with Stripe"
                                 >
                                     <CreditCard className="w-4 h-4 mr-2" />
                                     Stripe
                                 </Button>
                                 <Button
                                     size="sm"
                                     onClick={() => handlePay(invoice.id, 'sslcommerz')}
                                     disabled={processingPayment === invoice.id}
                                     className="flex-1 sm:flex-none bg-orange-600 hover:bg-orange-700 text-white border-none"
                                     title="Pay with SSLCommerz"
                                 >
                                     <CreditCard className="w-4 h-4 mr-2" />
                                     SSLCommerz
                                 </Button>
                                 <span className="text-sm font-bold text-secondary-700 dark:text-secondary-300 ml-1">
                                    ${Number(invoice.due_amount ?? invoice.total_amount).toFixed(2)}
                                 </span>
                             </div>
                        )}
                        {/* Telemedicine Actions */}
                        {joinStatus === 'joinable' && (
                          <a
                            href={apt.meeting_link}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm hover:shadow-md"
                          >
                            <Video className="w-4 h-4" />
                            Join Call
                          </a>
                        )}
                        {joinStatus === 'too_early' && (
                             <div className="flex items-center gap-2 px-4 py-2 bg-secondary-100 dark:bg-secondary-800 text-secondary-500 dark:text-secondary-400 text-sm font-medium rounded-lg cursor-not-allowed" title="Link becomes available 15 minutes before appointment">
                                <Video className="w-4 h-4" />
                                Available Soon
                             </div>
                        )}
                        {joinStatus === 'pending_confirmation' && (
                             <div className="flex items-center gap-2 px-4 py-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-sm font-medium rounded-lg cursor-not-allowed" title="Wait for appointment confirmation">
                                <Video className="w-4 h-4" />
                                Waiting Confirmation
                             </div>
                        )}
                        {joinStatus === 'awaiting_invoice' && (
                             <div className="flex items-center gap-2 px-4 py-2 bg-secondary-100 dark:bg-secondary-800 text-secondary-500 dark:text-secondary-400 text-sm font-medium rounded-lg cursor-not-allowed" title="Invoice will appear after confirmation">
                                <Video className="w-4 h-4" />
                                Invoice Pending
                             </div>
                        )}
                        {joinStatus === 'payment_required' && (
                             <div className="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 text-sm font-medium rounded-lg cursor-not-allowed" title="Pay the invoice to unlock the join link">
                                <Video className="w-4 h-4" />
                                Pay To Join
                             </div>
                        )}
                        {joinStatus === 'no_link' && (
                             <div className="flex items-center gap-2 px-4 py-2 bg-secondary-100 dark:bg-secondary-800 text-secondary-500 dark:text-secondary-400 text-sm font-medium rounded-lg cursor-not-allowed" title="Meeting link will be provided after payment">
                                <Video className="w-4 h-4" />
                                Waiting Link
                             </div>
                        )}

                        {filter === "upcoming" ? (
                          <>
                            {status === "pending" && !isTimePassed && (
                              <Button
                                variant="outline"
                                size="sm"
                                className="flex-1 sm:flex-none min-w-[140px] font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-700 dark:hover:text-primary-300 hover:border-primary-300 dark:hover:border-primary-600 transition-colors"
                                onClick={() => openRequestModal(apt, "reschedule")}
                                disabled={hasPendingRequest}
                              >
                                <Calendar className="w-4 h-4 mr-2" />
                                Reschedule
                              </Button>
                            )}

                            {isTimePassed && (
                              <Button
                                variant="outline"
                                size="sm"
                                className="flex-1 sm:flex-none min-w-[140px] font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-700 dark:hover:text-primary-300 hover:border-primary-300 dark:hover:border-primary-600 transition-colors"
                                onClick={() => navigate("/portal/appointments/book")}
                              >
                                <Calendar className="w-4 h-4 mr-2" />
                                Book New
                              </Button>
                            )}

                            {status === "pending" && !isTimePassed && (
                              <Button
                                variant="danger"
                                size="sm"
                                className="flex-1 sm:flex-none min-w-[140px] font-semibold"
                                onClick={() => openRequestModal(apt, 'cancel')}
                                disabled={hasPendingRequest}
                              >
                                Cancel
                              </Button>
                            )}
                          </>
                        ) : (
                          <Button
                            variant="outline"
                            size="sm"
                            className="flex-1 sm:flex-none min-w-[140px] font-semibold hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors"
                            onClick={() => openSummaryModal(apt)}
                          >
                            View Summary
                          </Button>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            );
          })
        ) : (
          <div className="text-center py-16 bg-gradient-to-br from-secondary-50 to-white dark:from-secondary-900 dark:to-secondary-900/50 rounded-2xl border-2 border-dashed border-secondary-300 dark:border-secondary-700">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-secondary-100 dark:bg-secondary-800 mb-4">
              <Calendar className="w-8 h-8 text-secondary-400 dark:text-secondary-500" />
            </div>
            <h3 className="text-lg font-bold text-secondary-900 dark:text-white mb-2">
              No appointments found
            </h3>
            <p className="text-sm text-secondary-600 dark:text-secondary-400 max-w-sm mx-auto">
              Try adjusting your filters or book a new appointment to get started.
            </p>
          </div>
        )}
      </div>

      {/* Request Modal */}
      {isRequestModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
          <div className="bg-white dark:bg-secondary-900 rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-secondary-200 dark:border-secondary-700">
            <div className="flex items-center justify-between p-4 border-b border-secondary-100 dark:border-secondary-800">
              <h3 className="text-lg font-bold text-secondary-900 dark:text-white">
                {requestType === 'cancel' ? 'Cancel Appointment' : 'Reschedule Appointment'}
              </h3>
              <button onClick={closeRequestModal} className="text-secondary-500 hover:text-secondary-700 dark:hover:text-secondary-300">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSubmitRequest} className="p-4 space-y-4">
              <div className="bg-secondary-50 dark:bg-secondary-800/50 p-3 rounded-lg text-sm">
                <p className="font-medium text-secondary-900 dark:text-white">
                  {selectedAppointment?.doctor?.user?.name}
                </p>
                <p className="text-secondary-500 dark:text-secondary-400">
                  {selectedAppointment?.appointment_date} at {formatTime12h(buildLocalDateTime(selectedAppointment?.appointment_date, selectedAppointment?.start_time))}
                </p>
              </div>

              {requestType === 'reschedule' && (
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-medium text-secondary-700 dark:text-secondary-300 mb-1">
                      Desired Date
                    </label>
                    <input
                      type="date"
                      required
                      min={new Date().toISOString().split('T')[0]}
                      value={desiredDate}
                      onChange={(e) => setDesiredDate(e.target.value)}
                      className="w-full rounded-lg border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-sm p-2"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-medium text-secondary-700 dark:text-secondary-300 mb-1">
                      Desired Time
                    </label>
                    <input
                      type="time"
                      required
                      value={desiredTime}
                      onChange={(e) => setDesiredTime(e.target.value)}
                      className="w-full rounded-lg border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-sm p-2"
                    />
                  </div>
                </div>
              )}

              <div>
                <label className="block text-xs font-medium text-secondary-700 dark:text-secondary-300 mb-1">
                  Reason for {requestType}
                </label>
                <textarea
                  required
                  value={requestReason}
                  onChange={(e) => setRequestReason(e.target.value)}
                  placeholder="Please explain why..."
                  rows={3}
                  className="w-full rounded-lg border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-sm p-2"
                />
              </div>

              <div className="flex gap-3 pt-2">
                <Button
                  type="button"
                  variant="outline"
                  className="flex-1"
                  onClick={closeRequestModal}
                >
                  Close
                </Button>
                <Button
                  type="submit"
                  className="flex-1"
                  disabled={submitting}
                >
                  {submitting ? 'Submitting...' : 'Submit Request'}
                </Button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Summary Modal */}
      {isSummaryModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
          <div className="bg-white dark:bg-secondary-900 rounded-xl shadow-xl max-w-2xl w-full overflow-hidden border border-secondary-200 dark:border-secondary-700 max-h-[90vh] flex flex-col">
            <div className="flex items-center justify-between p-4 border-b border-secondary-100 dark:border-secondary-800">
              <h3 className="text-lg font-bold text-secondary-900 dark:text-white">
                Appointment Summary
              </h3>
              <button onClick={closeSummaryModal} className="text-secondary-500 hover:text-secondary-700 dark:hover:text-secondary-300">
                <X className="w-5 h-5" />
              </button>
            </div>

            <div className="p-6 overflow-y-auto custom-scrollbar">
              {summaryLoading ? (
                <div className="flex justify-center py-8">
                  <MedicalLoader text="Loading details..." />
                </div>
              ) : summaryData?.appointment ? (
                <div className="space-y-6">
                  {/* Doctor & Time Info */}
                  <div className="flex flex-col sm:flex-row gap-4 p-4 rounded-xl bg-secondary-50 dark:bg-secondary-800/50">
                    <div className="flex items-center gap-3">
                      <div className="w-12 h-12 rounded-full bg-white dark:bg-secondary-700 flex items-center justify-center text-xl font-bold text-primary-600 dark:text-primary-400 border border-secondary-200 dark:border-secondary-600">
                        <User className="w-6 h-6" />
                      </div>
                      <div>
                        <h4 className="font-bold text-secondary-900 dark:text-white">
                          {summaryData.appointment.doctor?.user?.name}
                        </h4>
                        <p className="text-sm text-secondary-500 dark:text-secondary-400">
                          {getSpecializations(summaryData.appointment.doctor)}
                        </p>
                      </div>
                    </div>
                    <div className="sm:ml-auto flex flex-col items-start sm:items-end justify-center">
                        <div className="flex items-center gap-2 text-sm text-secondary-600 dark:text-secondary-300">
                            <Calendar className="w-4 h-4" />
                            <span>{summaryData.appointment.appointment_date}</span>
                        </div>
                        <div className="flex items-center gap-2 text-sm text-secondary-600 dark:text-secondary-300 mt-1">
                            <Clock className="w-4 h-4" />
                            <span>
                              {(() => {
                                const sdt = buildLocalDateTime(summaryData.appointment.appointment_date, summaryData.appointment.start_time);
                                const edt = buildLocalDateTime(summaryData.appointment.appointment_date, summaryData.appointment.end_time);
                                return sdt && edt
                                  ? `${formatTime12h(sdt)} - ${formatTime12h(edt)}`
                                  : `${summaryData.appointment.start_time} - ${summaryData.appointment.end_time}`;
                              })()}
                            </span>
                        </div>
                    </div>
                  </div>

                  {/* Vitals */}
                  {summaryData.appointment.visit?.vitals?.length > 0 && (() => {
                      const vitalRecord = summaryData.appointment.visit.vitals[0];
                      const displayVitals = [
                          { key: 'Blood Pressure', value: vitalRecord.blood_pressure, unit: 'mmHg' },
                          { key: 'Heart Rate', value: vitalRecord.heart_rate, unit: 'bpm' },
                          { key: 'Temperature', value: vitalRecord.temperature, unit: '°C' },
                          { key: 'Weight', value: vitalRecord.weight, unit: 'kg' },
                          { key: 'SpO2', value: vitalRecord.spo2, unit: '%' },
                      ].filter(v => v.value);

                      if (displayVitals.length === 0) return null;

                      return (
                          <div>
                              <h4 className="flex items-center gap-2 font-bold text-secondary-900 dark:text-white mb-3">
                                  <Activity className="w-5 h-5 text-rose-500" />
                                  Vitals Recorded
                              </h4>
                              <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                  {displayVitals.map((vital, idx) => (
                                      <div key={idx} className="p-3 rounded-lg border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800">
                                          <p className="text-xs text-secondary-500 dark:text-secondary-400 uppercase font-semibold">
                                              {vital.key}
                                          </p>
                                          <p className="font-bold text-secondary-900 dark:text-white">
                                              {vital.value} <span className="text-xs font-normal text-secondary-500">{vital.unit}</span>
                                          </p>
                                      </div>
                                  ))}
                              </div>
                          </div>
                      );
                  })()}

                  {/* Consultation Notes */}
                  {summaryData.appointment.visit?.consultation && (
                      <div>
                          <h4 className="flex items-center gap-2 font-bold text-secondary-900 dark:text-white mb-3">
                              <Stethoscope className="w-5 h-5 text-primary-500" />
                              Consultation Notes
                          </h4>
                          <div className="p-4 rounded-xl border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 text-sm text-secondary-700 dark:text-secondary-300 whitespace-pre-wrap">
                              {summaryData.appointment.visit.consultation.doctor_notes || summaryData.appointment.visit.consultation.notes || "No notes recorded."}
                          </div>
                      </div>
                  )}

                  {/* Prescriptions */}
                  {summaryData.appointment.visit?.consultation?.prescriptions?.length > 0 && (() => {
                      const prescriptions = summaryData.appointment.visit.consultation.prescriptions;
                      const allItems = prescriptions.flatMap(p => p.items || []);

                      if (allItems.length === 0) return null;

                      return (
                          <div>
                              <h4 className="flex items-center gap-2 font-bold text-secondary-900 dark:text-white mb-3">
                                  <Pill className="w-5 h-5 text-emerald-500" />
                                  Prescriptions
                              </h4>
                              <div className="space-y-2">
                                  {allItems.map((item, idx) => (
                                      <div key={idx} className="flex items-start gap-3 p-3 rounded-lg border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800">
                                          <div className="mt-1">
                                              <div className="w-2 h-2 rounded-full bg-emerald-500" />
                                          </div>
                                          <div>
                                              <p className="font-bold text-secondary-900 dark:text-white">
                                                  {item.medicine?.name || 'Unknown Medicine'}
                                              </p>
                                              <p className="text-sm text-secondary-600 dark:text-secondary-400">
                                                  {item.dosage} • {item.frequency} • {item.duration_days} days
                                              </p>
                                              {item.instructions && (
                                                  <p className="text-xs text-secondary-500 dark:text-secondary-500 mt-1 italic">
                                                      Note: {item.instructions}
                                                  </p>
                                              )}
                                          </div>
                                      </div>
                                  ))}
                              </div>
                          </div>
                      );
                  })()}

                  {/* Fallback if no visit data */}
                  {!summaryData.appointment.visit && (
                      <div className="text-center py-8 text-secondary-500 dark:text-secondary-400">
                          <FileText className="w-12 h-12 mx-auto mb-2 opacity-20" />
                          <p>No medical records found for this appointment yet.</p>
                      </div>
                  )}
                </div>
              ) : (
                <div className="text-center py-8 text-secondary-500">
                  Failed to load details.
                </div>
              )}
            </div>

            <div className="p-4 border-t border-secondary-100 dark:border-secondary-800 bg-secondary-50 dark:bg-secondary-800/50 flex justify-end">
              <Button onClick={closeSummaryModal}>Close</Button>
            </div>
          </div>
        </div>
      )}

    </div>
  );
};

export default Appointments;
