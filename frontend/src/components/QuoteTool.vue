<template>
  <div class="quote-tool">
    <h2>Get a Quote</h2>
    <form @submit.prevent="submitQuote">
      <label>Check-in Date:</label>
      <input type="date" v-model="checkIn" required />
      <label>Check-out Date:</label>
      <input type="date" v-model="checkOut" required />
      <button type="submit">Verify Price</button>
    </form>
    <div v-if="quote">
      <p>Estimated Price: <strong>{{ quote.price }}</strong></p>
      <p>Location: <strong>{{ quote.location }}</strong></p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const checkIn = ref('')
const checkOut = ref('')
const quote = ref(null)

const submitQuote = async () => {
  const { data } = await axios.post('/api/quote', {
    check_in: checkIn.value,
    check_out: checkOut.value
  })
  quote.value = data
}
</script>

<style scoped>
.quote-tool {
  background: #f7f7f7;
  padding: 2rem;
  border-radius: 8px;
}
form label {
  display: block;
  margin-top: 1rem;
}
form input {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.2rem;
}
button {
  margin-top: 1rem;
  background: #222;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 0.5rem 1rem;
}
</style>