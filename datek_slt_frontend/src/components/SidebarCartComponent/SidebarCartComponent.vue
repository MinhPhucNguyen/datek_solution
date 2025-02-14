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
            <div class="product-image">
              <img
                :src="item.product.product_images[0].image_url"
                alt="Product Image"
              />
            </div>
            <div class="cart-details">
              <h3>{{ item.product.name }}</h3>

              <div
                class="actions w-100 d-flex justify-content-between align-items-center"
              >
                <div class="quantity-control mt-2 bt-2">
                  <button
                    class="quantity-btn decrease-btn"
                    @click="changeQuantity(index, item.quantity - 1)"
                  >
                    <font-awesome-icon :icon="['fas', 'minus']" />
                  </button>
                  <input
                    type="number"
                    v-model="item.quantity"
                    class="quantity-input"
                    readonly
                    :min="1"
                  />
                  <button
                    class="quantity-btn increase-btn"
                    @click="changeQuantity(index, item.quantity + 1)"
                  >
                    <font-awesome-icon :icon="['fas', 'plus']" />
                  </button>
                </div>

                <button class="remove-button" @click="removeItem(index)">
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </div>
              <small v-if="quantityError" class="text-danger">{{
                quantityError
              }}</small>
              <p>
                Giá: {{ formatCurrency(item.product.price * item.quantity) }}
              </p>
            </div>
          </div>
        </div>

        <div v-else>
          <p>Giỏ hàng của bạn đang trống!</p>
        </div>

        <div class="subtotal">
          <p>Tổng: {{ formatCurrency(totalPrice) }}</p>
          <button
            class="btn checkout-btn"
            @click="navigateToCheckoutPage"
            :disabled="props.cartItems.length === 0"
          >
            Đặt hàng
          </button>
        </div>
        <div class="view-cart">
          <router-link :to="{ name: 'cart-page' }"> Xem giỏ hàng </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { defineProps, defineEmits } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { formatCurrency } from "@/utils/formatCurrency";

const router = useRouter();
const store = useStore();
const props = defineProps({
  isCartVisible: Boolean,
  cartItems: Array,
});

const emits = defineEmits(["updateCart"]);

const totalPrice = ref(0);

const calculateTotalPrice = () => {
  totalPrice.value = props.cartItems.reduce(
    (total, item) => total + item.product.price * item.quantity,
    0
  );
};

watch(
  () => props.cartItems,
  () => {
    calculateTotalPrice();
    if (props.cartItems.length === 0) {
      emits("closeCart");
      store.dispatch("cart/toggleCartVisibility", false);
    }
  },
  { deep: true }
);

watch(
  () => router.currentRoute.value.fullPath,
  () => {
    if (props.isCartVisible) {
      emits("closeCart");
    }
  }
);

const removeItem = async (index) => {
  await store.dispatch("cart/removeItem", props.cartItems[index].id);

  await store.dispatch("cart/fetchCart");

  emits("updateCart", store.getters["cart/getCartItems"]);

  if (store.getters["cart/getCartItems"].length === 0) {
    emits("closeCart");
  }
};

const quantityError = ref(null);
const changeQuantity = async (index, newQuantity) => {
  const product = props.cartItems[index].product;

  if (newQuantity > product.quantity) {
    quantityError.value = `Không đủ số lượng yêu cầu. Chỉ còn ${product.quantity} sản phẩm.`;
    return;
  }

  newQuantity = Math.max(newQuantity, 1);

  try {
    const productId = props.cartItems[index].product.id;
    await store.dispatch("cart/updateQuantity", {
      productId,
      quantity: newQuantity,
    });

    await store.dispatch("cart/fetchCart");

    const updatedCart = [...props.cartItems];
    updatedCart[index].quantity = newQuantity;

    emits("updateCart", updatedCart);
  } catch (error) {
    console.error(error);
  }
};

calculateTotalPrice();

const navigateToCheckoutPage = () => {
  router.push("/checkout");
};
</script>

<style scoped>
@import url(SidebarCartComponent.scss);
</style>
