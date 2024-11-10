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
        path: "/customer/login",
        component: () => import("@/pages/LoginPage/LoginPage.vue"),
        name: "login",
      },
    ],
  },
];

export default app;
