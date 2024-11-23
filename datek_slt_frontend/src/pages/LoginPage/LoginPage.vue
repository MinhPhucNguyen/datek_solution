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
                    />
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
                    />
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

const loginSubmit = () => {
  store
      .dispatch("auth/login", credentials.value)
      .then(() => {
        store.getters["auth/getUser"] && store.getters["auth/isAdmin"]
            ? router.push({name: "admin.dashboard"})
            : router.push({name: "home"});
      })
      .catch((e) => {
        console.error(e);
      });
};
</script>

<style scoped>
@import url(LoginPage.scss);
</style>
