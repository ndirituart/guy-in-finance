<template>
  <div class="admin-dashboard-home">
    <div class="header-container">
      <h1>Admin Dashboard</h1>
      <p class="current-date">{{ currentDate }}</p>
    </div>

    <div class="summary-section">
      <h2>System Overview</h2>
      <div class="metrics-grid">
        <div class="metric-card">
          <h3>Total Savings Across All Users</h3>
          <p class="metric-value">Ksh. {{ totalSavings.toFixed(2) }}</p>
        </div>
        <div class="metric-card">
          <h3>Total Outstanding Loans</h3>
          <p class="metric-value">Ksh. {{ totalOutstandingLoans.toFixed(2) }}</p>
        </div>
        <div class="metric-card">
          <h3>Pending Loan Requests</h3>
          <p class="metric-value">{{ pendingLoanRequestsCount }}</p>
        </div>
        <div class="metric-card">
          <h3>Active OTP Requests</h3>
          <p class="metric-value">{{ activeOtpRequestsCount }}</p>
        </div>
      </div>
    </div>

    <div class="admin-actions-container">
      <h2>Admin Actions</h2>
      <div class="options-grid">
        <div class="option-box" @click="navigateToLoanApprovals">
          <span class="icon">✅</span>
          <h3>Approve Loans</h3>
          <p>Review and approve pending loan requests.</p>
          <button @click.stop="navigateToLoanApprovals">Go to Approvals</button>
        </div>

        <div class="option-box" @click="navigateToUserAccounts">
          <span class="icon">👥</span>
          <h3>Manage User Accounts</h3>
          <p>Track user balances, loans, and assist with passwords.</p>
          <button @click.stop="navigateToUserAccounts">Go to Users</button>
        </div>

        <div class="option-box" @click="navigateToOtpSupport">
          <span class="icon">🔑</span>
          <h3>OTP Support</h3>
          <p>Assist users with One-Time Password requests.</p>
          <button @click.stop="navigateToOtpSupport">Go to OTP</button>
        </div>

        <div class="option-box">
            <span class="icon">💰</span>
            <h3>Loan Collections</h3>
            <p>Identify and follow up on overdue loan payments.</p>
            <button @click.stop="navigateToUserAccounts">Manage Collections</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// No need to import createApp here, as we are using Vue Router now
// Import specific components for mock data simulation, not for direct rendering
// import LoanApprovals from './LoanApprovals.vue'; // Removed as not directly rendered
// import UserAccounts from './UserAccounts.vue';   // Removed
// import OtpSupport from './OtpSupport.vue';       // Removed

export default {
  name: 'AdminDashboardHome',
  data() {
    return {
      currentDate: '',
      // Mock data for demonstration purposes
      // In a real app, this data would come from a backend API
      mockUsersData: [],
      mockLoanRequests: [],
      mockOtpRequests: [],
    };
  },
  computed: {
    totalSavings() {
      return this.mockUsersData.reduce((sum, user) => sum + (user.savingsBalance || 0), 0);
    },
    totalOutstandingLoans() {
      return this.mockUsersData.reduce((sum, user) => sum + (user.loanBalance || 0), 0);
    },
    pendingLoanRequestsCount() {
      // Assuming a 'status' field for loan requests
      return this.mockLoanRequests.filter(request => request.status === 'pending').length;
    },
    activeOtpRequestsCount() {
      // Assuming a 'status' field for OTP requests (e.g., 'active', 'pending', 'resolved')
      return this.mockOtpRequests.filter(request => request.status === 'active' || request.status === 'pending').length;
    }
  },
  mounted() {
    this.updateCurrentDate();
    this.loadMockData(); // Load initial mock data
    // In a real app, you would make API calls here to fetch real data
  },
  methods: {
    updateCurrentDate() {
      const today = new Date();
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      this.currentDate = today.toLocaleDateString(undefined, options);
    },
    loadMockData() {
      //simulation before database connectivity
     //already established July  12th 
      const savedUsers = localStorage.getItem('adminMockAllUsers');
      if (savedUsers) {
        this.mockUsersData = JSON.parse(savedUsers);
      } else {
        // Default mock users
        this.mockUsersData = [
          { id: 'user1', name: 'Alice Smith', savingsBalance: 12390, loanBalance: 500 },
          { id: 'user2', name: 'Bob Johnson', savingsBalance: 2500, loanBalance: 1200 },
          { id: 'user3', name: 'Charlie Brown', savingsBalance: 30000, loanBalance: 0 },
          { id: 'user4', name: 'Diana Prince', savingsBalance: 800, loanBalance: 3500 },
        ];
        localStorage.setItem('adminMockAllUsers', JSON.stringify(this.mockUsersData));
      }

      const savedLoanRequests = localStorage.getItem('adminMockLoanRequests');
      if (savedLoanRequests) {
        this.mockLoanRequests = JSON.parse(savedLoanRequests);
      } else {
        this.mockLoanRequests = [
          { id: 'L001', userId: 'user1', amount: 1000, status: 'pending', date: '2025-06-10' },
          { id: 'L002', userId: 'user2', amount: 500, status: 'approved', date: '2025-06-05' },
          { id: 'L003', userId: 'user4', amount: 2000, status: 'pending', date: '2025-06-15' },
        ];
        localStorage.setItem('adminMockLoanRequests', JSON.stringify(this.mockLoanRequests));
      }

      const savedOtpRequests = localStorage.getItem('adminMockOtpRequests');
      if (savedOtpRequests) {
        this.mockOtpRequests = JSON.parse(savedOtpRequests);
      } else {
        this.mockOtpRequests = [
          { id: 'O001', userId: 'user1', type: 'password_reset', status: 'active', date: '2025-06-16' },
          { id: 'O002', userId: 'user3', type: 'login_verification', status: 'resolved', date: '2025-06-15' },
        ];
        localStorage.setItem('adminMockOtpRequests', JSON.stringify(this.mockOtpRequests));
      }
    },
    // --- (using Vue Router) ---
    navigateToLoanApprovals() {
      this.$router.push({ name: 'LoanApprovals' }); // Navigates to /admin/loan-approvals
    },
    navigateToUserAccounts() {
      this.$router.push({ name: 'UserAccounts' }); // Navigates to /admin/user-accounts
    },
    navigateToOtpSupport() {
      this.$router.push({ name: 'OtpSupport' }); // Navigates to /admin/otp-support
    }
  }
};
</script>

<style scoped>
.admin-dashboard-home {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
  background-color: #f4f7fa;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e0e6ed;
}

.header-container h1 {
  color: #0056b3;
  margin: 0;
  font-size: 2.2em;
}

.current-date {
  font-size: 1em;
  color: #6a7c92;
  font-weight: bold;
}

.summary-section, .admin-actions-container {
  margin-bottom: 50px;
}

h2 {
  color: #007BFF;
  margin-bottom: 25px;
  font-size: 1.8em;
  position: relative;
  display: inline-block;
  padding-bottom: 8px;
}

h2::after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: 0;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background-color: #007BFF;
  border-radius: 2px;
}

.metrics-grid, .options-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
  justify-content: center;
  max-width: 1200px;
  margin: 0 auto;
}

.metric-card, .option-box {
  background-color: #ffffff;
  border: 1px solid #e0e6ed;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.metric-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.metric-card h3 {
  color: #555;
  font-size: 1.1em;
  margin-bottom: 15px;
}

.metric-value {
  font-size: 2.5em;
  font-weight: bold;
  color: #007BFF;
  margin: 0;
}

.option-box {
  cursor: pointer;
}

.option-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.option-box .icon {
  font-size: 3.5em;
  margin-bottom: 15px;
  color: #007BFF;
}

.option-box h3 {
  color: #0056b3;
  margin-bottom: 10px;
  font-size: 1.3em;
}

.option-box p {
  color: #6a7c92;
  font-size: 0.95em;
  margin-bottom: 20px;
  flex-grow: 1; 
}

.option-box button {
  padding: 12px 25px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1em;
  font-weight: bold;
  transition: background-color 0.3s ease;
  width: 90%;
}

.option-box button:hover {
  background-color: #0056b3;
}
</style>
