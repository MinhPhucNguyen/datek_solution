<template>
  <div>
    <ToastMessage :message="successMessage" />

    <my-modal
      @clickTo="handleDeleteBrand"
      idModal="deleteConfirmModal"
      bgColor="danger"
    >
      <template v-slot:title>Xóa danh mục</template>
      <h6 class="text-dark text-center fs-5 mt-4">
        Bạn có chắc chắn muốn xóa danh mục này không?
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
        Delete
      </template>
    </my-modal>

    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent d-flex align-items-center">
          <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">
            Danh sách danh mục
          </div>
          <div>
            <button
              class="btn btn-success fw-bold float-right ml-3"
              @click="addCategory"
            >
              <i class="fa-solid fa-plus"></i>
              Thêm danh mục
            </button>
          </div>
        </div>
        <div class="card-body m-0">
          <table class="table table-bordered table-striped">
            <thead>
              <tr class="text-dark">
                <th class="text-center">ID</th>
                <th class="text-center">Tên danh mục</th>
                <th class="text-center">Mô tả</th>
                <th class="text-center">Danh mục cha</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in flatCategories" :key="category.id">
                <td class="text-center">{{ category.id }}</td>
                <td class="text-center">
                  {{ category.category_name }}
                </td>
                <td class="text-center">
                  {{
                    category.description
                      ? category.description
                      : "_"
                  }}
                </td>
                <td class="text-center">
                  {{ category.parent_id ? getParentCategoryName(category.parent_id) : '_' }}
                </td>
                <td
                  class="text-center"
                  :class="
                    category.status === 1 ? 'text-success' : 'text-danger'
                  "
                >
                  {{ category.status === 1 ? "Hiển thị" : "Không hiển thị" }}
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
                          @click="editBrand(brand)"
                        >
                          <i class="fa-solid fa-pen-to-square"></i>
                          <span class="ml-2">Sửa</span>
                        </button>
                      </li>
                      <li>
                        <button
                          type="button"
                          class="dropdown-item fs-6 text-danger bg-white"
                          @click="deleteBrand(brand)"
                        >
                          <i class="fa-solid fa-trash"></i>
                          <span class="ml-2">Xóa</span>
                        </button>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              <tr v-if="categories.length === 0">
                <td colspan="6" class="text-center">
                  <stateLoading />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Category Form Modal -->
      <div
        class="modal fade"
        id="categoryFormModal"
        tabindex="-1"
        aria-labelledby="CategoryFormModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1
                class="modal-title fs-5"
                id="CategoryFormModalLabel"
                v-if="isEditing"
              >
                Cập nhật danh mục
              </h1>
              <h1 class="modal-title fs-5" id="CategoryFormModalLabel" v-else>
                Thêm danh mục
              </h1>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="brandSubmit">
                <div class="form-group">
                  <label for="" class="text-dark fw-bold">Tên danh mục</label>
                  <input
                    type="text"
                    class="form-control"
                    name="category_name"
                    v-model="model.category_name"
                  />
                  <small
                    class="text-danger"
                    v-if="errors && errors.category_name[0]"
                    >{{ errors.category_name[0] }}</small
                  >
                </div>
                <div class="form-group">
                  <label for="" class="text-dark fw-bold">Slug</label>
                  <div class="d-flex align-items-center">
                    <input
                      type="text"
                      class="form-control"
                      name="slug"
                      v-model="model.slug"
                      :style="{ width: '75%' }"
                    />
                    <button
                      type="button"
                      class="btn btn-light ms-2"
                      @click="generateSlug"
                    >
                      <i class="fa-solid fa-rotate"></i>
                      Tạo slug
                    </button>
                  </div>
                  <small class="text-danger" v-if="errors && errors.slug[0]">{{
                    errors.slug[0]
                  }}</small>
                </div>
                <div class="form-group">
                  <label for="" class="text-dark fw-bold">Mô tả</label>
                  <textarea
                    type="text"
                    class="form-control description"
                    name="description"
                    v-model="model.description"
                  />
                </div>
                <div class="d-flex align-items-center mb-4">
                  <label for="" class="text-dark mr-2 mb-0 fw-bold"
                    >Hiển thị</label
                  >
                  <input type="checkbox" name="status" v-model="isChecked" />
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                  >
                    Đóng
                  </button>
                  <button
                    type="submit"
                    class="btn btn-success pr-4 pl-4 d-flex align-items-center"
                    v-if="isEditing"
                    :disabled="isLoading"
                  >
                    <div
                      class="spinner-border"
                      role="status"
                      style="width: 20px; height: 20px; margin-right: 10px"
                      v-if="isLoading && isEditing"
                    >
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    Lưu thay đổi
                  </button>
                  <button
                    type="submit"
                    class="btn btn-success pr-4 pl-4 d-flex align-items-center"
                    v-else
                    :disabled="isLoading"
                  >
                    <div
                      class="spinner-border"
                      role="status"
                      style="width: 20px; height: 20px; margin-right: 10px"
                      v-if="isLoading"
                    >
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    Thêm
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { computed, onBeforeMount, ref } from "vue";
import axios from "axios";
import MyModal from "@/components/Modals/Modal.vue";
import ToastMessage from "@/components/Toast/Toast.vue";
import stateLoading from "@/components/Loading/Loading.vue";

const categories = ref([]);
const model = ref({
  category_name: "",
  slug: "",
  description: "",
  status: 1,
});
const errors = ref(null);
const isEditing = ref(false);
const successMessage = ref(null);
const isLoading = ref(false);

/**
 * TODO: CHECKBOX STATUS
 */
const isChecked = computed({
  get() {
    return model.value.status === 1;
  },
  set(value) {
    model.value.status = value ? 1 : 0;
  },
});

const resetForm = () => {
  model.value = {
    category_name: "",
    slug: "",
    description: "",
    status: 1,
  };
};

const getCategories = async () => {
  return await axios.get("/categories").then((response) => {
    categories.value = response.data.categories;
  });
};

/**
 * todo: ADD CATEGORY
 */
const addCategory = () => {
  isEditing.value = false;
  resetForm();
  $("#categoryFormModal").modal("show");
};

onBeforeMount(() => {
  getCategories();
});

/**
 * todo: GENERATE SLUG
 */
const generateSlug = () => {
  if (model.value.category_name) {
    model.value.slug = model.value.category_name
      .toLowerCase()
      .trim()
      .replace(/[\s\W-]+/g, "-");
  } else {
    model.value.slug = "";
  }
};

const flatCategories = computed(() => {
  return categories.value.reduce((acc, category) => {
    acc.push(category);
    if (category.sub_categories && category.sub_categories.length) {
      acc.push(...category.sub_categories);
    }
    return acc;
  }, []);
});

const getParentCategoryName = (parent_id) => {
  if (!parent_id) return "-";
  const parentCategory = categories.value.find(cat => cat.id === parent_id);
  return parentCategory ? parentCategory.category_name : "_";
};

</script>

<style scoped>
.description {
  height: 200px;
}
</style>
