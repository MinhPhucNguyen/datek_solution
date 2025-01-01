<template>
  <div class="product-detail-page">
    <ToastMessage
      :message="successMessage ? successMessage : errorMessage"
      :type="errorMessage ? 'danger' : 'success'"
    />

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
          <p class="sku">Mã sản phẩm: {{ productDetail.sku }}</p>
          <h1>{{ productDetail.name }}</h1>
          <div>
            <div class="product-categories">
              <p
                v-for="category in productDetail.categories"
                :key="category.id"
              >
                {{ category.category_name }}
              </p>
            </div>
          </div>
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
            v-if="productDetail.description !== 'null'"
            class="product-description"
            v-html="productDetail.description"
          ></div>
          <div v-else class="product-description">
            <span>Không có mô tả sản phẩm.</span>
          </div>
        </div>
      </div>
      <div class="product-info-section">
        <div class="section-title">
          <span> Thông số chi tiết </span>
        </div>
        <div class="section-content">
          <div
            v-if="productDetail.detailed_specifications !== 'null'"
            class="product-description"
            v-html="productDetail.detailed_specifications"
          ></div>
          <div v-else class="product-description">
            <span>Không có thông số kỹ thuật chi tiết.</span>
          </div>
        </div>
      </div>
      <div class="product-info-section">
        <div class="section-title border-0 mt-5 fs-3">
          <span> Đánh giá sản phẩm </span>
        </div>
        <div class="section-content reviews">
          <div class="add-reviews">
            <form @submit.prevent="submitReview" class="d-flex flex-column">
              <label class="fw-bold">Đánh giá: </label>
              <input
                type="number"
                v-model="newReview.rating"
                min="1"
                max="5"
                required
              />
              <label class="fw-bold mt-3">Viết đánh giá:</label>
              <textarea v-model="newReview.comment" class="mb-3"></textarea>
              <button type="submit" class="add-review-btn fw-bold"  :disabled="!isFilledForm">
                Thêm đánh giá sản phẩm
              </button>
            </form>
          </div>
          <div class="reviews-list">
            <ul v-if="reviews.length > 0">
              <li
                v-for="review in reviews"
                :key="review.id"
                class="review-item"
              >
                <div class="review-header d-flex align-items-center">
                  <div class="review-avatar me-3">
                    <i class="fa-solid fa-user"></i>
                  </div>
                  <div class="review-user-info">
                    <p class="fw-bold mb-0">{{ review.user.name }}</p>
                    <small class="review-date">{{
                      formatDateTime(review.created_at)
                    }}</small>
                  </div>
                </div>
                <div class="review-content">
                  <div class="review-stars">
                    <span
                      v-for="star in 5"
                      :key="star"
                      class="star"
                      :class="{ active: star <= review.rating }"
                    >
                      <i class="fa-solid fa-star"></i>
                    </span>
                    <span class="review-rating-text"
                      >{{ review.rating }}/5</span
                    >
                  </div>
                  <p class="review-text">{{ review.review }}</p>
                </div>
              </li>
            </ul>
            <div v-else>
              <p>Không có đánh giá nào!</p>
            </div>
          </div>
        </div>
      </div>
      <div class="product-info-section" v-if="relatedProducts.length > 0">
        <div class="section-title border-0 mt-5 fs-3">
          <span> Các sản phẩm liên quan </span>
        </div>
        <div class="section-content related-products">
          <div class="product-slider">
            <button
              class="slider-btn left"
              @click="prevSlide"
              :disabled="isPrevDisabled"
              v-if="relatedProducts.length > 5"
            >
              <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="product-list-wrapper">
              <div class="product-list-related" :style="sliderStyle">
                <ProductItemComponent
                  v-for="product in relatedProducts"
                  :key="product.id"
                  :product="product"
                />
              </div>
            </div>

            <button
              class="slider-btn right"
              @click="nextSlide"
              :disabled="isNextDisabled"
              v-if="relatedProducts.length > 5"
            >
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
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
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import { formatCurrency } from "@/utils/formatCurrency";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import CartSideBar from "@/components/SidebarCartComponent/SidebarCartComponent.vue";
import ProductItemComponent from "@/components/ProductItemComponent/ProductItemComponent.vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import { formatDateTime } from "@/utils/formatDateTime";

const route = useRoute();
const productDetail = ref({});
const productImages = ref([]);
const relatedProducts = ref([]);
const currentIndex = ref(0);
const productsPerPage = 5;
const productId = ref(null);
const isLoginModalVisible = ref(false);
const store = useStore();
const router = useRouter();
const isLoading = ref(true);
const successMessage = ref(null);
const errorMessage = ref(null);
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

onMounted(() => {
  getProductDetail();
  getRelatedProducts();
});

const getRelatedProducts = async () => {
  try {
    await axios.get(`products/${productId.value}/related`).then((response) => {
      relatedProducts.value = response.data.data.products;
    });
  } catch (error) {
    errorMessage.value = error;
  }
};

const nextSlide = () => {
  if (currentIndex.value < relatedProducts.value.length - productsPerPage) {
    currentIndex.value += productsPerPage;
  }
};

const prevSlide = () => {
  if (currentIndex.value > 0) {
    currentIndex.value -= productsPerPage;
  }
};

const sliderStyle = computed(() => ({
  transform: `translateX(-${(currentIndex.value / productsPerPage) * 100}%)`,
}));

const isPrevDisabled = computed(() => currentIndex.value === 0);
const isNextDisabled = computed(
  () => currentIndex.value >= relatedProducts.value.length - productsPerPage
);

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

// Reviews
const reviews = ref([]);
const newReview = ref({
  rating: 0,
  comment: "",
});
const fetchReviews = async () => {
  try {
    const response = await axios.get(`products/${productId.value}/reviews`);
    reviews.value = response.data.data;
  } catch (error) {
    console.error(error);
  }
};

const submitReview = async () => {
  const response = await axios.post(`products/${productId.value}/reviews`, {
    rating: newReview.value.rating,
    review: newReview.value.comment,
  });
  if (response.data.success) {
    successMessage.value = response.data.message;
    $(".toast").toast("show");
    fetchReviews();
    newReview.value.rating = 1;
    newReview.value.comment = "";
  } else {
    errorMessage.value = response.data.message;
    $(".toast").toast("show");
  }
};

onMounted(() => {
  fetchReviews();
});
</script>

<style scoped>
@import url(ProductDetailPage.scss);
</style>
