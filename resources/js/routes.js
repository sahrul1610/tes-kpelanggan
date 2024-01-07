export const routes = [
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: () => import(/* webpackChunkName: "404" */ './Pages/404.vue')
    },
    {
        path: '/home',
        name: 'home',
        component: () => import(/* webpackChunkName: "home" */ './Pages/Home.vue')
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import(/* webpackChunkName: "dashboard" */ './Pages/DashboardView.vue')
    },
    {
        path: '/keluhan-pelanggan',
        name: 'keluhan_pelanggan',
        component: () => import(/* webpackChunkName: "keluhan_pelanggan" */ './Pages/KeluhanPelanggan.vue')
    },
    {
        path: '/tambah-keluhan',
        name: 'formTambahKeluhan',
        component: () => import(/* webpackChunkName: "keluhan_pelanggan" */ './Pages/FormTambahKeluhan.vue')
    },
    {
        path: '/keluhan-pelanggan/:id',
        name: 'formEditKeluhan',
        component: () => import(/* webpackChunkName: "keluhan_pelanggan" */ './Pages/FormEditKeluhan.vue')
    },
];
