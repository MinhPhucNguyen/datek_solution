<template>
   <ToastMessage :message="successMessage" />

   <my-modal @clickTo="handleDeleteBrand" idModal="deleteConfirmModal" bgColor="danger">
      <template v-slot:title>Delete brand</template>
      <h6 class="text-dark text-center fs-5 mt-4">Are you sure, you want to delete this brand?</h6>
      <template v-slot:buttonName>
         <div class="spinner-border" role="status" style="width: 24px; height: 24px; margin-right: 10px" v-if="isLoading">
            <span class="visually-hidden">Loading...</span>
         </div>
         Delete
      </template>
   </my-modal>

   <div class="col-md-12">
      <div class="card">
         <div class="card-header bg-transparent d-flex align-items-center">
            <div class="d-inline-block fw-bold text-dark fs-4 flex-grow-1">Brand List</div>
            <div>
               <button class="btn btn-success fw-bold float-right ml-3" @click="addBrand">
                  <i class="fa-solid fa-plus"></i>
                  Add New Brand
               </button>
            </div>
         </div>
         <div class="card-body m-0">
            <table class="table table-bordered table-striped">
               <thead>
                  <tr class="text-dark">
                     <th class="text-center">ID</th>
                     <th class="text-center">Logo</th>
                     <th class="text-center">Brand Name</th>
                     <th class="text-center">Status</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="brand in brands" :key="brand.brand_id">
                     <td class="text-center">{{ brand.brand_id }}</td>
                     <td class="text-center"><img :src="brand.logo" :alt="brand.brand_name" class="brand-logo">
                     </td>
                     <td class="text-center">{{ brand.brand_name.toUpperCase() }}</td>
                     <td class="text-center" :class="brand.status === 1 ? 'text-danger' : 'text-success'">
                        {{ brand.status === 1 ? "Hidden" : "Visible" }}
                     </td>
                     <td class="text-center">
                        <div class="dropdown">
                           <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              Action
                           </button>
                           <ul class="dropdown-menu">
                              <li>
                                 <button type="button" class="dropdown-item mb-3 fs-6 text-success bg-white"
                                    @click="editBrand(brand)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="ml-2">Edit</span>
                                 </button>
                              </li>
                              <li>
                                 <button type="button" class="dropdown-item fs-6 text-danger bg-white"
                                    @click="deleteBrand(brand)">
                                    <i class="fa-solid fa-trash"></i>
                                    <span class="ml-2">Delete</span>
                                 </button>
                              </li>
                           </ul>
                        </div>
                     </td>
                  </tr>
                  <tr v-if="brands.length === 0">
                     <td colspan="5" class="text-center">
                        <stateLoading />
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>

      <div class="modal fade" id="brandFormModal" tabindex="-1" aria-labelledby="BrandFormModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-5" id="BrandFormModalLabel" v-if="isEditing">
                     Edit Brand
                  </h1>
                  <h1 class="modal-title fs-5" id="BrandFormModalLabel" v-else>Add Brand</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form @submit.prevent="brandSubmit">
                     <div class="form-group">
                        <label for="" class="text-dark fw-bold">Logo</label>
                        <input type="file" class="form-control" name="brand_logo" @change="uploadLogo" />
                        <!-- <small class="text-danger" v-if="errors && errors.brand_name[0]">{{
                           errors.brand_name[0]
                        }}</small> -->
                        <div class="mt-3">
                           <img :src="logoUrl" alt="logo" v-if="logoUrl" />
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="" class="text-dark fw-bold">Brand Name</label>
                        <input type="text" class="form-control" name="brand_name" v-model="model.brand_name" />
                        <small class="text-danger" v-if="errors && errors.brand_name[0]">{{
                           errors.brand_name[0]
                        }}</small>
                     </div>
                     <div class="d-flex align-items-center mb-4">
                        <label for="" class="text-dark mr-2 mb-0 fw-bold">Hidden</label>
                        <input type="checkbox" name="status" v-model="isChecked" />
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                           Close
                        </button>
                        <button type="submit" class="btn btn-success pr-4 pl-4 d-flex align-items-center" v-if="isEditing"
                           :disabled="isLoading">
                           <div class="spinner-border" role="status" style="width: 20px; height: 20px; margin-right: 10px"
                              v-if="isLoading && isEditing">
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           Save changes
                        </button>
                        <button type="submit" class="btn btn-success pr-4 pl-4 d-flex align-items-center" v-else
                           :disabled="isLoading">
                           <div class="spinner-border" role="status" style="width: 20px; height: 20px; margin-right: 10px"
                              v-if="isLoading">
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           Add
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from "vue";
import axios from "axios";
import MyModal from "@/components/Modal/Modal.vue";
import ToastMessage from "@/components/Toast/index.vue";
import stateLoading from "@/components/Loading/Loading.vue";

const brands = ref([]);
const model = ref({
   logo: "",
   brand_name: "",
   status: 0,
});
const errors = ref(null);
const isEditing = ref(false);
const successMessage = ref(null);
const isLoading = ref(false);
const logoUrl = ref("");

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
      logo: "",
      brand_name: "",
      status: 0,
   };
   logoUrl.value = ""
};

const getBrands = async () => {
   return await axios.get("v2/admin/brands").then((response) => {
      brands.value = response.data.brands;
   });
};

const uploadLogo = (event) => {
   const file = event.target.files[0];
   model.value.logo = file;
   logoUrl.value = URL.createObjectURL(file);
}

/**
 * todo: ADD BRAND
 */
const addBrand = () => {
   isEditing.value = false;
   resetForm();
   $("#brandFormModal").modal("show");
};

const addNewBrand = () => {
   const formData = new FormData();
   isLoading.value = true;

   for (const key in model.value) {
      if (model.value.hasOwnProperty(key)) {
         const value = model.value[key];
         formData.append(key, value)
      }
   };

   axios
      .post("v2/admin/brands/create", formData)
      .then((response) => {
         successMessage.value = response.data.message;
         getBrands().then(() => {
            isLoading.value = false;
            $("#brandFormModal").modal("hide");
            $(".toast").toast("show");
            resetForm();
         });
      })
      .catch((e) => {
         if (e.response) {
            isLoading.value = false;
            $("#brandFormModal").modal("show");
            errors.value = e.response.data.errors;
         }
      });
};

/**
 * TODO: EDIT BRAND
 * @param {*} brand
 */
const editBrand = (brand) => {
   isEditing.value = true;
   resetForm();
   $("#brandFormModal").modal("show");
   model.value = { ...brand };
};

const updateBrand = () => {
   const formData = new FormData();
   isLoading.value = true;

   for (const key in model.value) {
      if (model.value.hasOwnProperty(key)) {
         const value = model.value[key];
         formData.append(key, value)
      }
   };

   axios
      .post(`v2/admin/brands/${model.value.brand_id}/update`, formData)
      .then((response) => {
         successMessage.value = response.data.message;
         getBrands().then(() => {
            isLoading.value = false;
            $("#brandFormModal").modal("hide");
            $(".toast").toast("show");
            resetForm();
         });
      })
      .catch((e) => {
         if (e.response) {
            isLoading.value = false;
            $("#brandFormModal").modal("show");
            errors.value = e.response.data.errors;
         }
      });
};

const brandSubmit = () => {
   if (isEditing.value) {
      updateBrand();
   } else {
      addNewBrand();
   }
};

/**
 * TODO: DELETE BRAND
 * @param {*} brand
 */
const deleteBrand = (brand) => {
   model.value = { ...brand };
   $("#deleteConfirmModal").modal("show");
};

const handleDeleteBrand = () => {
   isLoading.value = true;
   axios.delete(`v2/admin/brands/${model.value.brand_id}/delete`).then((response) => {
      getBrands().then(() => {
         isLoading.value = false;
         successMessage.value = response.data.message;
         $("#deleteConfirmModal").modal("hide");
         $(".toast").toast("show");
      });
   });
};

onBeforeMount(() => {
   getBrands();
});

onMounted(() => {
   $("#brandFormModal").on("hide.bs.modal", () => {
      errors.value = null;
   });
});
</script>

<style scoped>
.brand-logo {
   width: 45px;
   height: 45px;
   object-fit: cover;
}
</style>
