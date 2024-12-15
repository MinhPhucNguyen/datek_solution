<template>
  <div class="checkout-page">
    <div class="container">
      <div class="checkout-page-inner">
        <div class="block-left">
          <div class="address">
            <h3>Địa chỉ nhận hàng</h3>

            <p>{{ currentUser?.name }} - {{ currentUser?.phone }}</p>

            <div v-if="userAddresses.length > 0">
              <div
                v-for="(address, index) in userAddresses"
                :key="address.id"
                class="address-item"
              >
                <div class="address-box">
                  <p><strong>Địa chỉ: </strong>{{ address }}</p>
                  <button
                    v-if="index !== 0"
                    class="btn select-address"
                    @click="selectAddress(address)"
                  >
                    Giao đến địa chỉ này
                  </button>
                  <button
                    class="btn delete-address"
                    @click="deleteAddress(address.id)"
                  >
                    Xóa
                  </button>
                  <button
                    class="btn change-address"
                    @click="showAddressForm = true"
                  >
                    Thay đổi
                  </button>
                </div>
              </div>
            </div>
            <div v-else>
              <button
                class="btn add-new-shipping-address"
                @click="showAddressForm = true"
              >
                Thêm địa chỉ nhận hàng
              </button>
            </div>

            <div v-if="showAddressForm" class="address-form">
              <h4>Thêm địa chỉ mới</h4>
              <form @submit.prevent="addNewAddress">
                <input
                  v-model="newAddress.address"
                  placeholder="Địa chỉ"
                  required
                />
                <button type="submit">Lưu thay đổi</button>
                <button type="button" @click="showAddressForm = false">
                  Hủy
                </button>
              </form>
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
              <span>0đ</span>
            </div>
            <div class="order-summary-item">
              <p class="fw-bold">Thành tiền</p>
              <spa>{{ formatCurrency(totalPrice) }}</spa>
            </div>
          </div>

          <div class="">
            <button class="btn order-button" @click="placeOrder">
              Đặt hàng
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { formatCurrency } from "@/utils/formatCurrency";

const userAddresses = ref([]);
const showAddressForm = ref(false);
const store = useStore();
const currentUser = ref(null);
const cartItems = ref([]);

onBeforeMount(() => {
  store.dispatch("cart/fetchCart");
  currentUser.value = store.getters["auth/getUser"];
});

onMounted(() => {
  cartItems.value = store.getters["cart/getCartItems"];
  calculateTotalPrice();
  const defaultAddress = currentUser.value.address;
  userAddresses.value = defaultAddress ? [defaultAddress] : [];
  console.log(userAddresses.value);
});

const totalPrice = ref(0);
const selectedPaymentMethod = ref("cash");

const calculateTotalPrice = () => {
  totalPrice.value = cartItems.value.reduce(
    (total, item) => total + item.product.price * item.quantity,
    0
  );
};
</script>

<style scoped>
@import url(CheckoutPage.scss);
</style>
