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
import QuoteTool from './QuoteTool.vue'

const router = useRouter()
const goToLogin = () => router.push('/login')
const goToRegister = () => router.push('/register')
</script>

<style scoped>
.homepage {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  background: #fff;
}
header {
  margin-top: 2rem;
  display: flex;
  justify-content: space-between;
  width: 100%;
  max-width: 500px;
}
nav button {
  margin: 0 0.5rem;
  padding: 0.5rem 1rem;
  background: #222;
  color: #fff;
  border: none;
  border-radius: 5px;
}
main {
  margin-top: 3rem;
  width: 100%;
  max-width: 500px;
}
</style>