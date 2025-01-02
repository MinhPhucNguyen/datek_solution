import axios from "axios";

const state = {
  isCartVisible: false,
  cartItems: [],
  totalPrice: 0,
  totalQuantity: 0,
};

const getters = {
  isCartVisible: (state) => state.isCartVisible,
  getCartItems: (state) => state.cartItems,
  totalPrice: (state) => state.totalPrice,
  totalQuantity: (state) =>
    state.cartItems.reduce((total, item) => total + item.quantity, 0),
};

const mutations = {
  SET_CART_VISIBLE(state, isVisible) {
    state.isCartVisible = isVisible;
  },
  SET_CART_ITEMS(state, items) {
    state.cartItems = items;
    state.totalPrice = items.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
    state.totalQuantity = items.reduce(
      (total, item) => total + item.quantity,
      0
    );
  },
  UPDATE_CART_ITEM_QUANTITY(state, { productId, quantity }) {
    const product = state.cartItems.find(
      (item) => item.product_id === productId
    );
    if (product) {
      product.quantity = quantity;
    }
    state.totalQuantity = state.cartItems.reduce(
      (total, item) => total + item.quantity,
      0
    );
    state.totalPrice = state.cartItems.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
  },
  REMOVE_CART_ITEM(state, productId) {
    state.cartItems = state.cartItems.filter(
      (item) => item.product_id !== productId
    );

    state.totalQuantity = state.cartItems.reduce(
      (total, item) => total + item.quantity,
      0
    );
    state.totalPrice = state.cartItems.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
  },
  CLEAR_CART(state) {
    state.cartItems = [];
    state.totalPrice = 0;
    state.totalQuantity = 0;
  },
};

const actions = {
  toggleCartVisibility({ commit }, isVisible) {
    commit("SET_CART_VISIBLE", isVisible);
  },

  async fetchCart({ commit, rootState }) {
    try {
      const userId = rootState.auth.user.id;
      await axios
        .get("/cart/get-cart", {
          params: { user_id: userId },
        })
        .then((response) => {
          commit("SET_CART_ITEMS", response.data.items);
        });
    } catch (error) {
      console.error("Error fetching cart data:", error);
    }
  },

  async updateOrAddToCart({ dispatch, rootState }, payload) {
    try {
      const userId = rootState.auth.user?.id;

      const { productId, quantity } = payload;

      const response = await axios.post("cart/check-product", {
        user_id: userId,
        product_id: productId,
      });

      if (response.data.exists) {
        await axios.post("cart/update-quantity", {
          user_id: userId,
          cart_id: response.data.cart_id,
          product_id: productId,
          quantity: response.data.quantity + quantity,
        });
        dispatch("toggleCartVisibility", true);
      } else {
        await axios.post("cart/add-to-cart", {
          user_id: userId,
          product_id: productId,
          quantity,
        });
        dispatch("toggleCartVisibility", true);
      }
    } catch (error) {
      console.error("Error in updateOrAddToCart:", error);
      throw error;
    }
  },

  async updateQuantity({ commit }, { productId, quantity }) {
    commit("UPDATE_CART_ITEM_QUANTITY", { productId, quantity });

    try {
      const response = await axios.post("/cart/update-quantity", {
        product_id: productId,
        quantity,
      });

      if (!response.data.success) {
        commit("UPDATE_CART_ITEM_QUANTITY", { productId, quantity: -quantity });
      }
    } catch (error) {
      console.error("Error updating quantity:", error);
    }
  },

  async removeItem({ commit }, productId) {
    try {
      await axios.delete(`/cart/remove-item/${productId}`).then(() => {
        commit("REMOVE_CART_ITEM", productId);
      });
    } catch (error) {
      console.error("Error removing item:", error);
    }
  },

  clearCart({ commit }) {
    commit("CLEAR_CART");
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
