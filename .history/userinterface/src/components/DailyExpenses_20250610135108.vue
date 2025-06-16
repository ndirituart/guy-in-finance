<template>
  <div class="daily-expenses-page">
    <h1>Daily Expenses</h1>
    <p><strong>Current Balance:</strong> {{ balance }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Buy Airtime</h3>
        <span class="icon">📱</span>
        <input type="number" v-model.number="airtimeAmount" placeholder="Enter Amount" />
        <input type="password" v-model="airtimePin" placeholder="Enter PIN" maxlength="4" />
        <button @click="processAirtimePayment">Buy Airtime</button>
      </div>

      <div class="option-box">
        <h3>Pay Fare</h3>
        <span class="icon">🚌</span>
        <input type="number" v-model.number="fareAmount" placeholder="Enter Amount" />
        <input type="password" v-model="farePin" placeholder="Enter PIN" maxlength="4" />
        <button @click="processFarePayment">Pay Fare</button>
      </div>

      <div class="option-box">
        <h3>Food</h3>
        <span class="icon">🍔</span>
        <input type="number" v-model.number="foodAmount" placeholder="Enter Amount" />
        <input type="password" v-model="foodPin" placeholder="Enter PIN" maxlength="4" />
        <button @click="processFoodPayment">Buy Food</button>
      </div>

      <div class="option-box">
        <h3>Entertainment</h3>
        <span class="icon">🎬👗💍</span>
        <input type="number" v-model.number="entertainmentAmount" placeholder="Enter Amount" />
        <input type="password" v-model="entertainmentPin" placeholder="Enter PIN" maxlength="4" />
        <button @click="processEntertainmentPayment">Entertainment</button>
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
      balance: 12390, // Initial balance
      airtimeAmount: 100, // Example amount
      airtimePin: '',
      fareAmount: 50, // Example amount
      farePin: '',
      foodAmount: 200, // Example amount
      foodPin: '',
      entertainmentAmount: 300, // Example amount
      entertainmentPin: '',
      transactions: [],
      correctPin: '2007' // Handcoded PIN
    };
  },
  mounted() {
    // Load balance and transactions from local storage on component mount
    const savedBalance = localStorage.getItem('dailyExpensesBalance');
    const savedTxns = localStorage.getItem('dailyExpensesTransactions');

    if (savedBalance) {
      this.balance = parseFloat(savedBalance);
    }
    if (savedTxns) {
      this.transactions = JSON.parse(savedTxns);
    }
  },
  methods: {
    processAirtimePayment() {
      if (this.airtimeAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0) for Airtime.');
        return;
      }
      if (!this.validatePin(this.airtimePin)) {
        alert('Enter correct PIN for Airtime transaction.');
        return;
      }
      this.processTransaction(this.airtimeAmount, 'Airtime');
      this.airtimeAmount = 0; // Reset amount after transaction
      this.airtimePin = ''; // Reset PIN
    },
    processFarePayment() {
      if (this.fareAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0) for Fare.');
        return;
      }
      if (!this.validatePin(this.farePin)) {
        alert('Enter correct PIN for Fare transaction.');
        return;
      }
      this.processTransaction(this.fareAmount, 'Fare');
      this.fareAmount = 0; // Reset amount after transaction
      this.farePin = ''; // Reset PIN
    },
    processFoodPayment() {
      if (this.foodAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0) for Food.');
        return;
      }
      if (!this.validatePin(this.foodPin)) {
        alert('Enter correct PIN for Food transaction.');
        return;
      }
      this.processTransaction(this.foodAmount, 'Food');
      this.foodAmount = 0; // Reset amount after transaction
      this.foodPin = ''; // Reset PIN
    },
    processEntertainmentPayment() {
      if (this.entertainmentAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0) for Entertainment.');
        return;
      }
      if (!this.validatePin(this.entertainmentPin)) {
        alert('Enter correct PIN for Entertainment transaction.');
        return;
      }
      this.processTransaction(this.entertainmentAmount, 'Entertainment');
      this.entertainmentAmount = 0; // Reset amount after transaction
      this.entertainmentPin = ''; // Reset PIN
    },
    processTransaction(amount, type) {
      if (this.balance >= amount) {
        this.balance -= amount;
        const txn = this.createTransaction(type, amount);
        this.transactions.push({ ...txn, balance: this.balance });
        alert(`${type} Payment Successful! New Balance: Ksh. ${this.balance}`);
        this.saveToLocalStorage();
      } else {
        alert('INSUFFICIENT BALANCE');
      }
    },
    createTransaction(type, amount) {
      return {
        code: 'DE' + Math.floor(Math.random() * 1000000), // Daily Expenses transaction code
        type,
        amount,
        time: new Date().toLocaleString()
      };
    },
    validatePin(pin) {
      return pin === this.correctPin;
    },
    saveToLocalStorage() {
      localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      localStorage.setItem('dailyExpensesTransactions', JSON.stringify(this.transactions));
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
  width: 250px; /* Increased width to accommodate new inputs */
  text-align: center;
  transition: box-shadow 0.3s;
}

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.icon {
  font-size: 40px; /* Adjust icon size */
  margin-bottom: 10px; /* Added margin for spacing */
  display: block; /* Make icon block to ensure new line for input */
}

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