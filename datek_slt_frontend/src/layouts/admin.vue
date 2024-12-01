<template>
  <div>
    <my-modal @clickTo="logout()" idModal="logoutModal" bgColor="danger">
      <template v-slot:title>Logout</template>
      <h6 class="text-dark text-center fs-5 mt-4">
        Bạn có chắc chắn muốn đăng xuất?
      </h6>
      <template v-slot:buttonName>Đăng xuất</template>
    </my-modal>

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
  </div>
</template>

<script setup>
import "../../public/admin/css/bootstrap.min.css";
import "../../public/admin/css/sb-admin-2.min.css";
import MainSidebar from "@/components/Admin/Sidebar.vue";
import MainTopBar from "@/components/Admin/Topbar.vue";
import MainFooter from "@/components/Admin/Footer.vue";
import MyModal from "@/components/Modals/Modal.vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { computed } from "vue";
const store = useStore();
const router = useRouter();

const logout = () => {
   store.dispatch("auth/logout").then(() => {
      router.push({ name: "login" });
      $("#logoutModal").modal("hide");
   });
};


const user = computed(() => store.getters["auth/getUser"]);
</script>

<style scoped>
@import url("../../public/admin/css/adminstyles.scss");
* {
  font-size: 15px;
}
</style>
