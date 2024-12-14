<template>
  <div class="product-list-block">
    <div class="container">
      <!-- Laptop, Macbook, Surface -->
      <div class="product-list-by-type">
        <h2>Laptop, Macbook, Surface</h2>
        <div class="block">
          <ProductItemComponent
            v-for="(product, index) in computerProducts"
            :key="index"
            :product="product"
          />
        </div>
      </div>

      <div class="product-list-by-type">
        <h2>PC</h2>
        <div class="block">
          <ProductItemComponent
            v-for="(product, index) in pcProducts"
            :key="index"
            :product="product"
          />
        </div>
      </div>

      <div class="product-list-by-type">
        <h2>Màn hình</h2>
        <div class="block">
          <ProductItemComponent
            v-for="(product, index) in monitorProducts"
            :key="index"
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

const computerProducts = ref([]);
const pcProducts = ref([]);
const monitorProducts = ref([]);

const fetchProductsListByCategorySlug = async (slug, categoryType) => {
  try {
    const response = await axios.get(`/products/category/${slug}`);
    if (categoryType === "laptop") {
      computerProducts.value = response.data.data.products;
    } else if (categoryType === "pc") {
      console.log("response.data.data.products", response.data.data.products);
      pcProducts.value = response.data.data.products;
    } else if (categoryType === "monitor") {
      monitorProducts.value = response.data.data.products;
    }
  } catch (error) {
    console.error("Error fetching products:", error);
  }
};

onMounted(() => {
  fetchProductsListByCategorySlug('laptop', 'laptop');
  fetchProductsListByCategorySlug('pc', 'pc');
  fetchProductsListByCategorySlug('man-hinh', 'monitor');
});
</script>

<style scoped>
@import url(ProductListSections.scss);
</style>
