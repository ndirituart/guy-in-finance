<template>
  <div class="family-friends-page">
    <h1>Family & Friends</h1>
    <p class="current-balance">Current Balance: Ksh. {{ balanceStore.accountBalance.toLocaleString() }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Send to Someone ❤️</h3>
        <input type="tel" v-model="sendNumber" placeholder="Enter phone number" maxlength="14" />
        <input type="number" v-model.number="sendAmount" placeholder="Amount (Ksh.)" min="1" />
        <button @click="confirmSend">Send</button>
      </div>
      <div class="option-box">
        <h3>Receive from Someone</h3>
        <input type="tel" v-model="receiveNumber" placeholder="Enter phone number" maxlength="14" />
        <input type="number" v-model.number="receiveAmount" placeholder="Amount (Ksh.)" min="1" />
        <button @click="confirmRequest">Request</button>
      </div>
    </div>

    <div v-if="transactionMessage" class="transaction-message">
      <p>{{ transactionMessage }}</p>
    </div>
  </div>
</template>

<script>
import { useBalanceStore } from '.src/stores/balanceStores'; // Adjust path

export default {
  name: 'FamilyFriends',
  setup() { // Use setup for Pinia integration in Options API components
    const balanceStore = useBalanceStore(); // Initialize the store
    return {
      balanceStore, // Make the store available in the template
    };
  },
  data() {
    return {
      sendNumber: '',
      sendAmount: null, // Added amount for sending
      receiveNumber: '',
      receiveAmount: null, // Added amount for receiving
      transactionMessage: '', // For displaying feedback
    };
  },
  methods: {
    confirmSend() {
      if (!this.validatePhoneNumber(this.sendNumber)) {
        this.transactionMessage = 'Please enter a valid phone number for sending.';
        return;
      }
      if (this.sendAmount <= 0 || isNaN(this.sendAmount)) {
        this.transactionMessage = 'Please enter a valid amount to send.';
        return;
      }

      this.transactionMessage = `Sending Ksh. ${this.sendAmount.toLocaleString()} to ${this.sendNumber}. Please wait, processing...`;

      setTimeout(() => {
        const success = this.balanceStore.deductAmount(this.sendAmount);
        if (success) {
          this.transactionMessage = `Successfully sent Ksh. ${this.sendAmount.toLocaleString()} to ${this.sendNumber}. New Balance: Ksh. ${this.balanceStore.accountBalance.toLocaleString()}`;
          this.sendNumber = ''; // Clear inputs
          this.sendAmount = null;
        } else {
          this.transactionMessage = 'INSUFFICIENT BALANCE to send money.';
        }
      }, 1500);
    },

    confirmRequest() {
      if (!this.validatePhoneNumber(this.receiveNumber)) {
        this.transactionMessage = 'Please enter a valid phone number for requesting.';
        return;
      }
      if (this.receiveAmount <= 0 || isNaN(this.receiveAmount)) {
        this.transactionMessage = 'Please enter a valid amount to request.';
        return;
      }

      this.transactionMessage = `Requesting Ksh. ${this.receiveAmount.toLocaleString()} from ${this.receiveNumber}. Please wait...`;

      setTimeout(() => {
        // In a real app, this would trigger an API call to a friend.
        // For simulation, we'll just add it to the balance.
        this.balanceStore.addAmount(this.receiveAmount); // Add money to balance
        this.transactionMessage = `Successfully requested Ksh. ${this.receiveAmount.toLocaleString()} from ${this.receiveNumber}. New Balance: Ksh. ${this.balanceStore.accountBalance.toLocaleString()}`;
        this.receiveNumber = ''; // Clear inputs
        this.receiveAmount = null;
      }, 1500);
    },

    validatePhoneNumber(number) {
      // Accept +254 followed by 9 digits OR a full 12-digit number (like 254799076883)
      return /^\+?\d{10,14}$/.test(number);
    }
  }
};
</script>

<style scoped>
/* Your existing styles */
.family-friends-page {
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

input[type="tel"],
input[type="number"] { /* Added number type */
  width: 80%;
  padding: 10px;
  margin: 10px 0;
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
}

button:hover {
  background-color: #0056b3;
}

.transaction-message {
  margin-top: 20px;
  font-weight: bold;
}
</style>