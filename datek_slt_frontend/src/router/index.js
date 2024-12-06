import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin";
import app from "./app";
import { useStore } from "vuex";
const routes = [...admin, ...app];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: routes,
});

router.beforeEach((to, from, next) => {
  const store = useStore(); // Vuex store
  const isAuthenticated = store.getters["auth/isAuthenticated"];
  const isAdmin = store.getters["auth/isAdmin"];

  if (to.meta.requiresAuth) {
    if (to.meta.permission === "admin") {
      if (!isAdmin) {
        return next({ name: "AdminLogin" });
      }
    } else {
      if (!isAuthenticated) {
        return next({ name: "login" });
      }
    }
  } else if (to.path === "/customer/account/login" && isAuthenticated) {
    return router.back();
  } else if (to.path === "/admin/login" && isAdmin) {
    return next({ name: "admin.dashboard" });
  }

  next();
});

export default router;
