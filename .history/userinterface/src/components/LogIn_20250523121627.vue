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
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value,
      password: password.value,
    });

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

  } catch (error) {
    errorMessage.value = 'Login failed. Please try again.';
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
.social-login {
  margin-top: 1.5rem;
}

.social-login p {
  margin-bottom: 0.5rem;
  color: #888;
}

.social-icons i {
  margin: 0 0.5rem;
  font-size: 1.2rem;
  cursor: pointer;
  color: #555;
}

.social-icons i:hover {
  color: #000;
}

.signup-link {
  margin-top: 1rem;
  color: #888;
  text-align: center;
}

.signup-link a {
  color: #0072ff;
  font-weight: bold;
  text-decoration: none;
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
</style>