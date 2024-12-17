<template>
  <div>
    <BannerComponent />
    <ShopByBrandComponent />
    <LatestProductListComponent />
    <ProductListSections />

    <CartSideBar
      :isCartVisible="isCartVisible"
      :cartItems="cartItems"
      @updateCart="cartItems = $event"
      @closeCart="closeCart"
    />
  </div>
</template>

<script setup>
import BannerComponent from "@/components/BannerComponent/BannerComponent.vue";
import ShopByBrandComponent from "@/components/ShopByBrandComponent/ShopByBrandComponent.vue";
import LatestProductListComponent from "@/components/LatestProductListComponent/LatestProductListComponent.vue";
import ProductListSections from "@/components/ProductListSections/ProductListSections.vue";
import CartSideBar from "@/components/SidebarCartComponent/SidebarCartComponent.vue";

import { onMounted, computed, onBeforeMount } from "vue";
import { useStore } from "vuex";

const store = useStore();
const isCartVisible = computed(() => store.getters["cart/isCartVisible"]);
const cartItems = computed(() => store.getters["cart/getCartItems"]);

onBeforeMount(() => {
  store.dispatch("cart/toggleCartVisibility", false);
});

onMounted(() => {
  store.dispatch("cart/fetchCart");
});

const closeCart = () => {
  store.dispatch("cart/toggleCartVisibility", false);
};
</script>

<style scoped lang="scss"></style>
