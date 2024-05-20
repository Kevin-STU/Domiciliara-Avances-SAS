import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import UpdatePassword from '../components/UpdatePassword.vue';
import Historial from '../components/Historial.vue';
import Dashboard from '../components/Dashboard.vue';
import Configuracion from '../components/Configuracion.vue';
import DashboardPaciente from '../components/DashboardPaciente.vue';
import HistorialPaciente from '../components/HistorialPaciente.vue';
import ConfiguracionPaciente from '../components/ConfiguracionPaciente.vue';
import Notificaciones from '../components/Notificaciones.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/update-password',
    name: 'UpdatePassword',
    component: UpdatePassword,
  },
  {
    path: '/professional/historial',
    name: 'Historial',
    component: Historial,
  },
  {
    path: '/professional/configuracion',
    name: 'Configuracion',
    component: Configuracion,
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
  },
  {
    path: '/paciente/dashboard',
    name: 'DashboardPaciente',
    component: DashboardPaciente,
  },
  {
    path: '/paciente/historial',
    name: 'HistorialPaciente',
    component: HistorialPaciente,
  },
  {
    path: '/paciente/configuracion',
    name: 'ConfiguracionPaciente',
    component: ConfiguracionPaciente,
  },
  {
    path: '/notificaciones',
    name: 'Notificaciones',
    component: Notificaciones,
  },
  {
    path: '/',
    redirect: '/dashboard',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const publicPages = ['/login'];
  const authRequired = !publicPages.includes(to.path);
  const token = localStorage.getItem('token');
  const userType = localStorage.getItem('user_type');
  

  if (authRequired && !token) {
    return next('/login');
  }

  if (authRequired && token) {
    if (to.path.startsWith('/professional') && userType !== 'professional') {
      return next('/paciente/dashboard');
    }
    if (to.path.startsWith('/paciente') && userType !== 'patient') {
      return next('/dashboard');
    }
    if (to.path === '/dashboard' && userType === 'patient') {
      return next('/paciente/dashboard');
    }
    if (to.path === '/paciente/dashboard' && userType === 'professional') {
      return next('/dashboard');
    }
  }

  next();
});

export default router;
