<template>
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
              <a href="#">
                <font-awesome-icon :icon="['fas', 'cart-shopping']" />
              </a>
            </div>
            <div class="header-content-item">
              <router-link to="/customer/login">
                <font-awesome-icon :icon="['fas', 'user']" />
              </router-link>
            </div>
            <div class="header-content-item top-search">
              <form action="">
                <div class="actions">
                  <button type="submit" title="Search" class="action search">
                    <span>Tìm kiếm</span>
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
</template>

<script setup></script>

<style scoped lang="scss">
@import url(HeaderComponent.scss);
</style>
