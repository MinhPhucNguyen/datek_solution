<template>
   <div class="col-md-12">
      <div class="card p-0">
         <div class="card-header bg-transparent">
            <div class="d-inline-block fw-bold text-dark fs-4">Thêm khách hàng</div>
            <router-link to="/admin/users" class="btn btn-danger fw-bold float-right">
               <i class="fa-solid fa-arrow-left"></i>
               Quay lại 
            </router-link>
         </div>
         <div class="card-body p-3 mt-0">
            <form @submit.prevent="createUser" method="POST" v-if="user">
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <label for="firstname">Tên</label>
                     <input
                        type="text"
                        name="firstname"
                        class="form-control"
                        v-model="user.firstname"
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
                        v-model="user.lastname"
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
                              v-model="user.gender"
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
                              v-model="user.gender"
                           />
                           <label class="form-check-label" for="female"> Nữ </label>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 mb-3">
                     <label for="email">Email</label>
                     <input type="text" name="email" class="form-control" v-model="user.email" />
                     <small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                  </div>
                  <div class="col-md-6 mb-3">
                     <label for="phone">Số điện thoại</label>
                     <input type="text" name="phone" class="form-control" v-model="user.phone" />
                     <small class="text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                  </div>
                  <div class="col-md-6 mb-3">
                     <label for="address">Địa chỉ</label>
                     <input
                        type="text"
                        name="address"
                        class="form-control"
                        v-model="user.address"
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
                        v-model="user.password"
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
                        v-model="user.confirm_password"
                     />
                     <small class="text-danger" v-if="errors.confirm_password">{{
                        errors.confirm_password[0]
                     }}</small>
                  </div>
                  <div class="col-md-6 mb-3">
                     <label for="role_as">Vai trò</label>
                     <select name="role_as" class="form-control" v-model="user.role_as">
                        <option value="">--Chọn vai trò--</option>
                        <option value="1">Quản trị viên</option>
                        <option value="0">Khách hàng</option>
                     </select>
                     <small class="text-danger" v-if="errors.role_as">{{
                        errors.role_as[0]
                     }}</small>
                  </div>
                  <div>
                     <button
                        type="submit"
                        name="create_btn"
                        class="btn btn-success p-3 fw-bold float-end"
                        :disabled="isInvalidForm"
                     >
                        Tạo
                     </button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const user = ref({
   firstname: "",
   lastname: "",
   gender: 1,
   email: "",
   phone: "",
   address: "",
   password: "",
   confirm_password: "",
   role_as: "",
});
const store = useStore();
const router = useRouter();
const errors = ref({});
const isInvalidForm = ref(true);

const createUser = () => {
   store
      .dispatch("users/createUsers", user.value)
      .then(() => {
         router.push({ name: "admin.users" });
      })
      .catch((e) => {
         if (e.response) {
            errors.value = e.response.data.errors;
         }
      });
};

/**
 * TODO: Watch user object, if user object has changed, set isInvalidForm to false
 */
watch(user.value, () => {
   isInvalidForm.value = false;
});
</script>

<style></style>
