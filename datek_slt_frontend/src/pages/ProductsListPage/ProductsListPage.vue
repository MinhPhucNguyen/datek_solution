<template>
  <div class="product-list-page">
    <div class="container">
      <h2>{{ categoryName?.category_name }}</h2>
      <div v-if="loading" class="loading">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div v-else-if="productsList.length === 0" class="no-products">
        Không có sản phẩm nào trong danh mục này.
      </div>
      <div class="product-list" v-else>
        <ProductItemComponent
          v-for="(product, index) in productsList"
          :key="index"
          :product="product"
        />
      </div>
    </div>

    <CartSideBar
      :isCartVisible="isCartVisible"
      :cartItems="cartItems"
      @updateCart="cartItems = $event"
      @closeCart="closeCart"
    />
  </div>
</template>

<script setup>
import ProductItemComponent from "@/components/ProductItemComponent/ProductItemComponent.vue";
import CartSideBar from "@/components/SidebarCartComponent/SidebarCartComponent.vue";
import { ref, onBeforeMount, watch, computed, onMounted} from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const router = useRouter();
const productsList = ref([]);
const categoryName = ref("");
const slug = ref(router.currentRoute.value.params.slug);
const loading = ref(true);

const store = useStore();
const isCartVisible = computed(() => store.getters["cart/isCartVisible"]);
const cartItems = computed(() => store.getters["cart/getCartItems"]);

onMounted(() => {
  store.dispatch("cart/fetchCart");
});

const closeCart = () => {
  store.dispatch("cart/toggleCartVisibility", false);
};

const fetchProducts = async (slug) => {
  try {
    const response = await axios.get(`/category-products/${slug}`);
    productsList.value = response.data.products.products;
    categoryName.value = response.data.category;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

onBeforeMount(async () => {
  fetchProducts(slug.value);
});

watch(
  () => router.currentRoute.value.params.slug,
  (newSlug) => {
    slug.value = newSlug;
    loading.value = true;
    fetchProducts(slug.value);
  }
);
</script>

<style scoped>
@import url(ProductsListPage.scss);

.loading {
  font-size: 18px;
  text-align: center;
  margin-top: 20px;
}
</style>
