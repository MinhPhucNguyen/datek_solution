<template>
  <div class="customer-account-register">
    <div class="container">
      <div class="page-main">
        <div class="register-container">
          <div class="block-customer-register">
            <h3 class="block-title">Đăng ký</h3>
            <form action="">
              <fieldset class="fieldset register">
                <div class="field name">
                  <div class="control required">
                    <label>Tên</label>
                    <input
                      name="firstname"
                      value=""
                      autocomplete="off"
                      id="firstname"
                      type="text"
                      class="input-text"
                      title="Tên"
                    />
                  </div>
                  <div class="control required">
                    <label>Họ</label>
                    <input
                      name="lastname"
                      value=""
                      autocomplete="off"
                      id="lastname"
                      type="text"
                      class="input-text"
                      title="Họ"
                    />
                  </div>
                </div>
                <div class="field email required">
                  <div class="control">
                    <label>Email</label>
                    <input
                      name="email"
                      value=""
                      autocomplete="off"
                      id="email"
                      type="email"
                      class="input-text"
                      title="Email"
                    />
                  </div>
                </div>
                <div class="field password required">
                  <div class="control">
                    <label>Mật khẩu</label>
                    <input
                      name="password"
                      type="password"
                      autocomplete="off"
                      class="input-text"
                      id="password"
                      title="Mật khẩu"
                    />
                  </div>
                </div>
                <div class="field confirm-password required">
                  <div class="control">
                    <label>Xác nhận mật khẩu</label>
                    <input
                      name="confirm-password"
                      type="password"
                      autocomplete="off"
                      class="input-text"
                      id="password"
                      title="Xác nhận mật khẩu"
                    />
                  </div>
                </div>
                <div class="field choice">
                  <input
                    type="checkbox"
                    name="show-password"
                    id="show-password"
                    class="checkbox"
                    title="Hiển thị mật khẩu"
                  />
                  <label for="show-password" class="label"
                    ><span>Hiển thị mật khẩu</span></label
                  >
                </div>
                <div class="actions-toolbar">
                  <div class="primary">
                    <button
                      type="submit"
                      class="action register primary"
                      name="send"
                      id="send2"
                    >
                      <span>Đăng ký</span>
                    </button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style scoped>
@import url(RegisterPage.scss);
</style>
