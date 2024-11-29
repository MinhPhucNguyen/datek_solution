<template>
  <div>
    <ToastMessage :message="successMessage" />

    <div class="col-md-12">
      <div class="card p-0">
        <div class="card-header bg-transparent">
          <div class="d-inline-block fw-bold text-dark fs-4">Cập nhật thông tin</div>
          <router-link
            :to="{ name: 'admin.users' }"
            class="btn btn-danger fw-bold float-right"
          >
            <i class="fa-solid fa-arrow-left"></i>
            Quay lại
          </router-link>
        </div>
        <div v-if="model" class="card-body p-4">
          <form @submit.prevent="editUser">
            <div class="row">
              <input type="hidden" name="user_id" />
              <div class="col-md-6 mb-3">
                <label for="firstname">Tên</label>
                <input
                  type="text"
                  name="firstname"
                  class="form-control"
                  v-model="model.firstname"
                />
                <small class="text-danger" v-if="errors.firstname">{{
                  errors.firstname[0]
                }}</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastname">Họ</label>
                <input
                  type="text"
                  name="lastname"
                  class="form-control"
                  v-model="model.lastname"
                />
                <small class="text-danger" v-if="errors.lastname">{{
                  errors.lastname[0]
                }}</small>
              </div>
              <div class="col-md-12 mb-3">
                <label for="">Giới tính</label>
                <div>
                  <div class="form-check d-inline-block">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="gender"
                      id="male"
                      value="1"
                      :checked="model.gender === 1"
                    />
                    <label class="form-check-label" for="male"> Nam </label>
                  </div>
                  <div style="width: 10px; display: inline-block"></div>
                  <div class="form-check d-inline-block">
                    <input
                      class="form-check-input"
                      name="gender"
                      type="radio"
                      id="female"
                      value="0"
                      :checked="model.gender === 0"
                    />
                    <label class="form-check-label" for="female">
                      Nữ
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email">Email</label>
                <input
                  type="email"
                  name="email"
                  class="form-control"
                  v-model="model.email"
                />
                <small class="text-danger" v-if="errors.email">{{
                  errors.email[0]
                }}</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="phone">Số điện thoại</label>
                <input
                  type="text"
                  name="phone"
                  class="form-control"
                  v-model="model.phone"
                />
                <small class="text-danger" v-if="errors.phone">{{
                  errors.phone[0]
                }}</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="address">Địa chỉ</label>
                <input
                  type="text"
                  name="address"
                  class="form-control"
                  v-model="model.address"
                />
                <small class="text-danger" v-if="errors.address">{{
                  errors.address[0]
                }}</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="password">Mật khẩu</label>
                <input
                  type="password"
                  name="password"
                  class="form-control"
                  placeholder="*Bỏ trống nếu không muốn thay đổi mật khẩu"
                  v-model="model.password"
                />
                <small class="text-danger" v-if="errors.password">{{
                  errors.password[0]
                }}</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input
                  type="password"
                  name="confirm_password"
                  class="form-control"
                  placeholder="*Bỏ trống nếu không muốn thay đổi mật khẩu"
                  v-model="model.confirm_password"
                />
                <small class="text-danger" v-if="errors.confirm_password">{{
                  errors.confirm_password[0]
                }}</small>
              </div>
              <div class="col-md-6 mb-3">
                <label for="role_as">Vai trò</label>
                <select name="role_as" class="form-control">
                  <option value="">--Chọn vai trò--</option>
                  <option value="admin" :selected="model.role_as === 1">
                    Quản trị viên
                  </option>
                  <option value="user" :selected="model.role_as === 0">
                    Khách hàng
                  </option>
                </select>
                <small class="text-danger" v-if="errors.role_as">{{
                  errors.role_as[0]
                }}</small>
              </div>
              <div>
                <button
                  name="create_btn"
                  class="btn btn-success p-3 float-end fw-bold"
                >
                  <div
                    class="spinner-border"
                    role="status"
                    style="width: 24px; height: 24px; margin-right: 10px"
                    v-if="isLoading"
                  >
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  Lưu thay đổi
                </button>
              </div>
            </div>
          </form>
        </div>
        <div v-else>
          <StateLoading />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import StateLoading from "@/components/Loading/Loading.vue";
import ToastMessage from "@/components/Toast/Toast.vue";

const store = useStore();
const router = useRouter();
const id = router.currentRoute.value.params.id;
const model = ref();
const successMessage = ref("");
const errors = ref({});
const isLoading = ref(false);

store.dispatch("users/resetUser");

onMounted(() => {
  store.dispatch("users/fetchUserById", id).then(() => {
    model.value = store.getters["users/getUserById"];
  });
});

const editUser = () => {
  isLoading.value = true;
  store
    .dispatch("users/editUser", { id, model: model.value })
    .then((response) => {
      successMessage.value = response.data.message;
      $(".toast").toast("show");
      isLoading.value = false;
      setTimeout(() => {
        router.push({ name: "admin.users" });
      }, 2000);
    })
    .catch((e) => {
      if (e.response) {
        isLoading.value = false;
        errors.value = e.response.data.errors;
      }
    });
};

onBeforeUnmount(() => {
  store.dispatch("users/resetUser");
});
</script>

<style></style>
