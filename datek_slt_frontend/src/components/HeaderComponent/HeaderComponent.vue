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
                  <font-awesome-icon :icon="['fas', 'list']"/>
                </a>
              </div>
              <div class="header-content-item">
                <a href="#">
                  <font-awesome-icon :icon="['fas', 'cart-shopping']"/>
                </a>
              </div>
              <div class="header-content-item user-menu" @click="toggleUserMenu">
                <font-awesome-icon :icon="['fas', 'user']"/>
                <div
                    v-if="isUserMenuVisible"
                    class="dropdown-menu"
                >
                  <template v-if="isLoggedIn">
                    <button @click="goToProfile">Trang cá nhân</button>
                    <button @click="logout">Đăng xuất</button>
                  </template>
                  <template v-else>
                    <router-link to="/customer/login">Đăng nhập</router-link>
                    <router-link to="/customer/account/create">Đăng ký</router-link>
                  </template>
                </div>
              </div>
              <div class="header-content-item top-search">
                <form action="">
                  <div class="actions">
                    <button type="submit" title="Search" class="action search">
                      <font-awesome-icon :icon="['fas', 'magnifying-glass']"/>
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
    <NavbarComponent/>
  </div>
</template>

<script setup>
import {ref} from "vue";
import NavbarComponent from "../NavbarComponent/NavbarComponent.vue";

const isUserMenuVisible = ref(false);

function toggleUserMenu() {
  isUserMenuVisible.value = !isUserMenuVisible.value;
}

</script>

<style scoped lang="scss">
@import url(HeaderComponent.scss);
</style>
