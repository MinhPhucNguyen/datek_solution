import axios from 'axios';

const state = {
  cartItems: [],
  totalPrice: 0,
  totalQuantity: 0, 
};

const getters = {
  cartItems: (state) => state.cartItems,
  totalPrice: (state) => state.totalPrice,
  totalQuantity: (state) => state.totalQuantity, 
};

const mutations = {
  setCartItems(state, items) {
    state.cartItems = items;
    state.totalPrice = items.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
    state.totalQuantity = items.reduce((total, item) => total + item.quantity, 0); 
  },
  updateCartItemQuantity(state, { productId, quantity }) {
    const product = state.cartItems.find((item) => item.product_id === productId);
    if (product) {
      product.quantity = quantity;
    }
    state.totalQuantity = state.cartItems.reduce((total, item) => total + item.quantity, 0);
  },
  removeCartItem(state, productId) {
    state.cartItems = state.cartItems.filter(item => item.product_id !== productId);
    
    state.totalQuantity = state.cartItems.reduce((total, item) => total + item.quantity, 0);
    state.totalPrice = state.cartItems.reduce(
      (total, item) => total + item.product.price * item.quantity,
      0
    );
  },
};

const actions = {
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

  async addToCart({ commit, state }, product) {
    const existingProduct = state.cartItems.find(
      (item) => item.product_id === product.product_id
    );

    if (existingProduct) {
      existingProduct.quantity += product.quantity;
    } else {
      state.cartItems.push(product);
    }

    commit('setCartItems', state.cartItems);
  },

  async updateQuantity({ commit }, { productId, quantity }) {
    commit('updateCartItemQuantity', { productId, quantity });
  },

  async removeItem({ commit }, productId) {
    try {
      await axios.delete(`/cart/remove-item/${productId}`);
      
      commit('removeCartItem', productId);
    } catch (error) {
      console.error('Error removing item:', error);
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
