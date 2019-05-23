<template>
    <div>
        <page-header>{{ $route.params.type }} Records</page-header>
        <d-row>
            <d-col cols="12">
                <div class="form-row">
                    <d-col md="1">
                        <d-button @click.native="$router.push({name: 'portal.records.new'})">Add New</d-button>
                    </d-col>
                    <d-col md="4" offset-md="7" sm="6" offset-sm="6" class="form-group">
                        <d-input v-model="table.filters[0].value" :placeholder="'Search '+ $route.params.type +'...'" class="ml-auto"></d-input>
                    </d-col>
                </div>

                <d-row>
                    <d-col cols="12">
                        <ul class="list-unstyled d-flex">
                            <li>
                                <d-link @click.native="filter(0)" class="text-secondary mr-2 small">Saved</d-link>
                                <d-link @click.native="filter(1)" class="text-secondary small">Archived</d-link>
                            </li>
                        </ul>
                    </d-col>
                </d-row>

                <data-tables :data="table.data" :filters="table.filters" @selection-change="tableSelection">
                    <el-table-column type="selection" width="55">
                    </el-table-column>

                    <el-table-column label="Name" min-width="100px">
                        <template slot-scope="scope">
                            <strong class="font-weight-bold">{{ scope.row.metas.fname ? scope.row.metas.fname +' '+ scope.row.metas.mname +' '+ scope.row.metas.lname : scope.row.email }}</strong>
                            <ul v-if="scope.row.deleted_at == null" class="list-unstyled m-0 d-flex">
                                <li v-for="button in tableButtons(scope.row).saved" :key="button.name">
                                    <d-link :class="button.class" @click.native="button.handler(scope.row)">
                                        {{ button.name }}
                                    </d-link>
                                </li>
                            </ul>
                            <ul v-else class="list-unstyled m-0 d-flex">
                                <li v-for="button in tableButtons(scope.row).archived" :key="button.name">
                                    <d-link :class="button.class" @click.native="button.handler(scope.row)">
                                        {{ button.name }}
                                    </d-link>
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
            filter(trashed = 0) {
                this.table.data = []

                this.axios({
                    url     : 'users/'+ this.$route.params.type +'/'+ trashed,
                    method  : 'GET',
                }).then((data) => {
                    this.table.data = data.data
                })
            },
            tableButtons() {
                return {
                    saved   : [{
                        name    : 'View Record',
                        class   : 'text-primary mr-2',
                        handler : row => {
                            this.$router.push({
                                name    : 'portal.records.medical', 
                                params  : {
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
                                url         : 'user/'+ row.id
                            }).then(({data}) => {
                                this.table.data.splice(this.table.data.indexOf(row), 1)
                                this.loading = false
                                this.$message(this.$route.params.type +' moved to archive')
                            })
                        }
                    }],
                    archived    : [{
                        name    : 'Restore',
                        class   : 'text-primary mr-2',
                        handler : row => {
                            Vue.axios({
                                method      : 'POST',
                                url         : '/user/restore',
                                params      : {
                                    id          : row.id,
                                }
                            }).then(({data}) => {
                                this.table.data.splice(this.table.data.indexOf(row), 1)
                                this.loading = false
                                this.$message(this.$route.params.type +' restored')
                            })
                        }
                    }, {
                        name    : 'Permanently Delete',
                        class   : 'text-danger',
                        handler : row => {
                            Vue.axios({
                                method      : 'DELETE',
                                url         : 'user/'+ row.id
                            }).then(({data}) => {
                                this.table.data.splice(this.table.data.indexOf(row), 1)
                                this.loading = false
                                this.$message(this.$route.params.type +' permanently deleted.')
                            })
                        }
                    }]
                }
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