import { useStore } from "vuex";

const app = [
  {
    path: "/",
    component: () => import("@/layouts/app.vue"),
    children: [
      {
        path: "/",
        component: () => import("@/pages/HomePage.vue"),
        name: "home",
      },
      {
        path: "/:slug/:id",
        component: () =>
          import("@/pages/ProductDetailPage/ProductDetailPage.vue"),
        name: "product-detail",
      },
      {
        path: "/customer/account/login",
        component: () => import("@/pages/LoginPage/LoginPage.vue"),
        name: "login",
      },
      {
        path: "/customer/account/create",
        component: () => import("@/pages/RegisterPage/RegisterPage.vue"),
        name: "register",
      },
      {
        path: "/customer/account/manage",
        component: () => import("@/pages/AccountPage/AccountPage.vue"),
        name: "account-page",
        meta: {
          requiresAuth: true,
        },
        beforeEnter: (to, from, next) => {
          if (
            useStore().getters["auth/getUser"] &&
            to.path === "/customer/account/manage"
          ) {
            next();
          }
        },
      },
    ],
  },
];

export default app;
