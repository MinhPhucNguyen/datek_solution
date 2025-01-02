<template>
  <div class="checkout-page">
    <div class="container">
      <div class="checkout-page-inner">
        <ToastMessage
          :message="successMessage ? successMessage : errorMessage"
          :type="errorMessage ? 'danger' : 'success'"
        />
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
            </div>
          </div>
        </div>

        <div class="block-right">
          <div class="order-summary">
            <h3>Đơn hàng</h3>
            <div
              class="order-summary-item d-flex align-items-center justify-content-between"
            >
              <p class="fw-bold">Tạm tính:</p>
              <span>{{ formatCurrency(subtotal) }}</span>
            </div>
            <div
              class="order-summary-item d-flex align-items-center justify-content-between"
            >
              <p class="fw-bold">Phí vận chuyển:</p>
              <span>{{ formatCurrency(shippingFee) }}</span>
            </div>
            <div
              class="order-summary-item d-flex align-items-center justify-content-between"
            >
              <p class="fw-bold">VAT:</p>
              <span>{{ taxRate + "%" }}</span>
            </div>
            <div
              class="order-summary-item d-flex align-items-center justify-content-between"
            >
              <p class="fw-bold">Giảm giá:</p>
              <span class="text-danger"
                >-{{ formatCurrency(totalDiscountAmount ?? 0) }}</span
              >
            </div>
            <div
              class="order-summary-item d-flex align-items-center justify-content-between"
            >
              <p class="fw-bold">Thành tiền:</p>
              <span>{{ formatCurrency(totalPrice) }}</span>
            </div>
          </div>
          <div class="discount-code">
            <form
              class="discount-code-container"
              @submit.prevent="applyDiscount"
            >
              <input
                type="text"
                class="discount-code-input"
                placeholder="Nhập mã giảm giá"
                v-model="applyDiscountCode"
              />
              <button
                type="submit"
                class="discount-code-button"
                @click="applyDiscount"
                :disabled="!isFilled"
              >
                Áp dụng
              </button>
            </form>
          </div>
          <button class="order-button" @click="placeOrder">Đặt hàng</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref, watch, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { formatCurrency } from "@/utils/formatCurrency";
import ToastMessage from "@/components/Toast/Toast.vue";
import axios from "axios";

const router = useRouter();
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
const successMessage = ref(null);
const errorMessage = ref(null);
const shippingFee = 30000;
const isFilled = ref(false);
const totalDiscountAmount = ref(0);

onBeforeMount(() => {
  store.dispatch("cart/fetchCart").then(() => {
    cartItems.value = store.getters["cart/getCartItems"];
    calculateTotalPrice();
  });
  currentUser.value = store.getters["auth/getUser"];
});

onMounted(() => {
  calculateTotalPrice();
  payload.value.user_id = currentUser.value?.id;
  payload.value.address = currentUser.value?.address || "";
});

const taxRate = 10;
const totalPrice = ref(0);
const subtotal = ref(0);
const selectedPaymentMethod = ref("cash");

watch(selectedPaymentMethod, (newMethod) => {
  payload.value.payment_method = newMethod;
});

const calculateTotalPrice = () => {
  subtotal.value = cartItems.value.reduce(
    (total, item) => total + item.product.price * item.quantity,
    0
  );
  const tax = subtotal.value * (taxRate / 100);
  totalPrice.value =
    subtotal.value + tax + shippingFee - totalDiscountAmount.value;
};

const placeOrder = async () => {
  payload.value.total_price = totalPrice.value;
  payload.value.cart_items = cartItems.value.map((item) => ({
    product_id: item.product_id,
    quantity: item.quantity,
    price: item.product.price,
  }));

  await store
    .dispatch("orders/placeOrder", payload.value)
    .then(() => {
      store.dispatch("cart/clearCart");
      localStorage.removeItem("discountCode");
      localStorage.removeItem("totalDiscountAmount");
      router.push({ name: "success-page" });
    })
    .catch((error) => {
      errorMessage.value = error.response.data.message;
      $(".toast").toast("show");
    });
};

const applyDiscountCode = ref("");

isFilled.value = computed(() => applyDiscountCode.value.trim().length > 0);

onMounted(() => {
  const savedDiscountCode = localStorage.getItem("discountCode");
  const savedTotalDiscountAmount = localStorage.getItem("totalDiscountAmount");

  if (savedDiscountCode) {
    applyDiscountCode.value = savedDiscountCode;
  }

  if (savedTotalDiscountAmount) {
    totalDiscountAmount.value = parseFloat(savedTotalDiscountAmount);
  }

  calculateTotalPrice();
  payload.value.user_id = currentUser.value?.id;
  payload.value.address = currentUser.value?.address || "";
});

const applyDiscount = async () => {
  try {
    const response = await axios.post("/apply-discount", {
      coupon_code: applyDiscountCode.value,
      user_id: currentUser.value.id,
    });
    successMessage.value = response.data.message;
    errorMessage.value = "";
    $(".toast").toast("show");
    totalDiscountAmount.value = response.data.total_discount;
    localStorage.setItem("discountCode", applyDiscountCode.value);
    localStorage.setItem("totalDiscountAmount", response.data.total_discount);
    calculateTotalPrice();
  } catch (error) {
    errorMessage.value = error.response.data.message;
    successMessage.value = "";
    $(".toast").toast("show");
  }
};
</script>

<style scoped>
@import url(CheckoutPage.scss);
</style>
