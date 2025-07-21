<template>
  <div>
    <h2>Your Reservations</h2>
    <ul>
      <li v-for="r in reservations" :key="r.id">
        {{ r.check_in }} - {{ r.check_out }} @ {{ r.location.name }} ({{ r.status }})
        <button v-if="r.status === 'active'" @click="cancel(r.id)">Cancel</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const reservations = ref([])

const fetchReservations = async () => {
  const { data } = await axios.get('/api/reservations')
  reservations.value = data
}
onMounted(fetchReservations)

const cancel = async (id) => {
  await axios.post(`/api/reservation/${id}/cancel`)
  fetchReservations()
}
</script>