<template>
  <div class="search-results-block">
    <div class="container">
      <div v-if="isLoading" style="text-align: center;">
        <div class="loading">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div v-else>
        <div class="page-title">
          <h4>
            Có {{ results.length }} kết quả tìm kiếm cho: "{{ searchQuery }}"
          </h4>
        </div>
        <div class="search-results mt-5">
          <div class="search-results-list">
            <ProductItemCompoent
              v-for="product in results"
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
import { ref, watch } from "vue";
import { useRouter } from "vue-router";
import ProductItemCompoent from "@/components/ProductItemComponent/ProductItemComponent.vue";

const searchQuery = ref("");
const results = ref([]);
const router = useRouter();
const isLoading = ref(false);

searchQuery.value = router.currentRoute.value.query.q;
const search = async (query) => {
  isLoading.value = true;
  try {
    const response = await axios.get("/search", {
      params: { q: query },
    });
    results.value = response.data.data.products;
    window.scrollTo({ top: 0, behavior: "smooth" });
    isLoading.value = false;
  } catch (error) {
    console.error(error);
    isLoading.value = false;
    results.value = [];
  }
};

watch(
  () => router.currentRoute.value.query.q,
  async (newQuery) => {
    searchQuery.value = newQuery;
    if (newQuery) {
      await search(newQuery);
    } else {
      results.value = [];
    }
  },
  { immediate: true }
);
</script>

<style scoped>
@import url(SearchResultsPage.scss);
</style>
