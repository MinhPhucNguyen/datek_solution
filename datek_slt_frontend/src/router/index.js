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
   const isAuthenticated = useStore().getters["auth/isAuthenticated"];
   if (to.meta.requiresAuth && !isAuthenticated) {
      return next({ name: "login" });
   } else if (to.meta.permission == "admin" && !useStore().getters["auth/isAdmin"]) {
      return next({ name: "home" });
   } else if ((to.path == "/customer/account/login" || to.path == "/customer/account/create") && isAuthenticated) {
      return router.back();
   } else {
      return next();
   }
});


export default router;
