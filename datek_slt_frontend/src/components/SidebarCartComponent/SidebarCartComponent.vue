<template>
  <div>
    <div
      class="sidebar-overlay"
      :class="{ visible: props.isCartVisible }"
      @click="$emit('closeCart')"
    ></div>
    <div
      id="sidebar-cart"
      class="sidebar-cart"
      :class="{ visible: props.isCartVisible }"
    >
      <div class="cart-sidebar-header">
        <span>Giỏ Hàng</span>
        <button class="close-btn" @click="$emit('closeCart')">
          <font-awesome-icon :icon="['fas', 'xmark']" />
        </button>
      </div>
      <div class="cart-content">
        <div v-if="props.cartItems.length > 0" class="cart-list">
          <div
            class="cart-item"
            v-for="(item, index) in props.cartItems"
            :key="index"
          >
            <img :src="item.image" alt="Product Image" />
            <div class="cart-details">
              <h3>{{ item.name }}</h3>
              <p>Số lượng: {{ item.quantity }}</p>
              <p>Giá: {{ item.price | currency }}</p>
            </div>
          </div>
        </div>
        <div v-else>
          <p>Giỏ hàng của bạn đang trống!</p>
        </div>

        <div class="subtotal">
          <p>Tổng: {{ totalPrice | currency }}</p>
          <button class="checkout-btn">Thanh toán</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { defineProps } from "vue";

const totalPrice = ref(0);

const props = defineProps({
  isCartVisible: Boolean,
  cartItems: Array,
});

// const updateTotalPrice = () => {
//   totalPrice.value = cartItems.value.reduce(
//     (total, item) => total + item.price * item.quantity,
//     0
//   );
// };


</script>

<style scoped>
@import url(SidebarCartComponent.scss);
</style>
