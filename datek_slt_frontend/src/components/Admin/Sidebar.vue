<template>
  <ul
    class="navbar-nav bg-light sidebar sidebar-light accordion border-end"
    id="accordionSidebar"
  >
    <!-- Sidebar - Brand -->
    <a
      class="sidebar-brand d-flex align-items-center justify-content-center border-bottom"
    >
      <div class="sidebar-brand-text">DATEK SOLUTIONS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active border-bottom">
      <router-link
        class="nav-link text-success"
        :to="{ name: 'admin.dashboard' }"
      >
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Tổng quan</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <router-link
        :to="{ name: 'admin.users' }"
        class="nav-link collapsed fw-bold text-dark"
      >
        <i class="fas fa-fw fa-user"></i>
        <span>Quản lý khách hàng</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <router-link :to="{name: 'admin.orders' }" class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-file-invoice"></i>
        <span>Quản lý đơn hàng</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <router-link :to="{name: 'admin.products'}" class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-box mr-2"></i>
        <span>Quản lý sản phẩm</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <router-link :to="{name: 'admin.categories' }" class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-list mr-2"></i>
        <span>Quản lý danh mục sản phẩm</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <a class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-box mr-2"></i>
        <span>Quản lý giảm giá</span>
      </a>
    </li>

    <li class="nav-item border-bottom">
      <router-link :to="{name: 'admin.brands'}" class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-box mr-2"></i>
        <span>Quản lý hãng</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <a
        class="nav-link fw-bold text-danger"
        data-bs-toggle="modal"
        data-bs-target="#logoutModal"
        style="cursor: pointer"
      >
        <i class="fa-solid fa-arrow-right-from-bracket text-danger"></i>
        <span>Đăng xuất</span>
      </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" />
  </ul>
</template>

<script>
export default {
  name: "MainSidebar",
};
</script>

<style scope>
.sidebar {
  width: 20rem !important;
}

.sidebar .nav-item .nav-link {
  width: 100% !important;
}
</style>
