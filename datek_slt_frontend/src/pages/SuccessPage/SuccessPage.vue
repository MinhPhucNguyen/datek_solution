<template>
  <div class="success-container">
    <div class="container">
      <div class="success-content">
        <i class="fa-regular fa-circle-check text-success"></i>
        <p>
          <strong>Đặt hàng thành công!</strong>
        </p>
      </div>
      <div class="shipping-continue mt-5">
        <router-link to="/" style="color: #4e43d8" class="fw-bold"><i class="fa-solid fa-arrow-left me-2"></i>Tiếp tục mua hàng</router-link>
      </div>    
    </div>
  </div>
</template>

<script setup></script>

<style scoped>
.success-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 100px 0;

  .success-content{
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    gap: 30px;

    & > i {
      font-size: 80px;
    }

    & > p {
      font-size: 1.5rem;
    }
  }
}
</style>
