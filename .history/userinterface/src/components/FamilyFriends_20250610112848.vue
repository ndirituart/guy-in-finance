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

    <div v-if="receipt" class="receipt">
      <h2>Transaction Receipt</h2>
      <p><strong>Code:</strong> {{ receipt.code }}</p>
      <p><strong>Name:</strong> {{ receipt.name }}</p>
      <p><strong>Amount:</strong> {{ receipt.amount }} ({{ receipt.type }})</p>
      <p><strong>Time:</strong> {{ receipt.time }}</p>
      <p><strong>New Balance:</strong> {{ receipt.balance }}</p>
    </div>
  </div>
</template>

  
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
      balance: 10000, // starting dummy balance
      receipt: null
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

        this.receipt = {
          code,
          name: this.sendName,
          amount: this.sendAmount,
          type: '- Sent',
          time,
          balance: this.balance
        };

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

        this.receipt = {
          code,
          name: this.receiveName,
          amount: this.receiveAmount,
          type: '+ Received',
          time,
          balance: this.balance
        };

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
  </style>