<template>
  <div class="loans-page">
    <h1>Loans</h1>

    <div class="options-container">
      <div class="option-box">
        <h3>Borrow from App</h3>
        <span class="icon">🏦</span>
        <input type="number" v-model.number="requestedLoanAmount" placeholder="Enter loan amount" min="1" />
        <button @click="submitAppLoanRequest">Request Loan</button>
      </div>

      <div class="option-box">
        <h3>Borrow from Someone</h3>
        <span class="icon">👨‍👩‍👧‍👦</span>
        <p>Future feature: Request loan from friends/family.</p>
        <button disabled>Request Loan</button> <!-- Disabled for now -->
      </div>
    </div>

    <div v-if="loanMessage" :class="['loan-message', messageType]">
      <p>{{ loanMessage }}</p>
    </div>

    <div v-if="loanStatusHistory.length" class="loan-status-history">
      <h2>Your Loan Request History</h2>
      <table>
        <thead>
          <tr>
            <th>Request ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Requested On</th>
            <th>Last Updated</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="request in loanStatusHistory" :key="request.id">
            <td>{{ request.id }}</td>
            <td>Ksh. {{ request.amount.toFixed(2) }}</td>
            <td>{{ request.status }}</td>
            <td>{{ new Date(request.requestedOn).toLocaleString() }}</td>
            <td>{{ new Date(request.lastUpdated).toLocaleString() }}</td>
          </tr>
        </tbody>
      </table>
      <button @click="checkLoanStatus" class="refresh-status-button">Refresh Status</button>
    </div>

    <!--For easy navigation between pages-->
  <footer class="main-footer">
    <nav class="footer-nav">
      <router-link to="/home" class="nav-item">
        <span class="icon">🏠</span>
        <span class="text">Home</span>
      </router-link>

      <router-link to="/family-friends" class="nav-item">
        <span class="icon">👨‍👩‍👧‍👦</span>
        <span class="text">Family & Friends</span>
      </router-link>

      <router-link to="/pay-bill" class="nav-item">
        <span class="icon">💧⚡</span>
        <span class="text">Bills</span>
      </router-link>

      <router-link to="/daily-expenses" class="nav-item">
        <span class="icon">🚌🛒</span>
        <span class="text">Daily Expenses</span>
      </router-link>

      <router-link to="/user-savings" class="nav-item">
        <span class="icon">💰</span>
        <span class="text">Savings</span>
      </router-link>
    </nav>
  </footer>
  </div>
</template>

<script>
import axios from 'axios'; // You would typically import axios

export default {
  name: 'LoansPage',
  data() {
    return {
      loanMessage: '',
      messageType: '', // 'success', 'error', 'info'
      requestedLoanAmount: 0,
      currentUserId: 'user1', 
      loanStatusHistory: [],
    };
  },
  mounted() {
    // On mount, immediately check the loan status to populate history from 'backend'
    this.checkLoanStatus();
  },
  methods: {
    async submitAppLoanRequest() {
      if (this.requestedLoanAmount <= 0) {
        this.displayMessage('Please enter a valid loan amount.', 'error');
        return;
      }

      this.displayMessage('Waiting for approval, message will be sent in an hour (simulated).', 'info');

      const newRequest = {
        userId: this.currentUserId,
        amount: this.requestedLoanAmount,
      };

      try {
        // --- Calling the backend for loan requests (commented out for current simulation) ---
        const response = await axios.post('/api/loan-requests', newRequest);
        //eslint-disable-next-line no-unused-vars
        const submittedLoan = response.data;

        // The following block simulates the backend creating the full loan request object
        // and saving it. This is the data format that LoansApproval.vue expects.
        const mockRequestId = 'LR' + Date.now();
        const simulatedSubmittedLoan = {
          id: mockRequestId,
          ...newRequest, // spread userId and amount from newRequest
          status: 'pending', // Initial status is pending
          requestedOn: new Date().toISOString(),
          lastUpdated: new Date().toISOString(),
        };

        // Simulate saving to 'admin's database' (for LoansApproval to pick up)
        let adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        adminLoansMock.unshift(simulatedSubmittedLoan);
        localStorage.setItem('adminMockLoanRequests', JSON.stringify(adminLoansMock));

        // Simulate saving to 'user's personal loan history in database'
        let userLoansMock = JSON.parse(localStorage.getItem(`userLoans_${this.currentUserId}`) || '[]');
        userLoansMock.unshift(simulatedSubmittedLoan);
        localStorage.setItem(`userLoans_${this.currentUserId}`, JSON.stringify(userLoansMock));
        // --- END OF MOCK BACKEND SIMULATION ---

        this.loanStatusHistory.unshift(simulatedSubmittedLoan); // Update local history immediately
        this.displayMessage('Loan request submitted successfully. Waiting for approval!', 'success');

      } catch (error) {
        this.displayMessage('Failed to submit loan request. Please try again.', 'error');
        console.error('Loan submission error:', error);
      } finally {
        this.requestedLoanAmount = 0; // Clear input field
      }
    },

    async checkLoanStatus() {
      try {
        // --- REAL API CALL (COMMENTED OUT FOR SIMULATION) ---
        // const response = await axios.get(`/api/loan-requests/status/${this.currentUserId}`);
        // this.loanStatusHistory = response.data.sort((a, b) => new Date(b.requestedOn) - new Date(a.requestedOn));

        // --- START OF MOCK BACKEND SIMULATION (using localStorage as fake DB) ---
        const userLoans = JSON.parse(localStorage.getItem(`userLoans_${this.currentUserId}`) || '[]');
        this.loanStatusHistory = userLoans.sort((a, b) => new Date(b.requestedOn) - new Date(a.requestedOn));
        // --- END OF MOCK BACKEND SIMULATION ---

        if (this.loanStatusHistory.length > 0) {
            const latestLoan = this.loanStatusHistory[0];
            if (latestLoan.status === 'approved') {
                this.displayMessage(`Your loan request (ID: ${latestLoan.id}) for Ksh. ${latestLoan.amount.toFixed(2)} has been APPROVED!`, 'success');
            } else if (latestLoan.status === 'denied' || latestLoan.status === 'rejected') {
                this.displayMessage(`Your loan request (ID: ${latestLoan.id}) for Ksh. ${latestLoan.amount.toFixed(2)} has been DENIED. Sorry.`, 'error');
            } else {
                this.displayMessage('Your loan request is still pending approval.', 'info');
            }
        } else {
            this.displayMessage('No loan requests found.', 'info');
        }
      } catch (error) {
        this.displayMessage('Failed to fetch loan status. Please try again.', 'error');
        console.error('Loan status check error:', error);
      }
    },

    borrowFromSomeone() {
      this.displayMessage('This feature is for borrowing from friends/family and is not yet implemented.', 'info');
    },

    displayMessage(msg, type) {
      this.loanMessage = msg;
      this.messageType = type;
      setTimeout(() => {
        this.loanMessage = '';
        this.messageType = '';
      }, 5000); // Message disappears after 5 seconds
    }
  }
};
</script>

<style scoped>
.loans-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
}

h1 {
  color: #007BFF;
  margin-bottom: 30px;
  font-size: 2.5em;
}

.options-container {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap; /* Allow wrapping on smaller screens */
  gap: 30px; /* Space between option boxes */
  margin: 40px 0;
}

.option-box {
  border: 1px solid #007BFF;
  border-radius: 12px;
  padding: 30px;
  width: 280px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
}

.option-box:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transform: translateY(-5px);
}

.option-box h3 {
  color: #0056b3;
  font-size: 1.5em;
  margin-bottom: 15px;
}

.icon {
  font-size: 3.5em; /* Adjust icon size */
  margin-bottom: 20px;
  color: #007BFF;
}

input[type="number"] {
  width: calc(100% - 20px);
  padding: 10px;
  margin: 10px 0 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1em;
}

button {
  padding: 12px 25px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.1em;
  font-weight: bold;
  transition: background-color 0.3s ease;
  width: 90%; /* Make buttons wider */
}

button:hover {
  background-color: #0056b3;
}

button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.loan-message {
  margin-top: 30px;
  padding: 15px;
  border-radius: 8px;
  font-weight: bold;
  position: fixed; /* Keep messages central */
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  min-width: 300px;
}

.loan-message.success {
  background-color: #d4edda;
  color: #155724;
  border-color: #c3e6cb;
}

.loan-message.error {
  background-color: #f8d7da;
  color: #721c24;
  border-color: #f5c6cb;
}

.loan-message.info {
  background-color: #d1ecf1;
  color: #0c5460;
  border-color: #bee5eb;
}

.loan-status-history {
  margin-top: 50px;
  background-color: #f9fbfd;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  border: 1px solid #e0e6ed;
}

.loan-status-history h2 {
  color: #007BFF;
  margin-bottom: 20px;
}

.loan-status-history table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.loan-status-history th, .loan-status-history td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
  font-size: 0.9em;
}

.loan-status-history th {
  background-color: #eaf3fb;
  color: #333;
}

.loan-status-history tbody tr:nth-child(even) {
  background-color: #f6f8fa;
}

.refresh-status-button {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #6c757d;
  color: white;
  border-radius: 5px;
  cursor: pointer;
  border: none;
}

.refresh-status-button:hover {
  background-color: #5a6268;
}
.main-footer {
  position: fixed; /* Fixes the footer at the bottom of the viewport */
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #333; /* Dark background for visibility */
  color: white;
  padding: 10px 0;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2); /* Shadow for depth */
  z-index: 1000; /* Ensures it stays on top of other content */
}

.footer-nav {
  display: flex;
  justify-content: space-around; /* Distributes items evenly */
  align-items: center;
  max-width: 1200px; /* Limit width for larger screens */
  margin: 0 auto; /* Center the nav items */
}

.nav-item {
  display: flex;
  flex-direction: column; /* Stack icon and text */
  align-items: center;
  text-decoration: none; /* Remove underline */
  color: white; /* Default text color */
  font-size: 0.85em; /* Smaller text for compactness */
  padding: 5px 10px;
  border-radius: 5px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-item:hover {
  background-color: #555; /* Slightly lighter on hover */
}

/* Active link style */
.nav-item.router-link-exact-active {
  color: #87ceeb; /* Light blue for active link */
  font-weight: bold;
}

.icon {
  font-size: 1.8em; /* Larger icons */
  margin-bottom: 3px; /* Space between icon and text */
}

.text {
  white-space: nowrap; /* Prevents text from wrapping */
}

/* Media queries for responsiveness */
@media (max-width: 600px) {
  .nav-item {
    padding: 5px 5px; /* Less padding on small screens */
    font-size: 0.75em;
  }
  .icon {
    font-size: 1.5em; /* Smaller icons on small screens */
  }
}

/* Ensure content above footer */
body {
  padding-bottom: 70px; /* Add padding to the bottom of the body to prevent footer from overlapping content */
}
</style>
