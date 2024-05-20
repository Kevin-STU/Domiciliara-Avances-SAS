<template>
    <div class="min-h-screen flex flex-col items-center bg-gray-100 p-4">
      <h1 class="text-3xl font-bold mb-4">Configuración</h1>
      <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Actualizar Información</h2>
        <form @submit.prevent="updateProfile">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
            <input v-model="profile.first_name" :placeholder="profile.first_name" type="text" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Apellido</label>
            <input v-model="profile.last_name" :placeholder="profile.last_name" type="text" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input v-model="profile.email" :placeholder="profile.email" type="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
            <input v-model="profile.phone_number" :placeholder="profile.phone_number" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Ubicación</label>
            <input v-model="profile.location" :placeholder="profile.location" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
          </div>
          <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Actualizar
          </button>
        </form>
        <button @click="goBack" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
          Atrás
        </button>
        <button @click="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
          Cerrar Sesión
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        profile: {
          first_name: '',
          last_name: '',
          email: '',
          phone_number: '',
          location: '',
        },
      };
    },
    mounted() {
      this.fetchProfile();
    },
    methods: {
      async fetchProfile() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.get('http://localhost:8000/api/user', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.profile = response.data;
        } catch (error) {
          console.error('Error al obtener la información del perfil:', error);
        }
      },
      async updateProfile() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.put('http://localhost:8000/api/user', this.profile, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          alert('Perfil actualizado con éxito');
        } catch (error) {
          console.error('Error al actualizar el perfil:', error);
        }
      },
      logout() {
        localStorage.removeItem('token');
        this.$router.push('/login');
      },
      goBack() {
        this.$router.go(-1);
      },
    },
  };
  </script>
  