import axios from 'axios';

const state = {
  cartItems: [],
  totalPrice: 0,
};

const getters = {
  cartItems: (state) => state.cartItems,
  totalPrice: (state) => state.totalPrice,
};

const mutations = {
  setCartItems(state, items) {
    state.cartItems = items;
    state.totalPrice = items.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
  },
  updateCartItemQuantity(state, { productId, quantity }) {
    const product = state.cartItems.find((item) => item.product_id === productId);
    if (product) {
      product.quantity = quantity;
    }
  },
  removeCartItem(state, productId) {
    state.cartItems = state.cartItems.filter((item) => item.product_id !== productId);
  },
};

const actions = {
  // Fetch the cart items from the backend
  async fetchCart({ commit, rootState }) {
    try {
      const userId = rootState.auth.user.id;
      const response = await axios.get('/cart/get-cart', {
        params: { user_id: userId },
      });
      commit('setCartItems', response.data.items);
    } catch (error) {
      console.error('Error fetching cart data:', error);
    }
  },

  // Add or update a product in the cart
  async addToCart({ commit, state }, product) {
    const existingProduct = state.cartItems.find(
      (item) => item.product_id === product.product_id
    );

    if (existingProduct) {
      existingProduct.quantity += product.quantity;
    } else {
      state.cartItems.push(product);
    }

    // Update the total price after adding
    commit('setCartItems', state.cartItems);
  },

  // Update quantity of an item in the cart
  async updateQuantity({ commit }, { productId, quantity }) {
    commit('updateCartItemQuantity', { productId, quantity });
  },

  // Remove an item from the cart
  async removeItem({ commit }, productId) {
    commit('removeCartItem', productId);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
