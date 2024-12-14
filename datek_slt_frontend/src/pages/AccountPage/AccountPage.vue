<template>
  <div>
    <div class="content-item user-profile">
      <div class="container">
        <div class="dashboard">
          <div class="sidebar">
            <ul>
              <li @click="changeContent('profile')">
                <a href="#"><i class="fa-solid fa-user"></i><span class="ms-3">Thông tin cá nhân</span></a>
              </li>
              <li @click="changeContent('address')">
                <a href="#"><i class="fa-solid fa-location-dot"></i><span class="ms-3">Địa chỉ</span></a>
              </li>
              <li @click="changeContent('orders')">
                <a href="#"><i class="fa-solid fa-file-invoice"></i><span class="ms-3">Đơn hàng</span></a>
              </li>
              <li class="text-danger" @click="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Đăng xuất</span>
              </li>
            </ul>
          </div>
          <div class="main-content">
            <div v-if="activeContent === 'profile'">
              <div v-if="activeContent === 'profile'">
                <div v-if="!isEditing">
                  <div class="header">
                    <h1>Xin chào {{ userData?.name || "User" }},</h1>
                  </div>
                  <div class="account-manager d-flex justify-content-between">
                    <div>
                      <h3>Quản lý thông tin cá nhân</h3>
                      <p class="mt-3">Name: {{ userData?.name || "User" }}</p>
                      <p>Email: {{ userData?.email }}</p>
                      <p>Phone: {{ userData?.phone || "Chưa có số điện thoại nào" }}</p>
                    </div>
                    <div>
                      <button class="btn edit-profile-btn" @click="startEditing">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Chỉnh sửa thông tin
                      </button>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <h3>Chỉnh sửa thông tin cá nhân</h3>
                  <form @submit.prevent="saveProfile">
                    <div class="form-group">
                      <label for="firstname">Tên</label>
                      <input type="text" id="firstname" v-model="formData.firstname" class="form-control"/>
                    </div>
                    <div class="form-group mb-3">
                      <label for="lastname">Họ</label>
                      <input type="text" id="lastname" v-model="formData.lastname" class="form-control"/>
                    </div>
                    <div class="form-group mb-3">
                      <label for="email">Email</label>
                      <input type="email" id="email" v-model="formData.email" class="form-control"/>
                    </div>
                    <div class="form-group mb-3">
                      <label for="phone">Số điện thoại</label>
                      <input type="text" id="phone" v-model="formData.phone" class="form-control"/>
                    </div>
                    <button type="submit" class="btn mt-3 me-3 save-profile-btn" :disabled="isLoading">
                      {{ isLoading ? "Đang lưu..." : "Lưu" }}
                    </button>
                    <button type="button" class="btn btn-secondary mt-3" @click="cancelEditing">Hủy</button>
                  </form>
                </div>
              </div>
            </div>
            <div v-if="activeContent === 'address'">
              <h2>Địa chỉ của bạn</h2>
              <div v-if="!isEditingAddress">
                <p>{{ userData?.address || "Không có địa chỉ nào, hay thêm địa chỉ." }}</p>
                <button class="btn btn-sm me-2 edit-address-btn" @click="startEditingAddress">Chỉnh sửa</button>
                <button class="btn btn-sm btn-danger" @click="clearAddress">Xóa</button>
              </div>
              <div v-else>
                <form @submit.prevent="saveProfile">
                  <input
                      type="text"
                      name="address"
                      v-model="formData.address"
                      class="form-control"
                  />
                  <button class="btn btn-sm save-address-btn mt-2 me-2" type="submit">Lưu</button>
                  <button class="btn btn-sm btn-secondary mt-2" @click="cancelEditingAddress">Hủy</button>
                </form>
              </div>
            </div>
            <div v-if="activeContent === 'orders'">
              <h2>Đơn hàng của bạn</h2>
              <p>Danh sách các đơn hàng của bạn sẽ được hiển thị ở đây.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, computed} from "vue";
import {useStore} from "vuex";
import {useRouter} from "vue-router";

const errors = ref({});
const store = useStore();
const router = useRouter();
const activeContent = ref("profile");
const isEditing = ref(false);

const isLoading = ref(false);
const formData = ref({
  firstname: "",
  lastname: "",
  email: "",
  phone: "",
  address: ""
});

const id = store.getters["auth/getUser"].id;
store.dispatch("users/fetchUserById", id);
const userData = computed(() => store.getters["users/getUserById"]);


const changeContent = (content) => {
  activeContent.value = content;
};

const startEditing = () => {
  isEditing.value = true;
  formData.value = {...userData.value};
};

const cancelEditing = () => {
  isEditing.value = false;
};

const saveProfile = () => {
  isLoading.value = true;
  store
      .dispatch("users/editUser", {id, model: formData.value})
      .then(() => {
        userData.value = {...formData.value};
        isEditing.value = false;
        isLoading.value = false;
        window.location.reload();
      })
      .catch((e) => {
        if (e.response) {
          isLoading.value = false;
          errors.value = e.response.data.errors;
        }
      });
};

const isEditingAddress = ref(false);
const startEditingAddress = () => {
  isEditingAddress.value = true;
  formData.value = {...userData.value};
};

const cancelEditingAddress = () => {
  isEditingAddress.value = false;
};


const logout = () => {
  store.dispatch("auth/logout").then(() => {
    router.push({name: "login"});
  });
};
</script>

<style scoped>
@import url(AccountPage.scss);
</style>