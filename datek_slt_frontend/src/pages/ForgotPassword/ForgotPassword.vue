<template>
  <div class="forgot-password-page">
    <div class="container">
      <div class="page-main">
        <div class="forgot-password-container">
          <div class="block-forgot-password">
            <div class="text-center bg-white border-0 mt-4">
              <h4 class="text-black">Quên mật khẩu</h4>
            </div>
            <form @submit.prevent="sendForgotPassword" id="login-form">
              <div class="mt-4 text-success text-center" v-if="successMessage">
                <i
                  class="fa-regular fa-circle-check mb-4"
                  style="font-size: 60px"
                ></i>
                <p class="fw-bold fs-4">{{ successMessage }}</p>
              </div>
              <div v-else>
                <div class="mt-1 text-danger text-center" v-if="errorMessage">
                  <i
                    class="fa-regular fa-circle-xmark mb-3"
                    style="font-size: 30px"
                  ></i>
                  <p class="fw-bold fs-6">{{ errorMessage }}</p>
                </div>
                <div class="form-group row mb-1 mt-4">
                  <label for="" class="fw-bold mb-3">Vui lòng nhập email</label>
                  <div class="col-md-12">
                    <input
                      type="text"
                      class="form-control email-input p-2"
                      v-model="model.email"
                    />
                    <small class="text-danger" v-if="errors.email">{{
                      errors.email[0]
                    }}</small>
                  </div>
                </div>
                <div class="form-group row mb-3 mt-4">
                  <div class="col-md-12">
                    <button
                      type="submit"
                      class="btn p-3 w-100 fw-bold text-white d-flex justify-content-center align-items-center continue-btn"
                      :disabled="!isFilledForm"
                    >
                      <div
                        class="spinner-grow text-light"
                        v-if="isLoading"
                        style="width: 2rem; height: 2rem"
                        role="status"
                      >
                        <span class="visually-hidden">Loading...</span>
                      </div>
                      <span v-if="!isLoading">Tiếp tục</span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useStore } from "vuex";

const model = ref({
  email: "",
});
const successMessage = ref(null);
const errorMessage = ref(null);
const errors = ref({});
const store = useStore();
const isFilledForm = ref(false);
const isLoading = ref(false);

watch(model.value, (value) => {
  isFilledForm.value = true;
  errorMessage.value = null;
  if (value.email === "") {
    isFilledForm.value = false;
    errors.value = {};
  }
});

const sendForgotPassword = () => {
  isLoading.value = true;
  store
    .dispatch("auth/sendForgotPasswordEmail", model.value)
    .then((response) => {
      successMessage.value = response.data.message;
      errorMessage.value = null;
      isLoading.value = false;
    })
    .catch((error) => {
      if (error.response.status === 422) {
        errors.value = error.response.data.errors;
        isLoading.value = false;
      } else if (error.response.status === 500) {
        errorMessage.value = error.response.data.message;
        successMessage.value = null;
        isLoading.value = false;
      }
    });
};
</script>

<style lang="scss" scoped>
.email-input {
  height: 50px;
  outline: none;
  border-radius: 2px;
}
.continue-btn {
  background: #4e43d8;
  border-radius: 10px;

  &:hover {
    background: #4e43d8;
  }
}

.forgot-password-page {
  background: #f9f9f9;
  height: 100vh;
  box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.15);

  .forgot-password-container {
    max-width: 540px;
    width: 100%;
    margin: 100px auto;
  }

  .block-forgot-password {
    background: #ffffff;
    padding: 30px 40px;
  }
}
</style>
