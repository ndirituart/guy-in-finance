<template>
  <div class="family-friends-page">
    <h1>Family & Friends</h1>
    <p><strong>Account Balance (debug):</strong> {{ calculatedBalance }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Send to Someone ❤️</h3>
        <input type="text" v-model="sendName" placeholder="Enter name" />
        <input type="tel" v-model="sendNumber" placeholder="Enter phone number" maxlength="14" />
        <input type="number" v-model.number="sendAmount" placeholder="Enter amount" />
        <input type="text" v-model="sendNote" placeholder="Optional Note (e.g., For the nails)" />
        <input type="password" v-model="sendPin" placeholder="Enter PIN" maxlength="4" />
        <button @click="confirmSend">Send</button>
      </div>

      <div class="option-box">
        <h3>Receive from Someone</h3>
        <input type="text" v-model="receiveName" placeholder="Enter name" />
        <input type="tel" v-model="receiveNumber" placeholder="Enter phone number" maxlength="14" />
        <input type="number" v-model.number="receiveAmount" placeholder="Enter amount" />
        <input type="text" v-model="receiveNote" placeholder="Optional Note (e.g., Hi Dad, rent is due)" />
        <button @click="confirmRequest">Request</button>
      </div>
    </div>

    <div v-if="transactions.length" class="receipt-table">
      <h2>Transaction History</h2>
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Note</th>
            <th>Time</th>
            <th>Balance After</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(txn, index) in transactions" :key="index">
            <td>{{ txn.code }}</td>
            <td>{{ txn.name }}</td>
            <td>{{ txn.number }}</td>
            <td>{{ txn.amount }}</td>
            <td>{{ txn.type }}</td>
            <td>{{ txn.note || 'N/A' }}</td>
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
  name: 'FamilyFriends',
  data() {
    return {
      sendName: '',
      sendNumber: '',
      sendAmount: 0,
      sendNote: '', // Added for send note
      sendPin: '',
      receiveName: '',
      receiveNumber: '',
      receiveAmount: 0,
      receiveNote: '', // Added for receive note
      balance: 12390,
      transactions: [],
      correctPin: '2007'
    };
  },
  mounted() {
    const savedTxns = localStorage.getItem('transactions');
    const savedBalance = localStorage.getItem('balance');

    if (savedTxns) {
      this.transactions = JSON.parse(savedTxns);
    }
    if (savedBalance) {
      this.balance = parseFloat(savedBalance);
    }
  },
  computed: {
    calculatedBalance() {
      let base = 12390;
      this.transactions.forEach(txn => {
        if (txn.type === '+ Received') base += txn.amount;
        if (txn.type === '- Sent') base -= txn.amount;
      });
      return base;
    }
  },
  methods: {
    confirmSend() {
      if (this.sendPin !== this.correctPin) {
        alert('Incorrect PIN.');
        return;
      }

      if (!this.validatePhoneNumber(this.sendNumber) || this.sendAmount <= 0) {
        alert('Invalid number or amount.');
        return;
      }

      if (this.sendAmount > this.balance) {
        alert('Insufficient balance!');
        return;
      }

      const txn = this.createTransaction(this.sendName, this.sendNumber, this.sendAmount, '- Sent', this.sendNote);
      this.balance -= this.sendAmount;
      this.transactions.push({ ...txn, balance: this.balance });
      this.resetSendForm();
      this.saveToLocalStorage();
    },

    confirmRequest() {
      if (this.receivePin !== this.correctPin) {
        alert('Incorrect PIN.');
        return;
      }

      if (!this.validatePhoneNumber(this.receiveNumber) || this.receiveAmount <= 0) {
        alert('Invalid number or amount.');
        return;
      }

      const txn = this.createTransaction(this.receiveName, this.receiveNumber, this.receiveAmount, '+ Received', this.receiveNote);
      this.balance += this.receiveAmount;
      this.transactions.push({ ...txn, balance: this.balance });
      this.resetReceiveForm();
      this.saveToLocalStorage();
    },

    createTransaction(name, number, amount, type, note) { // Modified to accept note
      return {
        code: 'TX' + Math.floor(Math.random() * 1000000),
        name,
        number,
        amount,
        type,
        note, // Storing the note
        time: new Date().toLocaleString()
      };
    },

    validatePhoneNumber(number) {
      return /^\+?\d{10,14}$/.test(number);
    },

    resetSendForm() {
      this.sendName = '';
      this.sendNumber = '';
      this.sendAmount = 0;
      this.sendNote = ''; // Resetting the note field
      this.sendPin = '';
    },

    resetReceiveForm() {
      this.receiveName = '';
      this.receiveNumber = '';
      this.receiveAmount = 0;
      this.receiveNote = ''; // Resetting the note field
      this.receivePin = '';
    },

    saveToLocalStorage() {
      localStorage.setItem('transactions', JSON.stringify(this.transactions));
      localStorage.setItem('balance', this.balance.toString());
    }
  }
};
</script>

<style scoped>
.family-friends-page {
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

input {
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

.template-footer {
  position: fixed; /* Fixes the footer at the bottom of the viewport */
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #333; /* Dark background for visibility */
  color: white;
  padding: 10px 0;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2); /* Shadow for depth */
  z-index: 1000; /* Ensures it stays on top of other content */
}

.footer-nav {
  display: flex;
  justify-content: space-around; /* Distributes items evenly */
  align-items: center;
  max-width: 1200px; /* Limit width for larger screens */
  margin: 0 auto; /* Center the nav items */
}

.nav-item {
  display: flex;
  flex-direction: column; /* Stack icon and text */
  align-items: center;
  text-decoration: none; /* Remove underline */
  color: white; /* Default text color */
  font-size: 0.85em; /* Smaller text for compactness */
  padding: 5px 10px;
  border-radius: 5px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-item:hover {
  background-color: #555; /* Slightly lighter on hover */
}

/* Active link style */
.nav-item.router-link-exact-active {
  color: #87ceeb; /* Light blue for active link */
  font-weight: bold;
}

.icon {
  font-size: 1.8em; /* Larger icons */
  margin-bottom: 3px; /* Space between icon and text */
}

.text {
  white-space: nowrap; /* Prevents text from wrapping */
}

/* Media queries for responsiveness */
@media (max-width: 600px) {
  .nav-item {
    padding: 5px 5px; /* Less padding on small screens */
    font-size: 0.75em;
  }
  .icon {
    font-size: 1.5em; /* Smaller icons on small screens */
  }
}

/* Ensure content above footer */
body {
  padding-bottom: 70px; /* Add padding to the bottom of the body to prevent footer from overlapping content */
}
</style>