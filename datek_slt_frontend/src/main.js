import {createApp} from 'vue'
import App from './App.vue'
import router from "./router/index";
import "./assets/styles/main.scss";
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {fas} from "@fortawesome/free-solid-svg-icons";
import store from "./store/store";
import axios from "axios";
import jquery from "jquery";
window.$ = window.jQuery = jquery;

library.add(fas);

require("@/store/subscriber");
axios.defaults.baseURL = "https://datek_slt.localhost.com/api";

store.dispatch("auth/attempt", localStorage.getItem("token")).then(() => {
    createApp(App)
        .use(router)
        .use(store)
        .component('font-awesome-icon', FontAwesomeIcon)
        .mount('#app')
});

