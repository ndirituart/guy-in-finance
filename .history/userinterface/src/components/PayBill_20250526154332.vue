<template>
  <div class="pay-bills-page">
    <h1>Pay Bills</h1>
    <p class="current-balance">Current Balance: Ksh. {{ balanceStore.accountBalance.toLocaleString() }}</p>

    <div class="options-container">
      <div class="option-box">
        <h3>Water Bill</h3>
        <span class="icon">💧</span> <p class="bill-info">PAYBILL: 444400</p>
        <input type="text" v-model="water.accountNumber" placeholder="Account Number (12 digits)" maxlength="12" />
        <input type="number" v-model.number="water.amount" placeholder="Amount (Ksh. > 100)" min="101" />
        <div class="save-details-group">
          <input type="checkbox" id="saveWater" v-model="water.saveDetails" />
          <label for="saveWater">Save Details</label>
        </div>
        <button @click="processWaterPayment">Pay</button>
      </div>

      <div class="option-box">
        <h3>Electricity KPLC</h3>
        <span class="icon">💡</span> <p class="bill-info">PAYBILL: 88888</p>
        <input type="text" v-model="kplc.accountNumber" placeholder="Account Number (12 digits)" maxlength="12" />
        <input type="number" v-model.number="kplc.amount" placeholder="Amount (Ksh. > 35)" min="36" />
        <div class="save-details-group">
          <input type="checkbox" id="saveKPLC" v-model="kplc.saveDetails" />
          <label for="saveKPLC">Save Details</label>
        </div>
        <button @click="processKPLCPayment">Pay</button>
      </div>

      <div class="option-box">
        <h3>Rent</h3>
        <span class="icon">🏠</span> <div class="radio-group">
          <label>
            <input type="radio" v-model="rent.paymentType" value="paybill" /> Paybill
          </label>
          <label>
            <input type="radio" v-model="rent.paymentType" value="till" /> Till Number
          </label>
        </div>

        <input v-if="rent.paymentType === 'paybill'" type="text" v-model="rent.paybillNumber" placeholder="Paybill Number (6-8 digits)" maxlength="8" />
        <input type="text" v-model="rent.accountNumber" :placeholder="rent.paymentType === 'paybill' ? 'Account Number (12 digits)' : 'Till Number (6-8 digits)'" :maxlength="rent.paymentType === 'paybill' ? 12 : 8" />
        <input type="number" v-model.number="rent.amount" placeholder="Amount (Ksh.)" min="1" />
        <div class="save-details-group">
          <input type="checkbox" id="saveRent" v-model="rent.saveDetails" />
          <label for="saveRent">Save Details</label>
        </div>
        <button @click="processRentPayment">Pay</button>
      </div>

      <div class="option-box">
        <h3>Security</h3>
        <span class="icon">👮</span> <div class="radio-group">
          <label>
            <input type="radio" v-model="security.paymentType" value="paybill" /> Paybill
          </label>
          <label>
            <input type="radio" v-model="security.paymentType" value="till" /> Till Number
          </label>
          <label>
            <input type="radio" v-model="security.paymentType" value="sendmoney" /> Send Money
          </label>
        </div>

        <input v-if="security.paymentType === 'paybill'" type="text" v-model="security.paybillNumber" placeholder="Paybill Number (6-8 digits)" maxlength="8" />
        <input type="text" v-model="security.accountNumber" :placeholder="security.paymentType === 'sendmoney' ? 'Phone Number (+254...)' : (security.paymentType === 'paybill' ? 'Account Number (12 digits)' : 'Till Number (6-8 digits)')" :maxlength="security.paymentType === 'sendmoney' ? 13 : (security.paymentType === 'paybill' ? 12 : 8)" />
        <input type="number" v-model.number="security.amount" placeholder="Amount (Ksh.)" min="1" />
        <div class="save-details-group">
          <input type="checkbox" id="saveSecurity" v-model="security.saveDetails" />
          <label for="saveSecurity">Save Details</label>
        </div>
        <button @click="processSecurityPayment">Pay</button>
      </div>

      <div class="option-box">
        <h3>Garbage Collection</h3>
        <span class="icon">🗑️</span> <div class="radio-group">
          <label>
            <input type="radio" v-model="garbage.paymentType" value="paybill" /> Paybill
          </label>
          <label>
            <input type="radio" v-model="garbage.paymentType" value="till" /> Till Number
          </label>
          <label>
            <input type="radio" v-model="garbage.paymentType" value="sendmoney" /> Send Money
          </label>
        </div>

        <input v-if="garbage.paymentType === 'paybill'" type="text" v-model="garbage.paybillNumber" placeholder="Paybill Number (6-8 digits)" maxlength="8" />
        <input type="text" v-model="garbage.accountNumber" :placeholder="garbage.paymentType === 'sendmoney' ? 'Phone Number (+254...)' : (garbage.paymentType === 'paybill' ? 'Account Number (12 digits)' : 'Till Number (6-8 digits)')" :maxlength="garbage.paymentType === 'sendmoney' ? 13 : (garbage.paymentType === 'paybill' ? 12 : 8)" />
        <input type="number" v-model.number="garbage.amount" placeholder="Amount (Ksh.)" min="1" />
        <div class="save-details-group">
          <input type="checkbox" id="saveGarbage" v-model="garbage.saveDetails" />
          <label for="saveGarbage">Save Details</label>
        </div>
        <button @click="processGarbagePayment">Pay</button>
      </div>

      <div class="option-box">
        <h3>HELB Repayment</h3>
        <span class="icon">🎓</span> <p class="helb-info">To repay HELB, dial *642#</p>
        <button @click="processHELBPrompt">Simulate Dialing</button>
      </div>
    </div>

    <div v-if="transactionMessage" class="transaction-message">
      <p>{{ transactionMessage }}</p>
      <div v-if="transactionFailed" class="transaction-actions">
        <button @click="handleTransactionFailure">Take a Loan</button>
        <button @click="stopTransaction">Stop Transaction</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useBalanceStore } from 'src/stores/balanceStores'; // Adjust path if your store file is elsewhere

const balanceStore = useBalanceStore(); // Initialize the store

const transactionMessage = ref('');
const transactionFailed = ref(false);

// State for each bill type (these are local component states for inputs)
const water = ref({
  accountNumber: '',
  amount: null,
  saveDetails: false,
});

const kplc = ref({
  accountNumber: '',
  amount: null,
  saveDetails: false,
});

const rent = ref({
  paymentType: 'paybill',
  paybillNumber: '',
  accountNumber: '',
  amount: null,
  saveDetails: false,
});

const security = ref({
  paymentType: 'paybill',
  paybillNumber: '',
  accountNumber: '',
  amount: null,
  saveDetails: false,
});

const garbage = ref({
  paymentType: 'paybill',
  paybillNumber: '',
  accountNumber: '',
  amount: null,
  saveDetails: false,
});

// Helper function to load saved details from localStorage
const loadSavedDetails = (key, stateRef) => {
  const saved = localStorage.getItem(key);
  if (saved) {
    try {
      const parsed = JSON.parse(saved);
      for (const prop in parsed) {
        
        if (stateRef.value.hasOwnProperty(prop)) {
          stateRef.value[prop] = parsed[prop];
        }
      }
      stateRef.value.saveDetails = true; // Mark as saved if data exists
    } catch (e) {
      console.error(`Error parsing saved details for ${key}:`, e);
      localStorage.removeItem(key);
    }
  }
};

// Helper function to save details to localStorage
const saveDetails = (key, data) => {
  if (data.saveDetails) {
    const dataToSave = {};
    for (const prop in data) {
      if (prop !== 'amount' && prop !== 'saveDetails') { // Don't save the amount
        dataToSave[prop] = data[prop];
      }
    }
    localStorage.setItem(key, JSON.stringify(dataToSave));
  } else {
    localStorage.removeItem(key);
  }
};

onMounted(() => {
  loadSavedDetails('waterDetails', water);
  loadSavedDetails('kplcDetails', kplc);
  loadSavedDetails('rentDetails', rent);
  loadSavedDetails('securityDetails', security);
  loadSavedDetails('garbageDetails', garbage);
});

watch(() => water.value.saveDetails, (newValue) => saveDetails('waterDetails', water.value));
watch(() => kplc.value.saveDetails, (newValue) => saveDetails('kplcDetails', kplc.value));
watch(() => rent.value.saveDetails, (newValue) => saveDetails('rentDetails', rent.value));
watch(() => security.value.saveDetails, (newValue) => saveDetails('securityDetails', security.value));
watch(() => garbage.value.saveDetails, (newValue) => saveDetails('garbageDetails', garbage.value));


// Generic transaction processing function
const processTransaction = (amount, billType, billNumber, accountNumber) => {
  if (amount <= 0 || isNaN(amount)) {
    transactionMessage.value = `Please enter a valid amount for ${billType}.`;
    transactionFailed.value = true;
    return false;
  }

  // Use the store's deductAmount action
  const success = balanceStore.deductAmount(amount);

  if (success) {
    transactionMessage.value = `${billType} Payment Successful! New Balance: Ksh. ${balanceStore.accountBalance.toLocaleString()}`;
    transactionFailed.value = false;
    return true;
  } else {
    transactionMessage.value = 'INSUFFICIENT BALANCE';
    transactionFailed.value = true;
    return false;
  }
};

// Validation functions (unchanged)
const validateAccountNumber = (number) => /^\d{12}$/.test(number);
const validateShortNumber = (number) => /^\d{6,8}$/.test(number);
const validatePhoneNumber = (number) => /^\+254\d{9}$/.test(number);

// --- Specific Bill Payment Methods (logic updated to use processTransaction) ---
const processWaterPayment = () => {
  if (!validateAccountNumber(water.value.accountNumber)) {
    transactionMessage.value = 'Please enter a valid 12-digit Account Number for Water.';
    transactionFailed.value = true;
    return;
  }
  if (water.value.amount <= 100) {
    transactionMessage.value = 'Water bill amount must be greater than Ksh. 100.';
    transactionFailed.value = true;
    return;
  }
  if (processTransaction(water.value.amount, 'Water Bill', '444400', water.value.accountNumber)) {
    saveDetails('waterDetails', water.value);
  }
};

const processKPLCPayment = () => {
  if (!validateAccountNumber(kplc.value.accountNumber)) {
    transactionMessage.value = 'Please enter a valid 12-digit Account Number for KPLC.';
    transactionFailed.value = true;
    return;
  }
  if (kplc.value.amount <= 35) {
    transactionMessage.value = 'KPLC bill amount must be greater than Ksh. 35.';
    transactionFailed.value = true;
    return;
  }
  if (processTransaction(kplc.value.amount, 'KPLC Bill', '88888', kplc.value.accountNumber)) {
    saveDetails('kplcDetails', kplc.value);
  }
};

const processRentPayment = () => {
  if (rent.value.paymentType === 'paybill') {
    if (!validateShortNumber(rent.value.paybillNumber)) {
      transactionMessage.value = 'Please enter a valid 6-8 digit Paybill Number for Rent.';
      transactionFailed.value = true;
      return;
    }
    if (!validateAccountNumber(rent.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid 12-digit Account Number for Rent.';
      transactionFailed.value = true;
      return;
    }
  } else if (rent.value.paymentType === 'till') {
    if (!validateShortNumber(rent.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid 6-8 digit Till Number for Rent.';
      transactionFailed.value = true;
      return;
    }
  } else {
    transactionMessage.value = 'Please select a payment type for Rent.';
    transactionFailed.value = true;
    return;
  }
  if (processTransaction(rent.value.amount, 'Rent Bill', rent.value.paybillNumber || rent.value.accountNumber, rent.value.accountNumber)) {
    saveDetails('rentDetails', rent.value);
  }
};

const processSecurityPayment = () => {
  let isValid = true;
  let billNumber = '';
  let accNum = '';

  if (security.value.paymentType === 'paybill') {
    if (!validateShortNumber(security.value.paybillNumber)) {
      transactionMessage.value = 'Please enter a valid 6-8 digit Paybill Number for Security.';
      isValid = false;
    }
    if (!validateAccountNumber(security.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid 12-digit Account Number for Security.';
      isValid = false;
    }
    billNumber = security.value.paybillNumber;
    accNum = security.value.accountNumber;
  } else if (security.value.paymentType === 'till') {
    if (!validateShortNumber(security.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid 6-8 digit Till Number for Security.';
      isValid = false;
    }
    billNumber = security.value.accountNumber;
  } else if (security.value.paymentType === 'sendmoney') {
    if (!validatePhoneNumber(security.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid Phone Number (+254XXXXXXXXX) for Security.';
      isValid = false;
    }
    billNumber = security.value.accountNumber;
  } else {
    transactionMessage.value = 'Please select a payment type for Security.';
    isValid = false;
  }

  if (!isValid) {
    transactionFailed.value = true;
    return;
  }
  if (processTransaction(security.value.amount, 'Security Bill', billNumber, accNum)) {
    saveDetails('securityDetails', security.value);
  }
};

const processGarbagePayment = () => {
  let isValid = true;
  let billNumber = '';
  let accNum = '';

  if (garbage.value.paymentType === 'paybill') {
    if (!validateShortNumber(garbage.value.paybillNumber)) {
      transactionMessage.value = 'Please enter a valid 6-8 digit Paybill Number for Garbage.';
      isValid = false;
    }
    if (!validateAccountNumber(garbage.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid 12-digit Account Number for Garbage.';
      isValid = false;
    }
    billNumber = garbage.value.paybillNumber;
    accNum = garbage.value.accountNumber;
  } else if (garbage.value.paymentType === 'till') {
    if (!validateShortNumber(garbage.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid 6-8 digit Till Number for Garbage.';
      isValid = false;
    }
    billNumber = garbage.value.accountNumber;
  } else if (garbage.value.paymentType === 'sendmoney') {
    if (!validatePhoneNumber(garbage.value.accountNumber)) {
      transactionMessage.value = 'Please enter a valid Phone Number (+254XXXXXXXXX) for Garbage.';
      isValid = false;
    }
    billNumber = garbage.value.accountNumber;
  } else {
    transactionMessage.value = 'Please select a payment type for Garbage Collection.';
    isValid = false;
  }

  if (!isValid) {
    transactionFailed.value = true;
    return;
  }
  if (processTransaction(garbage.value.amount, 'Garbage Collection Bill', billNumber, accNum)) {
    saveDetails('garbageDetails', garbage.value);
  }
};

const processHELBPrompt = () => {
  transactionMessage.value = 'To repay HELB, please dial *642# on your phone.';
  transactionFailed.value = false;
};

const handleTransactionFailure = () => {
  transactionMessage.value = 'You chose to take a loan. Loan processing...';
  setTimeout(() => {
    transactionFailed.value = false;
    transactionMessage.value = '';
  }, 2000);
};

const stopTransaction = () => {
  transactionMessage.value = '';
  transactionFailed.value = false;
};
</script>

<style scoped>
/* Your existing styles remain unchanged */
.pay-bills-page {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  text-align: center;
  margin: 20px;
  background-color: #f0f2f5;
  min-height: 90vh;
  padding: 20px;
  border-radius: 10px;
}

h1 {
  color: #333;
  margin-bottom: 30px;
  font-size: 2.5em;
  font-weight: 600;
}

.current-balance {
  font-size: 1.5em;
  font-weight: bold;
  color: #007BFF;
  margin-bottom: 25px;
}

.options-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin: 20px auto;
  max-width: 1200px;
  justify-content: center;
}

.option-box {
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 15px;
  padding: 25px;
  text-align: center;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.option-box:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.option-box h3 {
  color: #007BFF;
  margin-bottom: 15px;
  font-size: 1.6em;
}

.icon {
  font-size: 50px;
  margin-bottom: 15px;
  display: block;
}

.bill-info {
  font-size: 0.9em;
  color: #666;
  margin-bottom: 10px;
}

input[type="text"],
input[type="number"] {
  width: calc(100% - 20px);
  padding: 12px;
  margin: 8px 0;
  border: 1px solid #ced4da;
  border-radius: 8px;
  font-size: 1em;
  box-sizing: border-box;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus {
  border-color: #007BFF;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  outline: none;
}

.radio-group {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin: 10px 0 15px;
  flex-wrap: wrap;
}

.radio-group label {
  display: flex;
  align-items: center;
  font-size: 0.95em;
  color: #555;
  cursor: pointer;
}

.radio-group input[type="radio"] {
  margin-right: 5px;
  cursor: pointer;
}

.save-details-group {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 10px 0 15px;
  font-size: 0.9em;
  color: #555;
}

.save-details-group input[type="checkbox"] {
  margin-right: 8px;
  transform: scale(1.2);
}

button {
  width: 100%;
  padding: 12px 25px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.1em;
  font-weight: 600;
  transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
  margin-top: 15px;
}

button:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

button:active {
  background-color: #004085;
  transform: translateY(0);
}

.transaction-message {
  margin-top: 40px;
  padding: 20px;
  border-radius: 10px;
  background-color: #e9ecef;
  color: #333;
  font-weight: bold;
  font-size: 1.2em;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.transaction-actions {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  gap: 15px;
}

.transaction-actions button {
  width: auto;
  padding: 10px 20px;
  font-size: 1em;
  margin-top: 0;
}

.transaction-actions button:first-child {
  background-color: #ffc107;
  color: #333;
}

.transaction-actions button:first-child:hover {
  background-color: #e0a800;
}

.transaction-actions button:last-child {
  background-color: #dc3545;
}

.transaction-actions button:last-child:hover {
  background-color: #c82333;
}

.helb-info {
  font-size: 1.1em;
  color: #333;
  margin: 15px 0;
  font-weight: 500;
}

@media (max-width: 768px) {
  .options-container {
    grid-template-columns: 1fr;
    padding: 0 10px;
  }

  .option-box {
    width: auto;
    margin: 0 auto;
  }
}
</style>