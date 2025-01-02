<template>
  <div class="col-md-12">
    <ToastMessage
      :message="successMessage ? successMessage : errorMessage"
      :type="errorMessage ? 'danger' : 'success'"
    />

    <div class="card p-0">
      <div class="card-header bg-transparent">
        <div class="d-inline-block fw-bold text-dark fs-4">
          Thêm mã giảm giá
        </div>
        <router-link
          :to="{ name: 'admin.sales' }"
          class="btn btn-danger fw-bold float-right"
        >
          <i class="fa-solid fa-arrow-left"></i>
          Quay lại
        </router-link>
      </div>
      <div class="card-body p-3 mt-0">
        <form @submit.prevent="createCouponCode">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="coupon_name">Tên chương trình</label>
              <input
                type="text"
                name="coupon_name"
                class="form-control"
                v-model="model.sale_name"
              />
              <small class="text-danger" v-if="errors.coupon_name">{{
                errors.coupon_name[0]
              }}</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="coupon_code">Mã giảm giá</label>
              <input
                type="text"
                name="coupon_code"
                class="form-control"
                v-model="model.coupon_code"
              />
              <small class="text-danger" v-if="errors.coupon_code">{{
                errors.coupon_code[0]
              }}</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="discount_amount">Mức giảm</label>
              <input
                type="number"
                name="discount_amount"
                class="form-control"
                v-model="model.sale_percentage"
              />
              <small class="text-danger" v-if="errors.sale_percentage">{{
                errors.sale_percentage[0]
              }}</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="status">Trạng thái</label>
              <select
                name="status"
                class="form-control"
                v-model="model.is_active"
              >
                <option value="1">Áp dụng</option>
                <option value="0">Không áp dụng</option>
              </select>
              <small class="text-danger" v-if="errors.status">{{
                errors.status[0]
              }}</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="start_date">Bắt đầu</label>
              <input
                type="datetime-local"
                name="start_date"
                class="form-control"
                v-model="model.sale_begin_at"
              />
              <small class="text-danger" v-if="errors.sale_begin_at">{{
                errors.sale_begin_at[0]
              }}</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="end_date">Kết thúc</label>
              <input
                type="datetime-local"
                name="end_date"
                class="form-control"
                v-model="model.sale_end_at"
              />
              <small class="text-danger" v-if="errors.sale_end_at">{{
                errors.sale_end_at[0]
              }}</small>
            </div>

            <div>
              <button
                type="submit"
                name="create_btn"
                class="btn btn-success p-3 fw-bold float-end"
                :disabled="isInvalidForm"
              >
                Tạo mã giảm giá
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import axios from "axios";

const model = ref({
  sale_name: "",
  coupon_code: "",
  sale_begin_date: "",
  sale_end_date: "",
  sale_percentage: 0,
  is_active: 1,
});

const errors = ref({});
const isInvalidForm = computed(() => {
  return (
    !model.value.sale_name ||
    !model.value.coupon_code ||
    model.value.sale_percentage <= 0
  );
});
const successMessage = ref("");
const errorMessage = ref("");

const createCouponCode = async () => {
  await axios
    .post("/sales/create-coupon", model.value)
    .then((response) => {
      successMessage.value = response.data.message;
      errorMessage.value = "";
      $(".toast").toast("show");
    })
    .catch((error) => {
      errors.value = error.response.data.errors;
      console.error(error.response.data.errors);
    });
};

watch(model.value, () => {
  isInvalidForm.value =
    !model.value.sale_name ||
    !model.value.coupon_code ||
    model.value.sale_percentage <= 0;
});
</script>

<style lang="scss" scoped></style>
