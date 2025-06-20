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
// For a real application, you would use 'axios' or 'fetch' to call your backend.
// import axios from 'axios';

export default {
  name: 'LoansPage',
  data() {
    return {
      loanMessage: '',
      messageType: '', // 'success', 'error', 'info'
      requestedLoanAmount: 0,
      currentUserId: 'user1', // IMPORTANT: In a real app, get this from user's login session
      loanStatusHistory: [], // To store mock loan request history (will not persist without backend)
    };
  },
  mounted() {
    // Without localStorage, loan history will not persist across sessions/reloads.
    // In a real app, this would be `this.checkLoanStatus();` to fetch from backend.
  },
  methods: {
    async submitAppLoanRequest() {
      if (this.requestedLoanAmount <= 0) {
        this.displayMessage('Please enter a valid loan amount.', 'error');
        return;
      }

      this.displayMessage('Waiting for approval, message will be sent in an hour (simulated).', 'info');
      
      const mockRequestId = 'LR' + Date.now(); // Simple unique ID
      const newRequest = {
        id: mockRequestId,
        userId: this.currentUserId,
        amount: this.requestedLoanAmount,
        status: 'pending', // Initial status
        requestedOn: new Date().toISOString(),
        lastUpdated: new Date().toISOString(),
      };

      // --- In a real application, this is where you'd make your POST API call to the backend ---
      /*
      try {
        const response = await axios.post('/api/loan-requests', {
          userId: this.currentUserId,
          amount: this.requestedLoanAmount
        });
        const submittedLoan = response.data; // Assuming backend returns the saved request details
        this.loanStatusHistory.unshift(submittedLoan); // Add to history (only in memory for this session)
        this.displayMessage('Loan request submitted successfully. Waiting for approval!', 'success');

      } catch (error) {
        this.displayMessage('Failed to submit loan request. Please try again.', 'error');
        console.error('Loan submission error:', error);
      }
      */

      // --- Removed localStorage simulation for admin side and user side ---
      // The loan request is now 'sent' but will not persist or be visible to the admin app
      // unless a backend API is implemented to receive and store it.
      this.loanStatusHistory.unshift(newRequest); // Add to history (only in current browser session)
      this.requestedLoanAmount = 0; // Clear input field
    },

    async checkLoanStatus() {
      // --- In a real application, this would make a GET API call to your backend: ---
      /*
      try {
        const response = await axios.get(`/api/loan-requests/status/${this.currentUserId}`);
        this.loanStatusHistory = response.data; // Assuming it returns array of user's loans
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
      */

      // --- Without localStorage or a backend, this will always show messages based on current session's history ---
      if (this.loanStatusHistory.length > 0) {
          const latestLoan = this.loanStatusHistory[0];
          this.displayMessage(`Loan request (ID: ${latestLoan.id}) is ${latestLoan.status}.`, 'info');
      } else {
          this.displayMessage('No loan requests found in current session.', 'info');
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
      }, 5000);
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
  flex-wrap: wrap;
  gap: 30px;
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
  font-size: 3.5em;
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
  width: 90%;
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
  position: fixed;
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
/* 
  This component allows users to request loans from the app
   or from friends/family (A future co)
  It displays a message based on the loan request status and shows a history of requests.
  The UI is styled for clarity and ease of use.
*/