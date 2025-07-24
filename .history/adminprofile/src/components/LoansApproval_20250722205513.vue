<!--Loans Approval simulation before connecting to client interface-->
<template>
  <div class="loans-approval-page">
    <h1>Loan Approvals</h1>
    <p>This is where admin can approve or deny loan requests.</p>
    <div v-if="pendingLoanRequests.length" class="pending-requests">
      <h2>Pending Loan Requests</h2>
      <table>
        <thead>
          <tr>
            <th>Request ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Requested On</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="request in pendingLoanRequests" :key="request.id">
            <td>{{ request.id }}</td>
            <td>{{ request.userId }}</td>
            <td>Ksh. {{ request.amount.toFixed(2) }}</td>
            <td>{{ new Date(request.requestedOn).toLocaleString() }}</td>
            <td>
              <button @click="approveLoan(request.id)" class="approve-button">Approve</button>
              <button @click="denyLoan(request.id)" class="deny-button">Deny</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else>
      <p>No pending loan requests.</p>
    </div>

    <div v-if="approvalMessage" :class="['approval-message', messageType]">
      <p>{{ approvalMessage }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LoansApproval',
  data() {
    return {
      pendingLoanRequests: [],
      approvalMessage: '',
      messageType: '', // 'success', 'error', 'info'
    };
  },
  mounted() {
    this.fetchPendingLoanRequests();
  },
  methods: {
    async fetchPendingLoanRequests() {
      try {
       
        const response = await axios.get('/api/admin/loan-requests/pending');
        this.pendingLoanRequests = response.data;

        // --- START OF MOCK BACKEND SIMULATION (using localStorage as fake DB) ---
        const adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        this.pendingLoanRequests = adminLoansMock.filter(request => request.status === 'pending');
        // --- END OF MOCK BACKEND SIMULATION ---

        if (this.pendingLoanRequests.length === 0) {
          this.displayMessage('No pending loan requests found.', 'info');
        }
      } catch (error) {
        this.displayMessage('Failed to fetch pending loan requests.', 'error');
        console.error('Fetch pending loan requests error:', error);
      }
    },

    async approveLoan(requestId) {
      this.displayMessage(`Approving loan request ${requestId}...`, 'info');
      try {
        // where the loans requests are received from user interface
        await axios.post(`/api/admin/loan-requests/${requestId}/approve`);

        // --- START OF MOCK BACKEND SIMULATION ---
        let adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        let userLoansMock = JSON.parse(localStorage.getItem(`userLoans_user1`) || '[]'); 

        const adminIndex = adminLoansMock.findIndex(req => req.id === requestId);
        if (adminIndex !== -1) {
          adminLoansMock[adminIndex].status = 'approved';
          adminLoansMock[adminIndex].lastUpdated = new Date().toISOString();
          localStorage.setItem('adminMockLoanRequests', JSON.stringify(adminLoansMock));
        }

        const userIndex = userLoansMock.findIndex(req => req.id === requestId);
        if (userIndex !== -1) {
          userLoansMock[userIndex].status = 'approved';
          userLoansMock[userIndex].lastUpdated = new Date().toISOString();
          localStorage.setItem(`userLoans_user1`, JSON.stringify(userLoansMock));
        }
        // --- END OF MOCK BACKEND SIMULATION ---

        this.displayMessage(`Loan request ${requestId} approved successfully!`, 'success');
        this.fetchPendingLoanRequests(); // Refresh the list
      } catch (error) {
        this.displayMessage(`Failed to approve loan request ${requestId}.`, 'error');
        console.error('Approve loan error:', error);
      }
    },

    async denyLoan(requestId) {
      this.displayMessage(`Denying loan request ${requestId}...`, 'info');
      try {
        // --- REAL API CALL (COMMENTED OUT FOR SIMULATION) ---
        await axios.post(`/api/admin/loan-requests/${requestId}/deny`);

        // --- START OF MOCK BACKEND SIMULATION ---
        let adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        let userLoansMock = JSON.parse(localStorage.getItem(`userLoans_user1`) || '[]'); // Assuming 'user1' for demo

        const adminIndex = adminLoansMock.findIndex(req => req.id === requestId);
        if (adminIndex !== -1) {
          adminLoansMock[adminIndex].status = 'denied';
          adminLoansMock[adminIndex].lastUpdated = new Date().toISOString();
          localStorage.setItem('adminMockLoanRequests', JSON.stringify(adminLoansMock));
        }

        const userIndex = userLoansMock.findIndex(req => req.id === requestId);
        if (userIndex !== -1) {
          userLoansMock[userIndex].status = 'denied';
          userLoansMock[userIndex].lastUpdated = new Date().toISOString();
          localStorage.setItem(`userLoans_user1`, JSON.stringify(userLoansMock));
        }
        // --- END OF MOCK BACKEND SIMULATION ---

        this.displayMessage(`Loan request ${requestId} denied.`, 'success');
        this.fetchPendingLoanRequests(); // Refresh the list
      } catch (error) {
        this.displayMessage(`Failed to deny loan request ${requestId}.`, 'error');
        console.error('Deny loan error:', error);
      }
    },

    displayMessage(msg, type) {
      this.approvalMessage = msg;
      this.messageType = type;
      setTimeout(() => {
        this.approvalMessage = '';
        this.messageType = '';
      }, 5000); // Message disappears after 5 seconds
    }
  }
};
</script>

<style scoped>
.loans-approval-page {
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

.pending-requests {
  margin-top: 50px;
  background-color: #f9fbfd;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  border: 1px solid #e0e6ed;
}

.pending-requests h2 {
  color: #007BFF;
  margin-bottom: 20px;
}

.pending-requests table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.pending-requests th, .pending-requests td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
  font-size: 0.9em;
}

.pending-requests th {
  background-color: #eaf3fb;
  color: #333;
}

.pending-requests tbody tr:nth-child(even) {
  background-color: #f6f8fa;
}

.approve-button {
  padding: 8px 15px;
  background-color: #28a745; /* Green for approve */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 0.9em;
  margin-right: 5px;
}

.approve-button:hover {
  background-color: #218838;
}

.deny-button {
  padding: 8px 15px;
  background-color: #dc3545; /* Red for deny */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 0.9em;
}

.deny-button:hover {
  background-color: #c82333;
}

.approval-message {
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

.approval-message.success {
  background-color: #d4edda;
  color: #155724;
  border-color: #c3e6cb;
}

.approval-message.error {
  background-color: #f8d7da;
  color: #721c24;
  border-color: #f5c6cb;
}

.approval-message.info {
  background-color: #d1ecf1;
  color: #0c5460;
  border-color: #bee5eb;
}
</style>

