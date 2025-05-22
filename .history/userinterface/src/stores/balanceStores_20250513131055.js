//for Account Balance to be accessed by all components eg when PayBill is paid 
// or DailyExpenses is clicked
import { defineStore } from 'pinia';

export const useBalanceStore = defineStore('balance', {
  state: () => ({
    accountBalance: 12390,
  }),
  actions: {
    deductAmount(amount) {
      if (this.accountBalance >= amount) {
        this.accountBalance -= amount;
        return true;
      }
      return false;
    },
    resetBalance() {
      this.accountBalance = 12390;
    }
  },
});
