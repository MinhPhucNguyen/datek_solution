import { createStore } from "vuex";
import auth from "./auth";
import users from "./users";
import cart from "./cart";
import orders from "./orders";

const store = createStore({
  modules: {
    auth,
    users,
    cart,
    orders,
  },
});

export default store;
