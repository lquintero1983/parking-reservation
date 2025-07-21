<template>
  <form @submit.prevent="register">
    <h2>Register</h2>
    <input v-model="name" type="text" placeholder="Name" required />
    <input v-model="email" type="email" placeholder="Email" required />
    <input v-model="password" type="password" placeholder="Password" required />
    <button type="submit">Register</button>
    <p v-if="error" style="color:red">{{ error }}</p>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const name = ref('')
const email = ref('')
const password = ref('')
const error = ref('')

const register = async () => {
  try {
    await axios.post('/api/register', { name: name.value, email: email.value, password: password.value })
    error.value = ''
    // Redirect or set user state here
  } catch (e) {
    error.value = 'Registration failed'
  }
}
</script>