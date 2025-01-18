<template>
  <div class="block-banner">
    <div class="container">
      <div class="block-banner-inner">
        <div class="block-banner-image">
          <img :src="bannerImage" alt="banner" />
        </div>
        <div class="block-banner-content">
          <p class="block-banner-title">Lenovo ThinkPad</p>
          <p class="block-banner-subtitle">T14s Gen 3</p>
          <div class="shop-now-btn">
            <a
              href="thinkpad-t14s-gen-3-14inch---2022---used-core-i7-1270p-32gb-512gb-fhd+/53"
              >Mua ngay</a
            >
          </div>
        </div>
        <div
          class="block-search"
          ref="searchBox"
          @mouseleave="handleMouseLeave"
        >
          <div class="block-search-inner">
            <p>Tìm kiếm sản phẩm để bắt đầu!</p>
            <form @submit.prevent="search">
              <div class="search-input-container">
                <input
                  type="text"
                  class="search-input"
                  name="q"
                  v-model="searchQuery"
                  placeholder="Nhập tên sản phẩm hoặc mã sản phẩm"
                  @keydown.enter="search"
                  @input="fetchSearchResults"
                />
                <div v-if="isLoading" class="spinner-border" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <button type="submit" class="search-btn">Tìm kiếm</button>
            </form>

            <ul
              v-if="!isLoading && searchQuery.length >= 2 && results.length"
              class="dropdown"
            >
              <li class="count-results">
                Có {{ results.length }} kết quả được tìm thấy
              </li>
              <li
                v-for="product in results"
                :key="product.id"
                @click="goToProductDetail(product)"
                class="dropdown-item"
              >
                <div class="product-image">
                  <img
                    :src="product.product_images[0].image_url"
                    alt="product"
                  />
                </div>
                <span class="product-name">
                  {{ product.name }}
                </span>
              </li>

              <li v-if="!results.length" class="no-results">
                Không có kết quả phù hợp.
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import bannerImage from "@/assets/images/banner-images/laptop.png";
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const isLoading = ref(false);
const results = ref([]);
const searchQuery = ref("");
const router = useRouter();
const searchBox = ref(null);

const handleMouseLeave = () => {
  results.value = [];
};

const fetchSearchResults = async () => {
  if (searchQuery.value.length < 2) {
    results.value = [];
    isLoading.value = false;
    return;
  }

  isLoading.value = true;

  await axios
    .get("/search", { params: { q: searchQuery.value } })
    .then((response) => {
      results.value = response.data.data.products;
      console.log("Kết quả tìm kiếm:", results.value);
    })
    .catch((error) => {
      console.error("Lỗi khi tìm kiếm sản phẩm:", error);
    })
    .finally(() => {
      isLoading.value = false;
    });
};

const goToProductDetail = (product) => {
  router.push({
    name: "product-detail",
    params: {
      slug: product.name.toLowerCase().replace(/\s+/g, "-"),
      id: product.id,
    },
  });
};

function search() {
  const searchQueryFormat = searchQuery.value.trim();
  if (searchQuery.value && searchQueryFormat) {
    router.push({ name: "search-results", query: { q: searchQueryFormat } });
  }
  fetchSearchResults();
}
</script>

<style scoped lang="scss">
@import url(BannerComponent.scss);
</style>
