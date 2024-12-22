import axios from "axios";

const order = {
  namespaced: true,
  state: {
    orders: [],
  },
  getters: {
    orders(state) {
      return state.orders;
    },
  },
  mutations: {
    SET_ORDERS(state, orders) {
      state.orders = orders;
    },
  },
  actions: {
    async placeOrder({ commit }, payload) {
      try {
        console.log(payload);
        const response = await axios.post("/place-order", payload);
        console.log(response.data);
        commit("SET_ORDERS", response.data.order);
      } catch (error) {
        throw new Error(
          error.response?.data?.message || "Lỗi đặt hàng hoặc số lượng không đủ"
        );
      }
    },
  },
};

export default order;
