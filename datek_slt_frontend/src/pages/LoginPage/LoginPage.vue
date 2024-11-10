<template>
  <div class="customer-account-login">
    <div class="container">
      <div class="page-main">
        <div class="login-container">
          <div class="block-customer-login">
            <form action="">
              <div class="block-title">
                <span class="active title">Đăng nhập</span>
                <a class="title" href="#">Đăng ký</a>
              </div>
              <fieldset class="fieldset login">
                <div class="field email required">
                  <div class="control">
                    <input
                      name="email"
                      value=""
                      autocomplete="off"
                      id="email"
                      type="email"
                      class="input-text"
                      title="Email"
                      placeholder="Email"
                    />
                  </div>
                </div>
                <div class="field password required">
                  <div class="control">
                    <input
                      name="password"
                      type="password"
                      autocomplete="off"
                      class="input-text"
                      id="password"
                      title="Password"
                      placeholder="Mật khẩu"
                    />
                  </div>
                </div>
                <div class="actions-toolbar">
                  <div class="primary">
                    <button
                      type="submit"
                      class="action login primary"
                      name="send"
                      id="send2"
                    >
                      <span>Đăng nhập</span>
                    </button>
                    <a
                      class="action remind"
                      href="#"
                      ><span>Quên mật khẩu</span></a
                    >
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
@import url(LoginPage.scss);
</style>
