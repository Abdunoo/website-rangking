import { createRouter, createWebHistory } from "vue-router";
const routes = [
  // Route
  {
    path: "/",
    name: "Home",
    component: () => import("@/view/Home.vue")
  },
  {
    path: "/:domain_name",
    name: "Domain",
    component: () => import("@/view/Domain.vue")
  }

];

const router = createRouter({
  history: createWebHistory("/rank"),
  routes,
});

// router.beforeEach((to, from, next) => {
//   const authStore = useAuthStore();
//   if (to.path === '/admin' || to.path === '/admin/') {
//     window.location.href = import.meta.env.VITE_APP_URL + '/admin/index.html';
//   }

//   if (to.meta.requiresAuth && !authStore.isLogin) {
//     let info = localStorage.getItem("isLogin");
//     info = JSON.parse(info);
//     if (!info) {
//       authStore.setRedirectPath(to.fullPath); 
//       next({ name: "Login" });
//     }
//     next();
//   } else {
//     next();
//   }
// });

export default router;
