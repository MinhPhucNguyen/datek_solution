const admin = [
  {
    path: "/admin",
    name: "Admin",
    component: () => import("@/layouts/admin.vue"),
    meta: {
      permission: "admin",
      requiresAuth: true,
    },
    redirect: { name: "admin.dashboard" },
    children: [
      {
        path: "dashboard",
        component: () => import("@/pages/Admin/Dashboard/Dashboard.vue"),
        name: "admin.dashboard",
      },
      {
        path: "users",
        component: () => import("@/pages/Admin/Users/index.vue"),
        name: "admin.users",
      },
      {
        path: "users/create",
        component: () => import("@/pages/Admin/Users/create.vue"),
        name: "admin.users.create",
      },
      {
        path: "users/:id/edit",
        component: () => import("@/pages/Admin/Users/edit.vue"),
        name: "admin.users.edit",
     },
     {
        path: "users/:id/profile",
        component: () => import("@/pages/Admin/Users/profile.vue"),
        name: "admin.users.profile",
        redirect: { name: "admin.users.mainProfile" },
     },
    ],
  },
  {
    path: "/admin/login",
    name: "AdminLogin",
    component: () => import("@/pages/Admin/AdminLoginPage/AdminLoginPage.vue"),
  },
];

export default admin;
