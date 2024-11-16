<template>
  <div class="latest-produtct-list-block">
    <div class="container">
      <div class="latest-produtct-list-title">
        <p>Sản phẩm mới nhất</p>
      </div>
      <div class="latest-product-list-content">
        <div class="product-list">
          <ProductItemComponent v-for="index in 10" :key="index" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import ProductItemComponent from '../ProductItemComponent/ProductItemComponent.vue';
</script>

<style scoped>
@import url(LatestProductListComponent.scss);
</style>
