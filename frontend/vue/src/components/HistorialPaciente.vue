<template>
    <div class="min-h-screen flex flex-col items-center bg-gray-100 p-4">
      <h1 class="text-3xl font-bold mb-4">Historial Médico</h1>
      <ul class="w-full max-w-3xl mt-8 space-y-4">
        <li v-for="historia in historias" :key="historia.id" class="bg-white p-4 rounded-lg shadow-lg">
          <h3 class="text-xl font-bold">Historia #{{ historia.consecutive }}</h3>
          <p class="text-gray-700">{{ historia.patient_info }}</p>
          <p class="text-gray-500">{{ historia.date_time }}</p>
          <button @click="viewDetails(historia)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">Ver Detalles</button>
          <div v-if="historia.assisted">
            <p class="text-green-500">Asistida</p>
          </div>
          <div v-else>
            <button @click="markAsAssisted(historia)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Marcar como Asistida</button>
            <input v-if="selectedHistoria === historia && !historia.assisted" v-model="firma" type="text" placeholder="Firma" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-2" />
            <button v-if="selectedHistoria === historia && !historia.assisted" @click="submitFirma(historia)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mt-2">Enviar Firma</button>
            <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
          </div>
          <div v-if="selectedHistoria === historia">
            <h4 class="text-lg font-bold mt-4">Detalles de la Historia</h4>
            <p><strong>Estado Actual:</strong> {{ historia.current_status }}</p>
            <p><strong>Antecedentes:</strong> {{ historia.antecedents }}</p>
            <p><strong>Evolución Final:</strong> {{ historia.final_evolution }}</p>
            <p><strong>Concepto Profesional:</strong> {{ historia.professional_concept }}</p>
            <p><strong>Recomendaciones:</strong> {{ historia.recommendations }}</p>
            <p><strong>Profesional:</strong> {{ historia.professional.first_name }} {{ historia.professional.last_name }}</p>
          </div>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        historias: [],
        selectedHistoria: null,
        firma: '',
        error: '',
      };
    },
    mounted() {
      this.fetchHistorias();
    },
    methods: {
      async fetchHistorias() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.get('http://localhost:8000/api/patient/historias', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.historias = response.data;
        } catch (error) {
          console.error('Error al obtener las historias:', error);
        }
      },
      viewDetails(historia) {
        this.selectedHistoria = historia;
      },
      markAsAssisted(historia) {
        this.selectedHistoria = historia;
        this.error = '';
      },
      async submitFirma(historia) {
        if (!this.firma) {
          this.error = 'Por favor, ingrese su firma.';
          return;
        }
  
        try {
          const token = localStorage.getItem('token');
          const response = await axios.post(`http://localhost:8000/api/patient/historias/${historia.id}/asistida`, { firma: this.firma }, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          historia.assisted = true;
          this.selectedHistoria = null;
          this.firma = '';
          this.error = '';
          alert('Firma enviada con éxito');
        } catch (error) {
          console.error('Error al enviar la firma:', error);
          this.error = 'Error al enviar la firma. Inténtalo de nuevo.';
        }
      },
    },
  };
  </script>
  