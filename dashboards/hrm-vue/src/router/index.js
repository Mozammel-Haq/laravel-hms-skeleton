import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/forbidden',
    name: 'Forbidden',
    component: () => import('../views/Forbidden.vue')
  },
  {
    path: '/staff',
    name: 'StaffDirectory',
    component: () => import('../views/StaffDirectory.vue')
  },
  {
    path: '/staff/:id',
    name: 'StaffView',
    component: () => import('../views/StaffView.vue')
  },
  {
    path: '/leaves',
    redirect: '/hr/leaves/requests'
  },
  {
    path: '/hr/leaves/requests',
    name: 'LeaveRequests',
    component: () => import('../views/Leaves.vue')
  },
  {
    path: '/hr/leaves/approvals',
    name: 'LeaveApprovals',
    component: () => import('../views/LeaveApprovals.vue')
  },
  {
    path: '/hr/leaves/calendar',
    name: 'LeaveCalendar',
    component: () => import('../views/LeaveCalendar.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/hr/departments',
    name: 'Departments',
    component: () => import('../views/Departments.vue')
  },
  {
    path: '/hr/designations',
    name: 'Designations',
    component: () => import('../views/Designations.vue')
  },
  {
    path: '/hr/shifts',
    name: 'Shifts',
    component: () => import('../views/Shifts.vue')
  },
  {
    path: '/hr/profiles',
    name: 'Profiles',
    component: () => import('../views/Profiles.vue')
  },
  {
    path: '/hr/holidays',
    name: 'Holidays',
    component: () => import('../views/Holidays.vue')
  },
  {
    path: '/hr/shift-calendar',
    name: 'ShiftCalendar',
    component: () => import('../views/ShiftCalendar.vue')
  },
  {
    path: '/hr/leaves/types',
    name: 'LeaveTypes',
    component: () => import('../views/LeaveTypes.vue')
  },
  {
    path: '/hr/leaves/accruals',
    name: 'LeaveBalances',
    component: () => import('../views/LeaveAccruals.vue')
  },
  {
    path: '/hr/attendance',
    name: 'Attendance',
    component: () => import('../views/Attendance.vue')
  },
  {
    path: '/hr/timesheets',
    name: 'Timesheets',
    component: () => import('../views/Timesheets.vue')
  },
  {
    path: '/hr/overtime',
    name: 'Overtime',
    component: () => import('../views/Overtime.vue')
  },
  {
    path: '/hr/payroll/runs',
    name: 'PayrollRuns',
    component: () => import('../views/PayrollRuns.vue')
  },
  {
    path: '/hr/payroll/runs/:id',
    name: 'PayrollRunDetail',
    component: () => import('../views/PayrollRunDetail.vue')
  },
  {
    path: '/hr/payroll/payslips',
    name: 'Payslips',
    component: () => import('../views/Payslips.vue')
  },
  {
    path: '/hr/payroll/structure',
    name: 'SalaryStructure',
    component: () => import('../views/SalaryStructure.vue')
  },
  {
    path: '/hr/payroll/allowances',
    name: 'PayrollAllowances',
    component: () => import('../views/PayrollAllowances.vue')
  },
  {
    path: '/hr/payroll/deductions',
    name: 'PayrollDeductions',
    component: () => import('../views/PayrollDeductions.vue')
  },
  {
    path: '/hr/payroll/taxes',
    name: 'PayrollTaxes',
    component: () => import('../views/PayrollTaxes.vue')
  },
  {
    path: '/hr/recruitment/jobs',
    name: 'RecruitmentJobs',
    component: () => import('../views/RecruitmentJobs.vue')
  },
  {
    path: '/hr/recruitment/candidates',
    name: 'RecruitmentCandidates',
    component: () => import('../views/RecruitmentCandidates.vue')
  },
  {
    path: '/hr/recruitment/interviews',
    name: 'RecruitmentInterviews',
    component: () => import('../views/RecruitmentInterviews.vue')
  },
  {
    path: '/hr/recruitment/offers',
    name: 'RecruitmentOffers',
    component: () => import('../views/RecruitmentOffers.vue')
  },
  {
    path: '/hr/recruitment/onboarding',
    name: 'RecruitmentOnboarding',
    component: () => import('../views/RecruitmentOnboarding.vue')
  },
  {
    path: '/hr/training/courses',
    name: 'TrainingCourses',
    component: () => import('../views/TrainingCourses.vue')
  },
  {
    path: '/hr/training/sessions',
    name: 'TrainingSessions',
    component: () => import('../views/TrainingSessions.vue')
  },
  {
    path: '/hr/training/evaluations',
    name: 'TrainingEvaluations',
    component: () => import('../views/TrainingEvaluations.vue')
  },
  {
    path: '/hr/performance/kpis',
    name: 'PerformanceKpis',
    component: () => import('../views/PerformanceKpis.vue')
  },
  {
    path: '/hr/performance/goals',
    name: 'PerformanceGoals',
    component: () => import('../views/PerformanceGoals.vue')
  },
  {
    path: '/hr/performance/reviews',
    name: 'PerformanceReviews',
    component: () => import('../views/PerformanceReviews.vue')
  },
  {
    path: '/hr/performance/appraisals',
    name: 'PerformanceAppraisals',
    component: () => import('../views/PerformanceAppraisals.vue')
  },
  {
    path: '/hr/compliance/policies',
    name: 'CompliancePolicies',
    component: () => import('../views/CompliancePolicies.vue')
  },
  {
    path: '/hr/compliance/documents',
    name: 'ComplianceDocuments',
    component: () => import('../views/ComplianceDocuments.vue')
  },
  {
    path: '/hr/compliance/letters',
    name: 'ComplianceLetters',
    component: () => import('../views/ComplianceLetters.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
