<template>
  <div class="navbar-block">
    <div class="container">
      <!-- Shop by Department -->
      <div
        class="navbar-item shop-by-department"
        @mouseover="hoverCategory('shop-by-department')"
      >
        <div class="d-flex align-items-center">
          <i class="fa-solid fa-bars fs-4"></i>
          <span class="ms-2 fs-6">Mua theo danh mục</span>
          <i class="fa-solid fa-chevron-down ms-3"></i>
        </div>

        <!-- Dropdown menu -->
        <div
          v-if="hoveredCategory === 'shop-by-department'"
          class="dropdown-menu-categories"
          @mouseenter="hoverCategory('shop-by-department')"
          @mouseleave="hoverCategory(null)"
        >
          <ul>
            <li
              v-for="category in categories"
              :key="category.id"
              @mouseenter="hoverSubCategory(category.id)"
              @mouseleave="hoverSubCategory(null)"
            >
              <div class="d-flex justify-content-between align-items-center">
                <router-link :to="'/' + category.slug">
                  {{ category.category_name }}
                </router-link>
                <i
                  v-if="category.sub_categories.length > 0"
                  class="fa-solid fa-chevron-right"
                ></i>
              </div>

              <!-- Sub-menu -->
              <div
                v-if="
                  hoveredSubCategory === category.id &&
                  category.sub_categories.length
                "
                class="sub-dropdown-menu"
              >
                <ul>
                  <li v-for="sub in category.sub_categories" :key="sub.id">
                    <router-link :to="'/' + sub.slug">
                      {{ sub.category_name }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from 'axios';

// Lưu trạng thái hover
const hoveredCategory = ref(null);
const hoveredSubCategory = ref(null);

const categories = ref([]);

const hoverCategory = (id) => {
  hoveredCategory.value = id;
};

const hoverSubCategory = (id) => {
  hoveredSubCategory.value = id;
};

axios.get('/categories')
  .then((response) => {
    categories.value = response.data.categories;
  })
  .catch((error) => {
    console.error(error);
  });

</script>

<style scoped>
@import url(NavbarComponent.scss);
</style>
