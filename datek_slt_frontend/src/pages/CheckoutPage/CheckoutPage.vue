<template>
  <div class="checkout-page">
    <div class="container">
      <div class="checkout-page-inner">
        <div class="block-left">
          <div class="address">
            <h3>Địa chỉ nhận hàng</h3>
            <p>{{ currentUser?.name }} - {{ currentUser?.phone }}</p>

            <div>
              <div class="address-item">
                <div class="address-box">
                  <strong>Địa chỉ: </strong>
                  <input
                    class="input-address"
                    placeholder="Vui lòng nhập địa chỉ"
                    v-model="payload.address"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="product-info">
            <h3>Sản phẩm</h3>
            <div
              v-for="item in cartItems"
              :key="item.product_id"
              class="product-item"
            >
              <img
                :src="item.product.product_images[0].image_url"
                alt="product-images"
              />
              <div class="product-details">
                <p>{{ item.product.name }}</p>
                <p>
                  <span class="fw-bold">Số lượng: </span> {{ item.quantity }}
                </p>
                <p>
                  <span class="fw-bold">Giá: </span>
                  {{ formatCurrency(item.product.price) }}
                </p>
                <p>
                  <span class="fw-bold">Thành tiền: </span>
                  {{ formatCurrency(item.product.price * item.quantity) }}
                </p>
              </div>
            </div>
          </div>

          <div class="payment-method-container">
            <h3>Phương thức thanh toán</h3>
            <div class="payment-options">
              <label>
                <input
                  type="radio"
                  name="payment-method"
                  value="cash"
                  v-model="selectedPaymentMethod"
                />
                Thanh toán khi nhận hàng
              </label>
              <label>
                <input
                  type="radio"
                  name="payment-method"
                  value="vnpay"
                  v-model="selectedPaymentMethod"
                />
                Thanh toán qua VnPay
              </label>
            </div>
          </div>
        </div>

        <div class="block-right">
          <div class="order-summary">
            <h3>Đơn hàng</h3>
            <div class="order-summary-item">
              <p class="fw-bold">Tạm tính:</p>
              <span>{{ formatCurrency(totalPrice) }}</span>
            </div>
            <div class="order-summary-item">
              <p class="fw-bold">Phí vận chuyển:</p>
              <span>{{ formatCurrency(20000) }}</span>
            </div>
            <div class="order-summary-item">
              <p class="fw-bold">Thành tiền</p>
              <span>{{ formatCurrency(totalPrice) }}</span>
            </div>
          </div>

          <button class="btn order-button" @click="placeOrder">Đặt hàng</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { formatCurrency } from "@/utils/formatCurrency";

const store = useStore();
const currentUser = ref(null);
const cartItems = ref([]);
const payload = ref({
  user_id: null,
  address: "",
  payment_method: "cash",
  total_price: 0,
  cart_items: [],
});
const shippingFee = 20000;

onBeforeMount(() => {
  store.dispatch("cart/fetchCart");
  currentUser.value = store.getters["auth/getUser"];
  cartItems.value = store.getters["cart/getCartItems"];
});

onMounted(() => {
  calculateTotalPrice();
  payload.value.user_id = currentUser.value?.id;
  payload.value.address = currentUser.value?.address || "";
});

const totalPrice = ref(0);
const selectedPaymentMethod = ref("cash");

const calculateTotalPrice = () => {
  totalPrice.value =
    cartItems.value.reduce(
      (total, item) => total + item.product.price * item.quantity,
      0
    ) + shippingFee;
};

const placeOrder = () => {
  payload.value.total_price = totalPrice.value;
  payload.value.cart_items = cartItems.value.map((item) => ({
    product_id: item.product_id,
    quantity: item.quantity,
    price: item.product.price,
  }));

  store.dispatch("order/placeOrder", payload.value);
};
</script>

<style scoped>
@import url(CheckoutPage.scss);
</style>
