import { createRouter, createWebHistory } from 'vue-router';

// --- Import User-Facing Components ---
import Dashboard from '../userinterface/user/Dashboard.vue';
import LoanRequest from '../userinterface/user/LoanRequest.vue';
import UserSavings from '../userinterface/user/UserSavings.vue';
import DepositSavings from '../userinterface/user/DepositSavings.vue';
import WithdrawSavings from '../userinterface/user/WithdrawSavings.vue';

// --- Import Admin-Facing Components ---
// Assuming 'AdminDashboard.vue' is your AdminDashboardHome.vue
import AdminDashboardHome from '../userinterface/admin/AdminDashboardHome.vue';
import LoanApprovals from '../userinterface/admin/LoanApprovals.vue';
import UserAccounts from '../userinterface/admin/UserAccounts.vue';
import OtpSupport from '../userinterface/admin/OtpSupport.vue';

const routes = [
  // --- User Routes ---
  // Redirect the root path to the user dashboard by default
  { path: '/', redirect: '/user/dashboard' },
  { path: '/user/dashboard', name: 'UserDashboard', component: Dashboard },
  { path: '/user/loan-request', name: 'LoanRequest', component: LoanRequest },
  { path: '/user/savings', name: 'UserSavings', component: UserSavings },
  { path: '/user/deposit-savings', name: 'DepositSavings', component: DepositSavings },
  { path: '/user/withdraw-savings', name: 'WithdrawSavings', component: WithdrawSavings },

  // --- Admin Routes ---
  // The Admin Dashboard route you asked for
  { path: '/admin', name: 'AdminHome', component: AdminDashboardHome },
  { path: '/admin/loan-approvals', name: 'LoanApprovals', component: LoanApprovals },
  { path: '/admin/user-accounts', name: 'UserAccounts', component: UserAccounts },
  { path: '/admin/otp-support', name: 'OtpSupport', component: OtpSupport },

  // --- Fallback/404 Route (Optional) ---
  // This route will catch any undefined paths and display a Not Found component
  // { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundComponent },
];

const router = createRouter({
  history: createWebHistory(), // Uses HTML5 history API for clean URLs (e.g., yoursite.com/admin)
  routes, // Your defined routes
});

// --- Navigation Guards (for Authorization/Authentication) ---
// This is crucial for restricting access to admin routes
router.beforeEach((to, from, next) => {
  // Basic example: Check if the user is trying to access an admin route
  if (to.path.startsWith('/admin')) {
    // In a real application, you'd check a secure authentication token
    // For demonstration, let's use a localStorage flag.
    const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true';

    if (!isAdminLoggedIn) {
      alert('Access Denied: Admin privileges required to view this page!');
      // You might redirect to a login page instead of just stopping
      // Example: next('/admin/login');
      next(false); // Stop the navigation
    } else {
      next(); // Allow navigation if authenticated as admin
    }
  } else {
    next(); // For non-admin routes, proceed
  }
});


export default router; // Export the configured router instance
