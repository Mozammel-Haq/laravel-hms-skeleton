import React, { useEffect, useState } from "react";
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
} from "lucide-react";
import Button from "../../components/common/Button";
import { useAuth } from "../../context/AuthContext";
import api from "../../services/api";
import API_ENDPOINTS from "../../services/endpoints";
import { useClinic } from "../../context/ClinicContext";
import MedicalLoader from "../../components/loaders/MedicalLoader";

const Appointments = () => {
  const navigate = useNavigate();
  const [filter, setFilter] = useState("upcoming");
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const { user } = useAuth();
  const {activeClinicId} = useClinic();

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

  // console.log(user);
  const getAppointments = async () => {
    try {
      setLoading(true);

      const response = await api.get(API_ENDPOINTS.PATIENT.APPOINTMENTS, {
        params: {
          patient_id: user?.id,
          search: searchTerm,
          status: filter === "upcoming" ? "upcoming" : (filter === "past" ? "past" : filter) // Backend handles 'upcoming' logic?
          // Wait, backend logic for 'status' in AppointmentsApiController was:
          // if ($request->filled('status') && $request->status !== 'all') { $query->where('status', $request->status); }
          // The backend doesn't seem to have 'upcoming' logic in the summary I saw.
          // Let's re-read AppointmentsApiController to be sure.
        }
      });

      setAppointments(response.data.appointments || []);
    } catch (error) {
      console.error("Failed to fetch appointments", error);
      setAppointments([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (activeClinicId) {
        const timer = setTimeout(() => {
            getAppointments();
        }, 500);
        return () => clearTimeout(timer);
    }
  }, [activeClinicId, searchTerm, filter]);
  console.log(appointments);
  const filteredAppointments = appointments.filter((apt) => {
    const status = apt.status?.toLowerCase().trim();
    const matchesFilter = filter === "upcoming"
      ? ["confirmed", "pending", "arrived"].includes(status)
      : ["completed", "cancelled"].includes(status);

    if (!matchesFilter) return false;

    if (!searchTerm) return true;

    const term = searchTerm.toLowerCase();
    const doctorName = apt.doctor?.user?.name?.toLowerCase() || "";
    const type = apt.type?.toLowerCase() || "";
    const notes = apt.notes?.toLowerCase() || "";
    const date = apt.appointment_date || "";

    return doctorName.includes(term) || type.includes(term) || notes.includes(term) || date.includes(term);
  });

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
            const isUpcoming = ["confirmed", "pending", "arrived"].includes(status);
            const hasPendingRequest = apt.requests && apt.requests.length > 0;
            const pendingRequestType = hasPendingRequest ? apt.requests[0].type : null;

            return (
              <div
                key={apt.id}
                className="group bg-white dark:bg-secondary-900 rounded-xl border border-secondary-200 dark:border-secondary-700 overflow-hidden hover:shadow-sm hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-200"
              >
                <div className="p-5 sm:p-6">
                  <div className="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    {/* Date Badge */}
                    <div className="flex-shrink-0">
                      <div className="inline-flex sm:flex flex-col items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-2xl border-2 border-primary-200 dark:border-primary-700 p-4 min-w-[88px] shadow-sm">
                        <span className="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wide">
                          {month}
                        </span>
                        <span className="text-3xl font-bold text-primary-700 dark:text-primary-300 leading-none my-1">
                          {day}
                        </span>
                        <span className="text-xs font-medium text-primary-600/70 dark:text-primary-400/70">
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
                              {apt.start_time} – {apt.end_time}
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

                      {/* Actions */}
                      <div className="flex flex-wrap gap-2 pt-3 border-t border-secondary-100 dark:border-secondary-800">
                        {isUpcoming ? (
                          <>
                            {status === 'pending' && (
                              <Button
                                variant="outline"
                                size="sm"
                                className="flex-1 sm:flex-none min-w-[140px] font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-700 dark:hover:text-primary-300 hover:border-primary-300 dark:hover:border-primary-600 transition-colors"
                                onClick={() => {
                                  navigate('/portal/appointments/book', {
                                    state: {
                                      prefill: {
                                        appointmentId: apt.id,
                                        doctorId: apt?.doctor?.id || apt?.doctor_id,
                                        departmentId: apt?.department_id || apt?.doctor?.primary_department_id,
                                        date: apt.appointment_date,
                                        time: apt.start_time,
                                        visitMode: apt.appointment_type
                                      }
                                    }
                                  });
                                }}
                                disabled={hasPendingRequest}
                              >
                                <Calendar className="w-4 h-4 mr-2" />
                                Reschedule
                              </Button>
                            )}

                            {status === 'pending' && (
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
                  {selectedAppointment?.appointment_date} at {selectedAppointment?.start_time}
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
                            <span>{summaryData.appointment.start_time} - {summaryData.appointment.end_time}</span>
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
