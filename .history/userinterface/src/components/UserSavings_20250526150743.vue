<template>
  <div class="user-savings-page">
    <h1>Your Savings</h1>
    <p class="current-balance">Account Balance: Ksh. {{ balanceStore.accountBalance.toLocaleString() }}</p>

    <div class="savings-container">
      <div class="savings-option-box">
        <h3>Deposit Funds</h3>
        <input type="number" v-model.number="depositAmount" placeholder="Amount to deposit" min="1" />
        <button @click="depositFunds">Deposit</button>
      </div>

      <div class="savings-option-box">
        <h3>Withdraw Funds</h3>
        <input type="number" v-model.number="withdrawAmount" placeholder="Amount to withdraw" min="1" />
        <button @click="withdrawFunds">Withdraw</button>
      </div>
    </div>

    <div v-if="savingsMessage" class="savings-message">
      <p>{{ savingsMessage }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useBalanceStore } from '../stores/balanceStore'; // Adjust path

const balanceStore = useBalanceStore(); // Initialize the store

const depositAmount = ref(null);
const withdrawAmount = ref(null);
const savingsMessage = ref('');

const depositFunds = () => {
  if (depositAmount.value <= 0 || isNaN(depositAmount.value)) {
    savingsMessage.value = 'Please enter a valid amount to deposit.';
    return;
  }
  balanceStore.addAmount(depositAmount.value);
  savingsMessage.value = `Successfully deposited Ksh. ${depositAmount.value.toLocaleString()}. New Balance: Ksh. ${balanceStore.accountBalance.toLocaleString()}`;
  depositAmount.value = null;
};

const withdrawFunds = () => {
  if (withdrawAmount.value <= 0 || isNaN(withdrawAmount.value)) {
    savingsMessage.value = 'Please enter a valid amount to withdraw.';
    return;
  }
  const success = balanceStore.deductAmount(withdrawAmount.value);
  if (success) {
    savingsMessage.value = `Successfully withdrew Ksh. ${withdrawAmount.value.toLocaleString()}. New Balance: Ksh. ${balanceStore.accountBalance.toLocaleString()}`;
    withdrawAmount.value = null;
  } else {
    savingsMessage.value = 'INSUFFICIENT ACCOUNT BALANCE for withdrawal.';
  }
};
</script>

<style scoped>
.user-savings-page {
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

.savings-container {
  display: flex;
  justify-content: space-around;
  margin: 20px 0;
}

.savings-option-box {
  border: 1px solid #007BFF;
  border-radius: 8px;
  padding: 20px;
  width: 250px;
  text-align: center;
  transition: box-shadow 0.3s;
}

.savings-option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

input[type="number"] {
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

.savings-message {
  margin-top: 20px;
  font-weight: bold;
}
</style>