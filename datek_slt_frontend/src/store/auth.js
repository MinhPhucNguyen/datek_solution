import axios from "axios";

const auth = {
  namespaced: true,
  state: {
    token: null,
    user: null,
  },
  getters: {
    isAdmin(state) {
      if (state.user) {
        return state.user.role_as === 1;
      }
    },
    getUser(state) {
      return state.user;
    },
    isAuthenticated(state) {
      if (state.user && state.token) {
        return true;
      }
    },
  },
  mutations: {
    SET_TOKEN(state, token) {
      state.token = token;
    },

    SET_USER(state, user) {
      state.user = user;
    },

    SET_AVATAR(state, avatarUrl) {
      state.user.avatar = avatarUrl;
    },

    RESET_USER(state) {
      state.user = null;
    },
  },
  actions: {
    async register({ dispatch }, registerForm) {
      const response = await axios.post("auth/register", registerForm);
      dispatch("attempt", response.data.token);
      return response;
    },
    async login({ dispatch }, credentials) {
      const response = await axios.post("auth/login", credentials);
      dispatch("attempt", response.data.token);
      return response;
    },
    async attempt({ commit, state }, token) {
      if (token) {
        commit("SET_TOKEN", token);
      }

      if (!state.token) {
        return;
      }

      try {
        const response = await axios.get("user");
        commit("SET_USER", response.data);
      } catch (e) {
        commit("SET_TOKEN", null);
        commit("SET_USER", null);
      }
    },

    async logout({ commit }) {
      return await axios.post("logout").then(() => {
        commit("SET_TOKEN", null);
        commit("SET_USER", null);
      });
    },

    async sendForgotPasswordEmail(ctx, payload) {
      const response = await axios.post("/forgot-password", payload);
      return response;
    },

    async resetPassword(ctx, payload) {
      return await axios.post("/reset-password", payload).then((response) => {
        return response;
      });
    },
  },
};

export default auth;
