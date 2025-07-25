<template>
  <div class="deposit-savings-page">
    <h2>Deposit into Savings</h2>
    <p>Current Savings Balance: Ksh. {{ currentSavingsBalance.toFixed(2) }}</p>

    <div class="deposit-form">
      <label for="depositAmount">Enter Amount to Deposit:</label>
      <input type="number" id="depositAmount" v-model.number="depositAmount" placeholder="e.g., 500" min="1" />
      <input type="password" v-model="pin" placeholder="Enter PIN" maxlength="4" />
      <button @click="processDeposit">Deposit</button>
    </div>

    <button class="back-button" @click="goBackToSavings">Back to Savings</button>

    <div v-if="message" :class="['popup-message', messageType]">
      <p>{{ message }}</p>
    </div>
  </div>
</template>

<script>
import { createApp } from 'vue'; // Import createApp for simulation
import UserSavings from './UserSavings.vue'; // Import UserSavings for simulation

export default {
  name: 'DepositSavings',
  data() {
    return {
      currentSavingsBalance: 0,
      depositAmount: 0,
      pin: '',
      correctPin: '2007', // Handcoded PIN (same as Pay Bills page for consistency)
      message: '',
      messageType: '', // 'success' or 'error'
    };
  },
  mounted() {
    this.loadCurrentBalance();
  },
  methods: {
    loadCurrentBalance() {
      const savedBalance = localStorage.getItem('userSavingsBalance');
      if (savedBalance) {
        this.currentSavingsBalance = parseFloat(savedBalance);
      } else {
        // If no balance found, initialize it (e.g., to 0 or a default)
        this.currentSavingsBalance = 5000; // Match UserSavings initial
        localStorage.setItem('userSavingsBalance', this.currentSavingsBalance.toFixed(2));
      }
    },
    processDeposit() {
      if (this.depositAmount <= 0) {
        this.showMessage('Please enter a valid deposit amount (greater than 0).', 'error');
        return;
      }
      if (this.pin !== this.correctPin) {
        this.showMessage('Incorrect PIN.', 'error');
        return;
      }

      this.currentSavingsBalance += this.depositAmount;
      localStorage.setItem('userSavingsBalance', this.currentSavingsBalance.toFixed(2));
      this.showMessage(`Deposit of Ksh. ${this.depositAmount} successful! New Balance: Ksh. ${this.currentSavingsBalance.toFixed(2)}`, 'success');

      // Clear form
      this.depositAmount = 0;
      this.pin = '';

      // Trigger storage event manually for immediate update (though 'storage' event fires only on other tabs/windows)
      // For immediate update in the same window, a proper state management or event bus is preferred.
      // Since we're using localStorage, the UserSavings component will pick up the change when re-mounted or if it explicitly listens for storage events.
      // For robust real-time updates in a single-page app, consider a central state management (Vuex).
    },
    goBackToSavings() {
      // In a real Vue application, you would use Vue Router here:
      // this.$router.push('/'); // Assuming '/' is your savings dashboard
      alert('Going back to Savings Dashboard (simulation)');
      // For this isolated example, we can simulate by replacing the component content
      document.body.innerHTML = '<div id="app"></div>'; // Clear existing app
      createApp(UserSavings).mount('#app'); // Mount UserSavings
    },
    showMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
        this.messageType = '';
      }, 5000); // Message disappears after 5 seconds
    }
  }
};
</script>

<style scoped>
.deposit-savings-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
  padding: 30px;
  border: 1px solid #007BFF;
  border-radius: 10px;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.deposit-form {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #eee;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.deposit-form label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
  color: #333;
}

input[type="number"],
input[type="password"] {
  width: calc(100% - 20px);
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1em;
}

button {
  padding: 10px 25px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1em;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #0056b3;
}

.back-button {
  margin-top: 30px;
  background-color: #6c757d;
}

.back-button:hover {
  background-color: #5a6268;
}

.popup-message {
  margin-top: 20px;
  padding: 15px;
  border-radius: 8px;
  font-weight: bold;
}

.popup-message.success {
  background-color: #d4edda;
  color: #155724;
  border-color: #c3e6cb;
}

.popup-message.error {
  background-color: #f8d7da;
  color: #721c24;
  border-color: #f5c6cb;
}
</style>