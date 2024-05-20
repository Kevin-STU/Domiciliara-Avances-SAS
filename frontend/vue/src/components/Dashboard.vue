<template>
    <div class="min-h-screen flex flex-col items-center bg-gray-100 p-4">
      <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
      <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <button @click="goTo('professional/historial')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">
          Historias
        </button>
        <button @click="goTo('professional/configuracion')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">
          Configuración
        </button>
        <button @click="goTo('notificaciones')" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">
          Notificaciones
        </button>
        <button @click="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          Cerrar Sesión
        </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    methods: {
      goTo(route) {
        this.$router.push(`/${route}`);
      },
      logout() {
        localStorage.removeItem('token');
        this.$router.push('/login');
      },
    },
  };
  </script>
  