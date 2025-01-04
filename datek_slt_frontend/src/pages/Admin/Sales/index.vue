<template>
  <div>
    <ToastMessage
      :message="successMessage ? successMessage : errorMessage"
      :type="errorMessage ? 'danger' : 'success'"
    />

    <div v-if="salesList" class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent d-flex align-items-center">
          <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">
            Danh sách mã giảm giá
          </div>
          <div>
            <router-link
              :to="{ name: 'admin.sales.create' }"
              class="btn btn-success fw-bold float-right ml-3"
            >
              <i class="fa-solid fa-plus"></i>
              Thêm mã giảm mới
            </router-link>
          </div>
        </div>
        <div class="card-body mt-0">
          <table class="table table-bordered table-striped text-dark fw-bold">
            <thead>
              <tr class="text-dark">
                <th class="text-center">ID</th>
                <th class="text-center">Tên chương trình giảm giá</th>
                <th class="text-center">Mã giảm giá</th>
                <th class="text-center">Giảm</th>
                <th class="text-center">Bắt đầu</th>
                <th class="text-center">Kết thúc</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody id="body-table">
              <tr v-for="sale in salesList" :key="sale.id">
                <td class="text-center">{{ sale.id }}</td>
                <td class="text-center">{{ sale.sale_name }}</td>
                <td class="text-center">{{ sale.coupon_code }}</td>
                <td class="text-center">{{ sale.sale_percentage }}%</td>
                <td class="text-center">{{ sale.sale_begin_at }}</td>
                <td class="text-center">{{ sale.sale_end_at }}</td>
                <td
                  class="text-center"
                  :class="sale.is_active === 1 ? 'text-success' : 'text-danger'"
                >
                  {{ sale.is_active ? "Áp dụng" : "Không áp dụng" }}
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
                        <router-link
                          :to="{
                            name: 'admin.sales.edit',
                            params: { id: sale.id },
                          }"
                          class="dropdown-item mb-3 fs-6 text-success bg-white"
                        >
                          <i class="fa-solid fa-user-pen"></i>
                          <span class="ml-2">Sửa</span>
                        </router-link>
                      </li>
                      <li>
                        <button
                          type="button"
                          class="dropdown-item fs-6 text-danger bg-white"
                          data-bs-toggle="modal"
                          :data-bs-target="`#deleteConfirmModal${sale.id}`"
                        >
                          <i class="fa-solid fa-trash"></i>
                          <span class="ml-2">Xóa</span>
                        </button>
                      </li>
                    </ul>
                  </div>
                </td>

                <!-- Modal Delete Confirm -->
                <my-modal
                  @clickTo="deleteDiscount(sale.id)"
                  :idModal="`deleteConfirmModal${sale.id}`"
                  bgColor="danger"
                >
                  <template v-slot:title>Xóa mã giảm giá</template>
                  <h6 class="text-dark text-center fs-5 mt-4">
                    Bạn có chắc chắn muốn xóa mã giảm giá này không?
                  </h6>
                  <template v-slot:buttonName>Xóa</template>
                </my-modal>
              </tr>
              <tr v-if="isLoading && !salesList.length">
                <td colspan="10" class="text-center">
                  <stateLoading />
                </td>
              </tr>
              <tr v-if="isNotFound && !isLoading">
                <td colspan="10" class="text-center">Không tìm thấy bản ghi</td>
              </tr>
            </tbody>
          </table>
          <div class="pagination">
            <Pagination
              :pagination="pagination"
              @pagination-page="getSalesList"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onBeforeMount, computed, watch } from "vue";
import MyModal from "@/components/Modals/Modal.vue";
import stateLoading from "@/components/Loading/Loading.vue";
import Pagination from "@/components/Pagination/index.vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import axios from "axios";

const salesList = ref({});
const pagination = ref({});
const isLoading = ref(false);
const searchInput = ref("");
const successMessage = ref(null);
const errorMessage = ref(null);

const isNotFound = computed(() => {
  return salesList.value.length === 0 ? true : false;
});

const getSalesList = (page = 1) => {
  isLoading.value = true;
  axios
    .get(`/sales?page=${page}`)
    .then((response) => {
      salesList.value = response.data.data;
      pagination.value = {
        currentPage: response.data.current_page,
        lastPage: response.data.last_page,
      };
    })
    .catch((err) => {
      console.error(err);
    });
};

watch(searchInput, () => {
  getSalesList();
});

onBeforeMount(() => {
  getSalesList();
});

const deleteDiscount = (id) => {
  axios
    .delete(`/sales/${id}/delete`)
    .then((response) => {
      successMessage.value = response.data.message;
      errorMessage.value = "";
      $(".toast").toast("show");
      $(`#deleteConfirmModal${id}`).modal("hide");
      getSalesList();
    })
    .catch((error) => {
      errorMessage.value = error.response.data.message;
      successMessage.value = "";
      $(".toast").toast("show");
    });
};
</script>

<style lang="scss" scoped>
.checked-announce {
  padding: 0 1.5rem;
}
.product-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
}
</style>
