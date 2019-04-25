<template>
    <div>
        <page-header>Categories</page-header>
        <alerts ref="alerts"></alerts>
        <d-row>
            <d-col cols="12" lg="3" md="4" sm="5">
                <vue-form :state="formState" @submit.prevent="model.category.mode == 'add' ? save() : update()">
                    <validate class="form-group">
                        <label class="mb-1">Parent</label>
                        <d-select v-model="model.category.parent.selected" 
                                :options="model.category.parent.options" 
                                class="form-control-sm"></d-select>
                    </validate>
                    <validate class="form-group">
                        <label class="mb-1">Name</label>
                        <d-input v-model="model.category.name" 
                                required 
                                name="name" 
                                class="form-control-sm"></d-input>
                    </validate>
                    <validate class="form-group">
                        <label class="mb-1">Slug</label>
                        <d-input v-model="model.category.slug" 
                                name="slug" 
                                class="form-control-sm"></d-input>
                    </validate>
                    <validate class="form-group">
                        <label class="mb-1">Description</label>
                        <d-textarea v-model="model.category.description" 
                                    name="description" 
                                    class="form-control-sm"></d-textarea>
                    </validate>

                    <validate class="form-group">
                        <d-checkbox v-model="model.category.complainable" 
                                    inline 
                                    toggle 
                                    :value="true"
                                    class="custom-toggle-sm">Is this a health complaint?</d-checkbox>
                    </validate>

                    <div class="d-flex">
                        <d-button :disabled="formState.$invalid">{{ model.category.mode == 'add' ? 'Save' : 'Update' }}</d-button>
                        <d-button v-if="model.category.mode == 'edit'" 
                                @click.prevent="reset()" 
                                theme="light" 
                                class="ml-auto">Cancel</d-button>
                    </div>
                </vue-form>
            </d-col>
            <d-col cols="12" lg="9" md="8" sm="7">
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
    import Alerts from '@js/components/components/Alerts.vue'

    export default {
        components  : {
            'page-header'   : PageHeader,
            'alerts'        : Alerts,
        },
        data() {
            return {
                formState   : {},
                model       : {
                    category    : {
                        mode        : 'add',
                        id          : null,
                        parent      : {
                            selected    : null,
                            options     : [
                                {value: null, text: 'Select Parent'},
                            ],
                        },
                        type        : this.$route.params.type,
                        name        : null,
                        slug        : null,
                        description : null,
                        complainable: false,
                    },
                },
                table       : {
                    activeIndex : null,
                    data        : [],
                    titles      : [
                        {prop: 'name', label: 'Name',},
                    ],
                    filters     : [
                        {prop: 'name', value: '',},
                    ],
                    selected    : [],
                }
            }
        },
        watch       : {
            '$route'    : {
                handler     : 'get',
            },
        },
        methods     : {
            get() {
                Vue.axios({
                    method  : 'GET',
                    url     : '/categories/'+ this.$route.params.type
                }).then((response) => {
                    this.table.data = response.data

                    if( 0 < response.data.length )
                        response.data.forEach((category) => {
                            if( category.parent == null )
                                this.model.category.parent.options.push({
                                    value   : category.id,
                                    text    : category.name
                                })
                        })
                })
            },
            update() {
                Vue.axios({
                    url     : '/category/'+ this.model.category.id,
                    method  : 'PUT',
                    params  : {
                        parent          : this.model.category.parent.selected,
                        name            : this.model.category.name,
                        slug            : this.model.category.slug,
                        description     : this.model.category.description,
                        complainable    : this.model.category.complainable ? 1 : 0,
                    },
                }).then((response) => {
                    this.$refs.alerts.add('Category updated.', 'messages')

                    this.table.data[this.table.activeIndex].parent = response.data.parent
                    this.table.data[this.table.activeIndex].name = response.data.name
                    this.table.data[this.table.activeIndex].slug = response.data.slug
                    this.table.data[this.table.activeIndex].description = response.data.description
                    this.table.data[this.table.activeIndex].complainable = response.data.complainable ? true : false

                    this.reset()
                })
            },
            save() {
                Vue.axios({
                    url     : '/category',
                    method  : 'POST',
                    params  : {
                        parent          : this.model.category.parent.selected,
                        type            : this.$route.params.type,
                        name            : this.model.category.name,
                        slug            : this.model.category.slug,
                        description     : this.model.category.description,
                        complainable    : this.model.category.complainable ? 1 : 0,
                    },
                }).then((response) => {
                    this.$refs.alerts.add('New category added', 'messages')

                    this.table.data.push(response.data)
                    if( response.data.parent == null )
                        this.model.category.parent.options.push({
                            value   : response.data.id,
                            text    : response.data.name
                        })

                    this.reset()
                })
            },
            reset() {
                this.table.activeIndex = null

                this.model.category.mode = 'add'
                this.model.category.parent.selected = null
                this.model.category.name = null
                this.model.category.slug = null
                this.model.category.description = null
                this.model.category.complainable = false
            },
            tableButtons() {
                return [{
                    name    : 'Edit',
                    class   : 'text-primary mr-2',
                    handler : row => {
                        Vue.axios({
                            method      : 'GET',
                            url         : 'category/'+ row.id
                        }).then((response) => {
                            this.table.activeIndex = this.table.data.indexOf(row)

                            this.model.category.id = response.data.id
                            this.model.category.mode = 'edit'
                            this.model.category.parent.selected = response.data.parent
                            this.model.category.name = response.data.name
                            this.model.category.slug = response.data.slug
                            this.model.category.description = response.data.description
                            this.model.category.complainable = response.data.complainable == 1 ? true : false
                        })
                    }
                },{
                    name    : 'Delete',
                    class   : 'text-danger',
                    handler : row => {
                        Vue.axios({
                            method      : 'DELETE',
                            url         : 'category/'+ row.id
                        }).then((response) => {
                            this.table.data.splice(this.table.response.indexOf(row), 1)
                            this.loading = false
                            this.$refs.alerts.add('Category '+ response.data.name +' deleted', 'errors')
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
