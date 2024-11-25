<template>
  <div>
    <div class="content-item user-profile">
      <div class="content-title d-flex align-items-center justify-content-between">
        <div class="title-edit d-flex align-items-center">
          <h5 class="fs-3 mb-0">Thông tin tài khoản</h5>
          <div
              class="wrap-edit-btn edit-account-btn"
              data-bs-toggle="modal"
              data-bs-target="#editAccount"
          >
            <i class="fa-solid fa-pen"></i>
          </div>
        </div>
      </div>
      <div class="content w-100 d-flex justify-content-between">
        <div class="avatar-wrap">
          <h6 class="user-fullname">dasds</h6>
          <div class="note">
            Tham gia: <span>{{ dateJoin }}</span>
          </div>
          <div class="trip-class">
            <div>
              <i class="fa-solid fa-medal fs-5"></i>
              <p class="mb-0">0</p>
              <span>điểm</span>
            </div>
          </div>
        </div>
        <div class="info-wrap h-100 d-flex flex-column">
          <div class="info-box">
            <div class="info-box-item d-flex justify-content-between align-items-center">
              <p>Ngày sinh</p>
              <p class="main">{{ formatBirth }}</p>
            </div>
<!--            <div class="info-box-item d-flex justify-content-between align-items-center">-->
<!--              <p>Giới tính</p>-->
<!--              <p class="main">{{ user.gender === 1 ? "Nam" : "Nữ" }}</p>-->
<!--            </div>-->
          </div>
          <div class="info-desc">
            <div class="info-desc-item">
              <div class="title-item">
                Số điện thoại
              </div>
            </div>
            <div class="info-desc-item">
              <div class="title-item">
                Email
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content-item driver-license">
      <div class="content-title d-flex align-items-center justify-content-between">
        <div class="btn d-flex align-items-center">
          <span>Chỉnh sửa</span>
          <i class="fa-solid fa-pen"></i>
        </div>
      </div>
      <div class="content w-100 d-flex justify-content-between">
        <div class="info-license">
          <div class="lincense-title">Thông tin chung</div>
          <div class="custom-input">
            <div class="wrap-info fw-bold mb-1">Họ và tên</div>
            <div class="wrap-input w-100">
              <input
                  type="text"
                  name="driver-license-fullname"
                  placeholder="Nhập đầy đủ họ tên"
                  disabled
              />
            </div>
          </div>
          <div class="custom-input">
            <div class="wrap-info fw-bold mb-1">Ngày sinh</div>
            <div class="wrap-input w-100">
              <input type="text" name="driver-license-birth" :value="formatBirth" disabled/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {useStore} from "vuex";

const store = useStore();
const user = ref({});

/**
 * TODO: FETCH USER WHEN AUTHENTICATED
 */
// user.value = store.getters["auth/getUser"];
user.value = store.getters["users/getUserById"];

</script>

<style scoped>
@import url("./AccountPage.scss");
</style>
