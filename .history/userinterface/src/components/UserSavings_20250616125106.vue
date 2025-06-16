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
    };
  },
  mounted() {
    this.updateCurrentDate();
    this.loadSavingsData();
    this.checkAndApplyGrowth(); // Check and apply growth automatically on mount
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
      // Calculate difference in milliseconds, then days
      const timeDifference = today.getTime() - lastGrowthDate.getTime();
      const daysDifference = timeDifference / (1000 * 60 * 60 * 24);

      if (daysDifference >= 30) {
        this.applyAutomaticGrowth();
      }
    },
    applyAutomaticGrowth() {
      const growthRate = 0.08; // 8% monthly growth
      this.savingsBalance += this.savingsBalance * growthRate;
      this.popupMessage = `Automatic monthly growth applied! New Balance: Ksh. ${this.savingsBalance.toFixed(2)}`;
      this.saveSavingsData();
      localStorage.setItem('lastSavingsGrowthDate', new Date().toISOString()); // Update last growth date to now
      setTimeout(() => {
        this.popupMessage = ''; // Clear message after a short delay
      }, 5000); // Message disappears after 5 seconds
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
      setTimeout(() => {
        this.popupMessage = ''; // Clear message after a short delay
      }, 3000);
    },
    handleEmergency(severity) {
      if (severity === 'very') {
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
      setTimeout(() => {
        this.popupMessage = ''; // Clear message after a short delay
      }, 3000);
    },
    navigateToDeposit() {
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

.popup-message {
  margin-top: 20px;
  padding: 15px;
  border: 1px solid #007BFF;
  border-radius: 8px;
  background-color: #e6f2ff;
  color: #333;
  position: fixed; /* Make it fixed so it stays on top */
  top: 50%; /* Center vertically */
  left: 50%; /* Center horizontally */
  transform: translate(-50%, -50%); /* Adjust for exact centering */
  z-index: 1000; /* Ensure it's above other content */
}

.popup-message button {
  margin: 0 5px;
}
</style>

---

### What's Changed

* **Removed "Apply Monthly Growth" Button:** The button for manual application has been completely removed from the template.
* **`checkAndApplyGrowth` Method:**
    * This new method replaces `checkMonthlyGrowth` and `applySavingsGrowth` from the previous version.
    * It retrieves the `lastSavingsGrowthDate` from `localStorage`.
    * If `lastSavingsGrowthDate` doesn't exist (e.g., first time loading the page), it sets the current date as the `lastSavingsGrowthDate` to start the 30-day count.
    * It calculates the difference in days between the `lastGrowthDate` and the current date.
    * If `daysDifference` is 30 or more, it calls `applyAutomaticGrowth()`.
* **`applyAutomaticGrowth` Method:**
    * This method now contains the core logic for applying the 8% growth.
    * It updates `savingsBalance`, saves it to `localStorage`, and crucially, updates `lastSavingsGrowthDate` to the current date/time using `new Date().toISOString()`. This resets the 30-day countdown.
    * It also displays a temporary `popupMessage` to inform the user that automatic growth has occurred, which then disappears after 5 seconds using `setTimeout`.
* **`mounted()` Hook Update:** `this.checkAndApplyGrowth()` is now called directly when the component mounts, ensuring the automatic check happens immediately.
* **Improved Popup Message Handling:** I've added `setTimeout` calls to automatically clear the `popupMessage` after a few seconds for a smoother user experience, rather than requiring a manual close.
* **Popup Message Styling:** Added `position: fixed`, `top: 50%`, `left: 50%`, `transform: translate(-50%, -50%)`, and `z-index: 1000` to the `.popup-message` style to ensure it always appears centered on the screen and on top of other content.

Now, your savings will truly grow automatically by 8% every 30 days!