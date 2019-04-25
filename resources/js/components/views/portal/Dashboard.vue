<template>
    <div>
        <page-header>Dashboard</page-header>
        <d-row>
            <d-col cols="12">
                {{ $auth.user().role }}
            </d-col>
        </d-row>
    </div>
</template>

<script>
    import PageHeader from '@js/components/components/PageHeader.vue'

    export default {
        components  : {
            'page-header'   : PageHeader,
        },
        mounted() {
        }
    }
</script>