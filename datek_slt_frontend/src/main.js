import {createApp} from 'vue'
import App from './App.vue'
import router from "./router/index";
import "./assets/styles/main.scss";
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {fas} from "@fortawesome/free-solid-svg-icons";
import store from "./store/store";
import axios from "axios";

library.add(fas);

require("@/store/subscriber");
axios.defaults.baseURL = "http://127.0.0.1:8000/api";

store.dispatch("auth/attempt", localStorage.getItem("token")).then(() => {
    createApp(App)
        .use(router)
        .use(store)
        .component('font-awesome-icon', FontAwesomeIcon)
        .mount('#app')
});

