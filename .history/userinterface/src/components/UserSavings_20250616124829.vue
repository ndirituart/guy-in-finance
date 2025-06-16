<template>
  <div class="user-savings-page">
    <div class="header-container">
      <h1>Your Savings</h1>
      <p class="current-date">{{ currentDate }}</p>
    </div>

    <div class="savings-balance">
      <h2>Savings Balance</h2>
      <p :class="{ 'blurred': isBlurred }">Ksh. {{ isBlurred ? '****' : savingsBalance.toFixed(2) }}</p>
      <button @click="toggleBlur">{{ isBlurred ? '👁️' : '👁️‍🗨️' }}</button>
      <button @click="applySavingsGrowth" :disabled="growthAppliedThisMonth">
        {{ growthAppliedThisMonth ? 'Monthly Growth Applied' : 'Apply Monthly Growth' }}
      </button>
    </div>

    <div class="options-container">
      <div class="option-box" @click="deposit">
        <h3>Deposit</h3>
        <span class="icon">😊</span> <button @click="navigateToDeposit">Add to Savings</button>
      </div>

      <div class="option-box" @click="withdraw">
        <h3>Withdraw</h3>
        <span class="icon">😢</span> <button @click="confirmWithdrawal">Withdraw</button>
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
      savingsBalance: 5000, // Initial savings balance
      isBlurred: false,
      popupMessage: '',
      showYesNo: false,
      showEmergency: false,
      currentDate: '',
      growthAppliedThisMonth: false,
    };
  },
  mounted() {
    this.updateCurrentDate();
    this.loadSavingsData();
    this.checkMonthlyGrowth();
  },
  methods: {
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
    checkMonthlyGrowth() {
      const lastGrowthDate = localStorage.getItem('lastSavingsGrowthDate');
      if (lastGrowthDate) {
        const lastDate = new Date(lastGrowthDate);
        const today = new Date();
        if (today.getMonth() === lastDate.getMonth() && today.getFullYear() === lastDate.getFullYear()) {
          this.growthAppliedThisMonth = true;
        } else {
          this.growthAppliedThisMonth = false;
        }
      } else {
        this.growthAppliedThisMonth = false;
      }
    },
    applySavingsGrowth() {
      if (this.growthAppliedThisMonth) {
        alert('Monthly growth has already been applied for this month.');
        return;
      }

      const growthRate = 0.08; // 8% monthly growth
      this.savingsBalance += this.savingsBalance * growthRate;
      this.popupMessage = `Savings balance updated! New Balance: Ksh. ${this.savingsBalance.toFixed(2)}`;
      this.saveSavingsData();
      localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString());
      this.growthAppliedThisMonth = true;
    },
    toggleBlur() {
      this.isBlurred = !this.isBlurred;
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
        const withdrawalAmount = 1000; // Example withdrawal amount
        if (this.savingsBalance >= withdrawalAmount) {
          this.savingsBalance -= withdrawalAmount;
          this.popupMessage = 'Withdrawal successful! NEXT TIME TUNASAVE! 👍';
          this.saveSavingsData();
        } else {
          this.popupMessage = 'Insufficient savings balance for this withdrawal.';
        }
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
  /* Blue background */
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

.option-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
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

button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.popup-message {
  margin-top: 20px;
  padding: 15px;
  border: 1px solid #007BFF;
  border-radius: 8px;
  background-color: #e6f2ff;
  color: #333;
}

.popup-message button {
  margin: 0 5px;
}
</style>
---

### Key Changes and Explanations

Here's a breakdown of the modifications made to your `UserSavings.vue` component:

* **Displaying Current Date:**
    * A new `div` with class `header-container` has been added to wrap the `h1` and the new `p` element for the date.
    * The `data` property now includes `currentDate: ''`.
    * The `mounted()` lifecycle hook calls `this.updateCurrentDate()` to set the date when the component is loaded.
    * The `updateCurrentDate()` method uses `new Date().toLocaleDateString()` to format the current date.

* **Automatic Monthly Interest Growth (8%):**
    * The `growthRate` in `applySavingsGrowth` is updated to `0.08` (for 8%).
    * **Persistence with Local Storage:** To ensure the growth is applied only once per month, I've added logic using `localStorage`:
        * `localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString());` saves the date when the growth was last applied.
        * `checkMonthlyGrowth()` is called in `mounted()` to determine if the growth has already been applied for the current month.
        * The **"Apply Monthly Growth"** button is now **disabled** (`:disabled="growthAppliedThisMonth"`) if the growth has already been applied this month, providing a better user experience. The button text also changes accordingly.
    * `loadSavingsData()` and `saveSavingsData()` methods were added to persist the `savingsBalance` in `localStorage`, so your savings balance will be remembered even if you close and reopen the browser tab.

* **Enhanced Withdrawal Logic:**
    * I've added a check in `handleEmergency('very')` to ensure there's enough `savingsBalance` before allowing a withdrawal, preventing negative balances.

* **Styling:**
    * Basic styles for `.header-container` and `.current-date` have been added to position the date.
    * Styles for disabled buttons have been included to visually indicate when the "Apply Monthly Growth" button is not clickable.

---

### How to Use This Updated Component

1.  **Replace your existing `UserSavings.vue`** file with the code provided above.
2.  **Run your Vue.js application.**

Now, when you visit your savings page, you'll see:

* Today's date displayed at the top right.
* Your savings balance will be loaded from `localStorage` (or start at 5000 if no data is found).
* You can manually click "Apply Monthly Growth" to see your balance increase by 8%. If you've already applied it for the current month, the button will be disabled, indicating that the growth has been applied.
* Your savings balance will persist across browser sessions.

Let me know if you have any other features or adjustments in mind!