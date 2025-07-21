<template>
  <div class="homepage minimalist">
    <header>
      <h1>Parking Reservation</h1>
      <nav>
        <button @click="goToLogin">Login</button>
        <button @click="goToRegister">Register</button>
      </nav>
    </header>
    <main>
      <QuoteTool />
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import QuoteTool from '../components/QuoteTool.vue'

const router = useRouter()
const goToLogin = () => router.push('/login')
const goToRegister = () => router.push('/register')
</script>