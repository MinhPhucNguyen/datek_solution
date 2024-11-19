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
        path: "/customer/account/login",
        component: () => import("@/pages/LoginPage/LoginPage.vue"),
        name: "login",
      },
      {
        path: "/customer/account/create",
        component: () => import("@/pages/RegisterPage/RegisterPage.vue"),
        name: "register",
      },
    ],
  },
];

export default app;
