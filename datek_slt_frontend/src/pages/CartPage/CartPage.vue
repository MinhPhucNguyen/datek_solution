<template>
  <div class="cart-page">
    <div class="container">
      <h2>Giỏ Hàng Của Bạn</h2>
      <div v-if="cartItems.length > 0">
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
            <td><input type="checkbox"/></td>
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
                  <font-awesome-icon :icon="['fas', 'minus']"/>
                </button>
                <input
                    type="number"
                    v-model="item.quantity"
                    class="quantity-input"
                    readonly
                />
                <button
                    class="quantity-btn increase-btn"
                    @click="updateQuantity(index, item.quantity + 1)"
                >
                  <font-awesome-icon :icon="['fas', 'plus']"/>
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
          <button class="checkout-button" @click="checkout">Thanh Toán</button>
        </div>
      </div>

      <div v-else>
        <p>Không có sản phẩm nào trong giỏ hàng của bạn.</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import {computed, onBeforeMount, ref} from "vue";
import {useStore} from "vuex";
import {formatCurrency} from "@/utils/formatCurrency";

const store = useStore();
const cartItems = ref([]);

onBeforeMount(async () => {
  await store.dispatch("cart/fetchCart");
  cartItems.value = store.getters["cart/getCartItems"];
});

const totalPrice = computed(() => {
  return cartItems.value.reduce(
      (total, item) => total + item.product.price * item.quantity,
      0
  );
});


const updateQuantity = async (index, newQuantity) => {
  newQuantity = Math.max(newQuantity, 1);

  try {
    const productId = cartItems.value[index].product.id;
    await store.dispatch("cart/updateQuantity", {
      productId,
      quantity: newQuantity - cartItems.value[index].quantity,
    });

    const updatedCart = store.getters["cart/getCartItems"];
    updatedCart[index].quantity = newQuantity;
    cartItems.value = updatedCart;
    
  } catch (error) {
    console.error(error);
  }
};

const removeItem = async (index) => {
  const cartId = cartItems.value[index].id;
  await store.dispatch("cart/removeItem", cartId);
  refreshCart();
};

const refreshCart = async () => {
  await store.dispatch("cart/fetchCart");
  cartItems.value = store.getters["cart/getCartItems"];
};

const checkout = () => {
  console.log("Proceeding to checkout...");
};
</script>

<style scoped>
@import url(CartPage.scss);
</style>
