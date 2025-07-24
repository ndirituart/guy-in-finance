<----
<template>
  <div class="pin-entry-container">
    <div class="pin-card">
      <h2>{{ currentStep === 1 ? 'Set Your PIN' : 'Confirm Your PIN' }}</h2>
      <p class="instruction">{{ currentStep === 1 ? 'Enter your 4-digit PIN.' : 'Re-enter your PIN to confirm.' }}</p>

      <div class="pin-display">
        <span v-for="n in 4" :key="n" :class="{ 'pin-digit-filled': (currentStep === 1 ? pinInput : confirmPinInput).length >= n }"></span>
      </div>
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

      <div class="keypad">
        <button v-for="number in numbers" :key="number" @click="appendPin(number)">{{ number }}</button>
        <button @click="deletePin" class="delete-button">DEL</button>
        <button @click="appendPin(0)">0</button>
        <button @click="processPin" class="enter-button">ENTER</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

export default {
  name: 'PinEntryPage',
  setup() {
    const router = useRouter();
    const route = useRoute(); // To access query parameters

    const pinInput = ref('');
    const confirmPinInput = ref('');
    const currentStep = ref(1); // 1: Enter PIN, 2: Confirm PIN
    const errorMessage = ref('');

    const numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    // Get the user ID from the route query parameter
    // IMPORTANT: In a real app, you'd rely on the auth token for user identification,
    // not just a userId passed in query params for security.
    // However, for this demo, we'll use it to simulate linking the PIN.
    const userId = route.query.userId;
    if (!userId) {
      errorMessage.value = 'User ID not provided. Please sign up again.';
      // Optionally redirect to signup if userId is missing
      // router.replace('/signup');
    }

    // Watch for changes in pinInput and confirmPinInput to clear error messages
    watch([pinInput, confirmPinInput], () => {
      errorMessage.value = '';
    });

    const appendPin = (number) => {
      if (currentStep.value === 1 && pinInput.value.length < 4) {
        pinInput.value += number;
      } else if (currentStep.value === 2 && confirmPinInput.value.length < 4) {
        confirmPinInput.value += number;
      }
    };

    const deletePin = () => {
      if (currentStep.value === 1) {
        pinInput.value = pinInput.value.slice(0, -1);
      } else {
        confirmPinInput.value = confirmPinInput.value.slice(0, -1);
      }
    };

    const processPin = async () => {
      if (currentStep.value === 1) {
        if (pinInput.value.length === 4) {
          currentStep.value = 2; // Move to confirmation step
        } else {
          errorMessage.value = 'PIN must be 4 digits.';
        }
      } else { // currentStep.value === 2
        if (confirmPinInput.value.length === 4) {
          if (pinInput.value === confirmPinInput.value) {
            // PINs match, now save to database
            await savePinToDatabase();
          } else {
            errorMessage.value = 'PINs do not match. Please try again.';
            pinInput.value = ''; // Reset both for re-entry
            confirmPinInput.value = '';
            currentStep.value = 1;
          }
        } else {
          errorMessage.value = 'Confirmation PIN must be 4 digits.';
        }
      }
    };

    const savePinToDatabase = async () => {
      try {
        // Retrieve the authentication token from localStorage
        const authToken = localStorage.getItem('authToken');
        if (!authToken) {
          errorMessage.value = 'Authentication token missing. Please log in again.';
          router.push('/login'); // Redirect to login if token is missing
          return;
        }

        // Set the Authorization header for this request
        axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;

        // Send the PIN to your Laravel backend
        const response = await axios.post('http://127.0.0.1:8000/api/user/set-pin', {
          userId: userId, // Pass userId for backend to identify which user's PIN to update
          pin: pinInput.value,
        });

        if (response.data.message === 'PIN updated successfully') {
          alert('PIN set successfully! You can now log in with your PIN.');
          router.push('/home'); // Redirect to home page after successful PIN setup
        } else {
          errorMessage.value = response.data.message || 'Failed to set PIN.';
        }
      } catch (error) {
        console.error('Error saving PIN:', error.response?.data || error.message);
        errorMessage.value = error.response?.data?.message || 'Failed to save PIN. Please try again.';
      }
    };

    return {
      pinInput,
      confirmPinInput,
      currentStep,
      errorMessage,
      numbers,
      appendPin,
      deletePin,
      processPin,
    };
  },
};
</script>

<style scoped>
.pin-entry-container {
  min-height: 100vh;
  background: linear-gradient(to right, #00c6ff, #0072ff);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.pin-card {
  background: white;
  padding: 2.5rem;
  border-radius: 15px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
  text-align: center;
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

h2 {
  color: #0072ff;
  margin-bottom: 1rem;
  font-size: 2em;
}

.instruction {
  color: #555;
  margin-bottom: 2rem;
  font-size: 1.1em;
}

.pin-display {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-bottom: 2rem;
}

.pin-display span {
  width: 40px;
  height: 40px;
  border: 2px solid #0072ff;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5em;
  font-weight: bold;
  color: #0072ff;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.pin-digit-filled {
  background-color: #0072ff;
  transform: scale(1.1);
}

.pin-digit-filled::after {
  content: ''; /* Filled circle */
  width: 15px;
  height: 15px;
  background-color: white;
  border-radius: 50%;
}

.keypad {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.keypad button {
  padding: 1.2rem;
  font-size: 1.8em;
  font-weight: bold;
  background-color: #e0f2f7;
  color: #0072ff;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: background-color 0.2s ease, transform 0.1s ease;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.keypad button:hover {
  background-color: #cce7f0;
  transform: translateY(-2px);
}

.keypad button:active {
  background-color: #aaddf0;
  transform: translateY(0);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.delete-button {
  background-color: #ff6b6b;
  color: white;
}

.delete-button:hover {
  background-color: #e04f4f;
}

.enter-button {
  background-color: #42b983;
  color: white;
}

.enter-button:hover {
  background-color: #369c6e;
}

.error-message {
  color: #ff6b6b;
  margin-top: 1rem;
  font-weight: bold;
}
</style>
