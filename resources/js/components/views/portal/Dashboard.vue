<template>
    <div>
        <page-header>Dashboard</page-header>
        <d-row>
            <d-col md="3" sm="6" cols="12">
                <d-card @click.native="$router.push({name: 'portal.records', params: { type: 'employee'}})" style="cursor: pointer;">
                    <d-card-body>
                        <h4 class="mb-0">Employees</h4>
                    </d-card-body>
                </d-card>
            </d-col>
            <d-col md="3" sm="6" cols="12">
                <d-card @click.native="$router.push({name: 'portal.records', params: { type: 'student'}})" style="cursor: pointer;">
                    <d-card-body>
                        <h4 class="mb-0">Students</h4>
                    </d-card-body>
                </d-card>
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