<template>
  <div>
    <HeaderComponent />
    <router-view></router-view>
    <FooterComponent v-if="!$route.meta.hideFooter" />
  </div>
</template>

<script setup>
import HeaderComponent from "@/components/HeaderComponent/HeaderComponent.vue";
import FooterComponent from "@/components/FooterComponent/FooterComponent.vue";
</script>

<style scoped></style>
