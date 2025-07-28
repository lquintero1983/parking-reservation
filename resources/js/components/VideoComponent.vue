<template>
  <v-container fluid class="pa-0 ma-0 video-container">
    <!-- Video de fondo con altura reducida -->
    <video
      ref="videoPlayer"
      autoplay
      muted
      loop
      playsinline
      class="background-video"
      :poster="parking1"
    >
      <source :src="video" type="video/mp4">
      <!-- Fallback para navegadores sin soporte de video -->
      <v-img
        :src="parking1"
        alt="Estacionamiento aeroportuario"
        cover
        class="fill-height"
      />
    </video>

    <!-- Capa oscura para mejorar legibilidad -->
    <div class="overlay"></div>

    <!-- Contenido sobrepuesto con Vuetify -->
    <v-row
      align="center"
      justify="center"
      class="content-overlay fill-height"
    >
        <v-col cols="12" md="8" class="text-center d-flex flex-column align-center">
        <h1 class="text-h1 text-md-h1 font-weight-bold mb-2 white--text"
            style="color: white; text-shadow: 2px 2px 4px #000000;">
        PARK TO FLY
        </h1>
        <h1  style="color: white; text-shadow: 2px 2px 4px #000000;">
        ¿Tenés pensado viajar?
        </h1>                
        <p class="text-h6 text-md-h5 font-weight-medium white--text mb-6 px-4 py-2"
        style="color: #FFA500; background-color: rgba(0,0,0,0.7);
            max-width: 80%;
        ">
        Nosotros cuidamos tu vehículo
        </p>
        <v-btn 
            x-large 
            color="primary" 
            @click="handleScrollToQuote"
            class="mt-4"
            style="box-shadow: 0 4px 8px rgba(0,0,0,0.3); min-width: 200px;"
            >
            Cotizar ahora
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted, defineEmits } from 'vue';
import { useRouter } from 'vue-router';
import video from "@images/ptf2.mp4";
import parking1 from "@images/parking1.jpg";

// Definir el emit
const emit = defineEmits(['scrollToQuote']);

// Método que dispara el emit al padre
const handleScrollToQuote = () => {
  emit('scrollToQuote');
};

const router = useRouter();
const videoPlayer = ref(null);

onMounted(() => {
  if (videoPlayer.value) {
    videoPlayer.value.play().catch(e => console.log("Autoplay prevented:", e));
  }
});
</script>

<style scoped>
.video-container {
  position: relative;
  width: 100%;
  height: 60vh; /* Altura reducida de 100vh a 70vh */
  overflow: hidden;
}

.background-video {
  position: absolute;
  top: 50%;
  left: 50%;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  transform: translate(-50%, -50%);
  z-index: 0;
  object-fit: cover;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

.content-overlay {
  position: relative;
  z-index: 2;
  height: 100%;
}

/* Ajustes responsivos */
@media (max-width: 960px) {
  .video-container {
    height: 60vh; /* Altura aún menor en tablets */
  }
}

@media (max-width: 600px) {
  .video-container {
    height: 50vh; /* Altura más pequeña en móviles */
  }
}


</style>