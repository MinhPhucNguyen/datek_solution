<template>
  <div class="shop-by-brand-block">
    <div class="container">
      <div class="shop-by-brand-content">
        <div class="brand-logos">
          <div class="brand-logo" v-for="brand in brands" :key="brand.id">
            <router-link
              :to="{
                name: 'products-by-brand',
                params: {
                  brandName: brand.brand_name.toLowerCase().replace(/ /g, '-'),
                  brandId: brand.id,
                },
              }"
            >
              <img
                v-if="brand.brand_logo"
                :src="brand.brand_logo"
                alt="brand-logo"
              />
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { onBeforeMount, ref } from "vue";

const brands = ref([]);

const fetchBrandLogos = async () => {
  try {
    const response = await axios.get("/brands");
    brands.value = response.data.brands;
  } catch (error) {
    console.error(error);
  }
};

onBeforeMount(() => {
  fetchBrandLogos();
});
</script>

<style scoped>
@import url(ShopByBrandComponent.scss);
</style>
