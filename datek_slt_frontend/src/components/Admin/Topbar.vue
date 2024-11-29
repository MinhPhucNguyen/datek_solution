<template>
   <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
      <div class="w-100 d-flex align-items-center justify-content-end">
         <ul class="navbar-nav ml-3">
            <li class="nav-item dropdown no-arrow d-sm-none">
               <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="searchDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
               >
                  <i class="fas fa-search fa-fw"></i>
               </a>
               <div
                  class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                  aria-labelledby="searchDropdown"
               >
                  <form class="form-inline mr-auto w-100 navbar-search">
                     <div class="input-group">
                        <input
                           type="text"
                           class="form-control bg-light border-0 small"
                           placeholder="Search for..."
                           aria-label="Search"
                           aria-describedby="basic-addon2"
                        />
                        <div class="input-group-append">
                           <button class="btn btn-success" type="button">
                              <i class="fas fa-search fa-sm"></i>
                           </button>
                        </div>
                     </div>
                  </form>
               </div>
            </li>
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow pr-5">
               <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="userDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
               >
                  <span class="mr-2 d-none d-lg-inline text-gray-600 fw-bold">{{
                     user.fullname
                  }}</span>
                  <img
                     class=""
                     :src="user.avatar"
                     alt="avatar"
                     style="width: 35px; height: 35px; border-radius: 50%"
                  />
                  <i class="fa-solid fa-caret-down ml-2"></i>
               </a>
               <!-- Dropdown - User Information -->
               <div
                  class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                  aria-labelledby="userDropdown"
               >
                  <a
                     class="dropdown-item p-2"
                  >
                     <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                     Profile
                  </a>
                  <a class="dropdown-item p-2" href="#">
                     <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                     Settings
                  </a>
               </div>
            </li>
         </ul>
      </div>
   </nav>
</template>

<script>
export default {
   name: "MainTopBar",
   props: {
      user: {
         type: Object,
         required: true,
      },
   },
   setup() {
      return {};
   },
};
</script>

<style></style>
