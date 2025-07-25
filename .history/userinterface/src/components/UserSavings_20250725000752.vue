<template>
  <div class="user-savings-page">
    <div class="header-container centered-header">
      <h1>Your Savings</h1>
      <p class="current-date">{{ currentDate }}</p>
    </div>

    <div class="savings-balance-card">
      <h2>Savings Balance</h2>
      <div class="balance-display-container">
        <p class="balance-amount" :class="{ 'blurred-balance': isBlurred }">
          Ksh. {{ isBlurred ? '****' : savingsBalance.toFixed(2) }}
        </p>
        <button @click="toggleBlur" class="blur-toggle-btn">
          {{ isBlurred ? '👁️' : '👁️‍🗨️' }}
        </button>
      </div>
    </div>

    <div class="options-container">
      <div class="option-box" @click="navigateToDepositPage">
        <h3>Deposit</h3>
        <span class="icon">😊</span>
        <button @click.stop="navigateToDepositPage">Add to Savings</button>
      </div>

      <div class="option-box" @click="navigateToWithdrawPage">
        <h3>Withdraw</h3>
        <span class="icon">😢</span>
        <button @click.stop="navigateToWithdrawPage">Withdraw</button>
      </div>
    </div>

    <hr class="separator">

    <div class="savings-goals-section">
      <h2>Your Savings Goals</h2>
      <button @click="toggleAddGoalForm" class="add-goal-button">
        {{ showAddGoalForm ? 'Hide Goal Form' : '+ New Savings Goal' }}
      </button>

      <div v-if="showAddGoalForm" class="add-goal-form">
        <label for="goalReason">Reason (Optional):</label>
        <input type="text" id="goalReason" v-model="newGoal.reason" placeholder="e.g., New Phone, Vacation" />

        <label for="goalAmount">Target Amount (Ksh.):</label>
        <input type="number" id="goalAmount" v-model.number="newGoal.amount" min="1" />

        <label for="goalDueDate">Due Date:</label>
        <input type="date" id="goalDueDate" v-model="newGoal.dueDate" />

        <button @click="addSavingsGoal" :disabled="!newGoal.amount || !newGoal.dueDate">Add Goal</button>
      </div>

      <div v-if="savingsGoals.length === 0 && !showAddGoalForm" class="no-goals-message">
        <p>No savings goals set yet! Start by creating one.</p>
      </div>

      <div v-else class="goals-list">
        <div v-for="(goal, index) in savingsGoals" :key="index" class="goal-item">
          <h3>{{ goal.reason || 'Unnamed Goal' }}</h3>
          <p>Target: Ksh. {{ goal.amount.toFixed(2) }}</p>
          <p>Due: {{ formatDate(goal.dueDate) }}</p>
          <button @click="removeGoal(index)" class="remove-goal-button">Remove</button>
        </div>
      </div>
    </div>

    <hr class="separator">

    <div v-if="transactions.length" class="transaction-history-table">
      <h2>Savings Transaction History</h2>
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Balance After</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(txn, index) in transactions" :key="index">
            <td>{{ txn.code }}</td>
            <td>{{ txn.type }}</td>
            <td>{{ txn.amount.toFixed(2) }}</td>
            <td>{{ txn.balance.toFixed(2) }}</td>
            <td>{{ txn.time }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="no-transactions-message">
      <p>No savings transactions yet.</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserSavings',
  data() {
    return {
      savingsBalance: 5000,
      isBlurred: false,
      currentDate: '',
      transactions: [], // To store savings transactions
      savingsGoals: [], // To store savings goals
      showAddGoalForm: false,
      newGoal: {
        reason: '',
        amount: null,
        dueDate: ''
      }
    };
  },
  // The mounted lifecycle hook is called after the component has been mounted.
  // We use it to load data and set up event listeners.
  mounted() {
    this.updateCurrentDate();
    this.loadSavingsData();
    this.loadSavingsGoals(); // Load goals on mount
    this.checkAndApplyGrowth();

    // The 'storage' event fires when a change is made to localStorage
    // in another browser tab/window or sometimes when changes are committed
    // in the same tab, but not always reliable for same-tab reactivity.
    window.addEventListener('storage', this.handleStorageChange);
  },
  // The beforeUnmount lifecycle hook is called just before a component is unmounted.
  // This is a good place to clean up event listeners to prevent memory leaks.
  beforeUnmount() {
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      // Reload relevant data if localStorage keys change
      if (event.key === 'userSavingsBalance' || event.key === 'userSavingsTransactions' || event.key === 'userSavingsGoals') {
        this.loadSavingsData();
        this.loadSavingsGoals();
      }
    },
    updateCurrentDate() {
      const today = new Date();
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      this.currentDate = today.toLocaleDateString(undefined, options);
    },
    loadSavingsData() {
      const savedBalance = localStorage.getItem('userSavingsBalance');
      if (savedBalance) {
        this.savingsBalance = parseFloat(savedBalance);
      } else {
        // Initialize balance if it doesn't exist
        localStorage.setItem('userSavingsBalance', this.savingsBalance.toFixed(2));
      }

      const savedTxns = localStorage.getItem('userSavingsTransactions');
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
        // Sort transactions by time in descending order (newest first)
        this.transactions.sort((a, b) => new Date(b.time) - new Date(a.time));
      } else {
        // Initialize transactions if it doesn't exist
        localStorage.setItem('userSavingsTransactions', JSON.stringify([]));
      }
    },
    saveSavingsData() {
      // This method is primarily for saving balance and transactions together
      localStorage.setItem('userSavingsBalance', this.savingsBalance.toFixed(2));
      localStorage.setItem('userSavingsTransactions', JSON.stringify(this.transactions));
    },
    loadSavingsGoals() {
      const savedGoals = localStorage.getItem('userSavingsGoals');
      if (savedGoals) {
        this.savingsGoals = JSON.parse(savedGoals);
      } else {
        localStorage.setItem('userSavingsGoals', JSON.stringify([]));
      }
    },
    saveSavingsGoals() {
      localStorage.setItem('userSavingsGoals', JSON.stringify(this.savingsGoals));
    },
    createTransaction(type, amount, balanceAfter) {
      return {
        code: type.substring(0, 3).toUpperCase() + Math.floor(Math.random() * 1000000), // e.g., DEP123456, WDR789012
        type, // 'Deposit', 'Withdrawal', or 'Interest Growth'
        amount: parseFloat(amount),
        balance: parseFloat(balanceAfter),
        time: new Date().toLocaleString()
      };
    },
    checkAndApplyGrowth() {
      const lastGrowthDateString = localStorage.getItem('lastSavingsGrowthDate');
      let lastGrowthDate = null;

      if (lastGrowthDateString) {
        lastGrowthDate = new Date(lastGrowthDateString);
      } else {
        // If no last growth date, set it to now to start the 30-day cycle
        localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString());
        lastGrowthDate = new Date();
      }

      const today = new Date();
      const timeDifference = today.getTime() - lastGrowthDate.getTime();
      const daysDifference = timeDifference / (1000 * 60 * 60 * 24);

      if (daysDifference >= 30) {
        this.applyAutomaticGrowth();
      }
    },
    applyAutomaticGrowth() {
      const growthRate = 0.08; // 8% monthly growth
      const growthAmount = this.savingsBalance * growthRate;
      this.savingsBalance += growthAmount;

      // Record growth as a transaction
      const txn = this.createTransaction('Interest Growth', growthAmount, this.savingsBalance);
      this.transactions.unshift(txn); // Add to the beginning for newest first

      this.saveSavingsData();
      localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString());
      alert(`Automatic monthly growth applied! Your new balance is Ksh. ${this.savingsBalance.toFixed(2)}`);
    },
    toggleBlur() {
      this.isBlurred = !this.isBlurred;
    },
    // *** IMPORTANT: Use Vue Router for navigation! ***
    navigateToDepositPage() {
      this.$router.push({ name: 'DepositSavings' });
    },
    navigateToWithdrawPage() {
      this.$router.push({ name: 'WithdrawSavings' });
    },
    toggleAddGoalForm() {
      this.showAddGoalForm = !this.showAddGoalForm;
      if (!this.showAddGoalForm) {
        this.resetNewGoalForm(); // Clear form if hidden
      }
    },
    addSavingsGoal() {
      if (this.newGoal.amount <= 0 || !this.newGoal.dueDate) {
        alert('Please enter a valid target amount and due date for your goal.');
        return;
      }
      this.savingsGoals.push({ ...this.newGoal, createdAt: new Date().toISOString() });
      this.saveSavingsGoals();
      this.showMessage('New savings goal added successfully!', 'success');
      this.resetNewGoalForm();
      this.showAddGoalForm = false; // Hide form after adding
    },
    removeGoal(index) {
      if (confirm('Are you sure you want to remove this goal? This cannot be undone.')) {
        this.savingsGoals.splice(index, 1);
        this.saveSavingsGoals();
        this.showMessage('Savings goal removed.', 'info');
      }
    },
    resetNewGoalForm() {
      this.newGoal = {
        reason: '',
        amount: null,
        dueDate: ''
      };
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    // Basic message display for alerts; can be expanded with a proper notification system
    showMessage(msg, type) {
      console.log(`${type.toUpperCase()}: ${msg}`);
      // In a real app, you might use a toast library or custom notification component here.
    }
  },
  // Watch for route changes to re-load data when returning to this page
  // This is a robust way to ensure data freshness when navigating back
  watch: {
    '$route.path': {
      immediate: true, // Run on initial render
      handler(newPath, oldPath) {
        // Only reload if we are explicitly returning to the user-savings page
        // from a path that implies a transaction (deposit or withdrawal)
        if (newPath === '/user-savings' && oldPath && (oldPath.includes('/deposit-savings') || oldPath.includes('/withdraw-savings'))) {
          this.loadSavingsData(); // Reload balance and transactions
          this.loadSavingsGoals(); // Reload goals
        }
      }
    }
  }
};
</script>

<style scoped>
.user-savings-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
}

.header-container {
  display: flex; /* Keep flex for potential future elements, but center content */
  flex-direction: column; /* Stack h1 and p vertically */
  align-items: center; /* Center horizontally */
  margin-bottom: 20px;
}

/* Specific style for centered header if it's dynamic */
.centered-header {
  justify-content: center; /* Override space-between if only one item is there */
}

.current-date {
  font-size: 0.9em;
  color: #555;
  margin-top: 5px; /* Small space below the main heading */
}

.savings-balance-card {
  background-color: #007BFF;
  color: white;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.balance-display-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.balance-amount {
  font-size: 2em;
  margin: 0;
  transition: filter 0.3s ease-in-out;
  font-weight: bold;
}

.blurred-balance {
  filter: blur(4px);
}

.blur-toggle-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.5em;
  cursor: pointer;
  padding: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s ease;
}

.blur-toggle-btn:hover {
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
}

.options-container {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
  margin: 20px 0;
}

.option-box {
  border: 1px solid #007BFF;
  border-radius: 8px;
  padding: 20px;
  width: 200px;
  text-align: center;
  cursor: pointer;
  transition: box-shadow 0.3s;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.option-box h3 {
  margin-top: 0;
  margin-bottom: 15px;
  color: #007BFF;
}

.option-box .icon {
  font-size: 2em;
  margin-bottom: 15px;
}

button {
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: auto;
  width: 90%;
  transition: background-color 0.2s ease;
}

button:hover {
  background-color: #0056b3;
}

.separator {
  border: 0;
  height: 1px;
  background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 123, 255, 0.75), rgba(0, 0, 0, 0));
  margin: 40px 0;
}

/* Savings Goals Section */
.savings-goals-section {
  margin-top: 30px;
  padding: 20px;
  max-width: 95%;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #eee;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  background-color: #fcfcfc;
}

.savings-goals-section h2 {
  color: #007BFF;
  margin-bottom: 15px;
}

.add-goal-button {
  margin-bottom: 20px;
  background-color: #28a745; /* Green for add button */
  width: auto;
  padding: 10px 30px;
}
.add-goal-button:hover {
  background-color: #218838;
}

.add-goal-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 400px;
  margin: 0 auto 20px auto;
  padding: 15px;
  border: 1px dashed #007BFF;
  border-radius: 8px;
  background-color: #e6f7ff;
}

.add-goal-form label {
  text-align: left;
  font-weight: bold;
  color: #0056b3;
}

.add-goal-form input[type="text"],
.add-goal-form input[type="number"],
.add-goal-form input[type="date"] {
  padding: 8px;
  border: 1px solid #007BFF;
  border-radius: 4px;
  width: auto;
  margin-bottom: 5px;
}

.add-goal-form button {
  width: auto;
  background-color: #007BFF;
  align-self: flex-end;
  margin-top: 10px;
}
.add-goal-form button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.no-goals-message {
  padding: 20px;
  color: #666;
  font-style: italic;
}

.goals-list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}

.goal-item {
  border: 1px solid #007BFF;
  border-radius: 8px;
  padding: 15px;
  width: 250px;
  text-align: left;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.goal-item h3 {
  color: #007BFF;
  margin-top: 0;
  margin-bottom: 10px;
}

.goal-item p {
  margin: 5px 0;
  color: #333;
}

.goal-item .remove-goal-button {
  background-color: #dc3545; /* Red for remove */
  width: 100%;
  margin-top: 15px;
  padding: 8px 15px;
}
.goal-item .remove-goal-button:hover {
  background-color: #c82333;
}

/* Styles for the transaction history table */
.transaction-history-table { /* Changed class name for clarity */
  margin-top: 30px;
  padding: 20px;
  max-width: 95%;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #eee;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  background-color: #fcfcfc;
}

.transaction-history-table h2 {
  color: #007BFF;
  margin-bottom: 15px;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

th,
td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

thead {
  background-color: #007BFF;
  color: white;
}

tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

tbody tr:hover {
  background-color: #e9e9e9;
}

.no-transactions-message {
  padding: 20px;
  color: #666;
  font-style: italic;
  margin-top: 20px;
}

</style>