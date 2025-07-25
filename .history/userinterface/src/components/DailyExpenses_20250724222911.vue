<template>
  <div class="daily-expenses-page">
    <h1>Daily Expenses</h1>
    <p><strong>Current Balance:</strong> {{ balance }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Buy Airtime</h3>
        <span class="icon">📱</span>
        <button @click="selectAirtimeProvider('Safaricom')" class="provider-btn">
          <img src="@/assets/Safaricom.png" class="provider-logo" />
         
          
        </button>
        <button @click="selectAirtimeProvider('Airtel')" class="provider-btn">
           <img src="@/assets/Airtel.png" class="provider-logo" />
         
          <!--Airtel-->
        </button>
        <button @click="selectAirtimeProvider('Telkom')" class="provider-btn">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Telkom_Kenya_Logo.png/640px-Telkom_Kenya_Logo.png" alt="Telkom" class="provider-logo" />
          Telkom
        </button>
      </div>

      <div class="option-box">
        <h3>Buy Data</h3>
        <span class="icon">📶</span>
        <button @click="selectDataProvider('Safaricom')" class="provider-btn">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Safaricom_Logo.svg/1200px-Safaricom_Logo.svg.png" alt="Safaricom" class="provider-logo" />
          Safaricom
        </button>
        <button @click="selectDataProvider('Airtel')" class="provider-btn">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Airtel_Logo.svg/1200px-Airtel_Logo.svg.png" alt="Airtel" class="provider-logo" />
          Airtel
        </button>
        <button @click="selectDataProvider('Telkom')" class="provider-btn">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Telkom_Kenya_Logo.png/640px-Telkom_Kenya_Logo.png" alt="Telkom" class="provider-logo" />
          Telkom
        </button>
      </div>

      <div class="option-box">
        <h3>Pay Fare</h3>
        <span class="icon">🚌</span>
        <button @click="navigateToDailyExpenseFunction('Fare')">Go to Payment</button>
      </div>

      <div class="option-box">
        <h3>Food</h3>
        <span class="icon">🍔</span>
        <button @click="navigateToDailyExpenseFunction('Food')">Go to Payment</button>
      </div>

      <div class="option-box">
        <h3>Entertainment</h3>
        <span class="icon">🎬👗💍</span>
        <button @click="navigateToDailyExpenseFunction('Entertainment')">Go to Payment</button>
      </div>
    </div>

    <div v-if="transactions.length" class="receipt-table">
      <h2>Transaction History</h2>
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
            <td>{{ txn.amount }}</td>
            <td>{{ txn.time }}</td>
            <td>{{ txn.balance }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DailyExpenses',
  data() {
    return {
      balance: 12390, // Initial balance, will be loaded from local storage
      transactions: [],
      // No need for airtimeAmount, dataAmount, airtimePin, dataPin here anymore
      correctPin: '2007' // Handcoded PIN
    };
  },
  mounted() {
    this.loadDataFromLocalStorage();
  },
  methods: {
    loadDataFromLocalStorage() {
      const savedBalance = localStorage.getItem('dailyExpensesBalance');
      const savedTxns = localStorage.getItem('dailyExpensesTransactions');

      if (savedBalance) {
        this.balance = parseFloat(savedBalance);
      } else {
        localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      }
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
      }
    },
    selectAirtimeProvider(provider) {
      this.$router.push({
        name: 'DailyExpenseFunction',
        query: { type: 'Airtime', provider: provider }
      });
    },
    selectDataProvider(provider) {
      this.$router.push({
        name: 'DailyExpenseFunction',
        query: { type: 'Data', provider: provider }
      });
    },
    navigateToDailyExpenseFunction(type) {
      this.$router.push({ name: 'DailyExpenseFunction', query: { type: type } });
    },
    // The processAirtimePayment, processDataPayment, processDirectTransaction, createTransaction, validatePin methods
    // are now moved to DailyExpenseFunction.vue or handled differently.
    // We only keep `saveToLocalStorage` here as it pertains to this component's data.
    saveToLocalStorage() {
      localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      localStorage.setItem('dailyExpensesTransactions', JSON.stringify(this.transactions));
    }
  },
  watch: {
    '$route.path': {
      immediate: true,
      handler(newPath, oldPath) {
        if (newPath === '/daily-expenses' && oldPath && oldPath.includes('/daily-expense-function')) {
          this.loadDataFromLocalStorage();
        }
      }
    }
  }
};
</script>

<style scoped>
.daily-expenses-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
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
  width: 250px;
  text-align: center;
  transition: box-shadow 0.3s;
}

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.icon {
  font-size: 40px;
  margin-bottom: 10px;
  display: block;
}

/* Provider buttons for Airtime/Data */
.provider-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px 15px;
  margin: 5px auto; /* Center the buttons */
  background-color: #f0f0f0;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 5px;
  cursor: pointer;
  width: 80%; /* Match input width */
}

.provider-btn:hover {
  background-color: #e0e0e0;
}

.provider-logo {
  height: 20px; /* Adjust logo size */
  margin-right: 8px;
}


/* Existing styles for inputs and buttons if they appear here */
input[type="number"],
input[type="password"] {
  width: 80%;
  padding: 10px;
  margin: 8px 0;
  border: 1px solid #007BFF;
  border-radius: 5px;
}

button {
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 10px;
}

button:hover {
  background-color: #0056b3;
}

.receipt-table {
  margin-top: 30px;
  padding: 20px;
  max-width: 95%;
  margin-left: auto;
  margin-right: auto;
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
</style>