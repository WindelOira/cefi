import VueRouter from 'vue-router'

import Public from '@js/routes/public.js'
import Portal from '@js/routes/portal.js'

var routes = []
routes = routes.concat(Public, Portal)

const router = new VueRouter({
    history : true,
    mode    : 'history',
    routes  : routes,
})

export default router