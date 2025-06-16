<template>
  <div class="withdraw-savings-page">
    <h2>Withdraw from Savings</h2>
    <p>Current Savings Balance: Ksh. {{ currentSavingsBalance.toFixed(2) }}</p>

    <div class="withdraw-form" v-if="!showConfirmation">
      <label for="withdrawAmount">Enter Amount to Withdraw:</label>
      <input type="number" id="withdrawAmount" v-model.number="withdrawAmount" placeholder="e.g., 200" min="1" />
      <input type="password" v-model="pin" placeholder="Enter PIN" maxlength="4" />
      <button @click="initiateWithdrawal">Withdraw</button>
    </div>

    <div v-if="showConfirmation" class="confirmation-popup">
      <h3>Are you sure you want to withdraw Ksh. {{ withdrawAmount.toFixed(2) }}?</h3>
      <div class="button-group">
        <button @click="confirmWithdrawal('yes')">YES</button>
        <button @click="confirmWithdrawal('no')">NO</button>
      </div>
    </div>

    <button class="back-button" @click="goBackToSavings">Back to Savings</button>

    <div v-if="message" :class="['popup-message', messageType]">
      <p>{{ message }}</p>
      <div v-if="showEmergencyOptions" class="emergency-options">
        <button @click="handleEmergency('very')">VERY SERIOUS</button>
        <button @click="handleEmergency('notBad')">NOT THAT BAD</button>
      </div>
    </div>
  </div>
</template>

<script>
import { createApp } from 'vue'; // Import createApp for simulation
import UserSavings from './UserSavings.vue'; // Import UserSavings for simulation

export default {
  name: 'WithdrawSavings',
  data() {
    return {
      currentSavingsBalance: 0,
      withdrawAmount: 0,
      pin: '',
      correctPin: '2007', // Handcoded PIN
      message: '',
      messageType: '',
      showConfirmation: false,
      showEmergencyOptions: false,
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
        this.currentSavingsBalance = 5000; // Match UserSavings initial
        localStorage.setItem('userSavingsBalance', this.currentSavingsBalance.toFixed(2));
      }
    },
    initiateWithdrawal() {
      if (this.withdrawAmount <= 0) {
        this.showMessage('Please enter a valid withdrawal amount (greater than 0).', 'error');
        return;
      }
      if (this.withdrawAmount > this.currentSavingsBalance) {
        this.showMessage('Insufficient balance for this withdrawal.', 'error');
        return;
      }
      if (this.pin !== this.correctPin) {
        this.showMessage('Incorrect PIN.', 'error');
        return;
      }

      this.message = ''; // Clear previous messages
      this.messageType = '';
      this.showConfirmation = true; // Show confirmation popup
    },
    confirmWithdrawal(choice) {
      this.showConfirmation = false; // Hide confirmation popup
      if (choice === 'yes') {
        this.message = 'How serious is this emergency?';
        this.messageType = ''; // No specific type for this intermediate message
        this.showEmergencyOptions = true;
      } else {
        this.showMessage("Yeyyyy, that's financial discipline", 'success');
        this.withdrawAmount = 0; // Clear amount on cancel
        this.pin = ''; // Clear PIN
        setTimeout(() => this.message = '', 3000);
      }
    },
    handleEmergency(severity) {
      this.showEmergencyOptions = false; // Hide emergency options
      if (severity === 'very') {
        this.currentSavingsBalance -= this.withdrawAmount;
        localStorage.setItem('userSavingsBalance', this.currentSavingsBalance.toFixed(2));
        this.showMessage('Withdrawal successful! NEXT TIME TUNASAVE! 👍', 'success');
        this.withdrawAmount = 0; // Clear form
        this.pin = ''; // Clear PIN
        setTimeout(() => this.message = '', 5000); // Message disappears after 5 seconds
      } else {
        this.showMessage("Yeyyyy, that's financial discipline", 'success');
        this.withdrawAmount = 0; // Clear amount on cancel
        this.pin = ''; // Clear PIN
        setTimeout(() => this.message = '', 3000);
      }
    },
    goBackToSavings() {
      // In a real Vue application, you would use Vue Router here:
      // this.$router.push('/');
      alert('Going back to Savings Dashboard (simulation)');
      document.body.innerHTML = '<div id="app"></div>';
      createApp(UserSavings).mount('#app');
    },
    showMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      if (!this.showEmergencyOptions && !this.showConfirmation) { // Only auto-clear if not waiting for user input
        setTimeout(() => {
          this.message = '';
          this.messageType = '';
        }, 5000); // Adjust as needed
      }
    }
  }
};
</script>

<style scoped>
.withdraw-savings-page {
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

.withdraw-form {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #eee;
  border-radius: 8px;
  background-color: #f9f9f9;
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
  position: relative; /* For emergency options positioning */
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

.confirmation-popup {
  background-color: #fff3cd;
  color: #856404;
  border: 1px solid #ffeeba;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
}

.confirmation-popup .button-group button {
  margin: 0 10px;
  background-color: #28a745; /* Green for YES */
}

.confirmation-popup .button-group button:last-child {
  background-color: #dc3545; /* Red for NO */
}

.emergency-options button {
  margin: 5px;
  background-color: #ffc107; /* Orange for emergency options */
  color: #343a40;
}

.emergency-options button:hover {
  background-color: #e0a800;
}
</style>