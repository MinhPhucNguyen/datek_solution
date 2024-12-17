<template>
  <div class="latest-produtct-list-block">
    <div class="container">
      <div class="latest-produtct-list-title">
        <p>Sản phẩm mới nhất</p>
      </div>
      <div class="latest-product-list-content" v-if="latestProducts.length > 0">
        <div class="product-slider">
          <button class="slider-btn left" @click="prevSlide" :disabled="isPrevDisabled">
            <i class="fa-solid fa-chevron-left"></i>
          </button>

          <div class="product-list-wrapper">
            <div class="product-list-latest" :style="sliderStyle">
              <ProductItemComponent
                v-for="product in latestProducts"
                :key="product.id"
                :product="product"
              />
            </div>
          </div>

          <button class="slider-btn right" @click="nextSlide"  :disabled="isNextDisabled">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import ProductItemComponent from "../ProductItemComponent/ProductItemComponent.vue";

const latestProducts = ref([]);
const currentIndex = ref(0);
const productsPerPage = 5;

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

const nextSlide = () => {
  if (currentIndex.value < latestProducts.value.length - productsPerPage) {
    currentIndex.value += productsPerPage;
  }
};

const prevSlide = () => {
  if (currentIndex.value > 0) {
    currentIndex.value -= productsPerPage;
  }
};

const sliderStyle = computed(() => ({
  transform: `translateX(-${(currentIndex.value / productsPerPage) * 100}%)`,
}));

const isPrevDisabled = computed(() => currentIndex.value === 0);
const isNextDisabled = computed(() => currentIndex.value >= latestProducts.value.length - productsPerPage);
</script>

<style scoped>
@import url(LatestProductListComponent.scss);
</style>
