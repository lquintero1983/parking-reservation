<template>
  <form @submit.prevent="login">
    <h2>Login</h2>
    <input v-model="email" type="email" placeholder="Email" required />
    <input v-model="password" type="password" placeholder="Password" required />
    <button type="submit">Login</button>
    <p v-if="error" style="color:red">{{ error }}</p>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const error = ref('')

const login = async () => {
  try {
    await axios.post('/api/login', { email: email.value, password: password.value })
    error.value = ''
    // Redirect or set user state here
  } catch (e) {
    error.value = 'Invalid credentials'
  }
}
</script>