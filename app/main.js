import App from '@/src/App.vue'
import LandingPage from '@/src/components/LandingPage.vue'
import HomePage from '@/src/components/HomePage.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            { path: '/homepage', component: HomePage,},
        }
    ],

})

