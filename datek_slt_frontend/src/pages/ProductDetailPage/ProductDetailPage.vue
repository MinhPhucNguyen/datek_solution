<template>
  <div class="product-detail-page">
    <div class="container">
      <div class="product-detail-container">
        <div class="product-images">
          <div class="main-image">
            <img :src="currentImage" alt="Main Product Image" />
          </div>
          <div class="thumbnail-images">
            <img
              v-for="(image, index) in productImages"
              :key="index"
              :src="image"
              alt="Thumbnail Image"
              @click="setCurrentImage(image)"
              :class="{ 'active-thumbnail': currentImage === image }"
            />
          </div>
        </div>

        <!-- Product Information Section -->
        <div class="product-info">
          <h1>{{ product.title }}</h1>
          <p class="sku">Mã sản phẩm: {{ product.sku }}</p>
          <p class="price">€{{ product.price }}</p>
          <ul class="product-features">
            <li v-for="(feature, index) in product.features" :key="index">
              {{ feature }}
            </li>
          </ul>

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
            <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
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
  </div>
</template>

<script setup>
import { ref } from "vue";

const product = {
  title: "Apple iPhone 16 128GB Teal",
  sku: "195949822551",
  price: 2049,
  features: [
    "Apple Intelligence - pomaže pisati, izražavati se i obavljati stvari bez napora",
    "Preuzmite potpunu kontrolu kamere",
    "A18 superčip skače dvije generacije ispred A16 Bionic čipa u iPhoneu 15",
    "Dulje trajanje baterije uz reprodukciju videozapisa do 22 sata",
    "iPhone 16 ima čvrst aluminijski dizajn za zrakoplove",
  ],
};

const productImages = [
  "https://via.placeholder.com/500",
  "https://via.placeholder.com/500",
  "https://via.placeholder.com/500",
  "https://via.placeholder.com/500",
];
const currentImage = ref(productImages[0]);

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
};

const infoRows = [
  { label: "Code", value: "12LN003SZY" },
  { label: "Color", value: "Indeterminate" },
  { label: "Brand", value: "Lenovo" },
  { label: "Guarantee", value: "36 months" },
];


</script>

<style scoped>
@import url(ProductDetailPage.scss);
</style>
