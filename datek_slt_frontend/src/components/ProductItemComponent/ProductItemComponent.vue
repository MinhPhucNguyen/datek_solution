<template>
  <div class="product-item" v-if="product && product.status == 1">
    <div class="product-item-image">
      <router-link
        :to="{
          name: 'product-detail',
          params: {
            slug: product.name.toLowerCase().replace(/\s+/g, '-'),
            id: product.id,
          },
        }"
      >
        <img
          :src="product?.product_images?.[0]?.image_url || ''"
          alt="product-image"
        />
      </router-link>
    </div>
    <div class="product-item-title">
      <router-link
        :to="{
          name: 'product-detail',
          params: {
            slug: product.name.toLowerCase().replace(/\s+/g, '-'),
            id: product.id,
          },
        }"
        class="car-item"
      >
        <p>{{ product.name }}</p>
      </router-link>
    </div>
    <div class="product-item-price">
      <p>{{ formatCurrency(product.price) }}</p>
    </div>
    <div class="action">
      <div class="quantity-control">
        <button class="quantity-btn decrease-btn" @click="decrementQuantity">
          <font-awesome-icon :icon="['fas', 'minus']" />
        </button>
        <input type="number" v-model="quantity" class="quantity-input" />
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
import { ref, defineProps } from "vue";
import { formatCurrency } from "@/utils/formatCurrency";

defineProps({
  product: {
    type: Object,
    required: true,
  },
});

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
