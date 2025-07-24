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
    this.initializeMockData(); // Call this to set up your dummy data
    this.fetchPendingLoanRequests();
  },
  methods: {
    initializeMockData() {
      // Dummy data for demonstration
      const dummyAdminLoans = [
        {
          id: 'REQ001',
          userId: 'user101',
          amount: 50000.00,
          requestedOn: '2023-07-15T10:00:00Z',
          status: 'approved',
          lastUpdated: '2023-07-16T11:30:00Z'
        },
        {
          id: 'REQ002',
          userId: 'user102',
          amount: 120000.00,
          requestedOn: '2023-07-16T14:30:00Z',
          status: 'denied',
          lastUpdated: '2023-07-17T09:00:00Z'
        },
        {
          id: 'REQ003',
          userId: 'user103',
          amount: 75000.00,
          requestedOn: '2023-07-20T08:45:00Z',
          status: 'pending'
        },
        {
          id: 'REQ004',
          userId: 'user104',
          amount: 200000.00,
          requestedOn: '2023-07-21T16:00:00Z',
          status: 'pending'
        },
        {
          id: 'REQ005',
          userId: 'user105',
          amount: 30000.00,
          requestedOn: '2023-07-22T09:15:00Z',
          status: 'pending'
        },
        // --- NEW DUMMY ENTRIES START HERE ---
        {
          id: 'REQ006',
          userId: 'user106',
          amount: 95000.00,
          requestedOn: '2023-07-23T11:00:00Z',
          status: 'pending'
        },
        {
          id: 'REQ007',
          userId: 'user107',
          amount: 15000.00,
          requestedOn: '2023-07-23T14:00:00Z',
          status: 'pending'
        },
        {
          id: 'REQ008',
          userId: 'user108',
          amount: 250000.00,
          requestedOn: '2023-07-24T09:30:00Z',
          status: 'approved',
          lastUpdated: '2023-07-24T10:15:00Z'
        },
        {
          id: 'REQ009',
          userId: 'user109',
          amount: 5000.00,
          requestedOn: '2023-07-24T11:45:00Z',
          status: 'pending'
        },
        {
          id: 'REQ010',
          userId: 'user110',
          amount: 60000.00,
          requestedOn: '2023-07-25T08:00:00Z',
          status: 'denied',
          lastUpdated: '2023-07-25T08:45:00Z'
        }
        // --- NEW DUMMY ENTRIES END HERE ---
      ];

      // Store in localStorage, simulating a database
      localStorage.setItem('adminMockLoanRequests', JSON.stringify(dummyAdminLoans));

      // Also simulate user's view of their loans for 'user1' if needed for other components
      // You might want to update this to include the new user IDs if you're actively using user-specific local storage
      const dummyUserLoans = [
        {
          id: 'REQ001',
          userId: 'user101',
          amount: 50000.00,
          requestedOn: '2023-07-15T10:00:00Z',
          status: 'approved',
          lastUpdated: '2023-07-16T11:30:00Z'
        },
        {
          id: 'REQ003',
          userId: 'user103',
          amount: 75000.00,
          requestedOn: '2023-07-20T08:45:00Z',
          status: 'pending'
        },
        { // New entry for user106
          id: 'REQ006',
          userId: 'user106',
          amount: 95000.00,
          requestedOn: '2023-07-23T11:00:00Z',
          status: 'pending'
        },
        { // New entry for user109
          id: 'REQ009',
          userId: 'user109',
          amount: 5000.00,
          requestedOn: '2023-07-24T11:45:00Z',
          status: 'pending'
        }
      ];
      localStorage.setItem(`userLoans_user101`, JSON.stringify(dummyUserLoans)); // This might need to be dynamic based on which user is logged in
      localStorage.setItem(`userLoans_user103`, JSON.stringify([dummyUserLoans[1]]));
      localStorage.setItem(`userLoans_user106`, JSON.stringify([dummyUserLoans[2]]));
      localStorage.setItem(`userLoans_user109`, JSON.stringify([dummyUserLoans[3]]));
      localStorage.setItem(`userLoans_user108`, JSON.stringify([
        {
          id: 'REQ008',
          userId: 'user108',
          amount: 250000.00,
          requestedOn: '2023-07-24T09:30:00Z',
          status: 'approved',
          lastUpdated: '2023-07-24T10:15:00Z'
        }
      ]));
      localStorage.setItem(`userLoans_user110`, JSON.stringify([
        {
          id: 'REQ010',
          userId: 'user110',
          amount: 60000.00,
          requestedOn: '2023-07-25T08:00:00Z',
          status: 'denied',
          lastUpdated: '2023-07-25T08:45:00Z'
        }
      ]));
    },

    async fetchPendingLoanRequests() {
      try {
        // --- START OF MOCK BACKEND SIMULATION (using localStorage as fake DB) ---
        const adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        this.pendingLoanRequests = adminLoansMock.filter(request => request.status === 'pending');
        // --- END OF MOCK BACKEND SIMULATION ---

        if (this.pendingLoanRequests.length === 0) {
          this.displayMessage('No pending loan requests found.', 'info');
        } else {
          // Clear info message if there are pending requests
          this.approvalMessage = '';
          this.messageType = '';
        }
      } catch (error) {
        this.displayMessage('Failed to fetch pending loan requests.', 'error');
        console.error('Fetch pending loan requests error:', error);
      }
    },

    async approveLoan(requestId) {
      this.displayMessage(`Approving loan request ${requestId}...`, 'info');
      try {
        // --- START OF MOCK BACKEND SIMULATION ---
        let adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        
        const adminIndex = adminLoansMock.findIndex(req => req.id === requestId);
        if (adminIndex !== -1) {
          adminLoansMock[adminIndex].status = 'approved';
          adminLoansMock[adminIndex].lastUpdated = new Date().toISOString();
          localStorage.setItem('adminMockLoanRequests', JSON.stringify(adminLoansMock));

          // Simulate updating user's loan status as well (assuming userId is known)
          const userId = adminLoansMock[adminIndex].userId;
          let userLoansMock = JSON.parse(localStorage.getItem(`userLoans_${userId}`) || '[]');
          const userIndex = userLoansMock.findIndex(req => req.id === requestId);
          if (userIndex !== -1) {
            userLoansMock[userIndex].status = 'approved';
            userLoansMock[userIndex].lastUpdated = new Date().toISOString();
            localStorage.setItem(`userLoans_${userId}`, JSON.stringify(userLoansMock));
          }
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
        // --- START OF MOCK BACKEND SIMULATION ---
        let adminLoansMock = JSON.parse(localStorage.getItem('adminMockLoanRequests') || '[]');
        
        const adminIndex = adminLoansMock.findIndex(req => req.id === requestId);
        if (adminIndex !== -1) {
          adminLoansMock[adminIndex].status = 'denied';
          adminLoansMock[adminIndex].lastUpdated = new Date().toISOString();
          localStorage.setItem('adminMockLoanRequests', JSON.stringify(adminLoansMock));

          // Simulate updating user's loan status as well (assuming userId is known)
          const userId = adminLoansMock[adminIndex].userId;
          let userLoansMock = JSON.parse(localStorage.getItem(`userLoans_${userId}`) || '[]');
          const userIndex = userLoansMock.findIndex(req => req.id === requestId);
          if (userIndex !== -1) {
            userLoansMock[userIndex].status = 'denied';
            userLoansMock[userIndex].lastUpdated = new Date().toISOString();
            localStorage.setItem(`userLoans_${userId}`, JSON.stringify(userLoansMock));
          }
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
      }, 5000); 
    }
  }
};
</script>