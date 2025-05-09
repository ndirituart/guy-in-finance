<template>
    <div class="daily-expenses-page">
      <h1>Daily Expenses</h1>
  
      <div class="options-container">
        <div class="option-box" @click="buyAirtime">
          <h3>Buy Airtime</h3>
          <span class="icon">📱</span> <!-- Phone icon -->
          <button @click="processAirtimePayment">Pay Ksh. {{ airtimeAmount }}</button>
        </div>
  
        <div class="option-box" @click="payFare">
          <h3>Pay Fare</h3>
          <span class="icon">🚌</span> <!-- Bus icon -->
          <button @click="processFarePayment">Pay Ksh. {{ fareAmount }}</button>
        </div>
  
        <div class="option-box" @click="buyFood">
          <h3>Food</h3>
          <span class="icon">🍔</span> <!-- Food icon -->
          <button @click="processFoodPayment">Pay Ksh. {{ foodAmount }}</button>
        </div>
  
        <div class="option-box" @click="entertainment">
          <h3>Entertainment</h3>
          <span class="icon">🎬</span> <!-- Movie icon -->
          <span class="icon">👗</span> <!-- Dress icon -->
          <span class="icon">💍</span> <!-- Jewelry icon -->
          <button @click="processEntertainmentPayment">Pay Ksh. {{ entertainmentAmount }}</button>
        </div>
      </div>
  
      <div v-if="transactionMessage" class="transaction-message">
        <p>{{ transactionMessage }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'DailyExpenses',
    data() {
      return {
        accountBalance: 12390, // Replace with actual balance from your data
        airtimeAmount: 100, // Example amount for airtime
        fareAmount: 50, // Example amount for fare
        foodAmount: 200, // Example amount for food
        entertainmentAmount: 300, // Example amount for entertainment
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
        if (this.accountBalance >= amount) {
          this.accountBalance -= amount;
          this.transactionMessage = `${type} Payment Successful! New Balance: Ksh. ${this.accountBalance}`;
        } else {
          this.transactionMessage = 'INSUFFICIENT BALANCE';
        }
      },
    },
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
    font-size: 40px; /* Adjust icon size */
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