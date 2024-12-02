<template>
  <div>
    <ToastMessage :message="successMessage" />

    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-transparent border-0">
          <div class="d-inline-block fw-bold text-dark fs-4">Thêm sản phẩm</div>
          <router-link
            :to="{ name: 'admin.products' }"
            class="btn btn-danger fw-bold float-right"
          >
            <i class="fa-solid fa-arrow-left"></i>
            Quay lại
          </router-link>
        </div>
        <div class="card-body mt-0">
          <form @submit.prevent="createNewProduct">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button
                  class="nav-link text-success fw-bold active"
                  id="home-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#car-tab-pane"
                  type="button"
                  role="tab"
                  aria-controls="car-tab-pane"
                  aria-selected="true"
                >
                  <i class="fa-solid fa-circle-info mr-1"></i>
                  Chi tiết sản phẩm
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button
                  class="nav-link text-success fw-bold"
                  id="profile-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#image-tab-pane"
                  type="button"
                  role="tab"
                  aria-controls="image-tab-pane"
                  aria-selected="false"
                >
                  <i class="fa-solid fa-image mr-1"></i>
                  Ảnh sản phẩm
                </button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div
                class="tab-pane fade show mt-3 active"
                id="car-tab-pane"
                role="tabpanel"
                aria-labelledby="home-tab"
                tabindex="0"
              >
                <h5 class="mb-4">Thông tin sản phẩm</h5>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="brand">Hãng</label>
                    <select class="form-control" v-model="model.brand_id">
                      <option value="">--Chọn hãng--</option>
                      <option
                        v-for="brand in brandsList"
                        :key="brand.brand_id"
                        :value="brand.brand_id"
                      >
                        {{ brand.brand_name }}
                      </option>
                    </select>
                    <small class="text-danger" v-if="errors.brand">{{
                      errors.brand[0]
                    }}</small>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="product_name">Tên sản phẩm</label>
                    <input
                      type="text"
                      name="product_name"
                      class="form-control"
                      v-model="model.name"
                    />
                    <small class="text-danger" v-if="errors.name">{{
                      errors.name[0]
                    }}</small>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="sku">Mã sản phẩm</label>
                    <input
                      type="text"
                      name="sku"
                      class="form-control"
                      v-model="model.year"
                    />
                    <small class="text-danger" v-if="errors.sku">{{
                      errors.sku[0]
                    }}</small>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="quantity">Số lượng</label>
                    <input
                      type="text"
                      name="quantity"
                      class="form-control"
                      v-model="model.quantity"
                    />
                    <small class="text-danger" v-if="errors.quantity">{{
                      errors.quantity[0]
                    }}</small>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="price">Giá bán</label>
                    <input
                      type="text"
                      name="price"
                      class="form-control"
                      v-model="model.price"
                    />
                    <small class="text-danger" v-if="errors.price">{{
                      errors.price[0]
                    }}</small>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="status">Trạng thái</label>
                    <div class="d-flex align-items-center">
                      <input
                        type="checkbox"
                        name="status"
                        value="1"
                        v-model="isStatusChecked"
                      />
                      <label for="status" class="ml-2 mb-0">Hiển thị</label>
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="description">Mô tả</label>
                    <Editor
                      api-key="6ctcvzv1prbljrvmhfwp3knb1k7b3ep2lsvx79de0vkacg24"
                      v-model="model.description"
                      :init="editorConfig"
                    />
                  </div>
                </div>
              </div>

              <div class="tab-pane fade mt-3" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab"
                     tabindex="0">
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <h5 class="mb-4">Tải ảnh lên</h5>
                           <input ref="filesInput" type="file" multiple name="image[]" class="form-control file-input"
                              @change="uploadProductImage" />
                           <div class="display_image mb-4" v-if="imagesUrl.length > 0">
                              <div class="product_image_input" v-for="(src, index) in imagesUrl" :key="index">
                                 <img :src="src" alt="" class="image_input" />
                                 <button class="btn btn-danger remove_btn" @click.prevent="removeImage(index)">
                                    Xóa
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
            </div>
            <button
              class="btn btn-success p-3 fw-bold float-end"
              type="submit"
              :disabled="isFilledForm"
            >
              <div
                class="spinner-border"
                role="status"
                style="width: 20px; height: 20px; margin-right: 10px"
                v-if="isLoading"
              >
                <span class="visually-hidden">Loading...</span>
              </div>
              Thêm sản phẩm
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Editor from "@tinymce/tinymce-vue";
import { ref, watch } from "vue";
import ToastMessage from "@/components/Toast/Toast.vue";

const brandsList = ref([]);
const successMessage = ref(null);
const errors = ref({});
const isLoading = ref(false);
const imagesUrl = ref([]);
const filesInput = ref(null);

const model = ref({
  brand_id: "",
  name: "",
  sku: "",
  quantity: 0,
  price: "",
  description: "",
  status: 0,
  product_images: [],
});

const editorConfig = {
  height: 550,
  selector: "textarea",
  menubar: true,
  plugins: [
    "advlist",
    "autolink",
    "lists",
    "link",
    "image",
    "charmap",
    "print",
    "preview",
    "anchor",
    "table",
  ],
  toolbar:
    "undo redo | formatselect | fontsize | bold italic | link image | alignleft aligncenter alignright | numlist bullist outdent indent | removeformat | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol",
  tinycomments_mode: "embedded",
  table_style_by_css: true,
  table_merge_content_on_paste: true,
  table_background_color_map: [
    { title: "Red", value: "FF0000" },
    { title: "White", value: "FFFFFF" },
    { title: "Yellow", value: "F1C40F" },
  ],
};

const createNewProduct = async () => {
   const formData = new FormData();

   for (const key in model.value) {
      if (Object.prototype.hasOwnProperty.call(model.value, key)) {
         //Xác định xem đối tượng có chứa thuộc tính được chỉ định hay không
         const value = model.value[key];
         if (Array.isArray(value)) {
            for (const item of value) {
               formData.append(`${key}[]`, item);
            }
         } else {
            formData.append(key, value);
         }
      }
   }

   isLoading.value = true;
};

watch(() => model.value.product_images, () => {
   console.log(model.value.product_images);
});

const uploadProductImage = (event) => {
   for (const file of event.target.files) {
      const imageURL = URL.createObjectURL(file);
      imagesUrl.value.push(imageURL);
      model.value.product_images.push(file);

      console.log("product_images after add:", model.value.product_images);
   }
};

/**
 * TODO: Remove product image before create new car
 * @param {*} index
 */
 const removeImage = (index) => {
   imagesUrl.value.splice(index, 1);

   const newFileList = new DataTransfer();

   for (let i = 0; i < filesInput.value.files.length; i++) {
      if (i !== index) {
         newFileList.items.add(filesInput.value.files[i]);
      }
   }
   filesInput.value.files = newFileList.files;
};

</script>

<style lang="scss" scoped>
.product_image_input {
   border-radius: 20px;
   margin-right: 20px;
   margin-top: 20px;
   margin-left: 12px;
   display: inline-block;
   text-align: center;
}

.product_image_input > .image_input {
   width: 120px;
   height: 120px;
   object-fit: cover;
   border-radius: 20px;
   display: block;
}</style>
