<template>
    <div class="pay-bills-page">
      <h1>Pay Bills</h1>
  
      <div class="options-container">
        <div class="option-box">
          <h3>Till Number</h3>
          <span class="icon">🏠</span> <!-- Rent/Services/Water/HELB -->
          <input type="text" v-model="tillNumber" placeholder="Enter Till Number (6-8 digits)" maxlength="8" />
          <button @click="processTillPayment">Pay</button>
        </div>
  
        <div class="option-box">
          <h3>Paybill</h3>
          <span class="icon">⚡</span> <!-- Electricity/School Fees -->
          <input type="text" v-model="paybillNumber" placeholder="Enter Paybill Number (6-8 digits)" maxlength="8" />
          <input type="text" v-model="accountNumber" placeholder="Enter Account Number (12 digits)" maxlength="12" />
          <button @click="processPaybillPayment">Pay</button>
        </div>
      </div>
  
      <div v-if="transactionMessage" class="transaction-message">
        <p>{{ transactionMessage }}</p>
        <button v-if="transactionFailed" @click="handleTransactionFailure">Take a Loan</button>
        <button v-if="transactionFailed" @click="stopTransaction">Stop Transaction</button>
      </div>
    </div>
  </template>
  
  <script>
import { useFinanceStore } from '@/stores/financeStore';

export default {
  name: 'PayBills',
  setup() {
    const store = useFinanceStore();
    return {
      store,
      tillNumber: '',
      paybillNumber: '',
      accountNumber: '',
      transactionMessage: '',
      transactionFailed: false
    };
  },
  methods: {
    processTillPayment() {
      if (this.validateTillNumber(this.tillNumber)) {
        const amount = 1000;
        this.processTransaction(amount);
      } else {
        alert('Invalid Till Number');
      }
    },
    processPaybillPayment() {
      if (this.validatePaybillNumber(this.paybillNumber) && this.validateAccountNumber(this.accountNumber)) {
        const amount = 2000;
        this.processTransaction(amount);
      } else {
        alert('Invalid Paybill or Account Number');
      }
    },
    processTransaction(amount) {
      if (this.store.withdraw(amount)) {
        this.transactionMessage = `Transaction Successful! New Balance: Ksh. ${this.store.accountBalance}`;
        this.transactionFailed = false;
      } else {
        this.transactionMessage = 'INSUFFICIENT BALANCE';
        this.transactionFailed = true;
      }
    },
    validateTillNumber(number) {
      return /^\d{6,8}$/.test(number);
    },
    validatePaybillNumber(number) {
      return /^\d{6,8}$/.test(number);
    },
    validateAccountNumber(number) {
      return /^\d{12}$/.test(number);
    },
    handleTransactionFailure() {
      alert('Taking a loan...');
    },
    stopTransaction() {
      this.transactionMessage = '';
      this.tillNumber = '';
      this.paybillNumber = '';
      this.accountNumber = '';
      this.transactionFailed = false;
    }
  }
};
</script>

  
  <style scoped>
  .pay-bills-page {
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
  
  input[type="text"] {
    width: 80%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #007BFF;
    border-radius: 5px;
  }
  
  button {
    padding: 10px 20px;
    background-color: #007BFF;
  }
  </style>