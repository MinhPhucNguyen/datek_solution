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
        <span>Dashboard</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <router-link
        :to="{ name: 'admin.users' }"
        class="nav-link collapsed fw-bold text-dark"
      >
        <i class="fas fa-fw fa-user"></i>
        <span>QL Người dùng</span>
      </router-link>
    </li>

    <li class="nav-item border-bottom">
      <a class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-box mr-2"></i>
        <span>QL Sản phẩm</span>
      </a>
    </li>

    <li class="nav-item border-bottom">
      <a class="nav-link collapsed fw-bold text-dark" href="#">
        <i class="fa-solid fa-box mr-2"></i>
        <span>QL Hãng</span>
      </a>
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

<style></style>
