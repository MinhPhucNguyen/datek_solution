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
            <a href="thinkpad-t14s-gen-3-14inch---2022---used-core-i7-1270p-32gb-512gb-fhd+/53">Mua ngay</a>
          </div>
        </div>
        <div class="block-search">
          <div class="block-search-inner">
            <p>Tìm kiếm sản phẩm để bắt đầu!</p>
            <form @submit.prevent="search">
              <input
                type="text"
                class="search-input"
                name="q"
                v-model="searchQuery"
                placeholder="Nhập tên sản phẩm hoặc SKU"
                @keydown.enter="search"
              />
              <button type="submit" class="search-btn">Tìm kiếm</button>
            </form>
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

const searchQuery = ref("");
const router = useRouter();

function search() {
  const searchQueryFormat = searchQuery.value.trim();
  if (searchQuery.value && searchQueryFormat) {
    router.push({ name: "search-results", query: { q: searchQueryFormat } });
  }
}
</script>

<style scoped lang="scss">
@import url(BannerComponent.scss);
</style>
