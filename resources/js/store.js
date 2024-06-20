import { reactive } from "vue";

export const store = reactive({
    isAuthenticated: false,
    token: '',
    authenticate(token) {
        this.token = token
        this.isAuthenticated = true
    },
    logout() {
        console.log('entramos')
        this.token = ''
        this.isAuthenticated = false
    }
})