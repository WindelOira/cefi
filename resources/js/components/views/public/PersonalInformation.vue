<template>
    <d-row>
        <d-col cols="12" lg="6" offset-lg="3" md="8" offset-md="2" sm="10" offset-sm="1">
            <d-card class="card-small">
                <d-card-header>
                    <h5 class="mb-0 text-capitalize d-flex align-items-start">
                        <d-link :to="{name: 'forms'}" class="mr-2">
                            <vue-material-icon name="chevron_left"></vue-material-icon>
                        </d-link>
                        {{ $route.params.type }}'s Infomation
                    </h5>
                </d-card-header>
                <d-card-body>
                    <vue-form :state="formState" @submit.prevent="register()">
                        <div v-if="panel == 0">
                            <validate class="form-group">
                                <label class="mb-">Email Address</label>
                                <d-input v-model="model.email" 
                                        type="email"  
                                        name="email" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>

                            <validate class="form-group">
                                <label class="mb-">Password</label>
                                <d-input v-model="model.password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>

                            <hr>

                            <validate class="form-group">
                                <label class="mb-1">Name</label>
                                <d-input v-model="model.name"
                                        name="name" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>
                            <validate class="form-group">
                                <label class="mb-1">Address</label>
                                <d-input v-model="model.meta.address" 
                                        name="address" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>
                            <validate class="form-group">
                                <label class="mb-1">Birthday</label>
                                <d-input v-model="model.meta.birthday"
                                        required 
                                        name="birthday" 
                                        class="form-control-sm"></d-input>
                            </validate>
                            <validate class="form-group">
                                <label class="mb-1">{{ $route.params.type == 'employee' ? 'Department/Position' : 'Grade/Course' }}</label>
                                <d-input v-model="model.meta.designation" 
                                        name="designation" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>

                            <div class="form-row">
                                <validate class="form-group col-md-6">
                                    <label class="mb-1">Weight</label>
                                    <d-input v-model="model.meta.weight" 
                                            name="weight" 
                                            required 
                                            class="form-control-sm"></d-input>
                                </validate>
                                <validate class="form-group col-md-6">
                                    <label class="mb-1">Height</label>
                                    <d-input v-model="model.meta.height" 
                                            name="height" 
                                            required 
                                            class="form-control-sm"></d-input>
                                </validate>
                                <validate class="form-group col-md-6">
                                    <label class="mb-1">BP</label>
                                    <d-input v-model="model.meta.bp" 
                                            name="bp" 
                                            required 
                                            class="form-control-sm"></d-input>
                                </validate>
                            </div>
                            
                            <h6 class="mb-1">Person to be notified in case of emergency:</h6>
                            <validate class="form-group">
                                <label class="mb-1">Name</label>
                                <d-input v-model="model.meta.guardian.name" 
                                        name="employee" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>
                            <validate class="form-group">
                                <label class="mb-1">Contact No.</label>
                                <d-input v-model="model.meta.guardian.phone" 
                                        name="employee" 
                                        required 
                                        class="form-control-sm"></d-input>
                            </validate>
                        </div>

                        <d-tabs v-if="panel == 1">
                            <d-tab v-if="categories.length" 
                                    v-for="category in categories" 
                                    :key="category.id" 
                                    :title="category.title">
                                <div class="p-3">
                                    <d-checkbox v-for="item in category.items" 
                                                v-model="model.records" 
                                                :key="item.key" 
                                                :value="item.key">{{ item.value }}</d-checkbox>
                                </div>
                            </d-tab>
                        </d-tabs>

                        <div class="d-flex">
                            <d-button v-if="panel == 0" theme="light" @click.prevent="panel = 1">Next</d-button>
                            <d-button v-if="panel == 1" theme="light" @click.prevent="panel = 0">Previous</d-button>
                            <d-button v-if="panel == 1" class="ml-auto">Submit</d-button>
                        </div>                         
                    </vue-form>
                </d-card-body>
            </d-card>
        </d-col>
    </d-row>
</template>

<script>
    import Forms from '@js/mixins/forms.js'

    export default {
        mixins  : [
            Forms, 
        ],
        data() {
            return {
                formState   : {},
                panel       : 0,
                categories  : [],
                model       : {
                    type        : this.$route.params.type,
                    email       : null,
                    password    : null,
                    name        : null,
                    meta        : {
                        address     : null,
                        birthday    : null,
                        designation : null,
                        height      : null,
                        weight      : null,
                        bp          : null,
                        guardian    : {
                            name        : null,
                            phone       : null,
                        },
                    },
                    records     : [],
                },
            }
        },
        methods : {
            getCategories() {
                var categories = []

                Vue.axios({
                    url     : '/categories/medical',
                    method  : 'GET',
                }).then((response) => {
                    if( 0 < response.data.length )
                        response.data.forEach(category => {
                            if( ! category.parent && category.parent == null )
                                categories[category.id] = {
                                    id      : category.id,
                                    title   : category.name,
                                    items   : [],
                                }
                            else
                                categories[category.parent].items.push({
                                    key     : category.id,
                                    value   : category.name
                                })
                        })

                        categories.forEach(category => {
                            this.categories.push(category)
                        })
                })
            },
            register() {
                Vue.axios({
                    url     : '/auth/register',
                    method  : 'POST',
                    params  : this.model,
                }).then((response) => {
                    this.$router.push({
                        name : 'personal-information'
                    })
                })
            },
        },
        mounted() {
            if( this.$auth.check() )
                this.$router.push({
                    name : 'portal.index'
                })

            this.getCategories()
        }
    }
</script>

