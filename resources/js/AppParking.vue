<template>
  <v-app>
    <!-- App Bar / Navbar -->
    <v-app-bar app color="primary" dark elevation="0">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="font-weight-bold">ParkingReserve</v-toolbar-title>
      
      <v-spacer></v-spacer>
      
      <template v-if="!user">
        <v-btn text @click="loginDialog = true">Iniciar Sesión</v-btn>
        <v-btn outlined @click="registerDialog = true">Registrarse</v-btn>
      </template>
      <template v-else>
        <v-btn icon>
          <v-icon>mdi-account</v-icon>
        </v-btn>
      </template>
    </v-app-bar>

    <!-- Navigation Drawer -->
    <v-navigation-drawer v-model="drawer" app temporary>
      <v-list nav dense>
        <v-list-item-group v-model="group" active-class="primary--text text--accent-4">
          <v-list-item to="/">
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Inicio</v-list-item-title>
          </v-list-item>

          <v-list-item to="/reservations">
            <v-list-item-icon>
              <v-icon>mdi-calendar</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Mis Reservas</v-list-item-title>
          </v-list-item>

          <v-list-item to="/locations">
            <v-list-item-icon>
              <v-icon>mdi-map-marker</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Nuestras Ubicaciones</v-list-item-title>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main>
      <!-- Hero Section -->
      <v-parallax :src="require('@/assets/parking-hero.jpg')" height="600">
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 white--text">Reserva tu estacionamiento con un solo clic</h1>
            <p class="text-h6 text-md-h5 white--text mb-8">Encuentra el lugar perfecto para tu vehículo al mejor precio</p>
            <v-btn x-large color="primary" @click="scrollToQuote">Cotizar ahora</v-btn>
          </v-col>
        </v-row>
      </v-parallax>

      <!-- Quote Calculator Section -->
      <v-sheet id="quote" color="grey lighten-4" class="py-12">
        <v-container>
          <v-row justify="center">
            <v-col cols="12" md="8">
              <v-card class="pa-6" elevation="4">
                <h2 class="text-h4 text-center mb-6">Cotiza tu estadía</h2>
                
                <v-form ref="quoteForm" v-model="validQuote">
                  <v-row>
                    <v-col cols="12" md="6">
                      <v-menu
                        v-model="menuCheckIn"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="quote.checkIn"
                            label="Fecha de entrada"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            :rules="[v => !!v || 'Campo requerido']"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="quote.checkIn"
                          @input="menuCheckIn = false"
                          :min="today"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>

                    <v-col cols="12" md="6">
                      <v-menu
                        v-model="menuCheckOut"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="quote.checkOut"
                            label="Fecha de salida"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            :rules="[
                              v => !!v || 'Campo requerido',
                              v => v > quote.checkIn || 'La fecha de salida debe ser posterior'
                            ]"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="quote.checkOut"
                          @input="menuCheckOut = false"
                          :min="quote.checkIn || today"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col cols="12" md="6">
                      <v-select
                        v-model="quote.vehicleType"
                        :items="vehicleTypes"
                        label="Tipo de vehículo"
                        prepend-icon="mdi-car"
                        :rules="[v => !!v || 'Campo requerido']"
                      ></v-select>
                    </v-col>

                    <v-col cols="12" md="6">
                      <v-select
                        v-model="quote.location"
                        :items="locations"
                        label="Ubicación (opcional)"
                        prepend-icon="mdi-map-marker"
                      ></v-select>
                    </v-col>
                  </v-row>

                  <v-row justify="center">
                    <v-col cols="12" md="6" class="text-center">
                      <v-btn
                        x-large
                        color="primary"
                        :loading="loadingQuote"
                        @click="calculateQuote"
                      >
                        Calcular precio
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-form>

                <v-expand-transition>
                  <v-card v-if="quoteResult" class="mt-6" outlined>
                    <v-card-text>
                      <div class="text-h5 text-center mb-4">Resultado de la cotización</div>
                      <v-divider class="mb-4"></v-divider>
                      
                      <v-row>
                        <v-col cols="6">
                          <div class="text-subtitle-1">Duración:</div>
                          <div class="text-subtitle-1">Tipo de vehículo:</div>
                          <div class="text-subtitle-1">Ubicación:</div>
                        </v-col>
                        <v-col cols="6" class="text-right">
                          <div class="text-subtitle-1">{{ quoteDuration }} días</div>
                          <div class="text-subtitle-1">{{ quote.vehicleType }}</div>
                          <div class="text-subtitle-1">{{ quote.location || 'Cualquier ubicación' }}</div>
                        </v-col>
                      </v-row>
                      
                      <v-divider class="my-4"></v-divider>
                      
                      <div class="text-h4 text-center primary--text font-weight-bold">${{ quoteResult.total }}</div>
                      
                      <div class="text-center mt-4">
                        <v-btn color="primary" @click="reserveNow">Reservar ahora</v-btn>
                      </div>
                    </v-card-text>
                  </v-card>
                </v-expand-transition>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-sheet>

      <!-- Features Section -->
      <v-container class="py-12">
        <h2 class="text-h3 text-center mb-8">¿Por qué elegirnos?</h2>
        
        <v-row>
          <v-col cols="12" md="4" v-for="(feature, i) in features" :key="i">
            <v-card flat class="text-center pa-4" height="100%">
              <v-icon x-large color="primary" class="mb-4">{{ feature.icon }}</v-icon>
              <h3 class="text-h5 mb-3">{{ feature.title }}</h3>
              <p class="text-body-1">{{ feature.description }}</p>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <!-- Locations Section -->
      <v-sheet color="grey lighten-4" class="py-12">
        <v-container>
          <h2 class="text-h3 text-center mb-8">Nuestras ubicaciones</h2>
          
          <v-row>
            <v-col cols="12" md="6" v-for="(location, i) in featuredLocations" :key="i">
              <v-card>
                <v-img :src="location.image" height="200"></v-img>
                <v-card-title>{{ location.name }}</v-card-title>
                <v-card-subtitle>{{ location.address }}</v-card-subtitle>
                <v-card-text>
                  <v-chip small color="green" text-color="white" class="mr-2">
                    {{ location.spots }} espacios disponibles
                  </v-chip>
                  <v-chip small color="orange" text-color="white">
                    ${{ location.hourlyRate }}/hora
                  </v-chip>
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" text @click="viewLocation(location.id)">Ver detalles</v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
          
          <div class="text-center mt-6">
            <v-btn color="primary" outlined large to="/locations">Ver todas las ubicaciones</v-btn>
          </div>
        </v-container>
      </v-sheet>

      <!-- Testimonials Section -->
      <v-container class="py-12">
        <h2 class="text-h3 text-center mb-8">Lo que dicen nuestros clientes</h2>
        
        <v-row justify="center">
          <v-col cols="12" md="8">
            <v-carousel cycle height="auto" hide-delimiters>
              <v-carousel-item v-for="(testimonial, i) in testimonials" :key="i">
                <v-card flat class="text-center pa-6">
                  <v-avatar size="80" class="mb-4">
                    <v-img :src="testimonial.avatar"></v-img>
                  </v-avatar>
                  <p class="text-h6 font-italic mb-4">"{{ testimonial.comment }}"</p>
                  <div class="text-subtitle-1 font-weight-bold">{{ testimonial.name }}</div>
                  <div class="text-caption">{{ testimonial.position }}</div>
                </v-card>
              </v-carousel-item>
            </v-carousel>
          </v-col>
        </v-row>
      </v-container>

      <!-- Call to Action -->
      <v-parallax :src="require('@/assets/parking-cta.jpg')" height="400">
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" class="text-center">
            <h2 class="text-h3 text-md-h2 font-weight-bold mb-4 white--text">¿Listo para reservar?</h2>
            <v-btn x-large color="primary" @click="scrollToQuote">Cotizar ahora</v-btn>
          </v-col>
        </v-row>
      </v-parallax>
    </v-main>

    <!-- Footer -->
    <v-footer color="primary" dark padless>
      <v-container>
        <v-row>
          <v-col cols="12" md="4">
            <h3 class="text-h5 mb-4">ParkingReserve</h3>
            <p>La solución más sencilla para reservar tu estacionamiento en cualquier momento y lugar.</p>
          </v-col>
          
          <v-col cols="12" md="4">
            <h3 class="text-h5 mb-4">Enlaces rápidos</h3>
            <v-list dense color="transparent" dark>
              <v-list-item to="/">Inicio</v-list-item>
              <v-list-item to="/locations">Ubicaciones</v-list-item>
              <v-list-item to="/pricing">Precios</v-list-item>
              <v-list-item to="/contact">Contacto</v-list-item>
            </v-list>
          </v-col>
          
          <v-col cols="12" md="4">
            <h3 class="text-h5 mb-4">Contacto</h3>
            <v-list dense color="transparent" dark>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-email</v-icon>
                </v-list-item-icon>
                <v-list-item-content>info@parkingreserve.com</v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-phone</v-icon>
                </v-list-item-icon>
                <v-list-item-content>+1 (555) 123-4567</v-list-item-content>
              </v-list-item>
            </v-list>
          </v-col>
        </v-row>
        
        <v-divider class="my-4"></v-divider>
        
        <div class="text-center">
          &copy; {{ new Date().getFullYear() }} ParkingReserve. Todos los derechos reservados.
        </div>
      </v-container>
    </v-footer>

    <!-- Login Dialog -->
    <v-dialog v-model="loginDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5">Iniciar Sesión</v-card-title>
        
        <v-card-text>
          <v-form ref="loginForm" v-model="validLogin">
            <v-text-field
              v-model="login.email"
              label="Correo electrónico"
              required
              :rules="emailRules"
            ></v-text-field>
            
            <v-text-field
              v-model="login.password"
              label="Contraseña"
              type="password"
              required
              :rules="[v => !!v || 'Campo requerido']"
            ></v-text-field>
            
            <div class="text-right">
              <v-btn text color="primary" small>¿Olvidaste tu contraseña?</v-btn>
            </div>
          </v-form>
        </v-card-text>
        
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="loginDialog = false">Cancelar</v-btn>
          <v-btn color="primary" @click="submitLogin" :loading="loadingLogin">Ingresar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Register Dialog -->
    <v-dialog v-model="registerDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5">Crear Cuenta</v-card-title>
        
        <v-card-text>
          <v-form ref="registerForm" v-model="validRegister">
            <v-text-field
              v-model="register.name"
              label="Nombre completo"
              required
              :rules="[v => !!v || 'Campo requerido']"
            ></v-text-field>
            
            <v-text-field
              v-model="register.email"
              label="Correo electrónico"
              required
              :rules="emailRules"
            ></v-text-field>
            
            <v-text-field
              v-model="register.password"
              label="Contraseña"
              type="password"
              required
              :rules="[v => !!v || 'Campo requerido', v => v.length >= 6 || 'Mínimo 6 caracteres']"
            ></v-text-field>
            
            <v-text-field
              v-model="register.passwordConfirm"
              label="Confirmar contraseña"
              type="password"
              required
              :rules="[
                v => !!v || 'Campo requerido',
                v => v === register.password || 'Las contraseñas no coinciden'
              ]"
            ></v-text-field>
          </v-form>
        </v-card-text>
        
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="registerDialog = false">Cancelar</v-btn>
          <v-btn color="primary" @click="submitRegister" :loading="loadingRegister">Registrarse</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  setup() {
    const drawer = ref(false);
    const group = ref(null);
    const user = ref(null);
    
    // Quote Calculator
    const today = new Date().toISOString().substr(0, 10);
    const menuCheckIn = ref(false);
    const menuCheckOut = ref(false);
    const validQuote = ref(false);
    const loadingQuote = ref(false);
    const quoteResult = ref(null);
    
    const quote = ref({
      checkIn: today,
      checkOut: today,
      vehicleType: 'Automóvil',
      location: null
    });
    
    const vehicleTypes = ref([
      'Automóvil',
      'Motocicleta',
      'Camioneta',
      'Vehículo grande'
    ]);
    
    const locations = ref([
      'Centro Comercial Plaza',
      'Aeropuerto Internacional',
      'Estación Central',
      'Zona Hospitalaria'
    ]);
    
    const quoteDuration = computed(() => {
      if (!quote.value.checkIn || !quote.value.checkOut) return 0;
      
      const oneDay = 24 * 60 * 60 * 1000;
      const firstDate = new Date(quote.value.checkIn);
      const secondDate = new Date(quote.value.checkOut);
      
      return Math.round(Math.abs((firstDate - secondDate) / oneDay)) + 1;
    });
    
    function calculateQuote() {
      if (!validQuote.value) return;
      
      loadingQuote.value = true;
      
      // Simulate API call
      setTimeout(() => {
        const basePrice = quoteDuration.value * 10;
        const total = basePrice * 1.16; // Add taxes
        
        quoteResult.value = {
          basePrice,
          taxes: basePrice * 0.16,
          total: total.toFixed(2)
        };
        
        loadingQuote.value = false;
      }, 1000);
    }
    
    function reserveNow() {
      if (!user.value) {
        loginDialog.value = true;
        return;
      }
      
      // Navigate to reservation page
      console.log('Redirect to reservation page');
    }
    
    function scrollToQuote() {
      const element = document.getElementById('quote');
      element.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Features
    const features = ref([
      {
        icon: 'mdi-clock-fast',
        title: 'Reserva rápida',
        description: 'Reserva tu espacio en menos de un minuto desde cualquier dispositivo.'
      },
      {
        icon: 'mdi-shield-check',
        title: 'Seguridad garantizada',
        description: 'Nuestros estacionamientos cuentan con vigilancia 24/7 y sistemas de seguridad.'
      },
      {
        icon: 'mdi-currency-usd',
        title: 'Precios competitivos',
        description: 'Ofrecemos las mejores tarifas del mercado con múltiples opciones de pago.'
      }
    ]);
    
    // Featured Locations
    const featuredLocations = ref([
      {
        id: 1,
        name: 'Centro Comercial Plaza',
        address: 'Av. Principal #123, Ciudad',
        spots: 42,
        hourlyRate: 2.5,
        image: require('@/assets/location1.jpg')
      },
      {
        id: 2,
        name: 'Aeropuerto Internacional',
        address: 'Terminal 1, Zona Aeroportuaria',
        spots: 128,
        hourlyRate: 3.0,
        image: require('@/assets/location2.jpg')
      }
    ]);
    
    function viewLocation(id) {
      console.log('View location', id);
    }
    
    // Testimonials
    const testimonials = ref([
      {
        name: 'María González',
        position: 'Ejecutiva de Ventas',
        comment: 'Increíble servicio. Siempre encuentro lugar cerca de mi oficina y a buen precio.',
        avatar: require('@/assets/avatar1.jpg')
      },
      {
        name: 'Carlos Mendoza',
        position: 'Viajero frecuente',
        comment: 'La mejor opción para dejar el auto cuando viajo. Seguro y conveniente.',
        avatar: require('@/assets/avatar2.jpg')
      },
      {
        name: 'Laura Jiménez',
        position: 'Estudiante',
        comment: 'Fácil de usar y me ahorra tiempo buscando estacionamiento en la universidad.',
        avatar: require('@/assets/avatar3.jpg')
      }
    ]);
    
    // Auth Dialogs
    const loginDialog = ref(false);
    const registerDialog = ref(false);
    const validLogin = ref(false);
    const validRegister = ref(false);
    const loadingLogin = ref(false);
    const loadingRegister = ref(false);
    
    const login = ref({
      email: '',
      password: ''
    });
    
    const register = ref({
      name: '',
      email: '',
      password: '',
      passwordConfirm: ''
    });
    
    const emailRules = [
      v => !!v || 'Correo electrónico requerido',
      v => /.+@.+\..+/.test(v) || 'Correo electrónico debe ser válido'
    ];
    
    function submitLogin() {
      if (!validLogin.value) return;
      
      loadingLogin.value = true;
      
      // Simulate login
      setTimeout(() => {
        user.value = { name: 'Usuario Demo', email: login.value.email };
        loginDialog.value = false;
        loadingLogin.value = false;
      }, 1500);
    }
    
    function submitRegister() {
      if (!validRegister.value) return;
      
      loadingRegister.value = true;
      
      // Simulate registration
      setTimeout(() => {
        user.value = { name: register.value.name, email: register.value.email };
        registerDialog.value = false;
        loadingRegister.value = false;
      }, 1500);
    }
    
    return {
      drawer,
      group,
      user,
      today,
      menuCheckIn,
      menuCheckOut,
      validQuote,
      loadingQuote,
      quoteResult,
      quote,
      vehicleTypes,
      locations,
      quoteDuration,
      calculateQuote,
      reserveNow,
      scrollToQuote,
      features,
      featuredLocations,
      viewLocation,
      testimonials,
      loginDialog,
      registerDialog,
      validLogin,
      validRegister,
      loadingLogin,
      loadingRegister,
      login,
      register,
      emailRules,
      submitLogin,
      submitRegister
    };
  }
};
</script>

<style scoped>
/* Custom styles */
.v-parallax {
  margin-bottom: 80px;
}

.v-card {
  transition: transform 0.3s ease;
}

.v-card:hover {
  transform: translateY(-5px);
}

.v-btn {
  text-transform: none;
}

.text-h1, .text-h2, .text-h3, .text-h4 {
  font-family: 'Roboto', sans-serif !important;
}

/* Responsive adjustments */
@media (max-width: 960px) {
  .v-parallax {
    height: 400px !important;
  }
  
  .text-h3 {
    font-size: 2rem !important;
  }
  
  .text-h6 {
    font-size: 1.1rem !important;
  }
}

@media (max-width: 600px) {
  .v-parallax {
    height: 300px !important;
  }
  
  .text-h3 {
    font-size: 1.5rem !important;
  }
  
  .text-h6 {
    font-size: 1rem !important;
  }
}
</style>