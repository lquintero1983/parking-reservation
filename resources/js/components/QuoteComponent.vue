<template>
  <v-container class="bg-grey-lighten-4" fluid>
    <v-row justify="center">
      <v-col cols="12" md="10" lg="8">
        <v-row>
          <v-col cols="12">
            <h1 class="text-h4 font-weight-bold text-grey-darken-4">Cotiza tu estadía</h1>
            <span class="text-grey-darken-1">Introduce tus datos para recibir información de precios</span>
          </v-col>
        </v-row>

        <v-row>
          <!-- Quote Form -->
          <v-col cols="12" lg="6">
            <v-card>
              <v-card-title class="d-flex align-center mb-5">
                <v-icon icon="ri-time-line" class="mr-2"></v-icon>
                Detalles de la reserva
              </v-card-title>
              <!--<v-card-subtitle>Complete sus requisitos de estacionamiento</v-card-subtitle>-->
              <v-card-text>
                <v-form @submit.prevent="handleSubmit">
                  <v-row>
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="form.checkInDate"
                        label="Fecha de ingreso"
                        type="date"
                        required
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="form.checkOutDate"
                        label="Fecha de salida"
                        type="date"
                        required
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="form.checkInTime"
                        label="Hora de ingreso"
                        type="time"                        
                        required
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <v-text-field
                        v-model="form.checkOutTime"
                        label="Hora de salida"
                        type="time"                        
                        required
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" sm="12" md="12">
                        <v-select
                            v-model="form.location"
                            :items="locations"
                            label="Ubicación"
                            item-title="text"
                            item-value="value"                           
                            required
                        ></v-select>
                    </v-col>                    
                  </v-row>  
                  <v-row>
                    <v-col cols="12" sm="12" md="12">
                      <v-select
                        v-model="form.vehicleType"
                        :items="vehicleTypes"
                        label="Tipo de vehículo"
                        item-title="text"
                        item-value="value"                        
                        required
                      ></v-select>
                    </v-col>
                  </v-row> 
                  <v-row>
                    <v-col cols="12" class="text-center">
                        <!--
                        <v-checkbox
                        v-model="form.termsAccepted"
                        label="Acepto los términos y condiciones"
                        required
                      ></v-checkbox>
                        -->                      
                    <v-btn
                        type="submit"
                        color="primary"
                        block
                        size="large"
                        :loading="loading"
                        :disabled="loading"
                    >
                        {{ loading ? 'Calculando...' : 'Cotizar' }}
                    </v-btn>
                    </v-col>
                  </v-row>

                  
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Quote Results -->
          <v-col cols="12" lg="6" v-if="quote">
            <v-card>
              <v-card-title class="d-flex align-center">
                <v-icon icon="ri-currency-line" color="green-darken-2" class="mr-2"></v-icon>
                Resultado de la cotización
              </v-card-title>
              <v-card-subtitle>Detalle de su reserva</v-card-subtitle>
              <v-card-text>
                <div class="d-flex align-center text-grey-darken-1 mb-4">
                  <v-icon icon="ri-map-pin-line" class="mr-2" size="small"></v-icon>
                  {{ quote.location }}
                </div>

                <div class="mb-4">
                  <div class="d-flex justify-space-between mb-2">
                    <span>Tarifa Diaria:</span>
                    <span class="font-weight-bold">${{ quote.dailyRate }}</span>
                  </div>
                  <div class="d-flex justify-space-between mb-2">
                    <span>Duración:</span>
                    <span>{{ quote.totalDays }} días</span>
                  </div>
                  <v-divider class="my-2"></v-divider>
                  <div class="d-flex justify-space-between text-h6 font-weight-bold">
                    <span>Total:</span>
                    <span class="text-green-darken-2">${{ quote.totalCost }}</span>
                  </div>
                </div>

                <v-alert type="success" variant="tonal" class="mb-4">
                  <template v-slot:prepend>
                    <v-icon icon="ri-time-line"></v-icon>
                  </template>
                  {{ quote.availableSpots }} lugares disponibles
                </v-alert>

                <div class="d-flex flex-column">
                  <v-btn color="primary" class="mb-2" block>Reservar</v-btn>
                  <v-btn variant="outlined" block>Enviar consulta</v-btn>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';

const loading = ref(false);
const quote = ref(null);

const form = ref({
  checkInDate: '',
  checkOutDate: '',
  checkInTime: '',
  checkOutTime: '',
  location: null,
  vehicleType: null
});

const locations = ref([
  { text: 'Centro comercial Toscas', value: 'downtown-a' },
  { text: 'Open Parking Ezeiza', value: 'downtown-b' },  
]);

const vehicleTypes = ref([
  { text: 'Auto', value: 'compact' },
  { text: 'Camion', value: 'truck' },
  { text: 'Camioneta', value: 'pickup' },  
  { text: 'Moto', value: 'motorcycle' },  
  { text: 'SUV', value: 'suv' },    
]);

const handleSubmit = async () => {
  loading.value = true;
  
  // Simulate API call
  setTimeout(() => {
    quote.value = {
      location: "Downtown Parking Lot A",
      dailyRate: 25,
      totalDays: 3,
      totalCost: 75,
      availableSpots: 12,
    };
    loading.value = false;
  }, 1500);
};
</script>

<style scoped>
/* Puedes añadir estilos personalizados aquí si es necesario */
</style>