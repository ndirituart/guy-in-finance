import { createRouter, createWebHistory } from 'vue-router';

//what the User sees when they visit the site
import HelloWorld from '@/components/HelloWorld.vue';
import HomePage from '@/components/HomePage.vue';
import AccountBalance from '@/components/AccountBalance.vue';
import DailyExpenses from '@/components/DailyExpenses.vue';
import FamilyFriends from '@/components/FamilyFriends.vue';
import LoansPage from '@/components/LoansPage.vue';
import PayBill from '@/components/PayBill.vue';
import UserSavings from '@/components/UserSavings.vue';
import UserProfile from '@/components/UserProfile.vue';
import LogIn from '@/components/LogIn.vue';
import SignUp from '@/components/SignUp.vue';
import DailyExpenseFunction from '@/components/DailyExpenseFunction.vue';
import DepositSavings from '@/components/DepositSavings.vue';
import WithdrawSavings from '@/components/WithdrawSavings.vue';

//what the Admin sees when they visit the site
import AdminDashboardHome from '../userinterface/admin/AdminDashboardHome.vue';
import LoanApprovals from '../userinterface/admin/LoanApprovals.vue';
import UserAccounts from '../userinterface/admin/UserAccounts.vue';
import OtpSupport from '../userinterface/admin/OtpSupport.vue';

const routes = [
  // --- Default Route:for System Admin
  { path: '/', redirect: '/admin' },

 //user routes
  

  //admin routes
  { path: '/admin', name: 'AdminHome', component: AdminDashboardHome },
  { path: '/admin/loan-approvals', name: 'LoanApprovals', component: LoanApprovals },
  { path: '/admin/user-accounts', name: 'UserAccounts', component: UserAccounts },
  { path: '/admin/otp-support', name: 'OtpSupport', component: OtpSupport },

  // --- Fallback/404 Route (Optional but Recommended) ---
  // This catches any unmatched routes and can display a 'Not Found' page
 // { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundComponent },
];

const router = createRouter({
  history: createWebHistory(), 
  routes, // Your defined routes
});

// --- Navigation Guard for Admin Access ---
// This is crucial for restricting access to admin routes
router.beforeEach((to, from, next) => {
  if (to.path.startsWith('/admin')) {
    // In a real application, you'd check a secure authentication token to lock out NON-Admins
    // For demonstration, we're using a localStorage flag.
    const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true';

    if (!isAdminLoggedIn) {
      alert('Access Denied: Admin privileges required to view this page!');
      // In a real app, you might redirect to a dedicated admin login page
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
