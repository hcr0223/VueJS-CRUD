import { createMemoryHistory, createRouter } from 'vue-router'
import { store } from './store.js'
import Home from './components/Home.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import UsersList from './components/UsersList.vue'

const routes = [
    { path:'/', name:"register", component: Register },
    { path:'/login', name:"login",  component: Login},
    { path:'/home', name:"home", component: Home},
    { path:'/users', name:"users", component: UsersList }
]

const router = new createRouter({
    history: createMemoryHistory(),
    routes,
})

router.beforeEach((to, from) => {
    if(!store.isAuthenticated && to.name !== 'login' && to.name !== 'register') {
        return {name: 'login'}
    }
})

export default router