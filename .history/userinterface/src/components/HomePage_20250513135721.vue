<template>
  <div class="account-page">
    <header class="header">
      <h1>Hello {{ username }}
        <router-link to="/user-profile" class="account-icon-link">
          <span class="account-icon">👤</span>
        </router-link>
      </h1>
    </header>

    <div class="balance-container">
      <div class="balance-box">
        <h3>Account Balance</h3>
        <p :class="{ 'blurred': isBlurred }">Ksh. {{ isBlurred ? '****' : accountBalance }}</p>
        <label>
          <input type="checkbox" v-model="isBlurred" /> Blur Value
        </label>
      </div>
      <div class="loan-box">
        <h3>Loan Balance</h3>
        <p :class="{ 'blurred': isBlurred }">Ksh. {{ isBlurred ? '****' : loanBalance }}</p>
        <label>
          <input type="checkbox" v-model="isBlurred" /> Blur Value
        </label>
      </div>
    </div>
    <h2>How can we help you today?</h2>
    <div class="category-container">
      <div class="category-box pink" @click="navigateTo('FamilyFriends')">
        <h3>Family & Friends</h3>
      </div>
      <div class="category-box blue" @click="navigateTo('Bills')">
        <h3>Bills</h3>
        <div class="icons">
          <span>💧</span>
          <span>⚡</span>
          <span>🏦</span>
        </div>
      </div>
      <div class="category-box purple" @click="navigateTo('DailyExpenses')">
        <h3>Daily Expenses</h3>
        <div class="icons">
          <span>🍔</span>
          <span>🚍</span>
          <span>📱</span>
        </div>
      </div>
      <div class="category-box yellow" @click="navigateTo('Savings')">
        <h3>Savings</h3>
        <span>💰</span>
      </div>
    </div>
    <div class="loan-inquiry">
      <h2>Do you need a loan?</h2>
      <button @click="navigateTo('LoanPage')">LOAN</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomePage',
  data() {
    return {
      username: 'User  ', // Replace with actual username
      accountBalance: 12390,
      loanBalance: 0.0,
      isBlurred: false
    };
  },
  methods: {
    navigateTo(page) {
      // Logic to navigate to the respective page
      this.$router.push({ name: page });
    }
  }
};
</script>

<style scoped>
.account-page {
  font-family: Arial, sans-serif;
}

.header {
  background-color: #007BFF; /* Blue header */
  color: white;
  padding: 20px;
  text-align: center;
}

.logo {
  display: block;
  margin: 20px auto; /* Center the logo */
  max-width: 100px; /* Adjust size as needed */
}

.balance-container {
  display: flex;
  justify-content: space-around;
  margin: 20px 0;
}

.balance-box, .loan-box {
  border: 1px solid #007BFF;
  border-radius: 8px;
  padding: 20px;
  width: 200px;
  text-align: center;
}

.blurred {
  filter: blur(4px);
}

.category-container {
  display: flex;
  justify-content: space-around;
  margin: 20px 0;
}

.category-box {
  border-radius: 8px;
  padding: 20px;
  width: 200px;
  text-align: center;
  cursor: pointer;
  transition: box-shadow 0.3s;
}

.category-box:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.category-box.pink {
  background-color: pink;
}

.category-box.blue {
  background-color: lightblue;
}

.category-box.purple {
  background-color: lavender;
}

.category-box.yellow {
  background-color: lightyellow;
}

.category-box:active {
  background-color: red
};
</style>