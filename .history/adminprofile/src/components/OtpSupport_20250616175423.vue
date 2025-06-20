<template>
  <div class="admin-page">
    <h2>OTP Support</h2>
    <p>Manage and assist with One-Time Password requests from users.</p>

    <div class="content-area">
      <h3>Active OTP Requests (Mock Data)</h3>
      <ul v-if="activeOtpRequests.length">
        <li v-for="otp in activeOtpRequests" :key="otp.id" class="otp-item">
          <p><strong>OTP ID:</strong> {{ otp.id }}</p>
          <p><strong>User ID:</strong> {{ otp.userId }}</p>
          <p><strong>Type:</strong> {{ otp.type }}</p>
          <p><strong>Date:</strong> {{ otp.date }}</p>
          <div class="actions">
            <button @click="resolveOtp(otp.id)" class="resolve-button">Mark as Resolved</button>
            <button @click="resendOtp(otp.id)" class="resend-button">Resend OTP</button>
          </div>
        </li>
      </ul>
      <p v-else>No active OTP requests at the moment.</p>
    </div>

    <button @click="goBackToDashboard" class="back-button">Back to Dashboard</button>

    <div v-if="message" :class="['popup-message', messageType]">
      <p>{{ message }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OtpSupport',
  data() {
    return {
      activeOtpRequests: [],
      message: '',
      messageType: '',
    };
  },
  mounted() {
    this.loadActiveOtpRequests();
  },
  methods: {
    loadActiveOtpRequests() {
      // In a real app, this would fetch from your backend API
      const allOtp = JSON.parse(localStorage.getItem('adminMockOtpRequests') || '[]');
      this.activeOtpRequests = allOtp.filter(otp => otp.status === 'active' || otp.status === 'pending');
    },
    resolveOtp(otpId) {
      // In a real app, this would send an API request to your backend
      let allOtp = JSON.parse(localStorage.getItem('adminMockOtpRequests') || '[]');
      const otpIndex = allOtp.findIndex(otp => otp.id === otpId);
      if (otpIndex !== -1) {
        allOtp[otpIndex].status = 'resolved';
        localStorage.setItem('adminMockOtpRequests', JSON.stringify(allOtp));
        this.loadActiveOtpRequests(); // Refresh the list
        this.showMessage(`OTP Request ${otpId} marked as resolved.`, 'success');
      }
    },
    resendOtp(otpId) {
      // In a real app, this would trigger a backend process to resend the OTP
      this.showMessage(`Resending OTP for Request ${otpId} (simulated).`, 'info');
    },
    goBackToDashboard() {
      this.$router.push({ name: 'AdminHome' });
    },
    showMessage(msg, type) {
      this.message = msg;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
        this.messageType = '';
      }, 3000);
    }
  }
};
</script>

<style scoped>
.admin-page {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
  padding: 30px;
  border: 1px solid #e0e6ed;
  border-radius: 10px;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.07);
  background-color: #fff;
}

h2 {
  color: #007BFF;
  margin-bottom: 25px;
  font-size: 2em;
}

.content-area {
  margin-top: 30px;
  padding: 20px;
  background-color: #f9fbfd;
  border-radius: 8px;
  border: 1px solid #e0e6ed;
}

.otp-item {
  border: 1px solid #d3e0ea;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  background-color: #ffffff;
  text-align: left;
}

.otp-item p {
  margin: 5px 0;
  color: #333;
}

.actions button {
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
  margin-right: 10px;
  font-weight: bold;
}

.resolve-button {
  background-color: #28a745;
  color: white;
  border: none;
}

.resolve-button:hover {
  background-color: #218838;
}

.resend-button {
  background-color: #17a2b8;
  color: white;
  border: none;
}

.resend-button:hover {
  background-color: #138496;
}

.back-button {
  padding: 10px 20px;
  background-color: #6c757d;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 30px;
}

.back-button:hover {
  background-color: #5a6268;
}

.popup-message {
  margin-top: 20px;
  padding: 15px;
  border-radius: 8px;
  font-weight: bold;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.popup-message.success {
  background-color: #d4edda;
  color: #155724;
  border-color: #c3e6cb;
}

.popup-message.error {
  background-color: #f8d7da;
  color: #721c24;
  border-color: #f5c6cb;
}

.popup-message.info {
  background-color: #d1ecf1;
  color: #0c5460;
  border-color: #bee5eb;
}
</style>

