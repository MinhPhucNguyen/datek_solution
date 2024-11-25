<template>
  <div>
    <div class="content-item user-profile">
      <div class="container">Account Page</div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useStore } from "vuex";

const store = useStore();
const user = ref({});

/**
 * TODO: FETCH USER WHEN AUTHENTICATED
 */
// user.value = store.getters["auth/getUser"];
user.value = store.getters["users/getUserById"];
</script>

<style scoped>
@import url("./AccountPage.scss");
</style>
