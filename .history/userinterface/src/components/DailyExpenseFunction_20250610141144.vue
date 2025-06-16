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
      transactions: [] // This will store the transactions from localStorage to update them
    };
  },
  created() {
    // Get expense type from route query parameter (e.g., ?type=Fare)
    this.expenseType = this.$route.query.type || 'Expense';
  },
  mounted() {
    // Load balance and transactions from local storage when the page loads
    this.loadDataFromLocalStorage();
  },
  methods: {
    loadDataFromLocalStorage() {
      const savedBalance = localStorage.getItem('dailyExpensesBalance');
      const savedTxns = localStorage.getItem('dailyExpensesTransactions');

      if (savedBalance) {
        this.balance = parseFloat(savedBalance);
      } else {
        // Fallback to a default balance if not found (should ideally be initialized by DailyExpenses)
        this.balance = 12390;
        localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      }
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
      }
    },
    processExpense() {
      // Input Validation
      if (this.amount <= 0) {
        this.setMessage('Please enter a valid amount (must be greater than 0).', false);
        return;
      }
      if (this.pin !== this.correctPin) {
        this.setMessage('Enter correct PIN.', false);
        return;
      }

      // Balance Check
      if (this.balance < this.amount) {
        this.setMessage('INSUFFICIENT BALANCE', false);
        return;
      }

      // Process Transaction if all checks pass
      this.balance -= this.amount; // Deduct amount from balance
      const txn = {
        code: 'DE' + Math.floor(Math.random() * 1000000), // Generate a transaction code
        type: this.expenseType,
        amount: this.amount,
        time: new Date().toLocaleString(),
        balance: this.balance // Store balance after this transaction
      };
      this.transactions.push(txn); // Add new transaction to the list

      // Save updated balance and transactions to local storage
      this.saveDataToLocalStorage();

      this.setMessage(`${this.expenseType} Payment Successful! New Balance: Ksh. ${this.balance}`, true);

      // Navigate back to DailyExpenses page after a short delay
      setTimeout(() => {
        this.$router.push({ name: 'DailyExpenses' });
      }, 1500); // 1.5 seconds delay to show success message
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
      // Simply navigate back to the DailyExpenses page without processing
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
  margin: 15px 5px;
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