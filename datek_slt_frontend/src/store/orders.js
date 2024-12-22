import axios from "axios";

const orders = {
  namespaced: true,
  state: {
    orders: [],
    ordersHistory: [],
    orderDetails: null,
    orderStatus: "",
  },
  getters: {
    getAllOrders(state) {
      return state.orders;
    },
    getOrdersHistory(state) {
      return state.ordersHistory;
    },
    getOrderDetails(state) {
      return state.orderDetails;
    },
    getOrderStatus(state) {
      return state.orderStatus;
    },
  },
  mutations: {
    SET_ORDERS(state, orders) {
      state.orders = orders;
    },
    SET_ORDERS_HISTORY(state, orders) {
      state.ordersHistory = orders;
    },
    SET_ORDER_DETAILS(state, order) {
      state.orderDetails = order;
    },
    SET_ORDER_STATUS(state, status) {
      state.orderStatus = status;
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

    async getOrders({ commit }, payload) {
      try {
        const response = await axios.get("/orders?page=" + payload.page);
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

    async getOrdersHistory({ commit }, payload) {
      try {
        const response = await axios.get("/orders/history", {
          params: {
            user_id: payload.user_id,
          },
        });
        commit("SET_ORDERS_HISTORY", response.data.data);
      } catch (error) {
        commit("SET_ORDERS_HISTORY", []);
        throw new Error(
          error.response?.data?.message || "Lỗi lấy lịch sử đơn hàng"
        );
      }
    },

    async getOrderDetails({ commit }, id) {
      try {
        const response = await axios.get(`/orders/history/${id}`);
        commit("SET_ORDER_DETAILS", response.data.data);
      } catch (error) {
        commit("SET_ORDER_DETAILS", null);
        throw new Error(
          error.response?.data?.message || "Lỗi lấy chi tiết đơn hàng"
        );
      }
    },

    async confirmOrder({ commit }, id) {
      try {
        const response = await axios.patch(`/orders/${id}/confirm`);
        commit("SET_ORDER_STATUS", response.data.order_status);
      } catch (error) {
        throw new Error(
          error.response?.data?.message || "Lỗi xác nhận đơn hàng"
        );
      }
    },
  },
};

export default orders;
