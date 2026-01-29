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

  // Request Modal State
  const [isRequestModalOpen, setIsRequestModalOpen] = useState(false);
  const [selectedAppointment, setSelectedAppointment] = useState(null);
  const [requestType, setRequestType] = useState(null); // 'cancel' or 'reschedule'
  const [requestReason, setRequestReason] = useState("");
  const [desiredDate, setDesiredDate] = useState("");
  const [desiredTime, setDesiredTime] = useState("");
  const [submitting, setSubmitting] = useState(false);

  // console.log(user);
  const getAppointments = async () => {
    try {
      setLoading(true);

      // The clinic_id is handled by the API interceptor (X-Clinic-ID header),

      const response = await api.get(API_ENDPOINTS.PATIENT.APPOINTMENTS, {
        params: {
          patient_id: user?.id
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
      getAppointments();
    }
  }, [activeClinicId]);
  console.log(appointments);
  const filteredAppointments = appointments.filter((apt) => {
    const status = apt.status?.toLowerCase().trim();
    // console.log(`Appointment ${apt.id} status: ${status}, filter: ${filter}`);
    if (filter === "upcoming")
      return ["confirmed", "pending", "arrived"].includes(status);
    if (filter === "past")
      return ["completed", "cancelled"].includes(status);
    return true;
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
            className="block w-full pl-10 pr-3 py-2 border border-secondary-200 dark:border-secondary-800 rounded-md leading-5 bg-secondary-50 dark:bg-secondary-800 text-secondary-900 dark:text-white placeholder-secondary-400 dark:placeholder-secondary-500 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 sm:text-sm"
            placeholder="Search doctor or specialty..."
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
                              <>
                                <Button
                                  variant="outline"
                                  size="sm"
                                  className="flex-1 sm:flex-none min-w-[140px] font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-700 dark:hover:text-primary-300 hover:border-primary-300 dark:hover:border-primary-600 transition-colors"
                                  onClick={() => {
                                    navigate('/portal/appointments/book', {
                                      state: {
                                        prefill: {
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
                                <Button
                                  variant="danger"
                                  size="sm"
                                  className="flex-1 sm:flex-none min-w-[140px] font-semibold"
                                  onClick={() => openRequestModal(apt, 'cancel')}
                                  disabled={hasPendingRequest}
                                >
                                  Cancel
                                </Button>
                              </>
                            )}
                          </>
                        ) : (
                          <Button
                            variant="outline"
                            size="sm"
                            className="flex-1 sm:flex-none min-w-[140px] font-semibold hover:bg-secondary-50 dark:hover:bg-secondary-800 transition-colors"
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

    </div>
  );
};

export default Appointments;
