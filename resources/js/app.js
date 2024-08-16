import './bootstrap';
import "vue-select/dist/vue-select.css";
import {createApp} from 'vue'
import * as VueRouter from 'vue-router'
import vSelect from 'vue-select'
import HomeComponent from './components/home.vue'
import DepartmentComponent from './components/department.vue'
import PaymentComponent from './components/payment.vue'
import RecordComponent from './components/payment-record.vue'
import UserComponent from './components/user.vue'
import ProfileComponent from './components/profile.vue'

const routes = [
    {path: '/', component: HomeComponent},
    {path: '/departments', component: DepartmentComponent},
    {path: '/payments', component: PaymentComponent},
    {path: '/records', component: RecordComponent},
    {path: '/users', component: UserComponent},
    {path: '/profile', component: ProfileComponent},

]

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory('/'),
    routes,
    linkActiveClass: "active",
    linkExactActiveClass: "exact-active",
})
window.url = '/'
const app = createApp({})
app.use(router)
app.mount('#app')
app.component('v-select', vSelect)
