<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
      <h2 class="text-2xl font-bold mb-6 text-center">Actualizar Contraseña</h2>
      <form @submit.prevent="updatePassword">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
            Nueva Contraseña
          </label>
          <input v-model="new_password" id="new_password" type="password" required
                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
            Confirmar Contraseña
          </label>
          <input v-model="confirm_password" id="confirm_password" type="password" required
                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
          <button type="submit"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      authenticated: false,
      new_password: '',
      confirm_password: '',
    };
  },
  async mounted() {
    try {
      const token = localStorage.getItem('token');
      if (token) {
        const response = await axios.get('http://localhost:8000/api/user', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.authenticated = true;
      } else {
        console.log('El usuario no está autenticado');
      }
    } catch (error) {
      console.error('Error al verificar la autenticación:', error);
    }
  },
  methods: {
    async updatePassword() {
      try {
        const token = localStorage.getItem('token');
        if (token) {
          const response = await axios.post('http://localhost:8000/api/update-password', {
            new_password: this.new_password,
            new_password_confirmation: this.confirm_password,
          }, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.$router.push('/dashboard');
        } else {
          console.log('El usuario no está autenticado');
        }
      } catch (error) {
        console.error('Error actualizando la contraseña:', error);
      }
    },
  },
};
</script>

