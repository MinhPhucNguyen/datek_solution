<template>
  <div class="customer-account-login">
    <div class="container">
      <div class="page-main">
        <div class="login-container">
          <div class="block-customer-login">
            <form @submit.prevent="loginSubmit()" id="login-form">
              <fieldset class="fieldset login">
                <h1>DATEK</h1>
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
                      <div
                          class="spinner-grow text-light"
                          v-if="isLoading"
                          style="width: 2rem; height: 2rem"
                          role="status"
                      >
                        <span class="visually-hidden">Loading...</span>
                      </div>
                      <span v-else> Đăng nhập </span>
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

const isLoading = ref(false);
const store = useStore();
const credentials = ref({
  email: "",
  password: "",
});
const router = useRouter();
const errors = ref(null);

const loginSubmit = () => {
  isLoading.value = true;
  store
      .dispatch("auth/login", credentials.value)
      .then(() => {
        isLoading.value = false;
        store.getters["auth/getUser"] && store.getters["auth/isAdmin"]
            ? router.push({name: "Admin"})
            : router.push({name: "AdminLogin"});
      })
      .catch((e) => {
        if (e) {
          errors.value = e.response.data.errors;
          if (!errors.value.email && !errors.value.password) {
            errors.value = {global: e.response.data.errors};
          }
          isLoading.value = false;
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
@import url(AdminLoginPage.scss);
</style>
