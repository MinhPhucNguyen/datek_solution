<template>
  <div>
    <ToastMessage :message="successMessage" />

    <div v-if="orders" class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent d-flex align-items-center">
          <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">
            Danh sách đơn hàng
          </div>
        </div>
        <div
          class="w-100 d-flex align-items-center justify-content-between mt-4 pl-4 pr-4"
        >
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
        <div class="card-body mt-0">
          <table class="table table-bordered table-striped text-dark fw-bold">
            <thead>
              <tr class="text-dark">
                <th class="text-center">Mã đơn hàng</th>
                <th class="text-center">Thời gian</th>
                <th class="text-center">Tổng tiền</th>
                <th class="text-center">Khách hàng</th>
                <th class="text-center">Địa chỉ</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody id="body-table">
              <tr v-for="order in orders" :key="order.order_id">
                <td class="text-center">{{ order.order_id }}</td>
                <td class="text-center">{{ order.order_date }}</td>
                <td class="text-center">
                  {{ formatCurrency(order.order_total_price) }}
                </td>
                <td class="text-center">{{ order.user_name }}</td>
                <td class="text-center">{{ order.address }}</td>
                <td class="text-center">
                  {{ order.order_status }}
                </td>
                <td class="text-center">
                  <button
                    class="btn btn-success"
                     v-if="order.order_status === 'Chờ xác nhận'"
                    @click.prevent="confirmOrder(order.order_id)"
                  >
                    Xác nhận đơn hàng
                  </button>
                </td>
              </tr>
              <tr v-if="isLoading && !orders.length">
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
              @pagination-page="getOrdersList"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import stateLoading from "@/components/Loading/Loading.vue";
import Pagination from "@/components/Pagination/index.vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import { formatCurrency } from "@/utils/formatCurrency";

const orders = ref({});
const store = useStore();
const pagination = ref({});
const isLoading = ref(false);
const searchInput = ref("");
const successMessage = ref(null);

const isNotFound = computed(() => {
  return orders.value.length === 0 ? true : false;
});

const getOrdersList = async (page = 1) => {
  isLoading.value = true;
  await store
    .dispatch("orders/getOrders", {
      page,
    })
    .then((response) => {
      isLoading.value = false;
      orders.value = store.getters["orders/getAllOrders"];
      pagination.value = response.pagination;
    })
    .catch((err) => {
      isLoading.value = false;
      console.error(err);
    });
};

onMounted(() => {
  getOrdersList();
});

const orderStatus = ref("");
const confirmOrder = async (orderId) => {
  await store.dispatch("orders/confirmOrder", orderId).then(() => {
    successMessage.value = "Xác nhận đơn hàng thành công";
    window.location.reload();
    orderStatus.value = store.getters["orders/getOrderStatus"];
    getOrdersList();
  });
};
</script>

<style lang="scss" scoped></style>
