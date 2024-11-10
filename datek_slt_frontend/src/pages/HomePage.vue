<template>
  <div>
    <div class="block-banner">
      <div class="container">
        <div class="block-banner-inner">
          <div class="block-banner-image">
            <img
              src="../../assets/images/banner-images/phuc.png"
              alt="banner"
            />
          </div>
          <div class="block-banner-content">
            <p class="block-banner-title">Lenovo ThinkPad</p>
            <p class="block-banner-subtitle">T14s Gen 3</p>
            <div class="shop-now-btn">
              <a href="">Mua ngay</a>
            </div>
          </div>
          <div class="block-search">
            <div class="block-search-inner">
              <p>Tìm kiếm sản phẩm để bắt đầu!</p>
              <form action="">
                <input
                  type="text"
                  class="search-input"
                  placeholder="Nhập tên sản phẩm hoặc SKU"
                />
                <button type="submit" class="search-btn">Tìm kiếm</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="test">asdasd</div>
  </div>
</template>

<script setup></script>

<style scoped lang="scss">
.block-banner {
  width: 100%;
  padding: 40px 0;
  position: relative;
}

.block-banner-inner {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: space-between;

  .block-search {
    position: absolute;
    width: 60%;
    margin: 0 auto;
    background: #ffffff;
    height: 120px;
    border-radius: 5px;
    bottom: -60px;
    left: 0;
    right: 0;
    border: 1px solid #4e43d8;

    .block-search-inner {
      display: flex;
      align-items: center;
      flex-direction: column;
      margin-top: 20px;

      p{
        margin-bottom: 8px;
        font-weight: bold;
      }

      form {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
      }

      input.search-input {
        width: 40%;
        height: 40px;
        border-radius: 2px;
        font-size: 1rem;
        border: 1px solid #ccc;
        padding: 0 10px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;

        &:focus-visible {
          outline: none;
        }
      }

      .search-btn {
        height: 40px;
        padding: 5px 30px;
        border: none;
        background: #4e43d8;
        color: #ffffff;
        font-weight: bold;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
      }
    }
  }
}
img {
  width: 80%;
  height: 80%;
  object-fit: cover;
}
.block-banner-image {
  width: 50%;
  height: 100%;
}

.block-banner-content {
  width: 70%;
  height: 100%;
  padding: 100px 0;

  p {
    font-family: "Roboto", sans-serif;
    font-size: 70px;
    font-weight: bold;
    color: #4e43d8;
  }
}

.shop-now-btn {
  margin-top: 40px;
  width: 200px;
  background-color: #4e43d8;
  padding: 20px 0px;
  text-align: center;
  border-radius: 10px;

  a {
    color: #ffffff;
    font-size: 18px;
    font-weight: bold;
  }
}

.test {
  background: #f5f5f5;
  height: 100vh;
}
</style>
