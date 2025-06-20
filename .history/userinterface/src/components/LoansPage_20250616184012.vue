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
  </div>
</template>

<script>
// For demonstration, we'll use a mock API call with setTimeout.
// In a real application, you would use 'axios' or 'fetch' to call your backend.
// import axios from 'axios';

export default {
  name: 'LoansPage',
  data() {
    return {
      loanMessage: '',
      messageType: '', // 'success', 'error', 'info'
      requestedLoanAmount: 0,
      currentUserId: 'user1', // IMPORTANT: In a real app, get this from user's login session
      loanStatusHistory: [], // To store mock loan request history
    };
  },
  mounted() {
    this.loadLoanHistory();
    // Simulate checking status when page loads, or add a polling mechanism
    this.checkLoanStatus(); 
  },
  methods: {
    async submitAppLoanRequest() {
      if (this.requestedLoanAmount <= 0) {
        this.displayMessage('Please enter a valid loan amount.', 'error');
        return;
      }

      this.displayMessage('Waiting for approval, message will be sent in an hour (simulated).', 'info');
      // this.requestedLoanAmount = 0; // Clear input after submission (optional)

      const mockRequestId = 'LR' + Date.now(); // Simple unique ID
      const newRequest = {
        id: mockRequestId,
        userId: this.currentUserId,
        amount: this.requestedLoanAmount,
        status: 'pending', // Initial status
        requestedOn: new Date().toISOString(),
        lastUpdated: new Date().toISOString(),
      };

      // In a real application, this is where you'd make your POST API call:
      /*
      try {
        const response = await axios.post('/api/loan-requests', {
          userId: this.currentUserId,
          amount: this.requestedLoanAmount
        });
        // Assuming backend returns the saved request details
        const submittedLoan = response.data;
        this.loanStatusHistory.unshift(submittedLoan); // Add to history
        this.saveLoanHistory(); // Save to local storage mock
        this.displayMessage('Loan request submitted successfully. Waiting for approval!', 'success');

      } catch (error) {
        this.displayMessage('Failed to submit loan request. Please try again.', 'error');
        console.error('Loan submission error:', error);
      }
      */

      // --- Mocking Backend Response (for frontend testing without a real backend) ---
      // This simulates the user's loan request being saved by the backend
      let allAdminLoans = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
      allAdminLoans.unshift(newRequest); // Add to admin's pending list
      localStorage.setItem('adminMockLoanRequests', JSON.stringify(allAdminLoans));
      // Save this user's specific request history
      let userLoans = JSON.parse(localStorage.getItem(`userLoans_${this.currentUserId}`) || '[]');
      userLoans.unshift(newRequest);
      localStorage.setItem(`userLoans_${this.currentUserId}`, JSON.stringify(userLoans));
      this.loadLoanHistory(); // Update local history immediately

      this.requestedLoanAmount = 0; // Clear input field

      // Optional: Simulate approval/denial after a delay (if no admin panel is used)
      // For actual interaction, the admin panel handles approval/denial
      // and the user checks status via API.
      // setTimeout(() => {
      //   this.checkLoanStatus(); // Re-check status after a simulated delay
      // }, 30000); // Check after 30 seconds
    },

    async checkLoanStatus() {
      // In a real application, this would make a GET API call to your backend:
      /*
      try {
        const response = await axios.get(`/api/loan-requests/status/${this.currentUserId}`);
        this.loanStatusHistory = response.data; // Assuming it returns array of user's loans
        // Process messages based on the status of the *latest* loan
        if (this.loanStatusHistory.length > 0) {
            const latestLoan = this.loanStatusHistory[0]; // Assuming latest is first
            if (latestLoan.status === 'approved') {
                this.displayMessage(`Your loan request (ID: ${latestLoan.id}) for Ksh. ${latestLoan.amount} has been APPROVED!`, 'success');
                // You would then update user's actual balance (via another API call/backend process)
            } else if (latestLoan.status === 'denied' || latestLoan.status === 'rejected') {
                this.displayMessage(`Your loan request (ID: ${latestLoan.id}) for Ksh. ${latestLoan.amount} has been DENIED. Sorry.`, 'error');
            } else {
                this.displayMessage('Your loan request is still pending approval.', 'info');
            }
        }
      } catch (error) {
        this.displayMessage('Failed to fetch loan status. Please try again.', 'error');
        console.error('Loan status check error:', error);
      }
      */

      // --- Mocking Frontend Status Check (for simulation) ---
      this.loadLoanHistory(); // Reload from local storage mock
      if (this.loanStatusHistory.length > 0) {
          const latestLoan = this.loanStatusHistory[0]; // Assuming latest is first
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
    },

    loadLoanHistory() {
      // Load user's loan history from local storage mock
      this.loanStatusHistory = JSON.parse(localStorage.getItem(`userLoans_${this.currentUserId}`) || '[]');
      this.loanStatusHistory.sort((a, b) => new Date(b.requestedOn) - new Date(a.requestedOn)); // Sort by newest
    },

    saveLoanHistory() {
      // Save user's loan history to local storage mock
      localStorage.setItem(`userLoans_${this.currentUserId}`, JSON.stringify(this.loanStatusHistory));
    },

    borrowFromSomeone() {
      // Placeholder for future logic if needed
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
</style>
