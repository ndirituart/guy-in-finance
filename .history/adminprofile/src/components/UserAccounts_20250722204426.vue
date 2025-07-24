<template>
  <div class="admin-page">
    <h2>Manage User Accounts</h2>
    <p>View user financial summaries, loan statuses, and assist with account details.</p>

    <div class="content-area">
      <h3>All Users (Mock Data)</h3>
      <table class="user-table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Savings Balance</th>
            <th>Loan Balance</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in allUsers" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>Ksh. {{ user.savingsBalance.toFixed(2) }}</td>
            <td>Ksh. {{ user.loanBalance.toFixed(2) }}</td>
            <td>
              <button @click="viewUserLoans(user.id)" class="action-button view-button">View Loans</button>
              <button @click="resetPassword(user.id)" class="action-button reset-button">Reset Pass</button>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-if="!allUsers.length">No user data available.</p>
    </div>

    <button @click="goBackToDashboard" class="back-button">Back to Dashboard</button>

    <div v-if="message" :class="['popup-message', messageType]">
      <p>{{ message }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserAccounts',
  data() {
    return {
      allUsers: [],
      message: '',
      messageType: '',
    };
  },
  mounted() {
    this.loadAllUsers();
  },
  methods: {
    loadAllUsers() {
      // In a real app, this would fetch from your backend API
      this.allUsers = JSON.parse(localStorage.getItem('adminMockAllUsers') || '[]');
    },
    viewUserLoans(userId) {
      // This would typically open a modal or navigate to a user-specific loan detail page
      this.showMessage(`Viewing loan details for User ID: ${userId} (simulated)`, 'info');
      // For "pressure people to pay back loans", this page would show overdue status
      // and this action would show detailed repayment history/options.
    },
    resetPassword(userId) {
      // In a real app, this would trigger a backend process to send a password reset link/OTP
      this.showMessage(`Password reset initiated for User ID: ${userId} (simulated)`, 'success');
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
  max-width: 900px;
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
  overflow-x: auto; /* For table responsiveness */
}

.user-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.user-table th, .user-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
  vertical-align: middle;
}

.user-table th {
  background-color: #eaf3fb;
  color: #333;
}

.user-table tbody tr:nth-child(even) {
  background-color: #f6f8fa;
}

.action-button {
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  margin: 2px;
  font-size: 0.9em;
  font-weight: bold;
}

.view-button {
  background-color: #17a2b8;
  color: white;
  border: none;
}

.view-button:hover {
  background-color: #138496;
}

.reset-button {
  background-color: #ffc107;
  color: #343a40;
  border: none;
}

.reset-button:hover {
  background-color: #e0a800;
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

.popup-message.info {
  background-color: #d1ecf1;
  color: #0c5460;
  border-color: #bee5eb;
}
</style>
