<template>
  <div class="savings-page">
    <header class="header">
      <h1>Your Savings</h1>
      <span class="date">{{ currentDate }}</span>
    </header>

    <div class="savings-balance-card">
      <h2>Savings Balance</h2>
      <p class="balance-amount">Ksh. {{ savingsBalance.toFixed(2) }}</p>
    </div>

    <div class="actions-container">
      <div class="action-box" @click="openDepositModal">
        <h3>Deposit</h3>
        <span class="icon">😊</span>
        <button>Add to Savings</button>
      </div>

      <div class="action-box" @click="openWithdrawModal">
        <h3>Withdraw</h3>
        <span class="icon">😔</span>
        <button>Withdraw</button>
      </div>
    </div>

    <!-- Transaction History -->
    <div class="transaction-history" v-if="transactions.length">
      <h2>Transaction History</h2>
      <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Date</th>
            <th>Balance After</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="transaction in transactions" :key="transaction.id" :class="transaction.type">
            <td>{{ transaction.type }}</td>
            <td>Ksh. {{ transaction.amount.toFixed(2) }}</td>
            <td>{{ transaction.description || '-' }}</td>
            <td>{{ new Date(transaction.created_at).toLocaleString() }}</td>
            <td>Ksh. {{ transaction.current_balance.toFixed(2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p v-else class="no-transactions">No transactions yet.</p>


    <!-- Deposit Modal -->
    <div v-if="showDepositModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Deposit Funds</h3>
        <input type="number" v-model.number="depositAmount" placeholder="Amount" min="0.01" />
        <input type="text" v-model="depositDescription" placeholder="Description (optional)" />
        <div class="modal-buttons">
          <button @click="confirmDeposit" class="confirm-btn">Deposit</button>
          <button @click="closeDepositModal" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Withdraw Modal -->
    <div v-if="showWithdrawModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Withdraw Funds</h3>
        <input type="number" v-model.number="withdrawAmount" placeholder="Amount" min="0.01" />
        <input type="text" v-model="withdrawDescription" placeholder="Description (optional)" />
        <div class="modal-buttons">
          <button @click="confirmWithdraw" class="confirm-btn">Withdraw</button>
          <button @click="closeWithdrawModal" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Global Message Box -->
    <div v-if="message" :class="['message-box', messageType]">
      {{ message }}
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  name: 'SavingsPage',
  data() {
    return {
      currentDate: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
      savingsBalance: 0.00,
      transactions: [],
      showDepositModal: false,
      depositAmount: 0,
      depositDescription: '',
      showWithdrawModal: false,
      withdrawAmount: 0,
      withdrawDescription: '',
      message: '',
      messageType: '',
    };
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  async mounted() {
    await this.fetchSavingsData();
  },
  methods: {
    async fetchSavingsData() {
      try {
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
          this.displayMessage('You are not logged in. Please log in to view savings.', 'error');
          this.router.push('/login');
          return;
        }
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        // Fetch balance
        const balanceResponse = await axios.get('http://127.0.0.1:8000/api/savings/balance');
        this.savingsBalance = balanceResponse.data.savings_balance;

        // Fetch transactions
        const transactionsResponse = await axios.get('http://127.0.0.1:8000/api/savings/transactions');
        this.transactions = transactionsResponse.data.transactions;

      } catch (error) {
        console.error('Error fetching savings data:', error.response?.data || error.message);
        this.displayMessage('Failed to load savings data. Please try again.', 'error');
        if (error.response?.status === 401 || error.response?.status === 403) {
          localStorage.removeItem('authToken');
          this.router.push('/login');
        }
      }
    },

    openDepositModal() {
      this.showDepositModal = true;
      this.depositAmount = 0;
      this.depositDescription = '';
      this.message = ''; // Clear any previous messages
    },
    closeDepositModal() {
      this.showDepositModal = false;
    },
    async confirmDeposit() {
      if (this.depositAmount <= 0) {
        this.displayMessage('Please enter a valid deposit amount.', 'error');
        return;
      }
      try {
        const authToken = localStorage.getItem('authToken');
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        const response = await axios.post('http://127.0.0.1:8000/api/savings/deposit', {
          amount: this.depositAmount,
          description: this.depositDescription,
        });
        this.displayMessage(response.data.message, 'success');
        this.closeDepositModal();
        await this.fetchSavingsData(); // Refresh data after transaction
      } catch (error) {
        console.error('Deposit error:', error.response?.data || error.message);
        this.displayMessage(error.response?.data?.message || 'Deposit failed. Please try again.', 'error');
      }
    },

    openWithdrawModal() {
      this.showWithdrawModal = true;
      this.withdrawAmount = 0;
      this.withdrawDescription = '';
      this.message = ''; // Clear any previous messages
    },
    closeWithdrawModal() {
      this.showWithdrawModal = false;
    },
    async confirmWithdraw() {
      if (this.withdrawAmount <= 0) {
        this.displayMessage('Please enter a valid withdrawal amount.', 'error');
        return;
      }
      if (this.withdrawAmount > this.savingsBalance) {
        this.displayMessage('Insufficient funds for withdrawal.', 'error');
        return;
      }
      try {
        const authToken = localStorage.getItem('authToken');
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        const response = await axios.post('http://127.0.0.1:8000/api/savings/withdraw', {
          amount: this.withdrawAmount,
          description: this.withdrawDescription,
        });
        this.displayMessage(response.data.message, 'success');
        this.closeWithdrawModal();
        await this.fetchSavingsData(); // Refresh data after transaction
      } catch (error) {
        console.error('Withdrawal error:', error.response?.data || error.message);
        this.displayMessage(error.response?.data?.message || 'Withdrawal failed. Please try again.', 'error');
      }
    },

    displayMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
        this.messageType = '';
      }, 3000);
    },
  },
};
</script>

<style scoped>
.savings-page {
  font-family: 'Inter', sans-serif;
  max-width: 900px;
  margin: 30px auto;
  padding: 25px;
  background-color: #f8f9fa;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e9ecef;
}

.header h1 {
  color: #007bff;
  font-size: 2.5em;
  margin: 0;
}

.header .date {
  font-size: 1em;
  color: #6c757d;
}

.savings-balance-card {
  background: linear-gradient(45deg, #007bff, #00c6ff);
  color: white;
  padding: 30px;
  border-radius: 15px;
  margin-bottom: 40px;
  box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
}

.savings-balance-card h2 {
  font-size: 1.8em;
  margin-bottom: 10px;
}

.balance-amount {
  font-size: 3.5em;
  font-weight: bold;
  letter-spacing: 1px;
}

.actions-container {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 30px;
  margin-bottom: 50px;
}

.action-box {
  background-color: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  width: 250px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 1px solid #e0e6ed;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.action-box:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transform: translateY(-5px);
}

.action-box h3 {
  color: #0056b3;
  font-size: 1.4em;
  margin-bottom: 15px;
}

.action-box .icon {
  font-size: 3em;
  margin-bottom: 20px;
}

.action-box button {
  padding: 12px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1em;
  font-weight: bold;
  transition: background-color 0.3s ease;
  width: 100%;
}

.action-box button:hover {
  background-color: #0056b3;
}

.transaction-history {
  background-color: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  border: 1px solid #e0e6ed;
}

.transaction-history h2 {
  color: #007bff;
  margin-bottom: 25px;
  font-size: 1.8em;
}

.transaction-history table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.transaction-history th, .transaction-history td {
  border: 1px solid #dee2e6;
  padding: 12px;
  text-align: left;
  font-size: 0.95em;
}

.transaction-history th {
  background-color: #eaf3fb;
  color: #333;
  font-weight: 600;
}

.transaction-history tbody tr:nth-child(even) {
  background-color: #f6f8fa;
}

.transaction-history .deposit {
  color: #28a745; /* Green for deposits */
  font-weight: bold;
}

.transaction-history .withdrawal {
  color: #dc3545; /* Red for withdrawals */
  font-weight: bold;
}

.no-transactions {
  color: #6c757d;
  font-style: italic;
  margin-top: 20px;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  width: 90%;
  max-width: 400px;
  text-align: center;
}

.modal-content h3 {
  color: #007bff;
  margin-bottom: 20px;
  font-size: 1.8em;
}

.modal-content input {
  width: calc(100% - 24px); 
  padding: 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1em;
}

.modal-buttons {
  display: flex;
  justify-content: space-around;
  gap: 15px;
  margin-top: 20px;
}

.modal-buttons button {
  flex: 1;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.confirm-btn {
  background-color: #28a745; /* Green */
  color: white;
  border: none;
}

.confirm-btn:hover {
  background-color: #218838;
}

.cancel-btn {
  background-color: #6c757d; /* Grey */
  color: white;
  border: none;
}

.cancel-btn:hover {
  background-color: #5a6268;
}


.message-box {
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 15px 25px;
  border-radius: 8px;
  font-weight: bold;
  z-index: 1001;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
  animation: fadeInOut 3.5s forwards;
}

.message-box.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.message-box.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

@keyframes fadeInOut {
  0% { opacity: 0; transform: translateX(-50%) translateY(20px); }
  10% { opacity: 1; transform: translateX(-50%) translateY(0); }
  90% { opacity: 1; transform: translateX(-50%) translateY(0); }
  100% { opacity: 0; transform: translateX(-50%) translateY(20px); }
}

/* Responsiveness */
@media (max-width: 600px) {
  .header {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
  .savings-balance-card {
    padding: 20px;
  }
  .balance-amount {
    font-size: 3em;
  }
  .actions-container {
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }
  .action-box {
    width: 100%;
  }
  .modal-content {
    padding: 20px;
  }
}
</style>