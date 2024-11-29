<template>
  <div id="wrapper" v-if="user">
    <!-- Sidebar -->
    <MainSidebar />
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <MainTopBar :user="user" />
        <div class="main">
          <div class="container-fluid">
            <div class="row mt-4">
              <router-view></router-view>
            </div>
          </div>
        </div>
      </div>
      <MainFooter />
    </div>
  </div>
</template>

<script setup>
import "../../public/admin/css/bootstrap.min.css";
import "../../public/admin/css/sb-admin-2.min.css";
import MainSidebar from "@/components/Admin/Sidebar.vue";
import MainTopBar from "@/components/Admin/Topbar.vue";
import MainFooter from "@/components/Admin/Footer.vue";
import { useStore } from "vuex";
import { computed } from "vue";
const store = useStore();

const user = computed(() => store.getters["auth/getUser"]);
</script>

<style scoped>
@import url("../../public/admin/css/adminstyles.scss");
* {
  font-size: 15px;
}
</style>
