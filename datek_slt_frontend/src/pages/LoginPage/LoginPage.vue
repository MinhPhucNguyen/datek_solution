<template>
  <div class="customer-account-login">
    <div class="container">
      <div class="page-main">
        <div class="login-container">
          <div class="block-customer-login">
            <form @submit.prevent="loginSubmit()" id="login-form">
              <div class="block-title">
                <span class="active title">Đăng nhập</span>
                <router-link class="title" to="/customer/account/create"
                >Đăng ký
                </router-link
                >
              </div>
              <fieldset class="fieldset login">
                <div class="mt-1 text-danger text-center bg-white" v-if="errors && errors.global">
                  <i class="fa-regular fa-circle-xmark mb-3" style="font-size: 30px"></i>
                  <p class="fs-6">{{ errors.global }}</p>
                </div>
                <div class="field email required">
                  <div class="control">
                    <input
                        name="email"
                        v-model="credentials.email"
                        autocomplete="off"
                        id="email"
                        type="email"
                        class="input-text"
                        title="Email"
                        placeholder="Email"
                        @input="clearError('email')"
                    />
                    <small v-if="errors && errors.email" class="text-danger">
                      {{ errors.email[0] }}
                    </small>
                  </div>
                </div>
                <div class="field password required">
                  <div class="control">
                    <input
                        v-model="credentials.password"
                        name="password"
                        type="password"
                        autocomplete="off"
                        class="input-text"
                        id="password"
                        title="Password"
                        placeholder="Mật khẩu"
                        @input="clearError('password')"
                    />
                    <small v-if="errors && errors.password" class="text-danger">
                      {{ errors.password[0] }}
                    </small>
                  </div>
                </div>
                <div class="actions-toolbar">
                  <div class="primary">
                    <button
                        type="submit"
                        class="action login primary"
                        name="send"
                        id="send2"
                    >
                      <span>Đăng nhập</span>
                    </button>
                    <a class="action remind" href="#"
                    ><span>Quên mật khẩu</span></a
                    >
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
const credentials = ref({
  email: "",
  password: "",
});
const router = useRouter();
const errors = ref(null);

const loginSubmit = () => {
  store
      .dispatch("auth/login", credentials.value)
      .then(() => {
        store.getters["auth/getUser"] && store.getters["auth/isAdmin"]
            ? router.push({name: "admin.dashboard"})
            : router.push({name: "home"});
      })
      .catch((e) => {
        if (e) {
          errors.value = e.response.data.errors;
          if (!errors.value.email && !errors.value.password) {
            errors.value = {global: e.response.data.errors};
          }
          console.error(e);
        }
      });
};

const clearError = (field) => {
  if (errors.value) {
    delete errors.value[field];
    errors.value = null;
  }
};

</script>

<style scoped>
@import url(LoginPage.scss);
</style>
