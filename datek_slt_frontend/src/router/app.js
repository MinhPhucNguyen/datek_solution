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
        path: "/search",
        component: () =>
          import("@/pages/SearchResultsPage/SearchResultsPage.vue"),
        name: "search-results",
      },
      {
        path: "/:slug/:id",
        component: () =>
          import("@/pages/ProductDetailPage/ProductDetailPage.vue"),
        name: "product-detail",
      },
      {
        path: "/cart",
        component: () => import("@/pages/CartPage/CartPage.vue"),
        name: "cart-page",
      },
      {
        path: "/success",
        component: () => import("@/pages/SuccessPage/SuccessPage.vue"),
        name: "success-page",
      },
      {
        path: "/:slug",
        name: "category-products",
        component: () =>
          import("@/pages/ProductsListPage/ProductsListPage.vue"),
        props: true,
      },
      {
        path: "/brand/:brandName/:brandId",
        name: "products-by-brand",
        component: () => import("@/pages/ProductsByBrand/ProductsByBrand.vue"),
      },
      {
        path: "/customer/account/login",
        component: () => import("@/pages/LoginPage/LoginPage.vue"),
        name: "login",
      },
      {
        path: "forgot-password",
        component: () => import("@/pages/ForgotPassword/ForgotPassword.vue"),
        name: "forgotPassword",
        meta: {
          hideFooter: true,
        },
      },
      {
        path: "/customer/account/create",
        component: () => import("@/pages/RegisterPage/RegisterPage.vue"),
        name: "register",
      },
      {
        path: "/checkout",
        name: "checkout",
        component: () => import("@/pages/CheckoutPage/CheckoutPage.vue"),
        meta: {
          requiresAuth: true,
        },
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
