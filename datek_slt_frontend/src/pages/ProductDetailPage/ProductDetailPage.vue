<template>
  <div class="product-detail-page">
    <div class="container">
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

        <!-- Product Information Section -->
        <div class="product-info">
          <h1>{{ productDetail.name }}</h1>
          <p class="sku">Mã sản phẩm: {{ productDetail.sku }}</p>
          <p class="price">{{ formatCurrency(productDetail.price) }}</p>
          <div
            class="product-description"
            v-html="productDetail.description"
          ></div>

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
            <button class="add-to-cart-btn" @click="addToCart">
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
          <ul>
            <li>
              <font style="vertical-align: inherit"
                ><font style="vertical-align: inherit"
                  >Series: ThinkCentre</font
                ></font
              >
            </li>
            <li>
              <font style="vertical-align: inherit"
                ><font style="vertical-align: inherit"
                  >Model: ThinkCentre neo 50 SFF</font
                ></font
              >
            </li>
            <li>
              <font style="vertical-align: inherit"
                ><font style="vertical-align: inherit"
                  >Warranty Term (month) : 36 month(s)</font
                ></font
              >
            </li>
            <li>
              <font style="vertical-align: inherit"
                ><font style="vertical-align: inherit"
                  >Pack Weight Gross (kg): 1.11 kg</font
                ></font
              >
            </li>
            <li>
              <font style="vertical-align: inherit"
                ><font style="vertical-align: inherit"
                  >Pieces in pack: 1</font
                ></font
              >
            </li>
            <li>
              <font style="vertical-align: inherit"
                ><font style="vertical-align: inherit"
                  >Box Weight Gross (kg): 1 kg</font
                ></font
              >
            </li>
          </ul>
        </div>
      </div>
      <div class="product-info-section">
        <div class="section-title">
          <span> Thông số chi tiết </span>
        </div>
        <div class="section-content">
          <table class="info-table">
            <tbody>
              <tr
                v-for="(row, index) in infoRows"
                :key="index"
                :class="{ 'odd-row': index % 2 !== 0 }"
              >
                <td class="label">{{ row.label }}</td>
                <td class="value">{{ row.value }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <CartSideBar
      :isCartVisible="isCartVisible"
      :cartItems="cartItems"
      @closeCart="closeCart"
    />
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { formatCurrency } from "@/utils/formatCurrency";
import CartSideBar from "@/components/SidebarCartComponent/SidebarCartComponent.vue";

const route = useRoute();
const productDetail = ref({});
const productImages = ref([]);
const errorMessage = ref("");
const productId = ref(null);
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

const infoRows = [
  { label: "Code", value: "12LN003SZY" },
  { label: "Color", value: "Indeterminate" },
  { label: "Brand", value: "Lenovo" },
  { label: "Guarantee", value: "36 months" },
];

const isCartVisible = ref(false);
const cartItems = ref([]);

const addToCart = () => {
  const newItem = {
    name: "Sản phẩm mẫu",
    image: "path/to/image.jpg",
    quantity: 1,
    price: 100,
  };

  cartItems.value.push(newItem);
  isCartVisible.value = true;
};

const closeCart = () => {
  isCartVisible.value = false;
};

onMounted(() => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});
</script>

<style scoped>
@import url(ProductDetailPage.scss);
</style>
