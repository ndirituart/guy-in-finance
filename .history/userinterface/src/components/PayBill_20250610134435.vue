<template>
  <div class="pay-bills-page">
    <h1>Pay Bills</h1>
    <p><strong>Current Balance:</strong> {{ balance }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Till Number</h3>
        <span class="icon">🏠</span>
        <input type="text" v-model="tillNumber" placeholder="Enter Till Number (6-8 digits)" maxlength="8" />
        <input type="text" v-model="businessNameTill" placeholder="Enter Business Name (Optional)" />
        <input type="number" v-model.number="amountTill" placeholder="Enter Amount" />
        <input type="password" v-model="pinTill" placeholder="Enter PIN" maxlength="4" />
        <button @click="processTillPayment">Pay</button>
      </div>

      <div class="option-box">
        <h3>Paybill</h3>
        <span class="icon">⚡</span>
        <input type="text" v-model="paybillNumber" placeholder="Enter Paybill Number (6-8 digits)" maxlength="8" />
        <input type="text" v-model="accountNumber" placeholder="Enter Account Number (12 digits)" maxlength="12" />
        <input type="text" v-model="businessNamePaybill" placeholder="Enter Business Name (Optional)" />
        <input type="number" v-model.number="amountPaybill" placeholder="Enter Amount" />
        <input type="password" v-model="pinPaybill" placeholder="Enter PIN" maxlength="4" />
        <button @click="processPaybillPayment">Pay</button>
      </div>
    </div>

    <div v-if="transactions.length" class="receipt-table">
      <h2>Transaction History</h2>
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Number/Account</th>
            <th>Business Name</th>
            <th>Amount</th>
            <th>Time</th>
            <th>Balance After</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(txn, index) in transactions" :key="index">
            <td>{{ txn.code }}</td>
            <td>{{ txn.type }}</td>
            <td>{{ txn.number }}</td>
            <td>{{ txn.businessName }}</td>
            <td>{{ txn.amount }}</td>
            <td>{{ txn.time }}</td>
            <td>{{ txn.balance }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PayBills',
  data() {
    return {
      balance: 12390, // Initial balance
      tillNumber: '',
      businessNameTill: '',
      amountTill: 0,
      pinTill: '',
      paybillNumber: '',
      accountNumber: '',
      businessNamePaybill: '',
      amountPaybill: 0,
      pinPaybill: '',
      transactions: [],
      correctPin: '2007' // Handcoded PIN
    };
  },
  mounted() {
    // Load balance and transactions from local storage on component mount
    const savedBalance = localStorage.getItem('payBillsBalance');
    const savedTxns = localStorage.getItem('payBillsTransactions');

    if (savedBalance) {
      this.balance = parseFloat(savedBalance);
    }
    if (savedTxns) {
      this.transactions = JSON.parse(savedTxns);
    }
  },
  methods: {
    processTillPayment() {
      if (!this.validateTillNumber(this.tillNumber)) {
        alert('Enter a valid Till Number (6-8 digits).');
        return;
      }
      if (this.amountTill <= 0) {
        alert('Enter a valid amount (must be greater than 0).');
        return;
      }
      if (!this.validatePin(this.pinTill)) {
        alert('Enter correct PIN.');
        return;
      }

      this.processTransaction(this.amountTill, 'Till Payment', this.tillNumber, this.businessNameTill);
      this.resetTillForm();
    },
    processPaybillPayment() {
      if (!this.validatePaybillNumber(this.paybillNumber)) {
        alert('Enter a valid Paybill Number (6-8 digits).');
        return;
      }
      if (!this.validateAccountNumber(this.accountNumber)) {
        alert('Enter a valid Account Number (12 digits).');
        return;
      }
      if (this.amountPaybill <= 0) {
        alert('Enter a valid amount (must be greater than 0).');
        return;
      }
      if (!this.validatePin(this.pinPaybill)) {
        alert('Enter correct PIN.');
        return;
      }

      this.processTransaction(this.amountPaybill, 'Paybill Payment', `${this.paybillNumber}/${this.accountNumber}`, this.businessNamePaybill);
      this.resetPaybillForm();
    },
    processTransaction(amount, type, number, businessName) {
      if (this.balance >= amount) {
        this.balance -= amount;
        const txn = this.createTransaction(type, number, businessName, amount);
        this.transactions.push({ ...txn, balance: this.balance });
        alert(`Transaction Successful! New Balance: Ksh. ${this.balance}`);
        this.saveToLocalStorage();
      } else {
        alert('INSUFFICIENT BALANCE');
      }
    },
    createTransaction(type, number, businessName, amount) {
      return {
        code: 'PB' + Math.floor(Math.random() * 1000000),
        type,
        number,
        businessName: businessName || 'N/A', // Set to 'N/A' if optional field is empty
        amount,
        time: new Date().toLocaleString()
      };
    },
    validateTillNumber(number) {
      return /^\d{6,8}$/.test(number); // Validates 6 to 8 digits
    },
    validatePaybillNumber(number) {
      return /^\d{6,8}$/.test(number); // Validates 6 to 8 digits
    },
    validateAccountNumber(number) {
      return /^\d{10,12}$/.test(number); // Validates exactly 12 digits
    },
    validatePin(pin) {
      return pin === this.correctPin;
    },
    resetTillForm() {
      this.tillNumber = '';
      this.businessNameTill = '';
      this.amountTill = 0;
      this.pinTill = '';
    },
    resetPaybillForm() {
      this.paybillNumber = '';
      this.accountNumber = '';
      this.businessNamePaybill = '';
      this.amountPaybill = 0;
      this.pinPaybill = '';
    },
    saveToLocalStorage() {
      localStorage.setItem('payBillsBalance', this.balance.toString());
      localStorage.setItem('payBillsTransactions', JSON.stringify(this.transactions));
    }
  }
};
</script>

<style scoped>
/* (No changes to styles, kept as is from your previous code) */
.pay-bills-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
}

.options-container {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
  margin: 20px 0;
}

.option-box {
  border: 1px solid #007BFF;
  border-radius: 8px;
  padding: 20px;
  width: 250px;
  text-align: center;
  transition: box-shadow 0.3s;
}

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

input[type="text"],
input[type="number"],
input[type="password"] {
  width: 80%;
  padding: 10px;
  margin: 8px 0;
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
  margin-top: 10px;
}

button:hover {
  background-color: #0056b3;
}

.receipt-table {
  margin-top: 30px;
  padding: 20px;
  max-width: 95%;
  margin-left: auto;
  margin-right: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

th,
td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

thead {
  background-color: #007BFF;
  color: white;
}

tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}
</style>