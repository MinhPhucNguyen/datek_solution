<template>
  <div class="container-fluid p-1">
    <div class="d-flex">
      <i class="mdi mdi-home text-muted hover-cursor"></i>
      <p class="text-muted mb-0 hover-cursor">&nbsp;Dashboard&nbsp;/&nbsp;</p>
      <!-- <p class="text-primary mb-0 hover-cursor">Analytics</p> -->
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h4 class="mb-0 text-gray-800 fw-bold">Dashboard</h4>
    </div>
    <div class="row pl-3 mt-2">
      <div
        class="mr-3 bg-success bg-gradient rounded-4"
        style="height: 180px; width: 378px"
      >
        <router-link class="dashboard-card h-100 d-block text-decoration-none" :to="{ name: 'admin.users' }">
          <div
            class="h-100 w-100 d-flex justify-content-center align-items-center text-white flex-column fw-bolder"
            style="font-size: 60px"
          >
            <i class="fa-solid fa-users"></i>
            <h5 class="mt-3 fw-bold">Khách hàng</h5>
          </div>
        </router-link>
      </div>

      <div
        class="mr-3 bg-success bg-gradient rounded-4"
        style="height: 180px; width: 378px"
      >
        <a class="dashboard-card h-100 d-block text-decoration-none">
          <div
            class="h-100 w-100 d-flex justify-content-center align-items-center text-white flex-column"
            style="font-size: 60px"
          >
            <i class="fa-solid fa-box mr-2"></i>
            <h5 class="mt-3 fw-bold">Sản phẩm</h5>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style></style>
