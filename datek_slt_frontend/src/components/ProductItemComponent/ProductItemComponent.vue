<template>
  <div class="product-item">
    <div class="product-item-image">
      <a href="">
        <img src="https://via.placeholder.com/150" alt="product-image" />
      </a>
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
      <div class="quantity-control">
        <button class="quantity-btn decrease-btn" @click="decrementQuantity">
          <font-awesome-icon :icon="['fas', 'minus']" />
        </button>
        <input
          type="number"
          v-model="quantity"
          class="quantity-input"
        />
        <button class="quantity-btn increase-btn" @click="incrementQuantity">
          <font-awesome-icon :icon="['fas', 'plus']" />
        </button>
      </div>
      <button class="add-to-cart-btn">
        <font-awesome-icon :icon="['fas', 'cart-shopping']" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const quantity = ref(1);

const incrementQuantity = () => {
  quantity.value++;
};

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

</script>

<style scoped>
@import url(ProductItemComponent.scss);
</style>
