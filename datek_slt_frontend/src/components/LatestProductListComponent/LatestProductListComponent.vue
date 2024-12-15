<template>
  <div class="latest-produtct-list-block">
    <div class="container">
      <div class="latest-produtct-list-title">
        <p>Sản phẩm mới nhất</p>
      </div>
      <div class="latest-product-list-content">
        <div class="product-list">
          <ProductItemComponent
            v-for="product in latestProducts"
            :key="product.id"
            :product="product"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import ProductItemComponent from "../ProductItemComponent/ProductItemComponent.vue";

const latestProducts = ref([]);

const fetchProducts = async () => {
  try {
    const latestResponse = await axios.get("products/latest");
    latestProducts.value = latestResponse.data.data.products;
  } catch (error) {
    console.error("Error fetching products:", error);
  }
};

onMounted(() => {
  fetchProducts();
});
</script>

<style scoped>
@import url(LatestProductListComponent.scss);
</style>
