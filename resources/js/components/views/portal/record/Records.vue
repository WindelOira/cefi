<template>
    <div>
        <page-header>{{ $route.params.type }} Records</page-header>
        <d-row>
            <d-col cols="12">
                <div class="form-row">
                    <d-col md="4" offset-md="8" sm="6" offset-sm="6" class="form-group">
                        <d-input v-model="table.filters[0].value" :placeholder="'Search '+ $route.params.type +'...'" class="ml-auto"></d-input>
                    </d-col>
                </div>

                <data-tables :data="table.data" :filters="table.filters" @selection-change="tableSelection">
                    <el-table-column type="selection" width="55">
                    </el-table-column>

                    <el-table-column label="Name" min-width="100px">
                        <template slot-scope="scope">
                            <strong class="font-weight-bold">{{ scope.row.name }}</strong>
                            <ul class="list-unstyled m-0 d-flex">
                                <li v-for="button in tableButtons(scope.row)" :key="button.name">
                                    <d-link :class="button.class" @click.native="button.handler(scope.row)">{{ button.name }}</d-link>
                                </li>
                            </ul>
                        </template>
                    </el-table-column>
                </data-tables>
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
        data() {
            return {
                table       : {
                    data            : [],
                    titles          : [
                        {prop: 'name', label: 'Name',},
                    ],
                    filters         : [
                        {prop: 'name', value: '',},
                    ],
                    selected        : [],
                },
            }
        },
        watch       : {
            '$route'        : {
                handler         : 'get',
            },
        },
        methods     : {
            get() {
                this.axios({
                    url     : 'users/'+ this.$route.params.type,
                    method  : 'GET',
                }).then((data) => {
                    this.table.data = data.data
                })
            },
            tableButtons() {
                return [{
                    name    : 'Edit',
                    class   : 'text-primary mr-2',
                    handler : row => {
                        this.$router.push({
                            name    : 'portal.records.edit', 
                            params  : {
                                type    : this.$route.params.type, 
                                id      : row.id
                            }
                        })
                    }
                }, {
                    name    : 'Delete',
                    class   : 'text-danger',
                    handler : row => {
                        Vue.axios({
                            method      : 'DELETE',
                            url         : 'patient/'+ row.id
                        }).then(({data}) => {
                            this.table.data.splice(this.data.indexOf(row), 1)
                            this.loading = false
                            this.$message('Employee '+ data.name +' deleted')
                        })
                    }
                }]
            },
            tableSelection(val) {
                this.table.selected = val
            },
        },
        mounted() {
            this.get()
        }
    }
</script>