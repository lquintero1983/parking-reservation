<script setup>
import { ref, computed } from 'vue'
import avatar8 from "@images/avatars/avatar-8.png";
import avatar2 from "@images/avatars/avatar-2.png";
import avatar3 from "@images/avatars/avatar-3.png";
import parking1 from "@images/parking1.jpg";
import parking2 from "@images/parking2.png";
import parking3 from "@images/parking3.png";
import parking4 from "@images/parking4.png";
import parking5 from "@images/parking5.png";
import parking6 from "@images/parking6.png";
import parking7 from "@images/parking7.png";
import parking8 from "@images/parking8.png";
import parking9 from "@images/parking9.png";
import parking10 from "@images/parking10.png";
import parking11 from "@images/parking11.png";
import logo from "@images/logos/logo-ptf-blanco.png";
import logoLogin from "@images/logos/logo-ptf-negro.png";
import { useTheme } from "vuetify";
import authV1MaskDark from "@images/pages/auth-v1-mask-dark.png";
import authV1MaskLight from "@images/pages/auth-v1-mask-light.png";
import VideoBackground from '@/components/VideoComponent.vue';
import Quote from '@/components/QuoteComponent.vue';


import { login } from "../api/auth";
import { useRouter } from "vue-router";

const router = useRouter();

const isLoading = ref(false);

const form = ref({
  email: "",
  password: "",
  remember: false,
});

async function handleLogin() {
  isLoading.value = true;
  try {
    await login(form.value.email, form.value.password);
    router.push("/dashboard");
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error de inicio de sesión",
      text: "Revisá tu email y contraseña",
    });
  } finally {
    isLoading.value = false;
  }
}

const vuetifyTheme = useTheme();

const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === "light" ? authV1MaskLight : authV1MaskDark;
});

const isPasswordVisible = ref(false);

const logoColor = computed(() => {
  return vuetifyTheme.global.name.value === "light" ? "#ff0000" : "#00ff00";
});

// Modern gradient colors
const gradientColors = computed(() => {
  return vuetifyTheme.global.name.value === "light"
    ? "linear-gradient(135deg, #9090E0 0%, #042990 100%)"
    : "linear-gradient(135deg, #9E95F5 0%, #B8B1FF 100%)";
});

// Color personalizado - azul corporativo #042990
const primaryColor = ref("#042990");

// Estilo para el logo
const logoStyle = computed(() => ({
  color: primaryColor.value,
}));

// Estilo para el botón
const buttonStyle = computed(() => ({
  background: primaryColor.value,
  "--hover-color": "#1a43b5", // Color más claro para hover
}));


// Estado de la aplicación
const drawer = ref(false)
const group = ref(null)
const user = ref(null)

// Quote Calculator
const today = new Date().toISOString().substr(0, 10)
const menuCheckIn = ref(false)
const menuCheckOut = ref(false)
const validQuote = ref(false)
const loadingQuote = ref(false)
const quoteResult = ref(null)

// Imágenes para el slider (reemplaza con tus propias imágenes)
const sliderImages = ref([
  {    
    src: parking6,
    title: '¿Tenés pensado viajar?',
    subtitle: 'Nosotros cuidamos tu vehículo'
  },
  {    
    src: parking2,
    title: 'Estacionamiento seguro y vigilado',
    subtitle: 'Tus vehículos protegidos las 24 horas'
  },
  {
    src: parking5,
    title: 'Reserva tu estacionamiento con un solo clic',
    subtitle: 'Encontrá el lugar perfecto para tu vehículo al mejor precio'
  },
])

const scrollToQuote = () => {
  // Tu lógica existente para scroll
  const element = document.getElementById('quote')

  element?.scrollIntoView({ behavior: 'smooth' });
}

const quote = ref({
  checkIn: today,
  checkOut: today,
  vehicleType: 'Automóvil',
  location: null,
})

const vehicleTypes = ref([
  'Automóvil',
  'Motocicleta',
  'Camioneta',
  'Vehículo grande',
])

const locations = ref([
  'Centro Comercial Plaza',
  'Aeropuerto Internacional',
  'Estación Central',
  'Zona Hospitalaria',
])

const quoteDuration = computed(() => {
  if (!quote.value.checkIn || !quote.value.checkOut) return 0
  
  const oneDay = 24 * 60 * 60 * 1000
  const firstDate = new Date(quote.value.checkIn)
  const secondDate = new Date(quote.value.checkOut)
  
  return Math.round(Math.abs((firstDate - secondDate) / oneDay)) + 1
})

function calculateQuote() {
  console.log('Calculating quote for:' , {
    checkIn: quote.value.checkIn,
    checkOut: quote.value.checkOut,
    vehicleType: quote.value.vehicleType,
    location: quote.value.location
  })
  //if (!validQuote.value) return
  
  loadingQuote.value = true
  
  // Simulate API call
  setTimeout(() => {
    const basePrice = quoteDuration.value * 10
    const total = basePrice * 1.16 // Add taxes
    
    quoteResult.value = {
      basePrice,
      taxes: basePrice * 0.16,
      total: total.toFixed(2)
    }
    
    loadingQuote.value = false
  }, 1000)
}

function reserveNow() {
  if (!user.value) {
    loginDialog.value = true

    return
  }
  
  // Navigate to reservation page
  console.log('Redirect to reservation page')
}

/*function scrollToQuote() {
  const element = document.getElementById('quote')
  element.scrollIntoView({ behavior: 'smooth' })
}*/

// Features
const features = ref([
  {
    icon: 'ri-time-line',
    title: 'Reserva rápida', 
    description: 'Reserva tu espacio en menos de un minuto desde cualquier dispositivo.'
  },
  {
    icon: 'ri-shield-check-line',
    title: 'Seguridad garantizada',
    description: 'Nuestros estacionamientos cuentan con vigilancia 24/7 y sistemas de seguridad.'
  },
  {
    icon: 'ri-money-dollar-box-line',
    title: 'Precios competitivos',
    description: 'Ofrecemos las mejores tarifas del mercado con múltiples opciones de pago.'
  }
])

// Featured Locations
const featuredLocations = ref([
  {
    id: 1,
    name: 'Centro Comercial Las Toscas',
    address: 'Formosa 123, Canning, Ezeiza',
    spots: 42,
    hourlyRate: 2.5,
    image: parking1,
  },
  {
    id: 2,
    name: 'Open Parking Ezeiza',
    address: 'Conquista del Desierto 456, Ezeiza',
    spots: 128,
    hourlyRate: 3.0,
    image: parking2
  }
])

function viewLocation(id) {
  console.log('View location', id)
}

// Testimonials
const testimonials = ref([
  {
    name: 'María González',
    position: 'Ejecutiva de Ventas',
    comment: 'Increíble servicio. Siempre encuentro lugar cerca de mi oficina y a buen precio.',
    avatar: avatar8,
  },
  {
    name: 'Laura Jiménez',
    position: 'Estudiante',
    comment: 'Fácil de usar y me ahorra tiempo buscando estacionamiento en la universidad.',
    avatar: avatar2,
  },
  {
    name: 'Carlos Mendoza',
    position: 'Viajero frecuente',
    comment: 'La mejor opción para dejar el auto cuando viajo. Seguro y conveniente.',
    avatar: avatar3,
  },
  
])

// Auth Dialogs
const loginDialog = ref(false)
const registerDialog = ref(false)
const validLogin = ref(false)
const validRegister = ref(false)
const loadingLogin = ref(false)
const loadingRegister = ref(false)



const register = ref({
  name: '',
  email: '',
  password: '',
  passwordConfirm: ''
})

const emailRules = [
  v => !!v || 'Correo electrónico requerido',
  v => /.+@.+\..+/.test(v) || 'Correo electrónico debe ser válido'
]

function submitLogin() {
  if (!validLogin.value) return
  
  loadingLogin.value = true
  
  // Simulate login
  setTimeout(() => {
    user.value = { name: 'Usuario Demo', email: login.value.email }
    loginDialog.value = false
    loadingLogin.value = false
  }, 1500)
}

function submitRegister() {
  if (!validRegister.value) return
  
  loadingRegister.value = true
  
  // Simulate registration
  setTimeout(() => {
    user.value = { name: register.value.name, email: register.value.email }
    registerDialog.value = false
    loadingRegister.value = false
  }, 1500)
}
</script>

<template>
  <v-app>
    <!-- App Bar / Navbar -->
    <v-app-bar app color="primary" dark elevation="0">
      <!-- Logo pequeño a la izquierda -->
      <v-img
        :src="logo"
        max-height="50"
        max-width="120"
        contain
        class="mr-4"
        style="cursor: pointer;"
        @click="$router.push('/')"
      ></v-img>

      <!-- Ícono de menú (opcional, puedes eliminarlo si no lo necesitas) -->
      <v-app-bar-nav-icon @click="drawer = !drawer" class="hidden-md-and-up"></v-app-bar-nav-icon>
      
      <v-spacer></v-spacer>
      
      <!-- Botones de navegación -->
      <template v-if="!user">
        <v-btn text @click="loginDialog = true" color="white">Iniciar Sesión</v-btn>
        <v-btn outlined @click="registerDialog = true" color="white" class="ml-2">Registrarse</v-btn>
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
       <!--
      <v-parallax :src="parking31" height="600">
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" class="text-center">
            <h1 class="text-h3 text-md-h2 font-weight-bold mb-4 white--text">Reserva tu estacionamiento con un solo clic</h1>
            <p class="text-h6 text-md-h5 white--text mb-8">Encuentra el lugar perfecto para tu vehículo al mejor precio</p>
            <v-btn x-large color="primary" @click="scrollToQuote">Cotizar ahora</v-btn>
          </v-col>
        </v-row>
      </v-parallax>
      -->
      <VideoBackground @scroll-to-quote="scrollToQuote" />
      <!--
      <v-carousel
        cycle
        height="600"
        hide-delimiters
        :show-arrows="false"
        interval="5000"
      >
        <v-carousel-item
          v-for="(image, i) in sliderImages"
          :key="i"
          :src="image.src"
          cover
          transition="fade-transition"
        >
          <v-row
            align="center"
            justify="center"
            class="fill-height ma-0"
          >
            <v-col cols="12" md="8" class="text-center d-flex flex-column align-center">
              
              <h1 class="text-h1 text-md-h1 font-weight-bold mb-2 white--text"
                  style="color: white; text-shadow: 2px 2px 4px #000000;">
                PARK TO FLY
              </h1>
              
              <h1  style="color: white; text-shadow: 2px 2px 4px #000000;">
                {{ image.title }}
              </h1>
              
              
              <p class="text-h6 text-md-h5 font-weight-medium white--text mb-6 px-4 py-2"
                style="color: #FFA500; background-color: rgba(0,0,0,0.7);
                  max-width: 80%;
                ">
                {{ image.subtitle }}
              </p>
              
             
              <v-btn 
                x-large 
                color="primary" 
                @click="scrollToQuote"
                class="mt-4"
                style="box-shadow: 0 4px 8px rgba(0,0,0,0.3); min-width: 200px;"
              >
                Cotizar ahora
              </v-btn>
            </v-col>
          </v-row>
        </v-carousel-item>
      </v-carousel>
    -->
      <!-- Quote Calculator Section -->
      <v-sheet id="quote" color="grey lighten-4" >
        <quote-component />          
        <!--
        <v-container>
          <v-row justify="center">
            <v-col cols="12" md="12">
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
        -->
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
      <v-parallax :src="parking5" height="400">
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" class="text-center">            
            <h2 class="text-h3 text-md-h2 font-weight-bold mb-4 white--text" style="color: white; text-shadow: 2px 2px 4px #000000;">¿Listo para reservar?</h2>
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
      <div class="auth-wrapper">
        <VCard class="auth-card">
          <VCardItem class="justify-center logo-container">
            <div class="d-flex align-center">                                                
              <img class="logo-wrapper"  :src="logoLogin" />
            </div>
            
          </VCardItem>

          <VCardText class="text-center pt-2">
            <h5 class="text-h5 font-weight-semibold mb-1">Bienvenido! 👋🏻</h5>
            <p class="mb-0">Por favor inicie sesión para comenzar</p>
          </VCardText>

          <VCardText>
            <VForm @submit.prevent="handleLogin">
              <VRow>
                <!-- email -->

                <VCol cols="12">
                  <VTextField
                    v-model="form.email"
                    label="Email"
                    type="email"
                    prepend-inner-icon="ri-mail-line"
                    variant="outlined"
                    color="primary"
                    rounded="lg"
                  />
                </VCol>

                <!-- password -->
                <VCol cols="12">
                  <VTextField
                    v-model="form.password"
                    label="Contraseña"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    prepend-inner-icon="ri-lock-line"
                    :append-inner-icon="isPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    variant="outlined"
                    color="primary"
                    rounded="lg"
                  />                                    
                  <!-- login button -->
                  <VBtn
                    block
                    type="submit"
                    :loading="isLoading"
                    rounded="lg"
                    size="large"
                    class="mt-6"
                    :style="{ background: gradientColors }"
                  >
                    Iniciar Sesión
                  </VBtn>
                </VCol>

                <!--<VCol cols="12" class="text-center mt-2">
                  <span class="text-caption">¿No tienes una cuenta?</span>
                  <RouterLink class="text-caption font-weight-bold ml-2" to="/register" :style="{ color: logoColor }">
                    Regístrate
                  </RouterLink>
                </VCol>-->
              </VRow>
            </VForm>
          </VCardText>
        </VCard>
        
      </div>
      <!--
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
      -->
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

/* Efecto de transición suave */
.fade-transition {
  transition: opacity 1s ease-in-out;
}

/* Asegurar que el contenido ocupe toda la altura */
/* Estilos para asegurar el posicionamiento correcto */
.v-carousel-item__content {
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Asegurar que la columna mantenga el diseño vertical */
.flex-column {
  flex-direction: column;
}
/* Mejorar contraste del texto */
.text-white {
  color: #FFFFFF !important;
}

/* Opcional: Efecto de vidrio para el texto */
.glass-effect {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  border-radius: 8px;
  padding: 16px;
}

/** login  */
.auth-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  padding: 2rem;

  .auth-background {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50%;
    z-index: 0;
    clip-path: ellipse(60% 60% at 50% 70%);
  }

  .auth-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 450px;
    border-radius: 16px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;

  .logo-container {
      padding-top: 2rem;

      .logo-wrapper {
        width: 75px;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;

        :deep(svg) {
          width: 100%;
          height: 100%;
        }
      }
      .logo-illustration {
        width: 200px;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;

        :deep(svg) {
          width: 100%;
          height: 100%;
        }
      }
    }
  }

  :deep(.v-field--outlined) {
    fieldset {
      border-color: rgba(0, 0, 0, 0.1);
    }

    &:hover fieldset {
      border-color: rgba(0, 0, 0, 0.3);
    }
  }

  :deep(.v-btn) {
    text-transform: none;
    letter-spacing: normal;
    box-shadow: 0 4px 12px #042990;
    transition: all 0.3s ease;

    &:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px #042990;
    }
  }
}

.login-bg {
  min-height: 50vh;
  display: flex;
  justify-content: center;
  align-items: center;

 
  /* Fondo degradado */
  background: linear-gradient(135deg, rgb(144, 144, 224) -1%, rgb(4, 41, 144) 53%);
  background-size: 400% 400%;
  animation: gradientShift 12s ease infinite;
}


@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

</style>