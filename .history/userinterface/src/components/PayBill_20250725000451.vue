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
        <div class="recurring-option">
          <input type="checkbox" id="tillRecurring" v-model="isTillRecurring" />
          <label for="tillRecurring">Is bill recurring?</label>
        </div>
        <div v-if="isTillRecurring" class="recurring-details">
          <label for="tillFrequency">Frequency:</label>
          <select id="tillFrequency" v-model="tillFrequency">
            <option value="monthly">Monthly</option>
            </select>
          <label for="tillDay">Day of Month:</label>
          <input type="number" id="tillDay" v-model.number="tillDay" min="1" max="31" placeholder="e.g., 4 (for 4th)" />
        </div>
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
        <div class="recurring-option">
          <input type="checkbox" id="paybillRecurring" v-model="isPaybillRecurring" />
          <label for="paybillRecurring">Is bill recurring?</label>
        </div>
        <div v-if="isPaybillRecurring" class="recurring-details">
          <label for="paybillFrequency">Frequency:</label>
          <select id="paybillFrequency" v-model="paybillFrequency">
            <option value="monthly">Monthly</option>
            </select>
          <label for="paybillDay">Day of Month:</label>
          <input type="number" id="paybillDay" v-model.number="paybillDay" min="1" max="31" placeholder="e.g., 4 (for 4th)" />
        </div>
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

    <hr>

    <div v-if="scheduledBills.length" class="scheduled-bills-section">
        <h2>Scheduled Recurring Bills</h2>
        <table>
            <thead>
                <tr>
                    <th>Bill Type</th>
                    <th>Identifier</th>
                    <th>Business Name</th>
                    <th>Amount</th>
                    <th>Frequency</th>
                    <th>Day</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(bill, index) in scheduledBills" :key="index">
                    <td>{{ bill.type }}</td>
                    <td>{{ bill.numberOrAccount }}</td>
                    <td>{{ bill.businessName }}</td>
                    <td>{{ bill.amount }}</td>
                    <td>{{ bill.frequency }}</td>
                    <td>{{ bill.day }}</td>
                    <td>
                        <button @click="removeScheduledBill(index)" class="remove-btn">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!--my navigation footer-->
     <footer class="main-footer">
    <nav class="footer-nav">
      <router-link to="/" class="nav-item">
        <span class="icon">🏠</span>
        <span class="text">Home</span>
      </router-link>

      <router-link to="/family-friends" class="nav-item">
        <span class="icon">👨‍👩‍👧‍👦</span>
        <span class="text">Family & Friends</span>
      </router-link>

      <router-link to="/bills" class="nav-item">
        <span class="icon">💧⚡</span>
        <span class="text">Bills</span>
      </router-link>

      <router-link to="/daily-expenses" class="nav-item">
        <span class="icon">🚌🛒</span>
        <span class="text">Daily Expenses</span>
      </router-link>

      <router-link to="/user-savings" class="nav-item">
        <span class="icon">💰</span>
        <span class="text">Savings</span>
      </router-link>
    </nav>
  </footer>
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
      isTillRecurring: false, // New: Till Recurring checkbox state
      tillFrequency: 'monthly', // New: Till Recurring frequency
      tillDay: null, // New: Till Recurring day of month
      pinTill: '',
      paybillNumber: '',
      accountNumber: '',
      businessNamePaybill: '',
      amountPaybill: 0,
      isPaybillRecurring: false, // New: Paybill Recurring checkbox state
      paybillFrequency: 'monthly', // New: Paybill Recurring frequency
      paybillDay: null, // New: Paybill Recurring day of month
      pinPaybill: '',
      transactions: [],
      scheduledBills: [], // New: Array to store recurring bill details
      correctPin: '2007' // Handcoded PIN
    };
  },
  mounted() {
    // Load balance, transactions, and scheduled bills from local storage on component mount
    const savedBalance = localStorage.getItem('payBillsBalance');
    const savedTxns = localStorage.getItem('payBillsTransactions');
    const savedScheduledBills = localStorage.getItem('scheduledBills'); // Load scheduled bills

    if (savedBalance) {
      this.balance = parseFloat(savedBalance);
    }
    if (savedTxns) {
      this.transactions = JSON.parse(savedTxns);
    }
    if (savedScheduledBills) {
      this.scheduledBills = JSON.parse(savedScheduledBills);
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
      if (this.isTillRecurring && (!this.tillDay || this.tillDay < 1 || this.tillDay > 31)) {
        alert('Please enter a valid day of the month for recurring till payment (1-31).');
        return;
      }
      if (!this.validatePin(this.pinTill)) {
        alert('Enter correct PIN.');
        return;
      }

      this.processTransaction(this.amountTill, 'Till Payment', this.tillNumber, this.businessNameTill, this.isTillRecurring, this.tillFrequency, this.tillDay);
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
      if (this.isPaybillRecurring && (!this.paybillDay || this.paybillDay < 1 || this.paybillDay > 31)) {
        alert('Please enter a valid day of the month for recurring paybill payment (1-31).');
        return;
      }
      if (!this.validatePin(this.pinPaybill)) {
        alert('Enter correct PIN.');
        return;
      }

      this.processTransaction(this.amountPaybill, 'Paybill Payment', `${this.paybillNumber}/${this.accountNumber}`, this.businessNamePaybill, this.isPaybillRecurring, this.paybillFrequency, this.paybillDay);
      this.resetPaybillForm();
    },
    processTransaction(amount, type, numberOrAccount, businessName, isRecurring, frequency, day) {
      if (this.balance >= amount) {
        this.balance -= amount;
        const txn = this.createTransaction(type, numberOrAccount, businessName, amount);
        this.transactions.push({ ...txn, balance: this.balance });
        alert(`Transaction Successful! New Balance: Ksh. ${this.balance}`);

        if (isRecurring) {
          this.scheduledBills.push({
            type,
            numberOrAccount,
            businessName: businessName || 'N/A',
            amount,
            frequency,
            day
          });
          alert(`Bill scheduled for: ${frequency} on day ${day}.`);
        }
        this.saveToLocalStorage();
      } else {
        alert('INSUFFICIENT BALANCE');
      }
    },
    createTransaction(type, numberOrAccount, businessName, amount) {
      return {
        code: 'PB' + Math.floor(Math.random() * 1000000),
        type,
        number: numberOrAccount,
        businessName: businessName || 'N/A', // Set to 'N/A' if optional field is empty
        amount,
        time: new Date().toLocaleString()
      };
    },
    removeScheduledBill(index) {
        if (confirm('Are you sure you want to remove this scheduled bill?')) {
            this.scheduledBills.splice(index, 1);
            this.saveToLocalStorage();
            alert('Scheduled bill removed.');
        }
    },
    validateTillNumber(number) {
      return /^\d{6,8}$/.test(number); // Validates 6 to 8 digits
    },
    validatePaybillNumber(number) {
      return /^\d{6,8}$/.test(number); // Validates 6 to 8 digits
    },
    validateAccountNumber(number) {
      return /^\d{10,12}$/.test(number); // Validates 10 to 12 digits (adjusted based on common practices)
    },
    validatePin(pin) {
      return pin === this.correctPin;
    },
    resetTillForm() {
      this.tillNumber = '';
      this.businessNameTill = '';
      this.amountTill = 0;
      this.isTillRecurring = false;
      this.tillFrequency = 'monthly';
      this.tillDay = null;
      this.pinTill = '';
    },
    resetPaybillForm() {
      this.paybillNumber = '';
      this.accountNumber = '';
      this.businessNamePaybill = '';
      this.amountPaybill = 0;
      this.isPaybillRecurring = false;
      this.paybillFrequency = 'monthly';
      this.paybillDay = null;
      this.pinPaybill = '';
    },
    saveToLocalStorage() {
      localStorage.setItem('payBillsBalance', this.balance.toString());
      localStorage.setItem('payBillsTransactions', JSON.stringify(this.transactions));
      localStorage.setItem('scheduledBills', JSON.stringify(this.scheduledBills)); // Save scheduled bills
    }
  }
};
</script>

<style scoped>
/* Existing styles */
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
input[type="password"],
select { /* Added select to input styles */
  width: 80%;
  padding: 10px;
  margin: 8px 0;
  border: 1px solid #007BFF;
  border-radius: 5px;
  box-sizing: border-box; /* Ensures padding doesn't increase total width */
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

.receipt-table, .scheduled-bills-section {
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

/* New styles for recurring bill feature */
.recurring-option {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 10px 0;
}

.recurring-option input[type="checkbox"] {
  width: auto; /* Override default input width */
  margin-right: 5px;
}

.recurring-details {
  background-color: #e9f5ff;
  border: 1px solid #cce0f2;
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 10px;
}

.recurring-details label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.recurring-details select,
.recurring-details input[type="number"] {
  width: calc(100% - 20px); /* Adjust width considering padding */
  margin-bottom: 10px;
}

.remove-btn {
    background-color: #dc3545;
}

.remove-btn:hover {
    background-color: #c82333;
}
/
</style>