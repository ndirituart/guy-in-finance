import { createRouter, createWebHistory } from 'vue-router';

// --- Import Admin-Facing Components (assuming they are in src/components within adminprofile project) ---
// Paths are relative to adminprofile/src/router/index.js
import AdminDashboardHome from '../components/AdminDashboardHome.vue';
import LoanApprovals from '../components/LoanApprovals.vue';
import UserAccounts from '../components/UserAccounts.vue';
import OtpSupport from '../components/OtpSupport.vue';
import AdminDashboard from '@/components/AdminDashboard.vue';
import LoansApproval from '@/components/LoansApproval.vue';

const routes = [
  // --- Default Route: Redirect root path '/' to Admin Dashboard ---
  // This makes your admin dashboard the first page seen in the adminprofile project
  { path: '/', redirect: '/admin' },

  // --- Admin Routes within the Admin Profile Project ---
  { path: '/admin', name: 'AdminHome', component: AdminDashboard },
  { path: '/admin/loan-approvals', name: 'LoanApprovals', component: LoansApproval },
  { path: '/admin/user-accounts', name: 'UserAccounts', component: UserAccounts },
  { path: '/admin/otp-support', name: 'OtpSupport', component: OtpSupport },

  // --- Fallback/404 Route (Optional but Recommended) ---
  // This will catch any unmatched routes within the adminprofile project
  // { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundComponent },
];

const router = createRouter({
  history: createWebHistory(), // Uses HTML5 history API for clean URLs
  routes, // Your defined routes
});

// --- Navigation Guard for Admin Access (still relevant for this project) ---
router.beforeEach((to, from, next) => {
  // This guard ensures only "admin" users (based on localStorage flag) can access admin routes.
  // In a real system, this would involve a secure backend check.
  if (to.path.startsWith('/admin')) {
    const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true'; // Mock check

    if (!isAdminLoggedIn) {
      alert('Access Denied: Admin privileges required to view this page!');
      // You might redirect to an admin login page here specific to the adminprofile project
      // Example: next('/admin-login');
      next(false); // Stop the navigation
    } else {
      next(); // Allow navigation if authenticated as admin
    }
  } else {
    next(); // For non-admin routes (if you add any), proceed
  }
});

export default router; // Export the configured router instance
