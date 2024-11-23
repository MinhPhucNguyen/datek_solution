<template>
  <div class="customer-account-register">
    <div class="container">
      <div class="page-main">
        <div class="register-container">
          <div class="block-customer-register">
            <h3 class="block-title">Đăng ký</h3>
            <form @submit.prevent="registerSubmit()" id="register-form">
              <fieldset class="fieldset register">
                <div class="field name">
                  <div class="control required">
                    <label>Tên</label>
                    <input
                        name="firstname"
                        v-model="formRegister.firstname"
                        autocomplete="off"
                        id="firstname"
                        type="text"
                        class="input-text"
                        title="Tên"
                        @input="clearError('firstname')"
                    />
                    <small v-if="errors.firstname" class="text-danger">
                      {{ errors.firstname[0] }}
                    </small>
                  </div>
                  <div class="control required">
                    <label>Họ</label>
                    <input
                        name="lastname"
                        v-model="formRegister.lastname"
                        autocomplete="off"
                        id="lastname"
                        type="text"
                        class="input-text"
                        title="Họ"
                        @input="clearError('lastname')"
                    />
                    <small v-if="errors.lastname" class="text-danger">
                      {{ errors.lastname[0] }}
                    </small>
                  </div>
                </div>
                <div class="field email required">
                  <div class="control">
                    <label>Email</label>
                    <input
                        name="email"
                        v-model="formRegister.email"
                        autocomplete="off"
                        id="email"
                        type="email"
                        class="input-text"
                        title="Email"
                        @input="clearError('email')"
                    />
                    <small v-if="errors.email" class="text-danger">
                      {{ errors.email[0] }}
                    </small>
                  </div>
                </div>
                <div class="field password required">
                  <div class="control">
                    <label>Mật khẩu</label>
                    <input
                        v-model="formRegister.password"
                        name="password"
                        type="password"
                        autocomplete="off"
                        class="input-text"
                        id="password"
                        title="Mật khẩu"
                        @input="clearError('password')"
                    />
                    <small v-if="errors.password" class="text-danger">
                      {{ errors.password[0] }}
                    </small>
                  </div>
                </div>
                <div class="field confirm-password required">
                  <div class="control">
                    <label>Xác nhận mật khẩu</label>
                    <input
                        v-model="formRegister.confirm_password"
                        name="confirm-password"
                        type="password"
                        autocomplete="off"
                        class="input-text"
                        id="confirm_password"
                        title="Xác nhận mật khẩu"
                        @input="clearError('confirm_password')"
                    />
                    <small v-if="errors.confirm_password" class="text-danger">
                      {{ errors.confirm_password[0] }}
                    </small>
                  </div>
                </div>
                <div class="field choice">
                  <input
                      type="checkbox"
                      name="show-password"
                      id="show-password"
                      class="checkbox"
                      title="Hiển thị mật khẩu"
                      @change="showPassword"
                  />
                  <label for="show-password" class="label"
                  ><span>Hiển thị mật khẩu</span></label
                  >
                </div>
                <div class="actions-toolbar">
                  <div class="primary">
                    <button
                        type="submit"
                        class="action register primary"
                        name="send"
                        id="send2"
                    >
                      <span>Đăng ký</span>
                    </button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {useStore} from "vuex";
import {useRouter} from "vue-router";

const store = useStore();
const router = useRouter();
const errors = ref({});
const formRegister = ref({
  firstname: "",
  lastname: "",
  password: "",
  email: "",
  confirm_password: "",
});

const registerSubmit = () => {
  store
      .dispatch("auth/register", formRegister.value)
      .then(() => {
        router.push({name: "home"});
      })
      .catch((e) => {
        errors.value = e.response.data.errors;
        console.error(e);
      });
};

const clearError = (field) => {
  delete errors.value[field];
};

const showPassword = (e) => {
  const password = document.getElementById("password");
  const confirm_password = document.getElementById("confirm_password");
  if (e.target.checked) {
    password.type = "text";
    confirm_password.type = "text";
  } else {
    password.type = "password";
    confirm_password.type = "password";
  }
};

</script>

<style scoped>
@import url(RegisterPage.scss);
</style>
