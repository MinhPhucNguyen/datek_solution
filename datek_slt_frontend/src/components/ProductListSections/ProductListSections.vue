<template>
  <div class="product-list-block">
    <div class="container">
      <ProductListSection
        title="Laptop, Macbook, Surface"
        :slugs="['laptop', 'macbook']"
      />
      <ProductListSection title="PC" slugs="pc" />
      <ProductListSection title="Màn hình" slugs="man-hinh" />
      <ProductListSection title="Phụ kiện" slugs="phu-kien" />
      <ProductListSection title="Phần mềm" slugs="phan-mem" />
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onBeforeMount } from "vue";
import ProductListSection from "./ProductListSectionComponent.vue";

const computerProducts = ref([]);
const pcProducts = ref([]);
const monitorProducts = ref([]);

const fetchProductsListByCategorySlug = async (slugs, categoryType) => {
  try {
    let responses = [];

    if (Array.isArray(slugs)) {
      responses = await Promise.all(
        slugs.map((slug) => axios.get(`/products/category/${slug}`))
      );
    } else {
      responses = [await axios.get(`/products/category/${slugs}`)];
    }

    if (categoryType === "laptop") {
      const combinedProducts = responses
        .map((response) => response.data.data.products)
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

onBeforeMount(() => {
  fetchProductsListByCategorySlug(["laptop", "macbook"], "laptop");
  fetchProductsListByCategorySlug("pc", "pc");
  fetchProductsListByCategorySlug("man-hinh", "monitor");
});
</script>

<style scoped>
@import url(ProductListSections.scss);
</style>
