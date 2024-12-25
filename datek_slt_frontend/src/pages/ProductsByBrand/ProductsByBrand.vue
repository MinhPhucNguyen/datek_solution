<template>
  <div class="product-by-brand-block">
    <div class="container">
      <div v-if="isLoading" style="text-align: center">
        <div class="loading">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div v-else>
        <div class="page-title">
          <h3>{{ brand.brand_name }}</h3>
        </div>
        <div class="product-by-brand mt-5">
          <div class="product-by-brand-list">
            <ProductItemCompoent
              v-for="product in products"
              :key="product.id"
              :product="product"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { onBeforeMount, ref } from "vue";
import { useRouter } from "vue-router";
import ProductItemCompoent from "@/components/ProductItemComponent/ProductItemComponent.vue";

const products = ref([]);
const router = useRouter();
const brandId = router.currentRoute.value.params.brandId;
const brand = ref(null);
const isLoading = ref(false);

const getBrandById = async () => {
  try {
    const response = await axios.get(`/brands/${brandId}`);
    brand.value = response.data.brand;
    console.log(brand.value);
  } catch (error) {
    console.error(error);
  }
};

const fetchProductsByBrand = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get(`/products/get-by-brand/${brandId}`);
    products.value = response.data.data.products;
    isLoading.value = false;
  } catch (error) {
    console.error(error);
  }
};

onBeforeMount(() => {
  window.scrollTo(0, 0);
  getBrandById();
  fetchProductsByBrand();
});
</script>

<style scoped>
@import url(ProductsByBrand.scss);
</style>
