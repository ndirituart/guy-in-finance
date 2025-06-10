<template>
  <div class="family-friends-page">
    <h1>Family & Friends</h1>

    <div class="options-container">
      <div class="option-box">
        <h3>Send to Someone ❤️</h3>
        <input type="text" v-model="sendName" placeholder="Enter name" />
        <input type="tel" v-model="sendNumber" placeholder="Enter phone number" maxlength="14" />
        <input type="number" v-model="sendAmount" placeholder="Enter amount" />
        <button @click="confirmSend">Send</button>
      </div>

      <div class="option-box">
        <h3>Receive from Someone</h3>
        <input type="text" v-model="receiveName" placeholder="Enter name" />
        <input type="tel" v-model="receiveNumber" placeholder="Enter phone number" maxlength="14" />
        <input type="number" v-model="receiveAmount" placeholder="Enter amount" />
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
        <th>Amount</th>
        <th>Type</th>
        <th>Time</th>
        <th>Balance After</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(txn, index) in transactions" :key="index">
        <td>{{ txn.code }}</td>
        <td>{{ txn.name }}</td>
        <td>{{ txn.amount }}</td>
        <td>{{ txn.type }}</td>
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
      receiveName: '',
      receiveNumber: '',
      receiveAmount: 0,
      balance: 10000, // starting balance
      transactions: [] // store all transactions
    };
  },
  methods: {
    confirmSend() {
      if (this.validatePhoneNumber(this.sendNumber) && this.sendAmount > 0) {
        if (this.sendAmount > this.balance) {
          alert("Insufficient balance!");
          return;
        }

        const code = 'TX' + Math.floor(Math.random() * 1000000);
        const time = new Date().toLocaleString();
        this.balance -= this.sendAmount;

        this.transactions.push({
          code,
          name: this.sendName,
          amount: this.sendAmount,
          type: '- Sent',
          time,
          balance: this.balance
        });

        this.resetSendForm();
      } else {
        alert("Invalid number or amount.");
      }
    },

    confirmRequest() {
      if (this.validatePhoneNumber(this.receiveNumber) && this.receiveAmount > 0) {
        const code = 'TX' + Math.floor(Math.random() * 1000000);
        const time = new Date().toLocaleString();
        this.balance += this.receiveAmount;

        this.transactions.push({
          code,
          name: this.receiveName,
          amount: this.receiveAmount,
          type: '+ Received',
          time,
          balance: this.balance
        });
        //to

        this.resetReceiveForm();
      } else {
        alert("Invalid number or amount.");
      }
    },

    validatePhoneNumber(number) {
      return /^\+?\d{10,14}$/.test(number);
    },

    resetSendForm() {
      this.sendName = '';
      this.sendNumber = '';
      this.sendAmount = 0;
    },

    resetReceiveForm() {
      this.receiveName = '';
      this.receiveNumber = '';
      this.receiveAmount = 0;
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
  
  input[type="tel"] {
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
  .receipt-table {
  margin-top: 30px;
  padding: 20px;
  max-width: 90%;
  margin-left: auto;
  margin-right: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

th, td {
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