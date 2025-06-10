<template>
    <div class="account-balance-page">
      <header class="header">
        <h1>Hello {{ username }} <span class="account-icon" @click="navigateToUserPage">👤</span></h1>
      </header>
      
      <div class="balance-container">
        <h2>Account Balance</h2>
        <p :class="{ 'blurred': isBlurred }">Ksh. {{ isBlurred ? '****' : accountBalance }}</p>
        <button @click="toggleBlur">{{ isBlurred ? '👁️' : '👁️‍🗨️' }}</button>
      </div>
  
      <div class="options-container">
        <div class="option-box" @click="navigateToDeposit">
          <h3>Deposit Money</h3>
          <span class="icon">💳</span> <!-- Replace with M-Pesa icon -->
        </div>
        <div class="option-box" @click="navigateToWithdraw">
          <h3>Withdraw Money</h3>
          <span class="icon">🏦</span> <!-- Replace with M-Pesa icon -->
        </div>
        <div class="option-box" @click="navigateToFamilyFriends">
          <h3>Send to Person</h3>
          <span class="icon">👨‍👩‍👧‍👦</span> <!-- Replace with appropriate icon -->
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AccountBalance',
    data() {
      return {
        username: 'User ', // Replace with actual username
        accountBalance: 12390,
        isBlurred: false
      };
    },
    methods: {
      toggleBlur() {
        this.isBlurred = !this.isBlurred;
      },
      navigateToUserPage() {
        // Logic to navigate to user page
        this.$router.push({ name: 'UserPage' }); // Adjust the route name as necessary
      },
      navigateToDeposit() {
        // Logic to navigate to deposit page
        this.$router.push({ name: 'DepositPage' }); // Adjust the route name as necessary
      },
      navigateToWithdraw() {
        // Logic to navigate to withdraw page
        this.$router.push({ name: 'WithdrawPage' }); // Adjust the route name as necessary
      },
      navigateToFamilyFriends() {
        // Logic to navigate to FamilyFriends page
        this.$router.push({ name: 'FamilyFriends' });
      }
    }
  };
  </script>
  
  <style scoped>
  .account-balance-page {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 20px;
  }
  
  .header {
    background-color: #007BFF; /* Blue header */
    color: white;
    padding: 20px;
  }
  
  .balance-container {
    margin: 20px 0;
  }
  
  .blurred {
    filter: blur(4px);
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
  </style>