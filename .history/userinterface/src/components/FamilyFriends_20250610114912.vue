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
  .receipt {
  margin-top: 30px;
  padding: 20px;
  border: 2px dashed #28a745;
  border-radius: 10px;
  background-color: #f9f9f9;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
  text-align: left;
}

  </style>