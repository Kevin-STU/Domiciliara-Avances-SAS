<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
      <h2 class="text-2xl font-bold mb-6 text-center">Inicio de Sesión</h2>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="identification_number">
            Número de Identificación
          </label>
          <input v-model="identification_number" id="identification_number" type="text" required
                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Contraseña
          </label>
          <input v-model="password" id="password" type="password" required
                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="user_type">
            Tipo de Usuario
          </label>
          <select v-model="user_type" id="user_type" required
                  class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
            <option value="professional">Profesional</option>
            <option value="patient">Paciente</option>
          </select>
        </div>
        <div class="flex items-center justify-between">
          <button type="submit"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Iniciar Sesión
          </button>
        </div>
      </form>
      <p v-if="error" class="text-red-500 mt-4">{{ error }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      identification_number: '',
      password: '',
      user_type: 'patient', // por defecto
      error: '',
    };
  },
  methods: {
    async login() {
      this.error = '';
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          identification_number: this.identification_number,
          password: this.password,
          user_type: this.user_type,
        });

        if (response.data.token) {
          localStorage.setItem('token', response.data.token);
          localStorage.setItem('user_type', response.data.user_type);
          localStorage.setItem('first_time', JSON.stringify(response.data.first_time));

          if (response.data.first_time) {
            this.$router.push({ name: 'UpdatePassword', params: { user: response.data.user } });
          } else {
            const userType = response.data.user_type;
            if (userType === 'professional') {
              this.$router.push('/dashboard');
            } else {
              this.$router.push('/paciente/dashboard');
            }
          }
        } else {
          console.error('Token no recibido');
        }
      } catch (error) {
        if (error.response && error.response.status === 401) {
          this.error = 'Credenciales incorrectas. Por favor, verifique y vuelva a intentarlo.';
        } else {
          console.error('Error durante el inicio de sesión:', error);
        }
      }
    },
  },
};
</script>
