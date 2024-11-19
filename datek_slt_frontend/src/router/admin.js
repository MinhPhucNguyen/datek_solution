const admin = [
    {
        path: "/admin",
        name: "Admin",
        component: () => import("@/layouts/admin.vue"),
        meta: {
            requiresAuth: true,
        }
    }
];

export default admin;
