<template>
   <div class="text-center" :class="{ width }">
      <div
         class="spinner-grow"
         style="width: 3rem; height: 3rem"
         :style="{ color: color }"
         role="status"
      >
         <span class="visually-hidden">Loading...</span>
      </div>
   </div>
</template>

<script>
export default {
   name: "stateLoading",
   props: {
      color: {
         type: String,
         default: "#1cc88a",
      },
      width: {
         type: String,
         default: "w-100",
      },
   },
};
</script>

<style></style>
