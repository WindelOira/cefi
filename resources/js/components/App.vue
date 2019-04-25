<template>
    <d-container fluid>
        <d-row>
            <cefi-navbar></cefi-navbar>
            <d-col tag="main" cols="12" class="main-content p-0">
                <d-container fluid>
                    <d-row>
                        <cefi-sidebar v-if="$auth.check()"></cefi-sidebar>
                        <d-col cols="12" :md="$auth.check() ? 9 : 12" :lg="$auth.check() ? 10 : 12">
                            <d-container fluid class="main-content-container py-4 px-0">
                                <router-view></router-view>
                            </d-container>
                            <cefi-footer></cefi-footer>
                        </d-col>
                    </d-row>
                </d-container>
            </d-col>
        </d-row>
    </d-container>
</template>

<script>
    import Navbar from '@js/components/components/Navbar.vue'
    import Sidebar from '@js/components/components/Sidebar.vue'
    import Footer from '@js/components/components/Footer.vue'

    export default {
        components  : {
            'cefi-navbar'   : Navbar,
            'cefi-sidebar'  : Sidebar,
            'cefi-footer'   : Footer,
        },
    }
</script>

