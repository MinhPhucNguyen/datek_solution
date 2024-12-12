import {createStore} from "vuex";
import auth from "./auth";
import users from "./users";
import cart from "./cart";

const store = createStore({
    modules: {
        auth,
        users,
        cart
    },
});

export default store;
