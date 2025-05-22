<template>
    <div class="loans-page">
      <h1>Loans</h1>
  
      <div class="options-container">
        <div class="option-box" @click="borrowFromApp">
          <h3>Borrow from App</h3>
          <span class="icon">🏦</span> <!-- Bank icon -->
          <button @click="processAppLoan">Borrow</button>
        </div>
  
        <div class="option-box" @click="borrowFromSomeone">
          <h3>Borrow from Someone</h3>
          <span class="icon">👨‍👩‍👧‍👦</span> <!-- People icon -->
          <button @click="sendLoanRequest">Request Loan</button>
        </div>
      </div>
  
      <div v-if="loanMessage" class="loan-message">
        <p>{{ loanMessage }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import { useFinanceStore } from '@/stores/financeStore';
  
  export default {
    name: 'LoansPage',
    setup() {
      const store = useFinanceStore();
      return {
        store,
        loanMessage: '',
        loanAmount: 1000
      };
    },
    methods: {
      processAppLoan() {
        this.store.takeLoan(this.loanAmount);
        this.loanMessage = `You borrowed Ksh. ${this.loanAmount}. Loan balance: Ksh. ${this.store.loanBalance}`;
      },
      sendLoanRequest() {
        this.loanMessage = 'Loan request sent to your friend/family member.';
      }
    }
  };
  </script>
  
  
  <style scoped>
  .loans-page {
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
  
  .loan-message {
    margin-top: 20px;
    font-weight: bold;
  }
  </style>