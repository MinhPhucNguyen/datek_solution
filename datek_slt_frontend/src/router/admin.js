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
      {
        path: "categories",
        component: () => import("@/pages/Admin/Categories/index.vue"),
        name: "admin.categories",
      },
      {
        path: "reviews",
        component: () => import("@/pages/Admin/ProductReviews/index.vue"),
        name: "admin.reviews",
      },
      {
        path: "brands",
        component: () => import("@/pages/Admin/Brands/index.vue"),
        name: "admin.brands",
      },
      {
        path: "orders",
        component: () => import("@/pages/Admin/Orders/index.vue"),
        name: "admin.orders",
      },
      {
        path: "sales",
        component: () => import("@/pages/Admin/Sales/index.vue"),
        name: "admin.sales",
      },
      {
        path: "sales/create",
        component: () => import("@/pages/Admin/Sales/create.vue"),
        name: "admin.sales.create",
      },
      {
        path: "sales/:id/edit",
        component: () => import("@/pages/Admin/Sales/edit.vue"),
        name: "admin.sales.edit",
      },
      {
        path: "products",
        component: () => import("@/pages/Admin/Products/index.vue"),
        name: "admin.products",
      },
      {
        path: "products/create",
        component: () => import("@/pages/Admin/Products/create.vue"),
        name: "admin.products.create",
      },
      {
        path: "products/:id/edit",
        component: () => import("@/pages/Admin/Products/edit.vue"),
        name: "admin.products.edit",
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
