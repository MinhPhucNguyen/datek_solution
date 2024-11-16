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
            <a href="">Mua ngay</a>
          </div>
        </div>
        <div class="block-search">
          <div class="block-search-inner">
            <p>Tìm kiếm sản phẩm để bắt đầu!</p>
            <form action="">
              <input
                type="text"
                class="search-input"
                placeholder="Nhập tên sản phẩm hoặc SKU"
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
import bannerImage from '@/assets/images/banner-images/laptop.png';
</script>

<style scoped lang="scss">
@import url(BannerComponent.scss);
</style>
