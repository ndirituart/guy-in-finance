<template>
  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

        <div class="input-group">
          <label for="email">Email</label>
          <div class="input-icon">
            <i class="fas fa-user"></i>
            <input
              type="email" id="email" v-model="email" placeholder="Type your email" required
            />
          </div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input
              type="password"
              id="password"
              v-model="password"
              placeholder="Type your password"
              required
            />
          </div>
          <div class="forgot-password">
            <a href="#">Forgot password?</a>
          </div>
        </div>

        <button class="login-btn" type="submit">LOGIN</button>

        <div class="social-login">
          <p>Or Sign Up Using</p>
          <div class="social-icons">
            <i class="fab fa-facebook-f"></i>
            <i class="fab fa-twitter"></i>
            <i class="fab fa-google"></i>
          </div>
        </div>

        <div class="signup-link">
          <p>Don't have an account?</p>
          <router-link to="/signup">SIGN UP</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios'; // Import Axios

const email = ref('');
const password = ref('');
const errorMessage = ref(''); // To display API errors
const router = useRouter();

const handleLogin = async () => {
  errorMessage.value = '';

  try {
    // You have a slight issue in your Axios call here with the `withCredentials` placement.
    // It should be within the config object after the data.
    // Let's correct that as well for robustness.

    const response = await axios.post(
      'http://127.0.0.1:8000/api/login',
      {
        email: email.value,
        password: password.value,
      },
      { // This is the config object
        withCredentials: true, // Place withCredentials here
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      }
    );

    const token = response.data.token;
    if (token) {
      // Save token and set default header
      localStorage.setItem('authToken', token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`; // Correct template literal

      // ✅ Navigate to home page
      router.push({ name: 'HomePage' }); // Assuming you have a route named 'HomePage'
    } else {
      errorMessage.value = 'Login successful, but no token received.';
    }
  }
  catch (error) {
    console.error('Login error:', error);
    if (error.response) {
      console.error('Error response data:', error.response.data);
      errorMessage.value = error.response.data.message || 'Login failed. Please try again.';
    } else if (error.request) {
        // The request was made but no response was received
        errorMessage.value = 'Network error. Please check your connection.';
    } else {
        // Something happened in setting up the request that triggered an Error
        errorMessage.value = 'An unexpected error occurred during login.';
    }
  }
};

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: linear-gradient(to right, #6a11cb, #2575fc);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding: 20px; /* Added for better mobile responsiveness */
  box-sizing: border-box;
}

.login-box {
  background-color: white;
  padding: 2rem;
  width: 100%;
  max-width: 400px;
  border-radius: 12px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  text-align: center;
}

h2 {
  margin-bottom: 1.5rem;
  color: #333;
}

.input-group {
  margin-bottom: 1rem;
  text-align: left;
}

.input-group label {
  font-weight: 600;
  font-size: 0.9rem;
  color: #555;
}

.input-icon {
  display: flex;
  align-items: center;
  background-color: #f3f3f3;
  border-radius: 8px;
  padding: 0.5rem;
  margin-top: 0.3rem;
}

.input-icon i {
  margin-right: 0.5rem;
  color: #aaa;
}

.input-icon input {
  border: none;
  background: transparent;
  width: 100%;
  font-size: 1rem;
  outline: none;
  padding: 0.25rem 0; /* Adjust padding for better input height */
}

.forgot-password {
  text-align: right;
  font-size: 0.8rem;
  margin-top: 0.3rem;
}

.forgot-password a {
  color: #0072ff; /* Make forgot password link more prominent */
  text-decoration: none;
}
.forgot-password a:hover {
  text-decoration: underline;
}

button.login-btn { /* Specific class for login button */
  width: 100%;
  padding: 0.75rem 1.5rem; /* Increased padding for larger button */
  font-size: 1.1rem; /* Larger font size */
  background-color: #007bff; /* A nice blue for login */
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  margin-top: 1.5rem; /* More space above login button */
  box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
}

button.login-btn:hover {
  background-color: #0056b3; /* Darker blue on hover */
  transform: translateY(-2px);
}

button.login-btn:active {
  background-color: #004085;
  transform: translateY(0);
}

.social-login {
  margin-top: 2rem; /* More space */
  border-top: 1px solid #eee; /* Separator line */
  padding-top: 1.5rem;
}

.social-login p {
  margin-bottom: 1rem;
  color: #888;
}

.social-icons i {
  margin: 0 0.8rem; /* More spacing for icons */
  font-size: 1.5rem; /* Larger icons */
  cursor: pointer;
  color: #555;
  transition: color 0.3s ease;
}

.social-icons i:hover {
  color: #000;
  transform: scale(1.1); /* Slight enlarge on hover */
}

.signup-link {
  margin-top: 1.5rem;
  color: #888;
  text-align: center;
}

.signup-link a {
  color: #0072ff;
  font-weight: bold;
  text-decoration: none;
  transition: color 0.3s ease;
}

.signup-link a:hover {
  color: #0056b3;
  text-decoration: underline;
}

/* Style for error message */
.error-message {
  color: red;
  background-color: #ffe6e6;
  border: 1px solid red;
  padding: 0.75rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  text-align: center;
  font-size: 0.9rem;
}
</style>