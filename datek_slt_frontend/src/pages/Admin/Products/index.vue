<template>
  <div>
    <ToastMessage :message="successMessage" />

    <my-modal
      @clickTo="deleteMultiProduct"
      :idModal="'deleteConfirmModal'"
      bgColor="danger"
    >
      <template v-slot:title>Xóa người dùng</template>
      <h6 class="text-dark text-center fs-5 mt-4">
        Bạn có chắc chắn muốn xóa {{ checked.length }} sản phẩm mà bạn chọn
      </h6>
      <template v-slot:buttonName>Xóa</template>
    </my-modal>

    <div v-if="products" class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent d-flex align-items-center">
          <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">
            Danh sách sản phẩm
          </div>
          <div>
            <router-link
              :to="{ name: 'admin.products.create' }"
              class="btn btn-success fw-bold float-right ml-3"
            >
              <i class="fa-solid fa-plus"></i>
              Thêm sản phẩm mới
            </router-link>
          </div>
        </div>
        <div
          class="w-100 d-flex align-items-center justify-content-between mt-4 pl-4 pr-4"
        >
          <div
            class="w-25 m-0 d-flex align-items-center justify-content-between"
          >
            <div class="dropdown mt-3" v-if="checked.length > 0">
              <button
                class="btn btn-secondary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Đã chọn ({{ checked.length }})
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a
                    class="dropdown-item text-danger fs-6"
                    href="#"
                    @click.prevent="deleteConfirm"
                    >Xóa</a
                  >
                </li>
              </ul>
            </div>
          </div>

          <div class="w-25 mt-3 d-flex align-items-center">
            <i class="fa-solid fa-magnifying-glass fs-5 mr-2"></i>
            <input
              type="text"
              name="search"
              class="form-control small search-input border border-dark-subtletext-dark"
              placeholder="Tìm kiếm..."
              v-model="searchInput"
            />
          </div>
        </div>
        <div class="checked-announce" v-if="selectPage">
          <div v-if="selectAll" class="mb-3">
            Hiện tại bạn đang chọn tất cả
            <strong>{{ checked.length }}</strong> sản phẩm.
          </div>
          <div v-else>
            <span class="fs-6"
              >Bạn đã chọn <strong>{{ checked.length }} </strong> sản phẩm.
            </span>
            <p class="fs-6 mt-2">
              Bạn chọn muốn chọn tất cả <strong>{{ totalUser }} </strong> sản
              phẩm?
              <a
                href=""
                class="ml-2 text-success fw-bolder"
                @click.prevent="selectAllUsers"
                >Chọn tất cả</a
              >
            </p>
          </div>
        </div>
        <div class="card-body mt-0">
          <table class="table table-bordered table-striped text-dark fw-bold">
            <thead>
              <tr class="text-dark">
                <th class="text-center">
                  <input type="checkbox" v-model="selectPage" />
                </th>
                <th class="text-center">ID</th>
                <th class="text-center">Hình ảnh</th>
                <th class="text-center">Tên sản phẩm</th>
                <th class="text-center">Mã sản phẩm</th>
                <th class="text-center">Hãng</th>
                <th class="text-center">Số lượng</th>
                <th class="text-center">Giá bán</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody id="body-table">
              <tr v-for="product in products" :key="product.id">
                <td class="text-center">
                  <input
                    type="checkbox"
                    :value="product.id"
                    v-model="checked"
                  />
                </td>
                <td class="text-center">{{ product.id }}</td>
                <td class="text-center">
                  <img
                    class="product-image"
                    alt="product_image"
                    :src="product.product_images?.[0]?.image_url || ''"
                  />
                </td>
                <td class="text-center">{{ product.name }}</td>
                <td class="text-center">{{ product.sku }}</td>
                <td class="text-center">{{ product.brand.name }}</td>
                <td class="text-center">{{ product.quantity }}</td>
                <td class="text-center">{{ formatCurrency(product.price) }}</td>
                <td
                  class="text-center"
                  :class="product.status === 1 ? 'text-success' : 'text-danger'"
                >
                  {{ product.status ? "Hiển thị" : "Ẩn" }}
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
                            name: 'admin.products.edit',
                            params: { id: product.id },
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
                          :data-bs-target="`#deleteConfirmModal${product.id}`"
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
                  @clickTo="deleteProduct(product.id)"
                  :idModal="`deleteConfirmModal${product.id}`"
                  bgColor="danger"
                >
                  <template v-slot:title>Xóa sản phẩm</template>
                  <h6 class="text-dark text-center fs-5 mt-4">
                    Bạn có chắc chắn muốn xóa sản phẩm này không?
                  </h6>
                  <template v-slot:buttonName>Xóa</template>
                </my-modal>
              </tr>
              <tr v-if="isLoading && !products.length">
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
              @pagination-page="getProductList"
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
import { formatCurrency } from "@/utils/formatCurrency";

const products = ref({});
const pagination = ref({});
const isLoading = ref(false);
const checked = ref([]);
const searchInput = ref("");
const successMessage = ref(null);
const selectPage = ref(false);
const selectAll = ref(false);

const isNotFound = computed(() => {
  return products.value.length === 0 ? true : false;
});

const getProductList = (page = 1) => {
  isLoading.value = true;
  axios
    .get(`/products?page=${page}&search=${searchInput.value}`)
    .then((response) => {
      products.value = response.data.data.products;
      pagination.value = {
        currentPage: response.data.meta.current_page,
        lastPage: response.data.meta.last_page,
      };
    })
    .catch((err) => {
      console.error(err);
    });
};

watch(searchInput, () => {
  getProductList();
});

onBeforeMount(() => {
  getProductList();
});

const deleteConfirm = () => {
  $("#deleteConfirmModal").modal("show");
};

const deleteProduct = (product_id) => {
  isLoading.value = true;
  axios
    .delete(`/products/${product_id}/delete`)
    .then((response) => {
      checked.value = checked.value.filter((id) => id != product_id);
      successMessage.value = response.data.message;
      isLoading.value = false;
      getProductList();
      $(`#deleteConfirmModal${product_id}`).modal("hide");
      $(".toast").toast("show");
    })
    .catch((e) => {
      if (e.response) {
        isLoading.value = false;
        alert(e.response.data.message);
      }
    });
};

const deleteMultiProduct = () => {
  axios
    .delete("/products/delete-multi-product/" + checked.value)
    .then((response) => {
      checked.value = [];
      selectPage.value = false;
      successMessage.value = response.data.message;
      getProductList();
      $("#deleteConfirmModal").modal("hide");
      $(".toast").toast("show");
    })
    .catch((e) => {
      if (e.response) {
        console.error(e.response.data.message);
      }
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
