<template>
    <div>
        <page-header v-if="$auth.user().role == 'admin'">{{ $route.params.type }}'s Record</page-header>
        <page-header v-else>Update Profile</page-header>
        <alerts ref="alerts"></alerts>
        <d-row>
            <d-col cols="12" md="6" offset-md="3" sm="8" offset-sm="2">
                <d-card>
                    <d-card-header class="d-flex">
                        <d-button-group size="small" class="ml-auto">
                            <d-button @click="$router.push({name: 'portal.records.medical', params: {id: $route.params.id}})">Medical</d-button>
                            <d-button @click="$router.push({name: 'portal.records.dental', params: {id: $route.params.id}})">Dental</d-button>
                        </d-button-group>
                    </d-card-header>
                    <d-card-body>
                        <vue-form :state="formState" @submit.prevent="update">
                            <d-row class="form-row">
                                <d-col>
                                    <validate class="form-group">
                                        <label class="mb-1">Name</label>
                                        <d-input v-model="model.record.name" 
                                                required 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col>
                                    <validate class="form-group">
                                        <label class="mb-1">Address</label>
                                        <d-input v-model="model.record.meta.address" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Birthday</label>
                                        <d-input v-model="model.record.meta.birthday" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Gender</label>
                                        <d-form-select v-model="model.record.meta.gender.selected" style="height: 34.2px;">
                                            <option v-for="gender in model.record.meta.gender.options" :key="gender.key" :value="gender.key">{{ gender.label }}</option>
                                        </d-form-select>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$auth.user().role != 'student'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Position</label>
                                        <d-input v-model="model.record.meta.position" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Weight</label>
                                        <d-input v-model="model.record.meta.weight" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Height</label>
                                        <d-input v-model="model.record.meta.height" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">BP</label>
                                        <d-input v-model="model.record.meta.bp" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Guardian</label>
                                        <d-input v-model="model.record.meta.guardian.name" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Contact No.</label>
                                        <d-input v-model="model.record.meta.guardian.phone" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <div class="d-flex">
                                <d-button>Update</d-button>
                                <d-button v-if="$auth.user().role == 'admin'" 
                                        theme="light" 
                                        @click="$router.push({name: 'portal.records', params: {type: $route.params.type}})" 
                                        class="ml-auto d-inline-flex align-items-center">
                                    <vue-material-icon name="keyboard_backspace" size="20"></vue-material-icon>
                                    <span class="ml-2">Return</span>
                                </d-button>
                            </div>
                        </vue-form>
                    </d-card-body>
                </d-card>
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
                    record    : {
                        name        : null,
                        meta        : {
                            address     : null,
                            birthday    : null,
                            position    : null,
                            weight      : null,
                            height      : null,
                            bp          : null,
                            gender      : {
                                selected    : 'm',
                                options     : [
                                    {key: 'm', label: 'Male'},
                                    {key: 'f', label: 'Female'},
                                ],
                            },
                            guardian    : {
                                name        : null,
                                phone       : null,
                            },
                        },
                    },
                },
            }
        },
        methods     : {
            get() {
                Vue.axios({
                    method  : 'GET',
                    url     : 'user/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                }).then((response) => {
                    this.model.record = response.data
                })
            },
            update() {
                Vue.axios({
                    method  : 'PUT',
                    url     : 'user/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                    params  : this.model.record,
                }).then((response) => {
                    this.$refs.alerts.add('Record updated.', 'messages')
                })
            }
        },
        mounted() {
            this.get()
        }
    }
</script>

