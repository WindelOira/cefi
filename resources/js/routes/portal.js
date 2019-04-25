import Dashboard from '@js/components/views/portal/Dashboard.vue'
import Records from '@js/components/views/portal/record/Records.vue'
import Record from '@js/components/views/portal/record/Record.vue'
import Medical from '@js/components/views/portal/record/Medical.vue'
import Dental from '@js/components/views/portal/record/Dental.vue'
import Computation from '@js/components/views/portal/compute/Computation.vue'
import Categories from '@js/components/views/portal/category/Categories.vue'

export default [
    {
        path        : '/portal/',
        component   : Dashboard,
        name        : 'portal.index',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/records/:type',
        component   : Records,
        name        : 'portal.records',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/records/:type/edit/:id',
        component   : Record,
        name        : 'portal.records.edit',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/records/medical/:id',
        component   : Medical,
        name        : 'portal.records.medical',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/records/dental/:id',
        component   : Dental,
        name        : 'portal.records.dental',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/computation/',
        component   : Computation,
        name        : 'portal.computation',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/categories/:type',
        component   : Categories,
        name        : 'portal.categories',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/user/medical/',
        component   : Medical,
        name        : 'portal.user.medical',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/user/dental/',
        component   : Dental,
        name        : 'portal.user.dental',
        meta        : {
            auth        : true,
        }
    },
    {
        path        : '/portal/user/profile',
        component   : Record,
        name        : 'portal.user.profile.edit',
        meta        : {
            auth        : true,
        }
    },
]