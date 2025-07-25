<template>
  <div class="user-savings-page">
    <div class="header-container">
      <h1>Your Savings</h1>
      <p class="current-date">{{ currentDate }}</p>
    </div>

    <div class="savings-balance-card">
      <h2>Savings Balance</h2>
      <div class="balance-display-container">
        <p class="balance-amount" :class="{ 'blurred-balance': isBlurred }">
          Ksh. {{ isBlurred ? '****' : savingsBalance.toFixed(2) }}
        </p>
        <button @click="toggleBlur" class="blur-toggle-btn">
          {{ isBlurred ? '👁️' : '👁️‍🗨️' }}
        </button>
      </div>
    </div>


    <div class="options-container">
      <div class="option-box" @click="navigateToDepositPage">
        <h3>Deposit</h3>
        <span class="icon">😊</span>
        <button @click.stop="navigateToDepositPage">Add to Savings</button>
      </div>

      <div class="option-box" @click="navigateToWithdrawPage">
        <h3>Withdraw</h3>
        <span class="icon">😢</span>
        <button @click.stop="navigateToWithdrawPage">Withdraw</button>
      </div>
    </div>
  </div>
</template>

<script>
import { createApp } from 'vue'; // Import createApp
import DepositSavings from './DepositSavings.vue'; // Import DepositSavings for simulation
import WithdrawSavings from './WithdrawSavings.vue'; // Import WithdrawSavings for simulation

export default {
  name: 'UserSavings',
  data() {
    return {
      savingsBalance: 5000, // Initial savings balance
      isBlurred: false,
      currentDate: '',
    };
  },
  mounted() {
    this.updateCurrentDate();
    this.loadSavingsData();
    this.checkAndApplyGrowth(); // Check and apply growth automatically on mount

    // Listen for balance updates from deposit/withdraw pages
    window.addEventListener('storage', this.handleStorageChange);
  },
  beforeUnmount() {
    window.removeEventListener('storage', this.handleStorageChange);
  },
  methods: {
    handleStorageChange(event) {
      if (event.key === 'userSavingsBalance') {
        this.loadSavingsData(); // Reload balance if localStorage changes
      }
    },
    updateCurrentDate() {
      const today = new Date();
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      this.currentDate = today.toLocaleDateString(undefined, options);
    },
    loadSavingsData() {
      const savedBalance = localStorage.getItem('userSavingsBalance');
      if (savedBalance) {
        this.savingsBalance = parseFloat(savedBalance);
      }
    },
    saveSavingsData() {
      localStorage.setItem('userSavingsBalance', this.savingsBalance.toFixed(2));
    },
    checkAndApplyGrowth() {
      const lastGrowthDateString = localStorage.getItem('lastSavingsGrowthDate');
      let lastGrowthDate = null;

      if (lastGrowthDateString) {
        lastGrowthDate = new Date(lastGrowthDateString);
      } else {
        // If no last growth date, set it to now to start the 30-day cycle
        localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString());
        lastGrowthDate = new Date();
      }

      const today = new Date();
      const timeDifference = today.getTime() - lastGrowthDate.getTime();
      const daysDifference = timeDifference / (1000 * 60 * 60 * 24);

      if (daysDifference >= 30) {
        this.applyAutomaticGrowth();
      }
    },
    applyAutomaticGrowth() {
      const growthRate = 0.08; // 8% monthly growth
      this.savingsBalance += this.savingsBalance * growthRate;
      this.saveSavingsData();
      localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString()); // Update last growth date to now
      alert(`Automatic monthly growth applied! New Balance: Ksh. ${this.savingsBalance.toFixed(2)}`);
    },
    toggleBlur() {
      this.isBlurred = !this.isBlurred;
    },
    navigateToDepositPage() {
      // For this isolated example, we can simulate by replacing the component content
      document.body.innerHTML = '<div id="app"></div>'; // Clear existing app
      createApp(DepositSavings).mount('#app'); // Mount DepositSavings
    },
    navigateToWithdrawPage() {
      // For this isolated example, we can simulate by replacing the component content
      document.body.innerHTML = '<div id="app"></div>'; // Clear existing app
      createApp(WithdrawSavings).mount('#app'); // Mount WithdrawSavings
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

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.current-date {
  font-size: 0.9em;
  color: #555;
}

.savings-balance {
  background-color: #007BFF;
  color: white;
  padding: 20px;
  border-radius: 8px;
  position: relative;
  margin-bottom: 30px;
}

.blurred {
  filter: blur(4px);
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
  width: 200px;
  text-align: center;
  cursor: pointer;
  transition: box-shadow 0.3s;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
}

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.option-box h3 {
  margin-top: 0;
  margin-bottom: 15px;
  color: #007BFF;
}

.option-box .icon {
  font-size: 2em;
  margin-bottom: 15px;
}

button {
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: auto;
  width: 90%;
}

button:hover {
  background-color: #0056b3;
}
</style>