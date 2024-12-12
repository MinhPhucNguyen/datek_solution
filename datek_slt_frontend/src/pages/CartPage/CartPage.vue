<template>
  <div class="cart-page">
    <div class="container">
      <h2>Giỏ Hàng Của Bạn</h2>
      <table class="cart-table">
        <thead>
          <tr>
            <th></th>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Số tiền</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in cartItems" :key="item.product_id">
            <td><input type="checkbox" /></td>
            <td>
              <div class="cart-item-details">
                <img
                  :src="item.product.product_images[0].image_url"
                  alt="Product Image"
                />
                <div class="product-name">
                  <p>{{ item.product.name }}</p>
                </div>
              </div>
            </td>
            <td>{{ formatCurrency(item.product.price) }}</td>
            <td>
              <div class="quantity-control">
                <button
                  class="quantity-btn decrease-btn"
                  @click="updateQuantity(index, item.quantity - 1)"
                >
                  <font-awesome-icon :icon="['fas', 'minus']" />
                </button>
                <input
                  type="number"
                  v-model="item.quantity"
                  class="quantity-input"
                  @input="onQuantityChange(index, $event)"
                />
                <button
                  class="quantity-btn increase-btn"
                  @click="updateQuantity(index, item.quantity + 1)"
                >
                  <font-awesome-icon :icon="['fas', 'plus']" />
                </button>
              </div>
            </td>
            <td>{{ formatCurrency(item.product.price * item.quantity) }}</td>
            <td>
              <button class="remove-btn" @click="removeItem(index)">Xóa</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="cart-summary">
        <p>Tổng cộng: {{ formatCurrency(totalPrice) }}</p>
        <button class="checkout-btn" @click="checkout">Thanh Toán</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, ref } from "vue";
import { useStore } from "vuex";
import { formatCurrency } from "@/utils/formatCurrency";
import axios from "axios";

const store = useStore();
const cartItems = ref([]);

const userId = store.getters["auth/getUser"].id;
const fetchCart = async () => {
  await axios
    .get("/cart/get-cart", {
      params: { user_id: userId },
    })
    .then((response) => {
      cartItems.value = response.data.items;
    })
    .catch((error) => {
      console.error("Error fetching cart:", error);
    });
};

onBeforeMount(() => {
  fetchCart();
});

const totalPrice = computed(() => {
  return cartItems.value.reduce(
    (total, item) => total + item.product.price * item.quantity,
    0
  );
});

const onQuantityChange = (index, event) => {
  const newQuantity = parseInt(event.target.value, 10);
  if (newQuantity <= 0) {
    removeItem(index);
  } else {
    updateQuantity(index, newQuantity);
  }
};

const updateQuantity = async (index, newQuantity) => {
  if (newQuantity <= 0) {
    removeItem(index);
  } else {
    cartItems.value[index].quantity = newQuantity;
  }
};

const removeItem = async (index) => {
  const cartId = cartItems.value[index].id;
  axios
    .delete(`/cart/remove-item/${cartId}`)
    .then(() => {
      cartItems.value.splice(index, 1);
    })
    .catch((error) => {
      console.error("Error removing item from cart:", error);
    });
};

const checkout = () => {
  console.log("Proceeding to checkout...");
};
</script>

<style scoped>
@import url("./CartPage.scss");
</style>
