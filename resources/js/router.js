import { createRouter,createWebHistory } from "vue-router";
import HomeComponent from './vuePages/home/HomeComponent.vue';
import ProductIndex from './vuePages/home/ProductIndex.vue';
import ProductShow from './vuePages/home/ProductShow.vue';
import Checkout from './vuePages/payment/Checkout.vue';
import PaymentStatus from './vuePages/payment/PaymentStatus.vue';
import PaymentFailed from './vuePages/payment/PaymentFailed.vue';
import PaymentSuccess from './vuePages/payment/PaymentSuccess.vue';
import Profile from './vuePages/profile/Profile.vue';
import Register from './vuePages/auth/Register.vue';
import Login from './vuePages/auth/Login.vue';

const routes = [
    { path: '/',name:'home', component: HomeComponent , children:[
        { path: '/',name:'home.product.index', component: ProductIndex },
        { path: 'product/:id',name:'home.product.show', component: ProductShow },
        { path: 'product/checkout/:id',name:'payment.checkout', component: Checkout , meta: { requiresAuth: true } },
    ]},

    { path: '/payment', name:'payment', component: PaymentStatus , children:[
        { path: 'transaction/:TransactionId/failed',name:'payment.failed', component: PaymentFailed},
        { path: 'transaction/:TransactionId/success',name:'payment.success', component: PaymentSuccess},
    ]},

    { path: '/profile',name:'profile', component: Profile , meta: { requiresAuth: true } },
    { path: '/register',name:'register', component: Register ,meta: { requiresVisitor: true } },
    { path: '/login',name:'login', component: Login ,meta: { requiresVisitor: true }},
];



const router = createRouter({
    history : createWebHistory(),
    routes
})

router.beforeEach((to, from) => {
    if (to.meta.requiresAuth && localStorage.getItem('access_token')==null) {
        return {
            name: 'login',
        }
    }
    if (to.meta.requiresVisitor && localStorage.getItem('access_token')!==null && localStorage.getItem('access_token').length > 50) {
        return {
          name: 'home.product.index',
        }
      }
  })

export default router;
