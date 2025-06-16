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
        <h3>Pay Data</h3>
        <span class="icon">📶</span>
        <input type="number" v-model.number="dataAmount" placeholder="Enter Amount" />
        <input type="password" v-model="dataPin" placeholder="Enter PIN" maxlength="4" />
        <button @click="processDataPayment">Buy Data</button>
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
      airtimeAmount: 0,
      airtimePin: '',
      dataAmount: 0,
      dataPin: '',
      transactions: [],
      correctPin: '2007' // Handcoded PIN
    };
  },
  mounted() {
    // Load balance and transactions from local storage on component mount
    this.loadDataFromLocalStorage();
  },
  methods: {
    loadDataFromLocalStorage() {
      const savedBalance = localStorage.getItem('dailyExpensesBalance');
      const savedTxns = localStorage.getItem('dailyExpensesTransactions');

      if (savedBalance) {
        this.balance = parseFloat(savedBalance);
      } else {
        // If no balance is saved, initialize and save the default balance
        localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      }
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
      }
    },
    processAirtimePayment() {
      if (this.airtimeAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0) for Airtime.');
        return;
      }
      if (!this.validatePin(this.airtimePin)) {
        alert('Enter correct PIN for Airtime transaction.');
        return;
      }
      this.processDirectTransaction(this.airtimeAmount, 'Airtime');
      this.airtimeAmount = 0; // Reset amount after transaction
      this.airtimePin = ''; // Reset PIN
    },
    processDataPayment() {
      if (this.dataAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0) for Data.');
        return;
      }
      if (!this.validatePin(this.dataPin)) {
        alert('Enter correct PIN for Data transaction.');
        return;
      }
      this.processDirectTransaction(this.dataAmount, 'Data');
      this.dataAmount = 0;
      this.dataPin = '';
    },
    processDirectTransaction(amount, type) {
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
    navigateToDailyExpenseFunction(type) {
      // Navigate to the DailyExpenseFunction page, passing the expense type as a query parameter
      this.$router.push({ name: 'DailyExpenseFunction', query: { type: type } });
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
  },
  // Use a watcher to reload data if navigating back to this route
  watch: {
    '$route.path': {
      immediate: true, // Run on initial render
      handler(newPath, oldPath) {
        // Only reload if we are returning to this specific page, potentially from the function page
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

---

## Daily Expense Function Component (`DailyExpenseFunction.vue`)

This new component provides the input fields for "Fare," "Food," and "Entertainment" expenses, processes the payment, and then saves the transaction to `localStorage` before returning to the main `DailyExpenses` page.

```vue
<template>
  <div class="daily-expense-function-page">
    <h1>{{ expenseType }} Payment</h1>
    <p><strong>Current Balance:</strong> Ksh. {{ balance }}</p>

    <div class="payment-box">
      <p>Please enter the amount and your PIN to proceed with {{ expenseType }} payment.</p>
      <input type="number" v-model.number="amount" placeholder="Enter Amount" />
      <input type="password" v-model="pin" placeholder="Enter PIN" maxlength="4" />
      <button @click="processExpense">Pay Now</button>
      <button @click="goBack">Done</button>
    </div>

    <p v-if="message" :class="{'message-success': isSuccess, 'message-error': !isSuccess}" class="transaction-message">
      {{ message }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'DailyExpenseFunction',
  data() {
    return {
      balance: 0,
      amount: 0,
      pin: '',
      correctPin: '2007', // Handcoded PIN
      expenseType: '',
      message: '',
      isSuccess: false,
      transactions: [] // To hold transactions from localStorage
    };
  },
  created() {
    // Get expense type from route query parameter
    this.expenseType = this.$route.query.type || 'Expense';
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
        // Fallback if balance isn't in local storage (should ideally be set by DailyExpenses)
        this.balance = 12390; // Default initial balance
        localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      }
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
      }
    },
    processExpense() {
      if (this.amount <= 0) {
        this.setMessage('Please enter a valid amount (must be greater than 0).', false);
        return;
      }
      if (this.pin !== this.correctPin) {
        this.setMessage('Enter correct PIN.', false);
        return;
      }

      if (this.balance < this.amount) {
        this.setMessage('INSUFFICIENT BALANCE', false);
        return;
      }

      // Process transaction
      this.balance -= this.amount;
      const txn = {
        code: 'DE' + Math.floor(Math.random() * 1000000),
        type: this.expenseType,
        amount: this.amount,
        time: new Date().toLocaleString(),
        balance: this.balance // Balance after this specific transaction
      };
      this.transactions.push(txn);

      this.saveDataToLocalStorage();
      this.setMessage(`${this.expenseType} Payment Successful! New Balance: Ksh. ${this.balance}`, true);

      // Navigate back after a short delay to allow the user to see the success message
      setTimeout(() => {
        this.$router.push({ name: 'DailyExpenses' }); // Navigate back by name
      }, 1500);
    },
    setMessage(msg, success) {
      this.message = msg;
      this.isSuccess = success;
    },
    saveDataToLocalStorage() {
      localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      localStorage.setItem('dailyExpensesTransactions', JSON.stringify(this.transactions));
    },
    goBack() {
      // Allow user to go back without processing the payment
      this.$router.push({ name: 'DailyExpenses' });
    }
  }
};
</script>

<style scoped>
.daily-expense-function-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
  border: 1px solid #007BFF;
  border-radius: 8px;
  padding: 30px;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #007BFF;
  margin-bottom: 20px;
}

p {
  margin-bottom: 15px;
  font-size: 1.1em;
}

.payment-box input {
  width: 80%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #007BFF;
  border-radius: 5px;
}

.payment-box button {
  padding: 10px 25px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1.1em;
  margin: 15px 5px; /* Added horizontal margin */
}

.payment-box button:hover {
  background-color: #0056b3;
}

.transaction-message {
  margin-top: 20px;
  padding: 10px;
  border-radius: 5px;
  font-weight: bold;
}

.message-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.message-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}
</style>

---

## Setup Instructions:

1.  **Create `DailyExpenseFunction.vue`:** Save the content of the "Daily Expense Function Component" into a new file named `DailyExpenseFunction.vue` inside your `src/components` folder.

2.  **Update `DailyExpenses.vue`:** Replace the entire content of your existing `DailyExpenses.vue` file with the "Daily Expenses Component" code block.

3.  **Configure Vue Router:**
    You'll need to make sure your Vue Router configuration (typically `src/router/index.js` or `src/router.js`) includes the new route for `DailyExpenseFunction.vue`.

    ```javascript
    // src/router/index.js (Example Vue 3 setup with createRouter)
    import { createRouter, createWebHistory } from 'vue-router';
    // Import your components
    import DailyExpenses from '../components/DailyExpenses.vue'; // Adjust path if needed
    import DailyExpenseFunction from '../components/DailyExpenseFunction.vue'; // Adjust path if needed

    const routes = [
      // ... your other routes
      {
        path: '/daily-expenses',
        name: 'DailyExpenses',
        component: DailyExpenses
      },
      {
        path: '/daily-expense-function',
        name: 'DailyExpenseFunction',
        component: DailyExpenseFunction
        // We use query parameters for `type`, so `props: true` is not strictly needed here
      }
    ];

    const router = createRouter({
      history: createWebHistory(process.env.BASE_URL),
      routes
    });

    export default router;
    ```

### How it Works:

* **`DailyExpenses.vue`**:
    * **Direct Payments:** "Buy Airtime" and "Pay Data" still handle the amount and PIN input directly on this page, processing the transaction and updating the table and `localStorage` on the spot.
    * **Navigation:** For "Pay Fare," "Food," and "Entertainment," the buttons no longer have direct inputs. Instead, they use `$router.push()` to navigate to the `DailyExpenseFunction.vue` page. They pass the specific `type` of expense (e.g., 'Fare') as a **query parameter** in the URL (e.g., `/daily-expense-function?type=Fare`).
    * **Data Reload:** When you return from `DailyExpenseFunction.vue` (after completing a transaction), the `DailyExpenses.vue` component will reload its data from `localStorage` in its `mounted` hook (or via the `watch` on `$route.path`). This ensures the transaction history table and the displayed balance are always up-to-date.

* **`DailyExpenseFunction.vue`**:
    * **Dynamic Title:** It retrieves the `expenseType` from the URL's query parameters (`this.$route.query.type`) to dynamically display the title (e.g., "Fare Payment").
    * **Input & Validation:** It provides dedicated input fields for the `amount` and `pin` for that specific expense. It includes validation for a positive amount, correct PIN, and sufficient balance.
    * **Transaction Processing:** When the "Pay Now" button is clicked, if validations pass, it processes the transaction: it deducts the amount from the balance, creates a transaction record, and *directly updates the `dailyExpensesBalance` and `dailyExpensesTransactions` keys in `localStorage`*.
    * **Navigation Back:** After successfully processing the transaction (with a small delay to show a message), or if the user clicks "Done," it navigates back to the `DailyExpenses` page (`/daily-expenses`).

This setup neatly separates the direct daily expenses from those that require a dedicated payment flow, providing a more structured user experience.