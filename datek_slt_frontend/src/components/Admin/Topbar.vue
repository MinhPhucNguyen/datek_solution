<template>
  <nav
    class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
  >
    <div class="w-100 d-flex align-items-center justify-content-end"></div>
  </nav>
</template>

<script>
export default {
  name: "MainTopBar",
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
  setup() {
    return {};
  },
};
</script>

<style></style>
