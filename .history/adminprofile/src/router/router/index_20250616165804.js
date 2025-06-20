import { createRouter, createWebHistory } from 'vue-router';

// --- Import General/User-Facing Components from '@/components' ---
// Assuming '@/components' maps to 'src/components'
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

// --- Import Admin-Facing Components from 'userinterface/admin' ---
import AdminDashboardHome from '../userinterface/admin/AdminDashboardHome.vue';
import LoanApprovals from '../userinterface/admin/LoanApprovals.vue';
import UserAccounts from '../userinterface/admin/UserAccounts.vue';
import OtpSupport from '../userinterface/admin/OtpSupport.vue';

const routes = [
  // --- New User/General Routes from your provided list ---
  {
    path: '/',
    name: 'HelloWorld',
    component: HelloWorld
  },
  {
    path: '/home',
    name: 'HomePage',
    component: HomePage
  },
  {
    path: '/account-balance',
    name: 'AccountBalance',
    component: AccountBalance
  },
  {
    path: '/daily-expenses',
    name: 'DailyExpenses',
    component: DailyExpenses
  },
  {
    path: '/family-friends',
    name: 'FamilyFriends',
    component: FamilyFriends
  },
  {
    path: '/loans',
    name: 'LoansPage',
    component: LoansPage
  },
  {
    path: '/pay-bill',
    name: 'PayBill',
    component: PayBill
  },
  {
    path: '/user-savings', // Renamed from '/user-savings' to match new import location
    name: 'UserSavings',
    component: UserSavings
  },
  {
    path: '/user-profile',
    name: 'UserProfile',
    component: UserProfile
  },
  {
    path: '/login',
    name: 'LogIn',
    component: LogIn,
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUp
  },
  {
    path: '/daily-expense-function',
    name: 'DailyExpenseFunction',
    component: DailyExpenseFunction
  },
  {
    path: '/deposit-savings', // Renamed from '/user/deposit-savings' to match new import location
    name: 'DepositSavings',
    component: DepositSavings
  },
  {
    path: '/withdraw-savings', // Renamed from '/user/withdraw-savings' to match new import location
    name: 'WithdrawSavings',
    component: WithdrawSavings
  },

  // --- Admin Routes (Preserved from previous setup) ---
  { path: '/admin', name: 'AdminHome', component: AdminDashboardHome },
  { path: '/admin/loan-approvals', name: 'LoanApprovals', component: LoanApprovals },
  { path: '/admin/user-accounts', name: 'UserAccounts', component: UserAccounts },
  { path: '/admin/otp-support', name: 'OtpSupport', component: OtpSupport },

  // --- Fallback/404 Route (Optional) ---
  // { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundComponent },
];

const router = createRouter({
  history: createWebHistory(), // Uses HTML5 history API for clean URLs
  routes, // Your defined routes
});

// --- Navigation Guard for Admin Access ---
// This remains crucial for restricting access to admin routes
router.beforeEach((to, from, next) => {
  if (to.path.startsWith('/admin')) {
    const isAdminLoggedIn = localStorage.getItem('isAdminLoggedIn') === 'true'; // Mock check

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
