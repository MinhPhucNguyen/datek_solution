const admin = [
    {
        path: "/admin",
        name: "Admin",
        component: () => import("@/layouts/admin.vue"),
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: "/admin/login",
        name: "AdminLogin",
        component: () => import("@/pages/Admin/AdminLoginPage/AdminLoginPage.vue"),
    },
];

export default admin;
