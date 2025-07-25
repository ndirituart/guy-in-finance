<template>
  <div class="daily-expenses-page">
    <h1>Daily Expenses</h1>
    <p><strong>Current Balance:</strong> {{ balance }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Buy Airtime</h3>
        <span class="icon">📱</span>
        <button @click="openAirtimeDataModal('Airtime', 'Safaricom')" class="provider-btn">
          <img src="@/assets/Safaricom.png" alt="Safaricom" class="provider-logo" />
        </button>

        <button @click="openAirtimeDataModal('Airtime', 'Airtel')" class="provider-btn">
          <img src="@/assets/Airtel.png" alt="Airtel" class="provider-logo" />
        </button>

        <button @click="openAirtimeDataModal('Airtime', 'Telkom')" class="provider-btn">
          <img src="@/assets/Telkom.png" alt="Telkom" class="provider-logo" />
        </button>

      </div>

      <div class="option-box">
        <h3>Buy Data</h3>
        <span class="icon">📶</span>

        <button @click="openAirtimeDataModal('Data', 'Safaricom')" class="provider-btn">
          <img src="@/assets/Safaricom.png" alt="Safaricom" class="provider-logo" />
        </button>

        <button @click="openAirtimeDataModal('Data', 'Airtel')" class="provider-btn">
          <img src="@/assets/Airtel.png" alt="Airtel" class="provider-logo" />
        </button>

        <button @click="openAirtimeDataModal('Data', 'Telkom')" class="provider-btn">
          <img src="@/assets/Telkom.png" alt="Telkom" class="provider-logo" />
        </button>
      </div>

      <div class="option-box">
        <h3>Pay Fare</h3>
        <span class="icon">🚌</span>
        <button @click="navigateToDailyExpenseFunction('Fare')">Go to Payment</button>
      </div>

      <div class="option-box">
        <h3>Food</h3>
        <span class="icon">🍔</span>
        <button @click="navigateToDailyExpenseFunction('Food')">Go to Payment</button>
      </div>

      <div class="option-box">
        <h3>Entertainment</h3>
        <span class="icon">🎬👗💍</span>
        <button @click="navigateToDailyExpenseFunction('Entertainment')">Go to Payment</button>
      </div>
    </div>

    <div v-if="transactions.length" class="receipt-table">
      <h2>Transaction History</h2>
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Time</th>
            <th>Balance After</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(txn, index) in transactions" :key="index">
            <td>{{ txn.code }}</td>
            <td>{{ txn.type }}</td>
            <td>{{ txn.amount }}</td>
            <td>{{ txn.time }}</td>
            <td>{{ txn.balance }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="showAirtimeDataModal" class="modal-overlay">
      <div class="modal-content">
        <h2>{{ currentModalType }} for {{ currentModalProvider }}</h2>
        <label for="modalPhoneNumber">Phone Number:</label>
        <input type="tel" id="modalPhoneNumber" v-model="modalPhoneNumber" placeholder="e.g., 07XXXXXXXX" maxlength="10" />

        <label for="modalAmount">Amount:</label>
        <input type="number" id="modalAmount" v-model.number="modalAmount" placeholder="Enter Amount" />

        <label for="modalPin">PIN:</label>
        <input type="password" id="modalPin" v-model="modalPin" placeholder="Enter PIN" maxlength="4" />

        <div class="modal-actions">
          <button @click="processAirtimeDataPurchase" class="confirm-btn">Confirm Purchase</button>
          <button @click="closeAirtimeDataModal" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>
    <!--for easy navigation between pages-->
  </div>
</template>

<script>
export default {
  name: 'DailyExpenses',
  data() {
    return {
      balance: 12390,
      transactions: [],
      correctPin: '2007',

      // Modal related data for Airtime/Data
      showAirtimeDataModal: false,
      currentModalType: '', // 'Airtime' or 'Data'
      currentModalProvider: '', // 'Safaricom', 'Airtel', 'Telkom'
      modalPhoneNumber: '',
      modalAmount: 0,
      modalPin: ''
    };
  },
  mounted() {
    this.loadDataFromLocalStorage();
  },
  methods: {
    loadDataFromLocalStorage() {
      const savedBalance = localStorage.getItem('dailyExpensesBalance');
      const savedTxns = localStorage.getItem('dailyExpensesTransactions');

      if (savedBalance) {
        this.balance = parseFloat(savedBalance);
      } else {
        localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      }
      if (savedTxns) {
        this.transactions = JSON.parse(savedTxns);
      }
    },
    openAirtimeDataModal(type, provider) {
      this.currentModalType = type;
      this.currentModalProvider = provider;
      this.modalPhoneNumber = ''; // Clear previous inputs
      this.modalAmount = 0;
      this.modalPin = '';
      this.showAirtimeDataModal = true;
    },
    closeAirtimeDataModal() {
      this.showAirtimeDataModal = false;
    },
    processAirtimeDataPurchase() {
      if (!this.validatePhoneNumber(this.modalPhoneNumber)) {
        alert('Please enter a valid 10-digit phone number (e.g., 07XXXXXXXX).');
        return;
      }
      if (this.modalAmount <= 0) {
        alert('Please enter a valid amount (must be greater than 0).');
        return;
      }
      if (!this.validatePin(this.modalPin)) {
        alert('Enter correct PIN.');
        return;
      }
      if (this.balance < this.modalAmount) {
        alert('INSUFFICIENT BALANCE');
        return;
      }

      // Process the transaction
      this.balance -= this.modalAmount;
      const txn = this.createTransaction(
        `${this.currentModalType} (${this.currentModalProvider})`,
        this.modalAmount,
        `To: ${this.modalPhoneNumber}` // Add phone number to transaction details
      );
      this.transactions.push({ ...txn, balance: this.balance });
      this.saveToLocalStorage();
      alert(`${this.currentModalType} Purchase Successful! New Balance: Ksh. ${this.balance}`);

      this.closeAirtimeDataModal(); // Close modal after successful purchase
    },
    navigateToDailyExpenseFunction(type) {
      // Still navigates for Fare, Food, Entertainment
      this.$router.push({ name: 'DailyExpenseFunction', query: { type: type } });
    },
    createTransaction(type, amount, details = '') {
      return {
        code: 'DE' + Math.floor(Math.random() * 1000000),
        type: details ? `${type} - ${details}` : type, // Include details if provided
        amount,
        time: new Date().toLocaleString()
      };
    },
    validatePin(pin) {
      return pin === this.correctPin;
    },
    validatePhoneNumber(number) {
      // Basic validation for Kenyan numbers: 10 digits starting with 07 or 01
      // or 12-13 digits starting with +2547 or +2541
      return /^(0|254)(1|7)\d{8}$/.test(number) || /^\+254(1|7)\d{8}$/.test(number);
    },
    saveToLocalStorage() {
      localStorage.setItem('dailyExpensesBalance', this.balance.toString());
      localStorage.setItem('dailyExpensesTransactions', JSON.stringify(this.transactions));
    }
  },
  watch: {
    '$route.path': {
      immediate: true,
      handler(newPath, oldPath) {
        if (newPath === '/daily-expenses' && oldPath && oldPath.includes('/daily-expense-function')) {
          this.loadDataFromLocalStorage();
        }
      }
    }
  }
};
</script>

<style scoped>
/* Existing styles */
.daily-expenses-page {
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

.icon {
  font-size: 40px;
  margin-bottom: 10px;
  display: block;
}

.provider-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px 15px;
  margin: 5px auto;
  background-color: #f0f0f0;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 5px;
  cursor: pointer;
  width: 80%;
  /* Removed text here to just show logo */
}

.provider-btn:hover {
  background-color: #e0e0e0;
}

.provider-logo {
  height: 30px; /* Make logos slightly larger for better visibility */
  width: auto;
  max-width: 100%;
  object-fit: contain;
  margin: 0; /* Remove margin from here as flexbox handles centering */
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


/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  width: 90%;
  max-width: 350px;
  text-align: left;
}

.modal-content h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #007BFF;
}

.modal-content label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.modal-content input {
  width: calc(100% - 20px);
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

.modal-actions {
  display: flex;
  justify-content: space-around;
  margin-top: 20px;
}

.modal-actions button {
  width: 45%;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
}

.modal-actions .confirm-btn {
  background-color: #28a745;
  color: white;
  border: none;
}

.modal-actions .confirm-btn:hover {
  background-color: #218838;
}

.modal-actions .cancel-btn {
  background-color: #dc3545;
  color: white;
  border: none;
}

.modal-actions .cancel-btn:hover {
  background-color: #c82333;
}
.main-footer {
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