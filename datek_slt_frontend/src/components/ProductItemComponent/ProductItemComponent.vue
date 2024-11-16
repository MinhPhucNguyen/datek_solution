<template>
  <div class="product-item">
    <div class="product-item-image">
      <img src="https://via.placeholder.com/150" alt="product-image" />
    </div>
    <div class="product-item-title">
      <a href="">
        <p>Product Title</p>
      </a>
    </div>
    <div class="product-item-price">
      <p>$100</p>
    </div>
    <div class="action">
      <button class="add-to-cart-btn">
        <font-awesome-icon :icon="['fas', 'cart-shopping']" />
      </button>
    </div>
  </div>
</template>

<script setup></script>

<style scoped>
@import url(ProductItemComponent.scss);
</style>
