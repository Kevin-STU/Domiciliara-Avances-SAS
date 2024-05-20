import {createStore} from "vuex";

import { mapActions } from "vuex/dist/vuex.cjs.js";

const store = createStore({
    state: {
        user: {
            data: {},
            token: null
        }
    },
    getters: {},
    actions: {},
    mutations: {},
    modules: {}
})

export default store;
