import Index from '@js/components/views/public/Index.vue'
import Forms from '@js/components/views/public/Forms.vue'
import PersonalInformation from '@js/components/views/public/PersonalInformation.vue'

export default [
    {
        path        : '/',
        component   : Index, 
        name        : 'index',
    },
    {
        path        : '*',
        redirect    : '/'
    },
]