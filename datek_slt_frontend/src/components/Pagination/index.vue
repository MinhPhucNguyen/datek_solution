<template>
   <ul class="pagination d-flex align-items-center">
      <li>
         <a class="d-flex align-items-center" href="#" @click.prevent="previousPage"
            ><i class="fa-solid fa-angle-left mr-1"></i></a
         >
      </li>
      <li class="center d-flex align-items-center">
         <select
            name=""
            id=""
            class="number-page"
            @change="pageNumberSelect"
         >
            <option
               v-for="(numberPage, index) of props.pagination.lastPage"
               :key="index"
               :value="numberPage"
            >
               {{ numberPage }}
            </option>
         </select>
         <span class="ml-2 mr-2">of</span>
         <p class="m-0 fs-6">{{ props.pagination.lastPage }}</p>
      </li>
      <li>
         <a class="d-flex align-items-center" href="#" @click.prevent="nextPage"
            ><i class="fa-solid fa-angle-right ml-1"></i
         ></a>
      </li>
   </ul>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
   pagination: Object,
});

const emits = defineEmits(["pagination-page"]);

const previousPage = () => {
   if (props.pagination.currentPage > 1) {
      emits("pagination-page", props.pagination.currentPage - 1);
   }
};

const nextPage = () => {
   if (props.pagination.currentPage < props.pagination.lastPage) {
      emits("pagination-page", props.pagination.currentPage + 1);
   }
};

const pageNumberSelect = (e) => {
   emits("pagination-page", e.target.value);
};
</script>

<style scoped lang="scss">
.pagination li a {
   color: #1cc88a;
   font-size: 16px;
   text-decoration: none;
}

.center {
   margin: 0 14px;
}

.number-page {
   width: 40px;
   height: 40px;
   text-align: center;
   font-weight: 500;
   font-size: 16px;
}
</style>
