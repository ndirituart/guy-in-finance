// eslint-disable-next-line no-unused-vars
import { createRouter, createWebHistory } from 'vue-router';
import HelloWorld from '../components/HelloWorld.vue';
import HomePage from '../components/HomePage.vue';
import AccountBalance from '@/components/AccountBalance.vue';
import DailyExpenses from '@/components/DailyExpenses.vue';
import FamilyFriends from '@/components/FamilyFriends.vue';
import LoansPage from '@/components/LoansPage.vue';
import PayBill from '@/components/PayBill.vue';
import UserSavings from '@/components/UserSavings.vue';
import LogIn from '@/components/LogInPage.vue'
// eslint-disable-next-line no-unused-vars


import SignUp from '@/components/SignUpPage.vue'
import UserProfile from '@/components/UserProfile.vue';
// Future components
// import BudgetPage from '@/components/BudgetPage.vue';
// import MonthlyExpenses from '@/components/MonthlyExpenses.vue';
// import MonthlyIncome from '@/components/MonthlyIncome.vue';
// import MonthlySavings from '@/components/MonthlySavings.vue';

const routes = [
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
    path: '/user-savings',
    name: 'UserSavings',
    component: UserSavings
  },
  {
    path: '/login',
    name: 'LogIn',
    component: LogIn
   },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUp
  },
  {
    path: '/user-profile',
    name: 'UserProfile',
    component: UserProfile
  }
  // Future routes
  // {
  //   path: '/budget',
  //   name: 'BudgetPage',
  //   component: BudgetPage
  // },
  // {
  //   path: '/monthly-expenses',
  //   name: 'MonthlyExpenses',
  //   component: MonthlyExpenses
  // },
  // {
  //   path: '/monthly-income',
  //   name: 'MonthlyIncome',
  //   component: MonthlyIncome
  // },
  // {
  //   path: '/monthly-savings',
  //   name: 'MonthlySavings',
  //   component: MonthlySavings
  // },
  // {
  //   path: '/user-profile',
  //   name: 'User Profile',
  //   component: UserProfile
  // }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
