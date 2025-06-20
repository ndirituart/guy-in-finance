<template>
  <div class="admin-page">
    <h2>Approve Loan Requests</h2>
    <p>This is where you will manage pending loan applications. You can review details and approve or reject requests.</p>
    
    <!-- Placeholder for loan request list -->
    <div class="content-area">
      <h3>Pending Requests (Mock Data)</h3>
      <ul v-if="pendingLoans.length">
        <li v-for="loan in pendingLoans" :key="loan.id" class="loan-item">
          <p><strong>Loan ID:</strong> {{ loan.id }}</p>
          <p><strong>User ID:</strong> {{ loan.userId }}</p>
          <p><strong>Amount:</strong> Ksh. {{ loan.amount }}</p>
          <p><strong>Date:</strong> {{ loan.date }}</p>
          <div class="actions">
            <button @click="approveLoan(loan.id)" class="approve-button">Approve</button>
            <button @click="rejectLoan(loan.id)" class="reject-button">Reject</button>
          </div>
        </li>
      </ul>
      <p v-else>No pending loan requests at the moment.</p>
    </div>

    <button @click="goBackToDashboard" class="back-button">Back to Dashboard</button>

    <div v-if="message" :class="['popup-message', messageType]">
      <p>{{ message }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoanApprovals',
  data() {
    return {
      pendingLoans: [],
      message: '',
      messageType: '',
    };
  },
  mounted() {
    this.loadPendingLoans();
    // Add event listener for storage changes
    window.addEventListener('storage', this.handleStorageChange);
  },
  beforeUnmount() {
    // Clean up event listener when component is unmounted
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      // Check if the change is relevant to our admin mock loans
      if (event.key === 'adminMockLoanRequests') {
        this.loadPendingLoans(); // Reload pending loans if the mock data changes
      }
    },
    loadPendingLoans() {
      // In a real app, this would fetch from your backend API
      const allLoans = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
      this.pendingLoans = allLoans.filter(loan => loan.status === 'pending');
    },
    approveLoan(loanId) {
      // In a real app, this would send an API request to your backend
      let allLoans = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
      const loanIndex = allLoans.findIndex(loan => loan.id === loanId);
      if (loanIndex !== -1) {
        allLoans[loanIndex].status = 'approved';
        allLoans[loanIndex].lastUpdated = new Date().toISOString(); // Update timestamp
        localStorage.setItem('adminMockLoanRequests', JSON.stringify(allLoans));
        this.loadPendingLoans(); // Refresh the list
        this.showMessage(`Loan ${loanId} approved successfully!`, 'success');
        // Simulate updating user's loan balance (in a real app, backend handles this)
        this.updateUserLoanBalance(allLoans[loanIndex].userId, allLoans[loanIndex].amount);
        // Also update the user's personal loan history (important for user's LoansPage)
        this.updateUserLoanStatusInLocalStorage(allLoans[loanIndex].userId, loanId, 'approved');
      }
    },
    rejectLoan(loanId) {
      // In a real app, this would send an API request to your backend
      let allLoans = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
      const loanIndex = allLoans.findIndex(loan => loan.id === loanId);
      if (loanIndex !== -1) {
        allLoans[loanIndex].status = 'rejected';
        allLoans[loanIndex].lastUpdated = new Date().toISOString(); // Update timestamp
        localStorage.setItem('adminMockLoanRequests', JSON.stringify(allLoans));
        this.loadPendingLoans(); // Refresh the list
        this.showMessage(`Loan ${loanId} rejected.`, 'error');
        // Also update the user's personal loan history (important for user's LoansPage)
        this.updateUserLoanStatusInLocalStorage(allLoans[loanIndex].userId, loanId, 'rejected');
      }
    },
    updateUserLoanBalance(userId, loanAmount) {
        let allUsers = JSON.parse(localStorage.getItem('adminMockAllUsers') || '[]');
        const userIndex = allUsers.findIndex(user => user.id === userId);
        if (userIndex !== -1) {
            allUsers[userIndex].loanBalance = (allUsers[userIndex].loanBalance || 0) + loanAmount;
            localStorage.setItem('adminMockAllUsers', JSON.stringify(allUsers));
        }
    },
    // New method to update the user's individual loan history in their localStorage
    updateUserLoanStatusInLocalStorage(userId, loanId, newStatus) {
        let userLoans = JSON.parse(localStorage.getItem(`userLoans_${userId}`) || '[]');
        const loanIndex = userLoans.findIndex(loan => loan.id === loanId);
        if (loanIndex !== -1) {
            userLoans[loanIndex].status = newStatus;
            userLoans[loanIndex].lastUpdated = new Date().toISOString();
            localStorage.setItem(`userLoans_${userId}`, JSON.stringify(userLoans));
        }
    },
    goBackToDashboard() {
      this.$router.push({ name: 'AdminHome' });
    },
    showMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
        this.messageType = '';
      }, 3000);
    }
  }
};
</script>

<style scoped>
.admin-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
  padding: 30px;
  border: 1px solid #e0e6ed;
  border-radius: 10px;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.07);
  background-color: #fff;
}

h2 {
  color: #007BFF;
  margin-bottom: 25px;
  font-size: 2em;
}

.content-area {
  margin-top: 30px;
  padding: 20px;
  background-color: #f9fbfd;
  border-radius: 8px;
  border: 1px solid #e0e6ed;
}

.loan-item {
  border: 1px solid #d3e0ea;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  background-color: #ffffff;
  text-align: left;
}

.loan-item p {
  margin: 5px 0;
  color: #333;
}

.actions button {
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
  margin-right: 10px;
  font-weight: bold;
}

.approve-button {
  background-color: #28a745;
  color: white;
  border: none;
}

.approve-button:hover {
  background-color: #218838;
}

.reject-button {
  background-color: #dc3545;
  color: white;
  border: none;
}

.reject-button:hover {
  background-color: #c82333;
}

.back-button {
  padding: 10px 20px;
  background-color: #6c757d;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 30px;
}

.back-button:hover {
  background-color: #5a6268;
}

.popup-message {
  margin-top: 20px;
  padding: 15px;
  border-radius: 8px;
  font-weight: bold;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.popup-message.success {
  background-color: #d4edda;
  color: #155724;
  border-color: #c3e6cb;
}

.popup-message.error {
  background-color: #f8d7da;
  color: #721c24;
  border-color: #f5c6cb;
}
</style>
