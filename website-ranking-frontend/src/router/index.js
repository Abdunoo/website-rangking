import { createRouter, createWebHistory } from "vue-router";
const routes = [
  // Route
  {
    path: "/",
    name: "Home",
    component: () => import("@/view/Home.vue")
  },
  {
    path: "/:domain",
    name: "Website",
    component: () => import("@/view/Website.vue")
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("@/view/Login.vue")
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("@/view/Register.vue")
  },
  {
    path: "/change-password",
    name: "ChangePassword",
    component: () => import("@/view/ChangePassword.vue")
  },
  {
    path: "/profile",
    name: "Profile",
    component: () => import("@/view/Profile.vue")
  },
  {
    path: "/buy-credits",
    name: "BuyCredits",
    component: () => import("@/view/BuyCredits.vue")
  },
  {
    path: "/payment-history",
    name: "PaymentHistory",
    component: () => import("@/view/PaymentHistory.vue")
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
