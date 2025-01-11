<template>
  <div class="customer-account-login">
    <ToastMessage
      :message="successMessage ? successMessage : errorMessage"
      :type="errorMessage ? 'danger' : 'success'"
    />

    <div class="container">
      <div class="page-main">
        <div class="login-container">
          <div class="block-customer-login">
            <form @submit.prevent="loginSubmit()" id="login-form">
              <div class="block-title">
                <span class="active title">Đăng nhập</span>
                <router-link class="title" to="/customer/account/create"
                  >Đăng ký
                </router-link>
              </div>
              <fieldset class="fieldset login">
                <div
                  class="mt-1 text-danger text-center bg-white"
                  v-if="errors && errors.global"
                >
                  <i
                    class="fa-regular fa-circle-xmark mb-3"
                    style="font-size: 30px"
                  ></i>
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
                      <span v-else>Đăng nhập</span>
                    </button>
                    <router-link
                      :to="{ name: 'forgotPassword' }"
                      class="action remind"
                      href="#"
                      ><span>Quên mật khẩu?</span></router-link
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
import { ref, onBeforeMount } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import ToastMessage from "@/components/Toast/Toast.vue";

const store = useStore();
const credentials = ref({
  email: "",
  password: "",
});
const router = useRouter();
const errors = ref(null);
const successMessage = ref(null);
const errorMessage = ref(null);
const isLoading = ref(false);

const loginSubmit = () => {
  isLoading.value = true;
  store
    .dispatch("auth/login", credentials.value)
    .then((response) => {
      const redirectPath = localStorage.getItem("redirectAfterLogin");
      if (store.getters["auth/getUser"] && store.getters["auth/isAdmin"]) {
        router.push({ name: "admin.dashboard" });
        successMessage.value = response.data.message;
        $(".toast").toast("show");
      } else if (redirectPath) {
        localStorage.removeItem("redirectAfterLogin");
        router.push(redirectPath);
        successMessage.value = response.data.message;
        $(".toast").toast("show");
      } else {
        successMessage.value = response.data.message;
        $(".toast").toast("show");
        isLoading.value = false;
        setTimeout(() => {
          router.push({ name: "home" });
        }, 3000);
      }
    })
    .catch((e) => {
      if (e) {
        errors.value = e.response.data.errors;
        isLoading.value = false;
        if (!errors.value.email && !errors.value.password) {
          errors.value = { global: e.response.data.errors };
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

onBeforeMount(() => {
  window.scrollTo(0, 0);
});
</script>

<style scoped>
@import url(LoginPage.scss);
</style>
