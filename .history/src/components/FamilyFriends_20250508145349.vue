<template>
    <div class="family-friends-page">
      <h1>Family & Friends</h1>
  
      <div class="options-container">
        <div class="option-box" @click="sendMoney">
          <h3>Send to Someone ❤️</h3>
          <input type="tel" v-model="sendNumber" placeholder="Enter phone number" maxlength="14" />
          <button @click="confirmSend">Send</button>
        </div>
        <div class="option-box" @click="requestMoney">
          <h3>Receive from Someone</h3>
          <input type="tel" v-model="receiveNumber" placeholder="Enter phone number" maxlength="13" />
          <button @click="confirmRequest">Request</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'FamilyFriends',
    data() {
      return {
        sendNumber: '',
        receiveNumber: ''
      };
    },
    methods: {
      confirmSend() {
        if (this.validatePhoneNumber(this.sendNumber)) {
          // Logic to send money
          alert(`Sending money to +254${this.sendNumber}`);
        } else {
          alert('Please enter a valid phone number.');
        }
      },
      confirmRequest() {
        if (this.validatePhoneNumber(this.receiveNumber)) {
          // Logic to request money
          alert(`Requesting money from +254${this.receiveNumber}`);
        } else {
          alert('Please enter a valid phone number.');
        }
      },
      validatePhoneNumber(number) {
        // Validate that the number is 12 digits long
        return /^\d{12}$/.test(number);
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
  </style>