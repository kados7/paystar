import { defineStore } from "pinia";

export const useAuthStore = defineStore('authData',{
    state: () => {
        return {
          // all these properties will have their type inferred automatically
            token : localStorage.getItem('access_token') || null,
            userLoggedIn : false,
        }
    },

    actions: {
        // since we rely on `this`, we cannot use an arrow function
        login() {
          this.userLoggedIn = true
        },
        logout() {
          this.userLoggedIn = false
        },

    },
})
