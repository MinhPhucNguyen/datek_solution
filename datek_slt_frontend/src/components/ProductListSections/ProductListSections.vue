<template>
  <div class="product-list-block">
    <div class="container">
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

const fetchProductsListByCategorySlug = async (slugs, categoryType) => {
  try {
    let responses = [];

    if (Array.isArray(slugs)) {
      responses = await Promise.all(
        slugs.map(slug => axios.get(`/products/category/${slug}`))
      );
    } else {
      responses = [await axios.get(`/products/category/${slugs}`)];
    }

    if (categoryType === "laptop") {
      const combinedProducts = responses
        .map(response => response.data.data.products)
        .flat();  

      computerProducts.value = combinedProducts;
    } else if (categoryType === "pc") {
      pcProducts.value = responses[0].data.data.products;
    } else if (categoryType === "monitor") {
      monitorProducts.value = responses[0].data.data.products;
    }
  } catch (error) {
    console.error("Error fetching products:", error);
  }
};

onMounted(() => {
  fetchProductsListByCategorySlug(['laptop', 'macbook'], 'laptop');
  fetchProductsListByCategorySlug('pc', 'pc');
  fetchProductsListByCategorySlug('man-hinh', 'monitor');
});
</script>

<style scoped>
@import url(ProductListSections.scss);
</style>
