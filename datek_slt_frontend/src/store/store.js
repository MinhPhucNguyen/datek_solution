import { createStore } from "vuex";
import auth from "./auth";
import users from "./users";
import cart from "./cart";
import order from "./order";

const store = createStore({
  modules: {
    auth,
    users,
    cart,
    order,
  },
});

export default store;
