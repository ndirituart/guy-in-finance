<template>
  <div class="user-savings-page">
    <div class="header-container">
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

    <div v-if="transactions.length" class="receipt-table">
      <h2>Savings Transaction History</h2>
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Time</th>
            <th>Balance After</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(txn, index) in transactions" :key="index">
            <td>{{ txn.code }}</td>
            <td>{{ txn.type }}</td>
            <td>{{ txn.amount.toFixed(2) }}</td>
            <td>{{ txn.time }}</td>
            <td>{{ txn.balance.toFixed(2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserSavings',
  data() {
    return {
      savingsBalance: 5000, // Initial savings balance
      isBlurred: false,
      currentDate: '',
      transactions: [], // To store savings transactions
    };
  },
  mounted() {
    this.updateCurrentDate();
    this.loadSavingsData();
    this.checkAndApplyGrowth();

    // Listen for balance updates from deposit/withdraw pages
    // 'storage' event fires when localStorage changes in another tab/window
    // or sometimes when changes are committed in the same tab.
    window.addEventListener('storage', this.handleStorageChange);
  },
  beforeUnmount() {
    // Clean up event listener
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      // Only react if the relevant localStorage keys change
      if (event.key === 'userSavingsBalance' || event.key === 'userSavingsTransactions') {
        this.loadSavingsData();
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
        // Initialize balance in localStorage if it doesn't exist
        localStorage.setItem('userSavingsBalance', this.savingsBalance.toFixed(2));
      }

      const savedTxns = localStorage.getItem('userSavingsTransactions');
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
      } else {
        // Initialize transactions in localStorage if it doesn't exist
        localStorage.setItem('userSavingsTransactions', JSON.stringify([]));
      }
    },
    saveSavingsData() {
      localStorage.setItem('userSavingsBalance', this.savingsBalance.toFixed(2));
      localStorage.setItem('userSavingsTransactions', JSON.stringify(this.transactions));
    },
    createTransaction(type, amount, balanceAfter) {
      return {
        code: 'SAV' + Math.floor(Math.random() * 1000000), // Savings transaction code
        type, // 'Deposit', 'Withdrawal', or 'Interest Growth'
        amount: parseFloat(amount),
        time: new Date().toLocaleString(),
        balance: parseFloat(balanceAfter)
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

      if (daysDifference >= 30) { // If 30 or more days have passed
        this.applyAutomaticGrowth();
      }
    },
    applyAutomaticGrowth() {
      const growthRate = 0.08; // 8% monthly growth
      const growthAmount = this.savingsBalance * growthRate;
      this.savingsBalance += growthAmount;

      // Record growth as a transaction
      const txn = this.createTransaction('Interest Growth', growthAmount, this.savingsBalance);
      this.transactions.push(txn); // Add to local array

      this.saveSavingsData(); // Save new balance and transactions to localStorage
      localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString()); // Update last growth date to now
      alert(`Automatic monthly growth applied! Your new balance is Ksh. ${this.savingsBalance.toFixed(2)}`);
    },
    toggleBlur() {
      this.isBlurred = !this.isBlurred;
    },
    navigateToDepositPage() {
      // Use Vue Router to navigate
      this.$router.push({ name: 'DepositSavings' });
    },
    navigateToWithdrawPage() {
      // Use Vue Router to navigate
      this.$router.push({ name: 'WithdrawSavings' });
    }
  },
  // Watch for route changes to re-load data when returning to this page
  // This is crucial if you don't solely rely on the 'storage' event which might not fire instantly
  watch: {
    '$route.path': {
      immediate: true, // Run on initial render
      handler(newPath, oldPath) {
        // Only reload if we are returning to this specific page from deposit/withdraw
        if (newPath === '/user-savings' && oldPath && (oldPath.includes('/deposit-savings') || oldPath.includes('/withdraw-savings'))) {
          this.loadSavingsData();
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
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.current-date {
  font-size: 0.9em;
  color: #555;
}

.savings-balance-card {
  background-color: #007BFF;
  color: white;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
  max-width: 400px; /* Make the card containing balance smaller */
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.balance-display-container {
  display: flex;
  align-items: center;
  justify-content: center; /* Center the balance and button */
  gap: 10px; /* Space between balance and button */
}

.balance-amount {
  font-size: 2em; /* Adjust font size for balance */
  margin: 0; /* Remove default paragraph margin */
  transition: filter 0.3s ease-in-out; /* Smooth blur transition */
  font-weight: bold;
}

.blurred-balance {
  filter: blur(4px);
}

.blur-toggle-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.5em; /* Size of the eye icon */
  cursor: pointer;
  padding: 5px;
  display: flex; /* To center the icon visually */
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
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); /* Lighter shadow for option boxes */
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
  margin-top: auto; /* Push button to the bottom */
  width: 90%;
  transition: background-color 0.2s ease;
}

button:hover {
  background-color: #0056b3;
}

/* Styles for the transaction history table */
.receipt-table {
  margin-top: 30px;
  padding: 20px;
  max-width: 95%;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #eee; /* Light border for the table container */
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.receipt-table h2 {
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
    background-color: #e9e9e9; /* Highlight row on hover */
}
</style>