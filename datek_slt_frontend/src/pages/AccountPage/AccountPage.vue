<template>
  <div>
    <ToastMessage
      :message="successMessage ? successMessage : errorMessage"
      :type="errorMessage ? 'danger' : 'success'"
    />

    <div class="content-item user-profile">
      <div class="container">
        <div class="dashboard">
          <div class="sidebar">
            <ul>
              <li @click="changeContent('profile')">
                <a href="#" :class="{ active: activeContent === 'profile' }"
                  ><i class="fa-solid fa-user"></i
                  ><span class="ms-3">Thông tin cá nhân</span></a
                >
              </li>
              <li @click="changeContent('address')">
                <a href="#" :class="{ active: activeContent === 'address' }"
                  ><i class="fa-solid fa-location-dot"></i
                  ><span class="ms-3">Địa chỉ</span></a
                >
              </li>
              <li @click="changeContent('reset-password')">
                <a
                  href="#"
                  :class="{ active: activeContent === 'reset-password' }"
                  ><i class="fa-solid fa-lock"></i
                  ><span class="ms-3">Đổi mật khẩu</span></a
                >
              </li>
              <li @click="changeContent('orders')">
                <a href="#" :class="{ active: activeContent === 'orders' }"
                  ><i class="fa-solid fa-file-invoice"></i
                  ><span class="ms-3">Đơn hàng</span></a
                >
              </li>
              <li class="text-danger" @click="logout">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                <span>Đăng xuất</span>
              </li>
            </ul>
          </div>
          <div class="main-content">
            <div v-if="activeContent === 'profile'">
              <div v-if="!isEditing">
                <div v-if="isLoading" class="loading">
                  <div
                    class="spinner-border"
                    style="width: 2rem; height: 2rem"
                    role="status"
                  >
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>
                <div v-else>
                  <div class="header">
                    <h1>
                      Xin chào
                      {{
                        userData?.lastname + " " + userData?.firstname ||
                        "User"
                      }},
                    </h1>
                  </div>
                  <div class="account-manager d-flex justify-content-between">
                    <div>
                      <h3>Quản lý thông tin cá nhân</h3>
                      <p class="mt-3">
                        Name:
                        {{
                          userData?.lastname + " " + userData?.firstname ||
                          "User"
                        }}
                      </p>
                      <p>Email: {{ userData?.email }}</p>
                      <p>
                        Phone:
                        {{ userData?.phone || "Chưa có số điện thoại nào" }}
                      </p>
                    </div>
                    <div>
                      <button
                        class="btn edit-profile-btn"
                        @click="startEditing"
                      >
                        <i class="fa-solid fa-pen-to-square"></i>
                        Chỉnh sửa thông tin
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else>
                <h3>Chỉnh sửa thông tin cá nhân</h3>
                <form @submit.prevent="saveProfile">
                  <div class="form-group">
                    <label for="firstname">Tên</label>
                    <input
                      type="text"
                      id="firstname"
                      v-model="formData.firstname"
                      class="form-control"
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label for="lastname">Họ</label>
                    <input
                      type="text"
                      id="lastname"
                      v-model="formData.lastname"
                      class="form-control"
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input
                      type="email"
                      id="email"
                      v-model="formData.email"
                      class="form-control"
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label for="phone">Số điện thoại</label>
                    <input
                      type="text"
                      id="phone"
                      v-model="formData.phone"
                      class="form-control"
                    />
                  </div>
                  <button
                    type="submit"
                    class="btn mt-3 me-3 save-profile-btn"
                    :disabled="isLoading"
                  >
                    {{ isLoading ? "Đang lưu..." : "Lưu" }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-secondary mt-3"
                    @click="cancelEditing"
                  >
                    Hủy
                  </button>
                </form>
              </div>
            </div>
            <div v-if="activeContent === 'address'">
              <h2>Địa chỉ của bạn</h2>
              <div v-if="!isEditingAddress">
                <p>
                  {{ userData?.address || "Không có địa chỉ nào" }}
                </p>
                <button
                  class="btn btn-sm me-2 edit-address-btn"
                  @click="startEditingAddress"
                >
                  Chỉnh sửa
                </button>
              </div>
              <div v-else>
                <form @submit.prevent="saveProfile">
                  <input
                    type="text"
                    name="address"
                    v-model="formData.address"
                    class="form-control"
                  />
                  <button
                    class="btn btn-sm save-address-btn mt-2 me-2"
                    type="submit"
                  >
                    Lưu
                  </button>
                  <button
                    class="btn btn-sm btn-secondary mt-2"
                    @click="cancelEditingAddress"
                  >
                    Hủy
                  </button>
                </form>
              </div>
            </div>
            <div v-if="activeContent === 'reset-password'">
              <ToastMessage
                :message="successMessage ? successMessage : errorMessage"
                :type="errorMessage ? 'danger' : 'success'"
              />
              <div class="content-title">
                <h2>Đổi mật khẩu</h2>
                <p class="fs-5 mb-3">
                  Vui lòng nhập mật khẩu hiện tại của bạn để thay đổi mật khẩu
                </p>
              </div>
              <div class="content-item change-pw">
                <div class="content">
                  <form @submit.prevent="changePassword" v-if="model">
                    <div class="custom-input mb-4">
                      <div class="wrap-info fw-bold mb-1">
                        Mật khẩu hiện tại
                      </div>
                      <div class="wrap-input w-100">
                        <input
                          type="password"
                          name="oldPassword"
                          v-model="model.oldPassword"
                        />
                        <i
                          class="fa-regular fa-eye-slash fs-5"
                          @click="showPw($refs.eye1)"
                          ref="eye1"
                        ></i>
                      </div>
                      <small class="text-danger">
                        <span v-if="errors.oldPassword">{{
                          errors.oldPassword[0]
                        }}</span>
                      </small>
                    </div>
                    <div class="custom-input mb-4">
                      <div class="wrap-info fw-bold mb-1">Mật khẩu mới</div>
                      <div class="wrap-input w-100">
                        <input
                          type="password"
                          name="newPassword"
                          v-model="model.newPassword"
                        />
                        <i
                          class="fa-regular fa-eye-slash fs-5"
                          @click="showPw($refs.eye2)"
                          ref="eye2"
                        ></i>
                      </div>
                      <small class="text-danger" v-if="errors.newPassword">
                        {{ errors.newPassword[0] }}</small
                      >
                    </div>
                    <div class="custom-input mb-4">
                      <div class="wrap-info fw-bold mb-1">
                        Xác nhận mật khẩu mới
                      </div>
                      <div class="wrap-input w-100">
                        <input
                          type="password"
                          name="newPasswordConfirmation"
                          v-model="model.newPasswordConfirmation"
                        />
                        <i
                          class="fa-regular fa-eye-slash fs-5"
                          @click="showPw($refs.eye3)"
                          ref="eye3"
                        ></i>
                      </div>
                      <small
                        class="text-danger"
                        v-if="errors.newPasswordConfirmation"
                      >
                        {{ errors.newPasswordConfirmation[0] }}</small
                      >
                    </div>
                    <div class="confirm-button">
                      <button
                        type="submit"
                        class="btn d-flex justify-content-center"
                        style="width: 150px"
                      >
                        <div
                          class="spinner-grow"
                          role="status"
                          style="width: 24px; height: 24px"
                          v-if="isLoading"
                        >
                          <span class="visually-hidden">Loading...</span>
                        </div>
                        <span v-if="!isLoading">Đổi mật khẩu</span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div v-if="activeContent === 'orders'">
              <h2>Đơn hàng của bạn</h2>
              <div v-if="ordersHistory.length && !isOrdersDetail">
                <table class="orders-history-table mt-3">
                  <thead>
                    <tr class="text-dark">
                      <th class="text-center">Mã đơn hàng</th>
                      <th class="text-center">Thời gian</th>
                      <th class="text-center">Tổng tiền</th>
                      <th class="text-center">Trạng thái</th>
                      <th class="text-center"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="order in ordersHistory" :key="order.order_id">
                      <td class="text-center">#{{ order.order_id }}</td>
                      <td class="text-center">{{ order.order_date }}</td>
                      <td class="text-center">
                        {{ formatCurrency(order.order_total_price) }}
                      </td>
                      <td class="text-center">{{ order.order_status }}</td>
                      <td class="text-center history-order-action">
                        <span @click="toggleOrderDetails(order.order_id)"
                          >Xem</span
                        >
                        <span
                          class="text-danger"
                          @click="cancelOrderConfirmation(order.order_id)"
                          v-if="
                            order.order_status !== 'Đã giao' &&
                            order.order_status !== 'Đã hủy'
                          "
                          >Hủy</span
                        >
                        <span v-else-if="order.order_status === 'Đã hủy'"
                          >Đã hủy</span
                        >
                      </td>

                      <my-modal
                        @clickTo="cancelOrder(order.order_id)"
                        :idModal="`cancelConfirmModal${order.order_id}`"
                        bgColor="danger"
                      >
                        <template v-slot:title>Hủy đơn hàng</template>
                        <h6 class="text-dark text-center fs-5 mt-4">
                          Bạn có chắc chắn hủy đơn hàng này không?
                        </h6>
                        <template v-slot:buttonName>Hủy</template>
                      </my-modal>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else>
                <div
                  class="order-detail"
                  v-if="isOrdersDetail && !isLoading && orderDetails"
                >
                  <div class="mb-3">
                    <span
                      @click="backOrderHistoryList"
                      class="fw-bold"
                      style="color: #4e43d8; cursor: pointer"
                      ><i class="fa-solid fa-arrow-left"></i> Quay lại</span
                    >
                  </div>
                  <div class="order-detail">
                    <div class="order-detail-header">
                      <h5 class="fw-bold">
                        Đơn hàng: #{{ orderDetails.order_id }}
                      </h5>
                      <span class="order-status">{{
                        orderDetails.order_status
                      }}</span>
                    </div>
                    <p>
                      Người nhận:
                      <span class="fw-bold">{{ orderDetails.user_name }}</span>
                    </p>
                    <p>
                      Ngày đặt hàng:
                      <span class="fw-bold">{{ orderDetails.order_date }}</span>
                    </p>
                    <p>
                      Địa chỉ nhận hàng:
                      <span class="fw-bold">{{ orderDetails.address }}</span>
                    </p>
                    <p>
                      Tổng tiền:
                      <span class="fw-bold">{{
                        formatCurrency(orderDetails.order_total_price)
                      }}</span>
                    </p>
                    <p class="fw-bold">Chi tiết đơn hàng:</p>
                    <table class="order-detail-table">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="text-center product-name">Tên sản phẩm</th>
                          <th class="text-center">Số lượng</th>
                          <th class="text-center">Giá</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="item in orderDetails.order_details"
                          :key="item.id"
                        >
                          <td class="text-center">
                            <div class="product-image-wrapper">
                              <img
                                :src="item.product_image"
                                alt="product-image"
                                class="product-image"
                              />
                            </div>
                          </td>
                          <td class="text-center">{{ item.product_name }}</td>
                          <td class="text-center">
                            {{ item.order_detail_quantity }}
                          </td>
                          <td class="text-center">
                            {{ formatCurrency(item.product_price) }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <button
                      class="btn-received"
                      v-if="orderDetails.order_status === 'Chờ giao hàng'"
                      @click.prevent="confirmOrder(orderDetails.order_id)"
                    >
                      Đã nhận được hàng
                    </button>
                  </div>
                </div>
                <div v-if="isLoading" class="loading">
                  <div
                    class="spinner-border"
                    style="width: 2rem; height: 2rem; text-align: center"
                    role="status"
                  >
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onBeforeMount, watch, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { formatCurrency } from "@/utils/formatCurrency";
import MyModal from "@/components/Modals/Modal.vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import axios from "axios";

const errors = ref({});
const store = useStore();
const router = useRouter();
const activeContent = ref("profile");
const isEditing = ref(false);
const isLoading = ref(false);
const isOrdersDetail = ref(false);
const orderDetails = ref({});
const isFilledForm = ref(false);
const successMessage = ref(null);

const model = ref({
  oldPassword: "",
  newPassword: "",
  newPasswordConfirmation: "",
});

const resetForm = () => {
  model.value = {
    oldPassword: "",
    newPassword: "",
    newPasswordConfirmation: "",
  };
  errors.value = {};
};

watch(model.value, (value) => {
  isFilledForm.value = true;
  if (
    value.email === "" ||
    value.password === "" ||
    value.password_confirmation === ""
  ) {
    errors.value = {};
  }
});

const formData = ref({
  firstname: "",
  lastname: "",
  email: "",
  phone: "",
  address: "",
});
const ordersHistory = ref([]);

const id = store.getters["auth/getUser"].id;
store.dispatch("users/fetchUserById", id);
const userData = computed(() => store.getters["users/getUserById"]);

const changeContent = (content) => {
  activeContent.value = content;
};

const startEditing = () => {
  isEditing.value = true;
  formData.value = { ...userData.value };
};

const cancelEditing = () => {
  isEditing.value = false;
};

const saveProfile = () => {
  isLoading.value = true;
  store
    .dispatch("users/editUser", { id, model: formData.value })
    .then((response) => {
      userData.value = { ...formData.value };
      isEditing.value = false;
      isLoading.value = false;

      localStorage.setItem("successMessage", response.data.message);

      setTimeout(() => {
        window.location.reload();
      }, 1000);
    })
    .catch((e) => {
      if (e.response) {
        isLoading.value = false;
        errors.value = e.response.data.errors;
      }
    });
};

onMounted(() => {
  const message = localStorage.getItem("successMessage");
  if (message) {
    successMessage.value = message;
    $(".toast").toast("show");
    localStorage.removeItem("successMessage");
  }
});

const isEditingAddress = ref(false);
const startEditingAddress = () => {
  isEditingAddress.value = true;
  formData.value = { ...userData.value };
};

const cancelEditingAddress = () => {
  isEditingAddress.value = false;
};

const getOrdersHistory = async () => {
  const userId = store.getters["auth/getUser"].id;
  isLoading.value = true;
  await store
    .dispatch("orders/getOrdersHistory", {
      user_id: userId,
    })
    .then(() => {
      isLoading.value = false;
      ordersHistory.value = store.getters["orders/getOrdersHistory"];
    })
    .catch((e) => {
      isLoading.value = false;
      console.error(e);
    });
};

onBeforeMount(() => {
  getOrdersHistory();
});

const cancelOrderConfirmation = (orderId) => {
  $(`#cancelConfirmModal${orderId}`).modal("show");
};

const cancelOrder = async (orderId) => {
  console.log(orderId);

  await store.dispatch("orders/cancelOrder", orderId).then(() => {
    getOrdersHistory();
    $(`#cancelConfirmModal${orderId}`).modal("hide");
  });
};

const toggleOrderDetails = async (orderId) => {
  isOrdersDetail.value = !isOrdersDetail.value;
  isLoading.value = true;
  await store
    .dispatch("orders/getOrderDetails", orderId)
    .then(() => {
      isLoading.value = false;
      orderDetails.value = store.getters["orders/getOrderDetails"];
    })
    .catch((e) => {
      isLoading.value = false;
      console.error(e);
    });
};

const backOrderHistoryList = () => {
  isOrdersDetail.value = false;
};

const confirmOrder = async (orderId) => {
  await store.dispatch("orders/confirmOrder", orderId).then(() => {
    window.location.reload();
  });
};

const errorMessage = ref(null);
const changePassword = async () => {
  isLoading.value = true;
  await axios
    .post(`/users/${id}/change-password`, model.value)
    .then((response) => {
      errorMessage.value = null;
      isLoading.value = false;
      successMessage.value = response.data.message;
      $(".toast").toast("show");
      setTimeout(() => {
        store.dispatch("auth/logout").then(() => {
          router.push({ name: "login" });
        });
      }, 2000);
      resetForm();
    })
    .catch((error) => {
      if (error.response.status === 400) {
        errorMessage.value = error.response.data.message;
        isLoading.value = false;
        successMessage.value = null;
        $(".toast").toast("show");
        model.value.oldPassword = "";
      }
      if (error.response.status === 422) {
        isLoading.value = false;
        successMessage.value = null;
        errorMessage.value = null;
        errors.value = error.response.data.errors;
      }
    });
};

/**
 * TODO: SHOW/HIDE PASSWORD
 */
const showPw = (icon) => {
  const input = icon.previousSibling;
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  }
};

const logout = () => {
  store.dispatch("auth/logout").then(() => {
    router.push({ name: "login" });
    store.dispatch("cart/clearCart");
  });
};
</script>

<style scoped>
@import url(AccountPage.scss);
</style>
