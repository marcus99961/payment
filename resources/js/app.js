import './bootstrap';
import "vue-select/dist/vue-select.css";
import {createApp} from 'vue'
import * as VueRouter from 'vue-router'
import vSelect from 'vue-select'
import { createPinia } from 'pinia';
import HomeComponent from './components/home.vue'
import DepartmentComponent from './components/department.vue'
import PaymentComponent from './components/payment.vue'
import { useSettingStore } from './stores/SettingStore';
import { useAuthUserStore } from './stores/AuthUserStore';
import RecordComponent from './components/payment-record.vue'
import UserComponent from './components/user.vue'
import SettingComponent from './components/setting.vue'
import ProfileComponent from './components/profile.vue'

const routes = [
    {path: '/', component: HomeComponent},
    {path: '/departments', component: DepartmentComponent},
    {path: '/payments', component: PaymentComponent},
    {path: '/records', component: RecordComponent},
    {path: '/users', component: UserComponent},
    {path: '/setting', component: SettingComponent},
    {path: '/profile', component: ProfileComponent},

]
const pinia = createPinia();
const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory('/'),
    routes,
    linkActiveClass: "active",
    linkExactActiveClass: "exact-active",
})
router.beforeEach(async (to, from) => {
    const authUserStore = useAuthUserStore();
    if (authUserStore.user.name === '' && to.name !== 'admin.login') {
        const settingStore = useSettingStore();
        await Promise.all([
            authUserStore.getAuthUser(),
            settingStore.getSetting(),
        ]);
    }
});
window.url = '/'
const app = createApp({})
app.use(router)
app.use(pinia)
app.mount('#app')
app.component('v-select', vSelect)
