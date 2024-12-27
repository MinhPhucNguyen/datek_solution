<template>
  <div>
    <ToastMessage :message="successMessage" />

    <my-modal
      @clickTo="deleteMultiUser"
      :idModal="'deleteConfirmModal'"
      bgColor="danger"
    >
      <template v-slot:title>Xóa người dùng</template>
      <h6 class="text-dark text-center fs-5 mt-4">
        Bạn có chắc chắn muốn xóa {{ checked.length }} người dùng mà bạn chọn
      </h6>
      <template v-slot:buttonName>Xóa</template>
    </my-modal>

    <div v-if="users" class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent d-flex align-items-center">
          <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">
            Danh sách người dùng
          </div>
          <div>
            <router-link
              :to="{ name: 'admin.users.create' }"
              class="btn btn-success fw-bold float-right ml-3"
            >
              <i class="fa-solid fa-plus"></i>
              Thêm người dùng mới
            </router-link>
          </div>
        </div>
        <div
          class="w-100 d-flex align-items-center justify-content-between mt-4 pl-4 pr-4"
        >
          <div
            class="w-25 m-0 d-flex align-items-center justify-content-between"
          >
            <div class="mb-3 w-50">
              <div class="d-flex align-items-center w-50">
                <label for="filterBy" class="form-label mb-0 fw-bolder"
                  >Vai trò:</label
                >
              </div>
              <select
                v-model="selected_role"
                name="filterBy"
                class="form-select w-100 mt-2 filter-by"
              >
                <option value="all">Tất cả</option>
                <option value="0">Khách hàng</option>
                <option value="1">Quản trị viên</option>
              </select>
            </div>
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
            <strong>{{ checked.length }}</strong> người dùng.
          </div>
          <div v-else>
            <span class="fs-6"
              >Bạn đã chọn <strong>{{ checked.length }} </strong> người dùng.
            </span>
            <p class="fs-6 mt-2">
              Bạn chọn muốn chọn tất cả <strong>{{ totalUser }} </strong> người
              dùng?
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
                <th class="text-center" @click.prevent="changeSort('name')">
                  Họ và tên
                  <span class="sort-id-icon float-end">
                    <i
                      class="fa-solid fa-arrow-down"
                      :class="
                        sort_field == 'name' && sort_direction == 'desc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                    <i
                      class="fa-solid fa-arrow-up"
                      :class="
                        sort_field == 'name' && sort_direction == 'asc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                  </span>
                </th>
                <th class="text-center">Giới tính</th>
                <th class="text-center" @click.prevent="changeSort('email')">
                  Email
                  <span class="sort-id-icon float-end">
                    <i
                      class="fa-solid fa-arrow-down"
                      :class="
                        sort_field == 'email' && sort_direction == 'desc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                    <i
                      class="fa-solid fa-arrow-up"
                      :class="
                        sort_field == 'email' && sort_direction == 'asc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                  </span>
                </th>
                <th class="text-center" @click.prevent="changeSort('phone')">
                  Số điện thoại
                  <span class="sort-id-icon float-end">
                    <i
                      class="fa-solid fa-arrow-down"
                      :class="
                        sort_field == 'phone' && sort_direction == 'desc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                    <i
                      class="fa-solid fa-arrow-up"
                      :class="
                        sort_field == 'phone' && sort_direction == 'asc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                  </span>
                </th>
                <th class="text-center" @click.prevent="changeSort('street')">
                  Địa chỉ
                  <span class="sort-id-icon float-end">
                    <i
                      class="fa-solid fa-arrow-down"
                      :class="
                        sort_field == 'address' && sort_direction == 'desc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                    <i
                      class="fa-solid fa-arrow-up"
                      :class="
                        sort_field == 'address' && sort_direction == 'asc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                  </span>
                </th>
                <th class="text-center">Vai trò</th>
                <th
                  class="text-center"
                  @click.prevent="changeSort('created_at')"
                >
                  Đăng ký vào
                  <span class="sort-id-icon float-end">
                    <i
                      class="fa-solid fa-arrow-down"
                      :class="
                        sort_field == 'created_at' && sort_direction == 'desc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                    <i
                      class="fa-solid fa-arrow-up"
                      :class="
                        sort_field == 'created_at' && sort_direction == 'asc'
                          ? 'text-success'
                          : 'text-muted'
                      "
                    ></i>
                  </span>
                </th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody id="body-table">
              <tr v-for="user in users" :key="user.id">
                <td class="text-center">
                  <input type="checkbox" :value="user.id" v-model="checked" />
                </td>
                <td class="text-center">
                  <router-link
                    :to="{
                      name: 'admin.users.profile',
                      params: { id: user.id },
                    }"
                    class="text-success"
                    >{{ user.name }}</router-link
                  >
                </td>
                <td class="text-center">
                  {{ user.gender === 1 ? "Nam" : "Nữ" }}
                </td>
                <td class="text-center">{{ user.email }}</td>
                <td class="text-center">{{ user.phone }}</td>
                <td class="text-center">{{ user.address }}</td>
                <td
                  class="text-center"
                  :class="{
                    'text-danger': user.role_as === 1,
                    'text-success': user.role_as !== 1,
                  }"
                >
                  {{ user.role_as === 0 ? "Khách hàng" : "Quản trị viên" }}
                </td>
                <td class="text-center">
                  {{ formatDateTime(user.created_at) }}
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
                            name: 'admin.users.edit',
                            params: { id: user.id },
                          }"
                          class="dropdown-item mb-3 fs-6 text-success bg-white"
                        >
                          <i class="fa-solid fa-user-pen"></i>
                          <span class="ml-2">Sửa</span>
                        </router-link>
                      </li>
                      <li v-if="user.role_as !== 1">
                        <button
                          type="button"
                          class="dropdown-item fs-6 text-danger bg-white"
                          data-bs-toggle="modal"
                          :data-bs-target="`#deleteConfirmModal${user.id}`"
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
                  @clickTo="deleteUser(user.id)"
                  :idModal="`deleteConfirmModal${user.id}`"
                  bgColor="danger"
                >
                  <template v-slot:title>Xóa người dùng</template>
                  <h6 class="text-dark text-center fs-5 mt-4">
                    Bạn có chắc chắn muốn xóa người dùng này không?
                  </h6>
                  <template v-slot:buttonName>Xóa</template>
                </my-modal>
              </tr>
              <tr v-if="isLoading && !users.length">
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
              @pagination-page="getUserList"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onBeforeMount, computed, watch } from "vue";
import { useStore } from "vuex";
import MyModal from "@/components/Modals/Modal.vue";
import stateLoading from "@/components/Loading/Loading.vue";
import Pagination from "@/components/Pagination/index.vue";
import { formatDateTime } from "@/utils/formatDateTime";
import ToastMessage from "@/components/Toast/Toast.vue";
import axios from "axios";

const store = useStore();
const users = ref({});
const pagination = ref({});
const selected_role = ref("all");
const sort_direction = ref("desc");
const sort_field = ref("created_at");
const searchInput = ref("");
const isLoading = ref(false);
const checked = ref([]);
const successMessage = ref(null);
const selectPage = ref(false);
const selectAll = ref(false);

const isNotFound = computed(() => {
  return users.value.length === 0 ? true : false;
});

const changeSort = (field) => {
  if (sort_field.value == field) {
    sort_direction.value = sort_direction.value == "asc" ? "desc" : "asc";
  } else {
    sort_field.value = field;
  }
  getUserList();
};

const getUserList = (page = 1) => {
  isLoading.value = true;
  store
    .dispatch("users/fetchUsers", {
      page,
      selected_role,
      sort_field,
      sort_direction,
      searchInput,
    })
    .then((res) => {
      isLoading.value = false;
      users.value = store.getters["users/getUserList"];
      pagination.value = res.pagination;
    })
    .catch((err) => {
      isLoading.value = false;
      console.error(err);
    });
};

const totalUser = computed(() => store.state["users"].totalUser);

onBeforeMount(() => {
  getUserList();
});

watch(selected_role, () => {
  getUserList();
});

watch(searchInput, () => {
  getUserList();
});

const selectAllUsers = () => {
   axios.get("users/all").then((response) => {
      selectAll.value = true;
      const usersIdArray = response.data;
      checked.value = usersIdArray;
   });
};

/**
 * TODO: DELETE A USER
 * @param {*} id
 */
 const deleteUser = (user_id) => {
   store
      .dispatch("users/deleteUser", user_id)
      .then((response) => {
         checked.value = checked.value.filter((id) => id != user_id);
         successMessage.value = response.data.message;
         getUserList();
         $(`#deleteConfirmModal${user_id}`).modal("hide");
         $(".toast").toast("show");
      })
      .catch((e) => {
         if (e.response) {
            alert(e.response.data.message);
         }
      });
};

/**
 * TODO: DELETE MULTIPLE USER
 */
 const deleteConfirm = () => {
   $("#deleteConfirmModal").modal("show");
};

const deleteMultiUser = () => {
   axios
      .delete("/users/delete-multi-user/" + checked.value)
      .then((response) => {
         checked.value = [];
         selectPage.value = false;
         successMessage.value = response.data.message;
         getUserList();
         $("#deleteConfirmModal").modal("hide");
         $(".toast").toast("show");
      })
      .catch((e) => {
         if (e.response) {
            console.error(e.response.data.message);
         }
      });
};

/**
 * TODO: DELETE USER LIST ON A PAGE
 */
watch(selectPage, (value) => {
   checked.value = [];
   if (value) {
      users.value.forEach((user) => {
         checked.value.push(user.id);
      });
   } else {
      checked.value = [];
      selectAll.value = false;
   }
});

</script>

<style lang="scss" scoped>
.checked-announce {
  padding: 0 1.5rem;
}
</style>
