<template>
  <div class="product-detail-page">
    <div v-if="isLoading" class="loading">
      <div
        class="spinner-border"
        style="width: 3rem; height: 3rem"
        role="status"
      >
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="container" v-else>
      <div class="product-detail-container">
        <div class="product-images">
          <div class="main-image">
            <transition name="slide" mode="out-in">
              <img :src="currentImage" alt="Main Product Image" />
            </transition>
          </div>
          <div class="thumbnail-images">
            <img
              v-for="(image, index) in productImages"
              :key="index"
              :src="image.image_url"
              alt="Thumbnail Image"
              @click="setCurrentImage(image.image_url)"
              :class="{ 'active-thumbnail': currentImage === image.image_url }"
            />
          </div>
        </div>

        <div class="product-info">
          <h1>{{ productDetail.name }}</h1>
          <p class="sku">Mã sản phẩm: {{ productDetail.sku }}</p>
          <p class="price">{{ formatCurrency(productDetail.price) }}</p>

          <div class="actions">
            <div class="quantity-control">
              <button
                class="quantity-btn decrease-btn"
                @click="decrementQuantity"
              >
                <font-awesome-icon :icon="['fas', 'minus']" />
              </button>
              <input type="number" v-model="quantity" class="quantity-input" />
              <button
                class="quantity-btn increase-btn"
                @click="incrementQuantity"
              >
                <font-awesome-icon :icon="['fas', 'plus']" />
              </button>
            </div>

            <p
              class="product-quantity-status"
              :class="productDetail.quantity > 0 ? 'in-stock' : 'out-of-stock'"
            >
              {{
                productDetail.quantity > 0
                  ? `Còn ${productDetail.quantity} sản phẩm`
                  : "Hết hàng"
              }}
            </p>

            <button
              class="add-to-cart-btn"
              :disabled="productDetail.quantity <= 0"
              @click="addToCart"
            >
              Thêm vào giỏ hàng
            </button>
          </div>
        </div>
      </div>
      <div class="product-info-section">
        <div class="section-title">
          <span> Mô tả sản phẩm </span>
        </div>
        <div class="section-content">
          <div
            class="product-description"
            v-html="productDetail.description"
          ></div>
        </div>
      </div>
      <div class="product-info-section">
        <div class="section-title">
          <span> Thông số chi tiết </span>
        </div>
        <div class="section-content">
          <div
            class="product-description"
            v-html="productDetail.detailed_specifications"
          ></div>
        </div>
      </div>
    </div>

    <CartSideBar
      :isCartVisible="isCartVisible"
      :cartItems="cartItems"
      @updateCart="cartItems = $event"
      @closeCart="closeCart"
    />

    <div
      v-if="isLoginModalVisible"
      class="login-modal"
      @click="closeLoginModal"
    >
      <div class="modal-content" @click.stop>
        <p>Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.</p>
        <div class="action-buttons">
          <button class="login-btn" @click="redirectToLogin">Đăng nhập</button>
          <button @click="closeLoginModal">Đóng</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { formatCurrency } from "@/utils/formatCurrency";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import CartSideBar from "@/components/SidebarCartComponent/SidebarCartComponent.vue";

const route = useRoute();
const productDetail = ref({});
const productImages = ref([]);
const errorMessage = ref("");
const productId = ref(null);
const isLoginModalVisible = ref(false);
const store = useStore();
const router = useRouter();
const isLoading = ref(true);
let autoChangeImageInterval = null;

productId.value = route.params.id;
const getProductDetail = async () => {
  try {
    const response = await axios.get(
      `product/detail?product_id=${productId.value}`
    );
    if (response.status === 200) {
      productDetail.value = response.data.data;
      productImages.value = response.data.data.product_images;
      currentImage.value = productImages.value[0].image_url;

      startAutoImageChange();
    } else {
      throw new Error("Something went wrong");
    }
  } catch (error) {
    errorMessage.value = error;
  } finally {
    isLoading.value = false;
  }
};
getProductDetail();

const currentImage = ref("");

const quantity = ref(1);

const incrementQuantity = () => {
  quantity.value++;
};

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

const setCurrentImage = (image) => {
  currentImage.value = image;
  resetAutoImageChange();
};

const startAutoImageChange = () => {
  let currentThumbnailIndex = 0;
  autoChangeImageInterval = setInterval(() => {
    currentImage.value = productImages.value[currentThumbnailIndex].image_url;
    currentThumbnailIndex =
      (currentThumbnailIndex + 1) % productImages.value.length;
  }, 3000);
};

const resetAutoImageChange = () => {
  if (autoChangeImageInterval) {
    clearInterval(autoChangeImageInterval);
    startAutoImageChange();
  }
};

const isCartVisible = ref(false);
const cartItems = ref([]);
const isAuthenticated = store.getters["auth/isAuthenticated"];
const isLoggedIn = ref(false);

const checkLoginStatus = () => {
  isLoggedIn.value = isAuthenticated;
};

const addToCart = async () => {
  try {
    checkLoginStatus();

    if (!isLoggedIn.value) {
      isLoginModalVisible.value = true;
    } else {
      if (quantity.value > productDetail.value.quantity) {
        alert(
          `Số lượng sản phẩm còn lại trong kho không đủ. Hiện chỉ còn ${productDetail.value.quantity} sản phẩm.`
        );
        return;
      }

      await store.dispatch("cart/updateOrAddToCart", {
        productId: productId.value,
        quantity: quantity.value,
      });

      await store.dispatch("cart/fetchCart");
      cartItems.value = store.getters["cart/getCartItems"];

      isCartVisible.value = true;
    }

    isCartVisible.value = true;
  } catch (error) {
    console.error(error);
  }
};

onMounted(async () => {
  await store.dispatch("cart/fetchCart");
  cartItems.value = store.getters["cart/getCartItems"];
});

const closeLoginModal = () => {
  isLoginModalVisible.value = false;
};

const redirectToLogin = () => {
  const currentPath = router.currentRoute.value.fullPath;
  localStorage.setItem("redirectAfterLogin", currentPath);
  router.push({ name: "login" });
};

const closeCart = () => {
  isCartVisible.value = false;
  store.dispatch("cart/toggleCartVisibility", false);
};

onMounted(() => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});
</script>

<style scoped>
@import url(ProductDetailPage.scss);
</style>
