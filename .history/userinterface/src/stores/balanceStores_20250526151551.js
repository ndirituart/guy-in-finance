// src/stores/balanceStore.js (or src/stores/financialStores.js)
import { defineStore } from 'pinia';

export const useBalanceStore = defineStore('balance', {
  state: () => ({
    accountBalance: 13290, // Updated to 13290
  }),
  actions: {
    deductAmount(amount) {
      if (this.accountBalance >= amount) {
        this.accountBalance -= amount;
        return true; // Indicate success
      }
      return false; // Indicate insufficient balance
    },
    addAmount(amount) { // You might need this for deposits/money received
      this.accountBalance += amount;
    },
    resetBalance() { // Useful for testing or specific scenarios
      this.accountBalance = 13290;
    }
  },
  // You can also add getters if you need computed properties from the state
  // getters: {
  //   formattedBalance: (state) => `Ksh. ${state.accountBalance.toLocaleString()}`,
  // }
});