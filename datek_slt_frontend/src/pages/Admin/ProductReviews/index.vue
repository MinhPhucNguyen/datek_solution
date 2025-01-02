<template>
  <div>
    <ToastMessage :message="successMessage" />

    <my-modal
      @clickTo="handleDeleteReview"
      idModal="deleteConfirmModal"
      bgColor="danger"
    >
      <template v-slot:title>Xóa đánh giá</template>
      <h6 class="text-dark text-center fs-5 mt-4">
        Bạn có chắc chắn muốn xóa đánh giá này?
      </h6>
      <template v-slot:buttonName>
        <div
          class="spinner-border"
          role="status"
          style="width: 24px; height: 24px; margin-right: 10px"
          v-if="isLoading"
        >
          <span class="visually-hidden">Loading...</span>
        </div>
        Xóa
      </template>
    </my-modal>

    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent d-flex align-items-center">
          <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">
            Danh sách đánh giá
          </div>
        </div>
        <div class="card-body m-0">
          <table class="table table-bordered table-striped">
            <thead>
              <tr class="text-dark">
                <th class="text-center">ID</th>
                <th class="text-center">Khách hàng</th>
                <th class="text-center">Nội dung đánh giá</th>
                <th class="text-center">Đánh giá</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="isLoading">
                <td colspan="6" class="text-center">
                  <state-loading />
                </td>
              </tr>
              <tr v-else-if="reviews.length === 0">
                <td colspan="6" class="text-center">Không có đánh giá nào!</td>
              </tr>

              <tr v-else v-for="review in reviews" :key="review.id">
                <td class="text-center">{{ review.id }}</td>
                <td class="text-center">{{ review.user.name }}</td>
                <td class="text-center">{{ review.review }}</td>
                <td class="text-center">
                  {{ review.rating }}
                </td>
                <td
                  class="text-center"
                  :class="review.status === 1 ? 'text-success' : 'text-danger'"
                >
                  {{
                    review.status === 1
                      ? "Cho phép hiển thị"
                      : "Không cho phép hiển thị"
                  }}
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <button
                      class="btn btn-success dropdown-toggle"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      Thao tác
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <button
                          type="button"
                          class="dropdown-item mb-3 fs-6 text-success bg-white"
                          @click="updateReviewStatus(review)"
                        >
                          <i class="fa-solid fa-check"></i>
                          <span class="ml-2">Hiển thị</span>
                        </button>
                      </li>
                      <li>
                        <button
                          type="button"
                          class="dropdown-item mb-3 fs-6 text-warning bg-white"
                          @click="updateReviewStatus(review)"
                        >
                          <i class="fa-solid fa-x"></i>
                          <span class="ml-2">Không hiển thị</span>
                        </button>
                      </li>
                      <li>
                        <button
                          type="button"
                          class="dropdown-item fs-6 text-danger bg-white"
                          @click="deleteReview(review)"
                        >
                          <i class="fa-solid fa-trash"></i>
                          <span class="ml-2">Xóa</span>
                        </button>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import axios from "axios";
import MyModal from "@/components/Modals/Modal.vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import stateLoading from "@/components/Loading/Loading.vue";

const reviews = ref([]);
const errors = ref(null);
const successMessage = ref(null);
const isLoading = ref(false);

const fetchReviews = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`products/reviews`);
    isLoading.value = false;
    reviews.value = response.data.data;
  } catch (error) {
    console.error(error);
  }
};
onBeforeMount(() => {
  fetchReviews();
});

const updateReviewStatus = async (review) => {
  try {
    const response = await axios.put(`products/reviews/${review.id}`, {
      status: review.status === 1 ? 0 : 1,
    });
    successMessage.value = response.data.message;
    $(".toast").toast("show");
    fetchReviews();
  } catch (error) {
    console.error(error);
  }
};

const deleteReview = () => {
  $("#deleteConfirmModal").modal("show");
};

const handleDeleteReview = async (review) => {
  isLoading.value = true;
  try {
    const response = await axios.delete(`products/reviews/${review.id}`);
    successMessage.value = response.data.message;
    $(".toast").toast("show");
    isLoading.value = false;
    fetchReviews();
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  $("#brandFormModal").on("hide.bs.modal", () => {
    errors.value = null;
  });
});
</script>

<style scoped></style>
