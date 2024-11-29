<template>
  <div>
    <my-modal
      @clickTo="deleteUser()"
      idModal="deleteUserModal"
      bgColor="danger"
    >
      <template v-slot:title>Delete user</template>
      <h6 class="text-dark text-center fs-5 mt-4">
        Are you sure, you want to delete this user?
      </h6>
      <template v-slot:buttonName>Delete</template>
    </my-modal>

    <div v-if="user">
      <router-link :to="{ name: 'admin.users' }" class="btn btn-danger fw-bold">
        <i class="fa-solid fa-arrow-left"></i>
        BACK
      </router-link>
      <div
        class="view-container w-100 shadow d-flex justify-content-between rounded"
      >
        <div class="view-left-container">
          <ul class="view-left-list">
            <li>
              <router-link
                :to="{ name: 'admin.users.mainProfile' }"
                class="view-left-item text-dark"
                :class="{
                  selected: $route.name === 'admin.users.mainProfile',
                }"
              >
                <i class="fa-solid fa-user"></i>
                <span class="ml-1">Profile</span>
              </router-link>
            </li>
            <li>
              <router-link
                :to="{ name: 'admin.users.sendEmail' }"
                class="view-left-item text-dark"
                :class="{
                  selected: $route.name === 'admin.users.sendEmail',
                }"
              >
                <i class="fa-solid fa-pen"></i>
                <span class="ml-1">Compose</span>
              </router-link>
            </li>

            <li
              v-if="user.role_as !== 1"
              class="text-danger mt-4 view-left-item-delete"
            >
              <button
                type="button"
                class="delete-user-btn fw-bold fs-6 text-danger"
                data-bs-toggle="modal"
                data-bs-target="#deleteUserModal"
              >
                <span>Delete User</span>
              </button>
            </li>
          </ul>
        </div>

        <div class="border bg-body-secondary view-border rounded-5"></div>

        <div class="view-right-container d-flex flex-column">
          <router-view></router-view>
        </div>
      </div>
    </div>
    <div v-else>
      <StateLoading />
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import StateLoading from "@/components/Loading/Loading.vue";
import MyModal from "@/components/Modals/Modal.vue";

const store = useStore();
const router = useRouter();
const id = router.currentRoute.value.params.id;
store.dispatch("users/resetUser");

onMounted(() => store.dispatch("users/fetchUserById", id));
const user = computed(() => store.getters["users/getUserById"]);
const deleteUser = () => {
  store.dispatch("users/deleteUser", id).then(() => {
    router.push({ name: "admin.users" });
    $("#deleteUserModal").modal("hide");
  });
};

onBeforeUnmount(() => {
  store.dispatch("users/resetUser");
});
</script>

<style lang="scss" scoped></style>
