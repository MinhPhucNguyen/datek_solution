<template>
  <div
      class="toast align-items-center shadow"
      role="toast"
      aria-live="assertive"
      aria-atomic="true"
      :style="{ 'border-left-color': bgColor }"
  >
    <div class="toast-content d-flex p-0">
      <div class="d-flex align-items-center me-3 fs-5">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      <div class="toast-body p-0 fw-bold mw-50">
        <p class="m-0">{{ message }}</p>
      </div>
      <div class="close-btn">
        <button
            type="button"
            class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast"
            aria-label="Close"
        ></button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, defineProps } from "vue";

const props = defineProps({
  message: {
    type: String,
    default: "",
  },
  type: {
    type: String,
    default: "success",
  },
});

const bgColor = computed(() => {
  switch (props.type) {
    case "danger":
      return "#e74a3be0";
    case "success":
      return "#1cc889e0";
    default:
      return "#fff";
  }
});
</script>

<style scoped>
.toast {
  position: fixed;
  right: 30px;
  top: 100px;
  padding: 18px 30px 18px 15px;
  font-size: 14px;
  border-radius: 5px;
  border-left: 5px solid;
  animation: slideInLeft ease 0.3s;
  width: 350px;
  z-index: 9999;
  background-color: #ffffff;
}

.toast-body {
  max-width: 280px;
}

.close-btn {
  position: absolute;
  top: 20px;
  right: 6px;
  transform: translateY(-50%);
  cursor: pointer;
  outline: none;
}

.hide-toast {
  animation: slideOutLeft ease 0.3s;
}

@keyframes slideInLeft {
  from {
    transform: translateX(calc(100% + 30px));
  }
  to {
    transform: translateX(0);
  }
}

@keyframes slideOutLeft {
  from {
    transform: translateX(0);
  }
  to {
    transform: translateX(calc(100% + 30px));
  }
}
</style>
