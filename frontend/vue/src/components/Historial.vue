<template>
    <div class="min-h-screen flex flex-col items-center bg-gray-100 p-4">
      <h1 class="text-3xl font-bold mb-4">Historial</h1>
      <button @click="showCreateForm = !showCreateForm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6">
        Crear Nueva Historia
      </button>
  
      <div v-if="showCreateForm || editForm" class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">{{ editForm ? 'Editar Historia' : 'Nueva Historia' }}</h2>
        <form @submit.prevent="editForm ? updateHistoria() : createHistoria()">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Paciente</label>
            <select v-model="newHistoria.patient_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                {{ patient.first_name }} {{ patient.last_name }}
              </option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Información del Paciente</label>
            <textarea v-model="newHistoria.patient_info" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Fecha y Hora</label>
            <input type="datetime-local" v-model="newHistoria.date_time" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Consecutivo</label>
            <input type="text" v-model="newHistoria.consecutive" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Estado Actual</label>
            <input type="text" v-model="newHistoria.current_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Antecedentes</label>
            <textarea v-model="newHistoria.antecedents" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Evolución Final</label>
            <textarea v-model="newHistoria.final_evolution" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Concepto del Profesional</label>
            <textarea v-model="newHistoria.professional_concept" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Recomendaciones</label>
            <textarea v-model="newHistoria.recommendations" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
          </div>
          <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            {{ editForm ? 'Actualizar' : 'Guardar' }}
          </button>
        </form>
      </div>
  
      <ul class="w-full max-w-3xl mt-8 space-y-4">
        <li v-for="historia in historias" :key="historia.id" class="bg-white p-4 rounded-lg shadow-lg">
          <h3 class="text-xl font-bold">Historia #{{ historia.consecutive }}</h3>
          <p class="text-gray-700">{{ historia.patient_info }}</p>
          <p class="text-gray-500">{{ historia.date_time }}</p>
          <button @click="viewDetails(historia)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">Ver Detalles</button>
          <button @click="editHistoria(historia)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded mr-2">Editar</button>
          <button @click="deleteHistoria(historia.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Borrar</button>
        </li>
      </ul>
  
      <div v-if="selectedHistoria" class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md mt-8">
        <h2 class="text-2xl font-bold mb-4">Detalles de la Historia</h2>
        <p v-if="selectedHistoria.patient">
          <strong>Paciente:</strong> {{ selectedHistoria.patient.first_name }} {{ selectedHistoria.patient.last_name }}
        </p>
        <p><strong>Información del Paciente:</strong> {{ selectedHistoria.patient_info }}</p>
        <p><strong>Fecha y Hora:</strong> {{ selectedHistoria.date_time }}</p>
        <p><strong>Consecutivo:</strong> {{ selectedHistoria.consecutive }}</p>
        <p><strong>Estado Actual:</strong> {{ selectedHistoria.current_status }}</p>
        <p><strong>Antecedentes:</strong> {{ selectedHistoria.antecedents }}</p>
        <p><strong>Evolución Final:</strong> {{ selectedHistoria.final_evolution }}</p>
        <p><strong>Concepto del Profesional:</strong> {{ selectedHistoria.professional_concept }}</p>
        <p><strong>Recomendaciones:</strong> {{ selectedHistoria.recommendations }}</p>
        <button @click="selectedHistoria = null" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Cerrar</button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        historias: [],
        patients: [],
        newHistoria: {
          patient_id: '',
          patient_info: '',
          date_time: '',
          consecutive: '',
          current_status: '',
          antecedents: '',
          final_evolution: '',
          professional_concept: '',
          recommendations: '',
        },
        showCreateForm: false,
        editForm: false,
        selectedHistoria: null,
      };
    },
    mounted() {
      this.fetchHistorias();
      this.fetchPatients();
    },
    methods: {
      async fetchHistorias() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.get('http://localhost:8000/api/historias', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.historias = response.data;
        } catch (error) {
          console.error('Error al obtener las historias:', error);
        }
      },
      async fetchPatients() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.get('http://localhost:8000/api/patients', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.patients = response.data;
        } catch (error) {
          console.error('Error al obtener los pacientes:', error);
        }
      },
      async createHistoria() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.post('http://localhost:8000/api/historias', this.newHistoria, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.historias.push(response.data);
          this.resetForm();
        } catch (error) {
          console.error('Error al crear la historia:', error);
        }
      },
      async updateHistoria() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.put(`http://localhost:8000/api/historias/${this.newHistoria.id}`, this.newHistoria, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          const index = this.historias.findIndex(historia => historia.id === this.newHistoria.id);
          this.historias.splice(index, 1, response.data);
          this.resetForm();
        } catch (error) {
          console.error('Error al actualizar la historia:', error);
        }
      },
      async deleteHistoria(id) {
        try {
          const token = localStorage.getItem('token');
          await axios.delete(`http://localhost:8000/api/historias/${id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.historias = this.historias.filter(historia => historia.id !== id);
        } catch (error) {
          console.error('Error al eliminar la historia:', error);
        }
      },
      viewDetails(historia) {
        this.selectedHistoria = historia;
      },
      editHistoria(historia) {
        this.newHistoria = { ...historia };
        this.showCreateForm = true;
        this.editForm = true;
      },
      resetForm() {
        this.newHistoria = {
          patient_id: '',
          patient_info: '',
          date_time: '',
          consecutive: '',
          current_status: '',
          antecedents: '',
          final_evolution: '',
          professional_concept: '',
          recommendations: '',
        };
        this.showCreateForm = false;
        this.editForm = false;
      },
    },
  };
  </script>
  