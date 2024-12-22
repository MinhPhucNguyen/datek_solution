import axios from "axios";

const orders = {
  namespaced: true,
  state: {
    orders: [],
  },
  getters: {
    getAllOrders(state) {
      return state.orders;
    },
  },
  mutations: {
    SET_ORDERS(state, orders) {
      state.orders = orders;
    },
  },
  actions: {
    async placeOrder({ commit, dispatch }, payload) {
      try {
        const response = await axios.post("/place-order", payload);
        commit("SET_ORDERS", response.data.order);
        dispatch("cart/clearCart", null, { root: true });
      } catch (error) {
        throw new Error(
          error.response?.data?.message || "Lỗi đặt hàng hoặc số lượng không đủ"
        );
      }
    },

    async getOrders({ commit }) {
      try {
        const response = await axios.get("/orders");
        commit("SET_ORDERS", response.data.data);
        const pagination = {
          currentPage: response.data.meta.current_page,
          lastPage: response.data.meta.last_page,
        };
        return { pagination };
      } catch (error) {
        commit("SET_ORDERS", []);
        throw new Error(
          error.response?.data?.message || "Lỗi lấy danh sách đơn hàng"
        );
      }
    },
  },
};

export default orders;
