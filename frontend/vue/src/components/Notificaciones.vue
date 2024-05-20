<template>
    <div class="min-h-screen flex flex-col items-center bg-gray-100 p-4">
      <h1 class="text-3xl font-bold mb-4">Notificaciones</h1>
      <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <ul class="space-y-4">
          <li v-for="notification in filteredNotifications" :key="notification.id" class="bg-gray-100 p-4 rounded-lg shadow">
            <p>{{ notification.data.message }}</p>
            <small class="text-gray-500">{{ new Date(notification.created_at).toLocaleString() }}</small>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        notifications: [],
      };
    },
    computed: {
      filteredNotifications() {
        const userType = localStorage.getItem('user_type');
        return this.notifications.filter(notification => notification.data.user_type === userType);
      }
    },
    mounted() {
      this.fetchNotifications();
    },
    methods: {
      async fetchNotifications() {
        try {
          const token = localStorage.getItem('token');
          const response = await axios.get('http://localhost:8000/api/notifications', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          this.notifications = response.data;
          console.log('Notificaciones obtenidas:', this.notifications);
        } catch (error) {
          console.error('Error al obtener las notificaciones:', error);
        }
      },
    },
  };
  </script>
