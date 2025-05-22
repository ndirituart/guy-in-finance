// handles global functionality
//this is the Account Balance that affects both Loan balance and Savings balance
//for easy sharing of vales between the 3 pages
import { defineStore } from 'pinia';

export const useFinanceStore = defineStore('finance', {
  state: () => ({
    username: 'User',
    accountBalance: 12390,
    loanBalance: 0
  }),
  actions: {
    withdraw(amount) {
      if (this.accountBalance >= amount) {
        this.accountBalance -= amount;
        return true;
      }
      return false;
    },
    deposit(amount) {
      this.accountBalance += amount;
    },
    takeLoan(amount) {
      this.loanBalance += amount;
      this.accountBalance += amount;
    },
    repayLoan(amount) {
      if (this.accountBalance >= amount && this.loanBalance >= amount) {
        this.accountBalance -= amount;
        this.loanBalance -= amount;
        return true;
      }
      return false;
    }
  }
});
