<template>
  <div class="daily-expenses-page">
    <h1>Daily Expenses</h1>
    <p class="current-balance">Current Balance: Ksh. {{ balanceStore.accountBalance.toLocaleString() }}</p>

    <div class="options-container">
      <div class="option-box" @click="buyAirtime">
        <h3>Buy Airtime</h3>
        <span class="icon">📱</span>
        <button @click="processAirtimePayment">Pay Ksh. {{ airtimeAmount }}</button>
      </div>

      <div class="option-box" @click="payFare">
        <h3>Pay Fare</h3>
        <span class="icon">🚌</span>
        <button @click="processFarePayment">Pay Ksh. {{ fareAmount }}</button>
      </div>

      <div class="option-box" @click="buyFood">
        <h3>Food</h3>
        <span class="icon">🍔</span>
        <button @click="processFoodPayment">Pay Ksh. {{ foodAmount }}</button>
      </div>

      <div class="option-box" @click="entertainment">
        <h3>Entertainment</h3>
        <span class="icon">🎬</span>
        <span class="icon">👗</span>
        <span class="icon">💍</span>
        <button @click="processEntertainmentPayment">Pay Ksh. {{ entertainmentAmount }}</button>
      </div>
    </div>

    <div v-if="transactionMessage" class="transaction-message">
      <p>{{ transactionMessage }}</p>
    </div>
  </div>
</template>

<script>
import { useBalanceStore } from ''; // Adjust path

export default {
  name: 'DailyExpenses',
  setup() { // Use setup for Pinia integration in Options API components
    const balanceStore = useBalanceStore(); // Initialize the store
    return {
      balanceStore, // Make the store available in the template
    };
  },
  data() {
    return {
      // accountBalance: 12390, // REMOVE THIS LINE - now from store
      airtimeAmount: 100,
      fareAmount: 50,
      foodAmount: 200,
      entertainmentAmount: 300,
      transactionMessage: '',
    };
  },
  methods: {
    processAirtimePayment() {
      this.processTransaction(this.airtimeAmount, 'Airtime');
    },
    processFarePayment() {
      this.processTransaction(this.fareAmount, 'Fare');
    },
    processFoodPayment() {
      this.processTransaction(this.foodAmount, 'Food');
    },
    processEntertainmentPayment() {
      this.processTransaction(this.entertainmentAmount, 'Entertainment');
    },
    processTransaction(amount, type) {
      // Use the store's deductAmount action
      const success = this.balanceStore.deductAmount(amount);

      if (success) {
        this.transactionMessage = `${type} Payment Successful! New Balance: Ksh. ${this.balanceStore.accountBalance.toLocaleString()}`;
      } else {
        this.transactionMessage = 'INSUFFICIENT BALANCE';
      }
    },
  },
};
</script>

<style scoped>
/* Your existing styles remain unchanged */
.daily-expenses-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
}

.current-balance {
  font-size: 1.5em;
  font-weight: bold;
  color: #007BFF;
  margin-bottom: 25px;
}

.options-container {
  display: flex;
  justify-content: space-around;
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
}

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.icon {
  font-size: 40px;
}

button {
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.transaction-message {
  margin-top: 20px;
  font-weight: bold;
}
</style>