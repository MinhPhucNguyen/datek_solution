<template>
  <div>
    <header class="page-header">
      <div class="container">
        <div class="header-main-inner">
          <div class="header-logo">
            <router-link to="/">
              <div class="logo">DATEK</div>
              <div>SOLUTIONS</div>
            </router-link>
          </div>
          <div class="header-content">
            <div class="header-content-inner">
              <div class="header-content-item">
                <a href="#">
                  <font-awesome-icon :icon="['fas', 'list']" />
                </a>
              </div>
              <div class="header-content-item">
                <a class="cart-icon" @click="toggleCart">
                  <font-awesome-icon :icon="['fas', 'cart-shopping']" />
                  <span v-if="itemQuantity != '0'" class="cart-badge">{{
                    itemQuantity
                  }}</span>
                </a>
              </div>
              <div
                class="header-content-item user-menu"
                @click="toggleUserMenu"
              >
                <font-awesome-icon :icon="['fas', 'user']" />
                <div v-if="isUserMenuVisible" class="user-dropdown-menu">
                  <template v-if="isLoggedIn">
                    <ul>
                      <li>
                        <router-link to="/customer/account/manage">
                          Trang cá nhân
                        </router-link>
                      </li>
                      <li @click="logout" class="logout-btn">Đăng xuất</li>
                    </ul>
                  </template>
                  <template v-else>
                    <router-link to="/customer/account/login"
                      >Đăng nhập</router-link
                    >
                    <router-link to="/customer/account/create"
                      >Đăng ký</router-link
                    >
                  </template>
                </div>
              </div>
              <div class="header-content-item top-search">
                <form action="">
                  <div class="actions">
                    <button type="submit" title="Search" class="action search">
                      <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
                    </button>
                  </div>
                  <div class="field search">
                    <div class="control">
                      <input
                        id="search"
                        type="text"
                        name="q"
                        value=""
                        class="input-text"
                        maxlength="128"
                        role="combobox"
                        aria-haspopup="false"
                        aria-autocomplete="both"
                        aria-expanded="false"
                        autocomplete="off"
                        data-block="autocomplete-form"
                      />
                      <div
                        id="search_autocomplete"
                        class="search-autocomplete"
                      ></div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <CartSidebar
      :isCartVisible="isCartVisible"
      :cartItems="cartItems"
      @updateCart="cartItems = $event"
      @closeCart="closeCart"
    />

    <NavbarComponent />
  </div>
</template>

<script setup>
import { computed, ref, onBeforeMount } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import NavbarComponent from "@/components/NavbarComponent/NavbarComponent.vue";
import CartSidebar from "@/components/SidebarCartComponent/SidebarCartComponent.vue";

const store = useStore();
const router = useRouter();
const isLoggedIn = computed(() => store.getters["auth/isAuthenticated"]);
const isCartVisible = ref(false);
const cartItems = ref([]);
const isUserMenuVisible = ref(false);

const itemQuantity = ref(0);

onBeforeMount(async () => {
  await store.dispatch("cart/fetchCart");

  itemQuantity.value = computed(() => store.getters["cart/totalQuantity"]);
});

function toggleUserMenu() {
  isUserMenuVisible.value = !isUserMenuVisible.value;
}

function toggleCart() {
  isCartVisible.value = true;
  cartItems.value = store.getters["cart/getCartItems"];
}

function closeCart() {
  isCartVisible.value = false;
  store.dispatch("cart/toggleCartVisibility", false);
}

const logout = () => {
  store.dispatch("auth/logout").then(() => {
    router.push({ name: "login" });
  });
};
</script>

<style scoped lang="scss">
@import url(HeaderComponent.scss);
</style>
