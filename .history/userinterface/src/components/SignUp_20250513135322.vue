<template>
  <div class="signup-container">
    <form class="signup-form" @submit.prevent="submitForm">
      <h2>Sign Up</h2>

      <!-- Profile Image -->
      <!--
      <div class="form-group image-upload">
        <label>Profile Image</label>
        <input type="file" @change="handleFileUpload" accept="image/*" />
        <img v-if="previewImage" :src="previewImage" alt="Profile Preview" class="preview-image" />
      </div>
      -->

      <!-- Username -->
      <div class="form-group">
        <label>Username</label>
        <input v-model="form.username" type="text" required />
      </div>

      <!-- Full Name -->
      <div class="form-group full-name">
        <div>
          <label>First Name</label>
          <input v-model="form.firstName" type="text" required />
        </div>
        <div>
          <label>Middle Name</label>
          <input v-model="form.middleName" type="text" />
        </div>
        <div>
          <label>Last Name</label>
          <input v-model="form.lastName" type="text" required />
        </div>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" required />
      </div>

      <!-- Birthday -->
      <div class="form-group">
        <label>Birthday</label>
        <input v-model="form.birthday" type="date" required @change="checkAge" />
        <span v-if="age && age < 14" class="error">Users must be over 14 years old</span>
      </div>

      <!-- ID Number -->
      <div v-if="age >= 18" class="form-group">
        <label>ID Number (optional)</label>
        <input v-model="form.idNumber" type="text" />
      </div>

      <!-- Phone -->
      <div class="form-group">
        <label>Phone Number</label>
        <input v-model="form.phone" type="tel" required pattern="^\+254\d{9}$" placeholder="+2547XXXXXXXX" />
      </div>

      <!-- Passwords -->
      <div class="form-group">
        <label>Password</label>
        <input v-model="form.password" type="password" required />
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input v-model="form.confirmPassword" type="password" required />
        <span v-if="passwordMismatch" class="error">Passwords do not match</span>
      </div>

      <!-- Submit -->
      <button type="submit" class="signup-btn" :disabled="!formValid">SIGN UP</button>

      <hr />
      <p>Do you have an account?</p>
      <button type="button" class="login-btn" @click="router.push('/login')">LOGIN</button>
    </form>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const router = useRouter()

    const form = ref({
      username: '',
      firstName: '',
      middleName: '',
      lastName: '',
      email: '',
      birthday: '',
      idNumber: '',
      phone: '',
      password: '',
      confirmPassword: '',
    })

    const age = ref(null)
    const previewImage = ref(null)

    const passwordMismatch = computed(() => {
      return form.value.password !== form.value.confirmPassword
    })

    const formValid = computed(() => {
      return (
        !passwordMismatch.value &&
        age.value >= 14 &&
        form.value.phone.startsWith('+254') &&
        form.value.password.length >= 6
      )
    })

    const checkAge = () => {
      const today = new Date()
      const birthDate = new Date(form.value.birthday)
      let calculatedAge = today.getFullYear() - birthDate.getFullYear()
      const monthDiff = today.getMonth() - birthDate.getMonth()
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        calculatedAge--
      }
      age.value = calculatedAge
    }

    const handleFileUpload = (e) => {
      const file = e.target.files[0]
      if (file) {
        previewImage.value = URL.createObjectURL(file)
      }
    }

    const submitForm = () => {
      if (!formValid.value) return
      alert('Account created successfully!')
      router.push('/home')
    }

    return {
      form,
      age,
      previewImage,
      passwordMismatch,
      formValid,
      checkAge,
      handleFileUpload,
      submitForm,
      router,
    }
  },
}
</script>

<style scoped>
/* your existing styles stay unchanged */
</style>
