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
    <div class="product-item-title" :class="{ placeholder: !product.name }">
      <router-link
        :to="{
          name: 'product-detail',
          params: {
            slug: product.name.toLowerCase().replace(/\s+/g, '-'),
            id: product.id,
          },
        }"
        class="car-item"
        :class="{ placeholder: !product.name }"
      >
        <p class="product-name">{{ product.name || "Loading..." }}</p>
      </router-link>
    </div>
    <div
      v-if="product.quantity > 0"
      class="product-item-price"
      :class="{ placeholder: !product.name }"
    >
      <p :class="{ placeholder: !product.price }">
        {{ product.price ? formatCurrency(product.price) : "0" }}
      </p>
    </div>
    <div v-if="product.quantity <= 0" class="out-of-stock-message">
      <p class="text-danger">Hết hàng</p>
    </div>
    <div class="action" v-if="product.quantity > 0">
      <div class="quantity-control">
        <button class="quantity-btn decrease-btn" @click="decrementQuantity">
          <font-awesome-icon :icon="['fas', 'minus']" />
        </button>
        <input type="number" v-model="quantity" class="quantity-input" />
        <button class="quantity-btn increase-btn" @click="incrementQuantity">
          <font-awesome-icon :icon="['fas', 'plus']" />
        </button>
      </div>
      <button
        class="add-to-cart-btn"
        :class="{ 'not-logged-in': !isLoggedIn }"
        @click="addToCart"
        :disabled="!isLoggedIn"
      >
        <font-awesome-icon :icon="['fas', 'cart-shopping']" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, onBeforeMount } from "vue";
import { formatCurrency } from "@/utils/formatCurrency";
import { useStore } from "vuex";

const prop = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const quantity = ref(1);
const store = useStore();
const cartItems = ref([]);
const isLoginModalVisible = ref(false);
const isLoggedIn = ref(false);

const isAuthenticated = store.getters["auth/isAuthenticated"];

const checkLoginStatus = () => {
  isLoggedIn.value = isAuthenticated;
};

const incrementQuantity = () => {
  quantity.value++;
};

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

onBeforeMount(() => {
  checkLoginStatus();
});

const addToCart = async () => {
  try {
    if (!isLoggedIn.value) {
      isLoginModalVisible.value = true;
    } else {
      if (quantity.value > prop.product.quantity) {
        alert(
          `Số lượng sản phẩm còn lại trong kho không đủ. Hiện chỉ còn ${prop.product.value.quantity} sản phẩm.`
        );
        return;
      }

      await store.dispatch("cart/updateOrAddToCart", {
        productId: prop.product.id,
        quantity: quantity.value,
      });
      store.dispatch("cart/toggleCartVisibility", true);
      store.dispatch("cart/fetchCart");
      cartItems.value = store.getters["cart/getCartItems"];
    }
  } catch (error) {
    console.error(error);
  }
};
</script>

<style scoped>
@import url(ProductItemComponent.scss);
</style>
