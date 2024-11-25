import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './views/Dashboard.vue';
import Websites from './views/Websites.vue';
import Categories from './views/Categories.vue';
import Rankings from './views/Rankings.vue';
import Users from './views/Users.vue';
import Credits from './views/Credits.vue';
import Reviews from './views/Reviews.vue';
import Settings from './views/Settings.vue';

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
  },
  {
    path: '/websites',
    name: 'Websites',
    component: Websites,
  },
  {
    path: '/categories',
    name: 'Categories',
    component: Categories,
  },
  {
    path: '/rankings',
    name: 'Rankings',
    component: Rankings,
  },
  {
    path: '/users',
    name: 'Users',
    component: Users,
  },
  {
    path: '/credits',
    name: 'Credits',
    component: Credits,
  },
  {
    path: '/reviews',
    name: 'Reviews',
    component: Reviews,
  },
  {
    path: '/settings',
    name: 'Settings',
    component: Settings,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
