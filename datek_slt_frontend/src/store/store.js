import {createStore} from "vuex";
import auth from "./auth";
import users from "./users";

const store = createStore({
    modules: {
        auth,
        users,
    },
});

export default store;
