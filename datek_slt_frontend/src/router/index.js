import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin";
import app from "./app";
const routes = [...admin, ...app];

const router = createRouter({
   history: createWebHistory(process.env.BASE_URL),
   routes: routes,
});

router.beforeEach((to, from, next) => {
   const adminToken = localStorage.getItem("admin_token");

   if (to.meta.requiresAuth && !adminToken) {
      next({ name: "AdminLogin" });
   } else if (to.name === "AdminLogin" && adminToken) {
      next({ name: "Admin" });
   } else {
      next();
   }
});


export default router;
