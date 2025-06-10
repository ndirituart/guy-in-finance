<template>
    <div class="user-savings-page">
      <h1>Your Savings</h1>
  
      <div class="savings-balance">
        <h2>Savings Balance</h2>
        <p :class="{ 'blurred': isBlurred }">Ksh. {{ isBlurred ? '****' : savingsBalance.toFixed(2) }}</p>
        <button @click="toggleBlur">{{ isBlurred ? '👁️' : '👁️‍🗨️' }}</button>
        <button @click="applySavingsGrowth">Apply Monthly Growth</button>
      </div>
  
      <div class="options-container">
        <div class="option-box" @click="deposit">
          <h3>Deposit</h3>
          <span class="icon">😊</span> <!-- Smile icon -->
          <button @click="navigateToDeposit">Add to Savings</button>
        </div>
  
        <div class="option-box" @click="withdraw">
          <h3>Withdraw</h3>
          <span class="icon">😢</span> <!-- Sad icon -->
          <button @click="confirmWithdrawal">Withdraw</button>
        </div>
      </div>
  
      <div v-if="popupMessage" class="popup-message">
        <p>{{ popupMessage }}</p>
        <button v-if="showYesNo" @click="handleYes">YES</button>
        <button v-if="showYesNo" @click="handleNo">NO</button>
        <button v-if="showEmergency" @click="handleEmergency('very')">VERY</button>
        <button v-if="showEmergency" @click="handleEmergency('notBad')">NOT THAT BAD</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'UserSavings',
    data() {
      return {
        savingsBalance: 5000, // Replace with actual savings balance
        isBlurred: false,
        popupMessage: '',
        showYesNo: false,
        showEmergency: false,
      };
    },
    methods: {
      toggleBlur() {
        this.isBlurred = !this.isBlurred;
      },
      applySavingsGrowth() {
        const growthRate = 0.04; // 4% monthly growth
        this.savingsBalance += this.savingsBalance * growthRate;
        this.popupMessage = `Savings balance updated! New Balance: Ksh. ${this.savingsBalance.toFixed(2)}`;
      },
      deposit() {
        // Logic to deposit money (navigate to M-Pesa or card payment)
        this.navigateToDeposit();
      },
      withdraw() {
        this.popupMessage = 'Are you sure?';
        this.showYesNo = true;
      },
      confirmWithdrawal() {
        this.popupMessage = 'Are you sure?';
        this.showYesNo = true;
      },
      handleYes() {
        this.popupMessage = 'How serious is this emergency?';
        this.showYesNo = false;
        this.showEmergency = true;
      },
      handleNo() {
        this.popupMessage = "Yeyyyy, that's financial discipline";
        this.showYesNo = false;
        this.showEmergency = false;
      },
      handleEmergency(severity) {
        if (severity === 'very') {
          // Logic to withdraw from savings
          this.savingsBalance -= 1000; // Example withdrawal amount
          this.popupMessage = 'Withdrawal successful! NEXT TIME TUNASAVE! 👍';
        } else {
          this.popupMessage = "Yeyyyy, that's financial discipline";
        }
        this.showEmergency = false;
      },
      navigateToDeposit() {
        // Logic to navigate to deposit page (M-Pesa or card payment)
        alert('Navigating to deposit page...');
      }
    }
  };
  </script>
  
  <style scoped>
  .user-savings-page {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 20px;
  }
  
  .savings-balance {
    background-color: #007BFF; /* Blue background */
    color: white;
    padding: 20px;
    border-radius: 8px;
    position: relative;
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
    
    </style>