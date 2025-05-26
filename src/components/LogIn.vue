<template>
  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

        <div class="input-group">
          <label for="email">Email</label> <div class="input-icon">
            <i class="fas fa-user"></i>
            <input
              type="email" id="email"   v-model="email" placeholder="Type your email" required
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

       <div class="signup-btn">
  <p>Don't have an account?</p>
  <router-link to="/signup" class="login-btn signup-login-btn">SIGN UP</router-link>
</div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios'; // Import Axios

// defineOptions({
//   name: 'LogIn',
// });

// Change username to email to match backend AuthController's expected field
const email = ref('');
const password = ref('');
const errorMessage = ref(''); // To display API errors
const router = useRouter();

const handleLogin = async () => {
  errorMessage.value = '';

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login',
    {
  withCredentials: true
},
  {
    email: email.value,
    password: password.value,
      },
  //  withCredentials: true,
  // Set headers to accept JSON
  {
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
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      // ✅ Navigate to home page
      router.push({ name: 'HomePage' });
    } else {
      errorMessage.value = 'Login successful, but no token received.';
    }

  }
  //specifying errors more is it lack of connection or wrong credentials
  catch (error) {
    console.error('Login error:', error);
    if (error.response) {
      console.error('Error response data:', error.response.data);
      errorMessage.value = error.response.data.message || 'Login failed. Please try again.';
    } else {
      errorMessage.value = 'Network error. Please check your connection.';
    }
  }
};

</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

.login-container {
  min-height: 100vh;
  background: linear-gradient(to right, #00c6ff, #0072ff);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px; /* Add some padding for smaller screens */
  box-sizing: border-box; /* Include padding in element's total width and height */
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
}

.input-group {
  margin-bottom: 1rem;
  text-align: left;
}

.input-group label {
  font-weight: 600;
  font-size: 0.9rem;
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
}

.forgot-password {
  text-align: right;
  font-size: 0.8rem;
  margin-top: 0.3rem;
}

.forgot-password a {
  color: #888;
  text-decoration: none;
}

button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983; /* Green by default */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s; /* Smooth transition for background color */
}

button.red {
  background-color: red; /* Red when clicked */
}

button.orange {
  background-color: rgb(0, 255, 85); /* Orange when hovered before clicked */
}

button:hover {
  background-color: orange; /* Orange on hover */
}

button:active {
  background-color: red;
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
}
/* Existing styles for button.login-btn remain */

.signup-login-btn {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983; /* Green by default */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s; /* Smooth transition for background color */
}

.signup-login-btn:hover {
  background-color: #218838; /* Darker green on hover */
}

.signup-login-btn:active {
  background-color: #1e7e34;
}
</style>