<template>
  <div class="product-list-by-type">
    <h2>{{ title }}</h2>
    <div class="block">
      <ProductItemComponent
        v-for="(product, index) in visibleProducts"
        :key="index"
        :product="product"
      />
    </div>
    <div v-if="products.length > visibleProducts.length" class="show-more">
      <button @click="showMore">Xem thêm</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, defineProps } from "vue";
import axios from "axios";
import ProductItemComponent from "../ProductItemComponent/ProductItemComponent.vue";

const props = defineProps({
  title: String,
  slugs: [String, Array],
});
const products = ref([]);
const visibleCount = ref(10);

const visibleProducts = computed(() =>
  products.value.slice(0, visibleCount.value)
);

const fetchProducts = async () => {
  try {
    let responses = [];

    if (Array.isArray(props.slugs)) {
      responses = await Promise.all(
        props.slugs.map((slug) => axios.get(`/products/category/${slug}`))
      );
    } else {
      responses = [await axios.get(`/products/category/${props.slugs}`)];
    }

    products.value = responses.map((res) => res.data.data.products).flat();
  } catch (error) {
    console.error("Error fetching products:", error);
  }
};

const showMore = () => {
  visibleCount.value += 10;
};

onMounted(fetchProducts);
</script>

<style scoped lang="scss">
.product-list-by-type {
  .block {
    margin-top: 20px;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
  }
  .show-more {
    display: flex;
    justify-content: center;
    margin-top: 20px;

    button {
      width: 300px;
      padding: 10px 20px;
      background-color: #f0f0f0;
      cursor: pointer;
      font-weight: bold;
      background: #ffffff;
      border: 1px solid #4e43d8;
      border-radius: 8px;
      &:hover {
        background: #4e43d8;
        color: #ffffff;
        transition: all 0.3s;
      }
    }
  }
}
</style>
