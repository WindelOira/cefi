<template>
    <div>
        <page-header v-if="$auth.user().role == 'admin'">Add New {{ $route.params.type }}</page-header>
        <page-header v-else>Update Profile</page-header>
        <alerts ref="alerts"></alerts>
        <d-row>
            <d-col cols="12" md="6" offset-md="3" sm="8" offset-sm="2">
                <d-card>
                    <d-card-body>
                        <vue-form :state="formState" @submit.prevent="save">
                            <d-row class="form-row">
                                <d-col>
                                    <validate class="form-group">
                                        <label class="mb-1 text-capitalize">{{ $route.params.type }} Number</label>
                                        <d-input v-model="model.record.meta.number"
                                                name="number" 
                                                required 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col md="4">
                                    <validate class="form-group">
                                        <label class="mb-1">First Name</label>
                                        <d-input v-model="model.record.meta.fname" 
                                                required 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col md="4">
                                    <validate class="form-group">
                                        <label class="mb-1">Middle Name</label>
                                        <d-input v-model="model.record.meta.mname" 
                                                required 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col md="4">
                                    <validate class="form-group">
                                        <label class="mb-1">Last Name</label>
                                        <d-input v-model="model.record.meta.lname" 
                                                required 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col>
                                    <validate class="form-group">
                                        <label class="mb-1">Email Address</label>
                                        <d-input v-model="model.record.email" 
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
                            <d-row class="form-row" v-if="$route.params.type == 'employee'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Position</label>
                                        <d-input v-model="model.record.meta.position" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$route.params.type == 'student'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Level</label>
                                        <d-select v-model="model.record.meta.level.selected" class="form-control-sm">
                                            <option v-for="level in model.record.meta.level.options" 
                                                    :key="level.key" 
                                                    :value="level.key">{{ level.label }}</option>
                                        </d-select>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$route.params.type == 'student' && model.record.meta.level.selected == 'elementary'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Grade</label>
                                        <d-select v-model="model.record.meta.stage" class="form-control-sm">
                                            <option v-for="elementary in options.elementary" 
                                                    :key="elementary.key" 
                                                    :value="elementary.key">{{ elementary.label }}</option>
                                        </d-select>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$route.params.type == 'student' && model.record.meta.level.selected == 'high-school'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Grade</label>
                                        <d-select v-model="model.record.meta.stage" class="form-control-sm">
                                            <option v-for="highSchool in options.highSchool" 
                                                    :key="highSchool.key" 
                                                    :value="highSchool.key">{{ highSchool.label }}</option>
                                        </d-select>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$route.params.type == 'student' && model.record.meta.level.selected == 'senior-high'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Grade</label>
                                        <d-select v-model="model.record.meta.stage" class="form-control-sm">
                                            <option v-for="seniorHigh in options.seniorHigh" 
                                                    :key="seniorHigh.key" 
                                                    :value="seniorHigh.key">{{ seniorHigh.label }}</option>
                                        </d-select>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$route.params.type == 'student' && model.record.meta.level.selected == 'college'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Course</label>
                                        <d-input v-model="model.record.meta.stage" class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate>
                                        <label class="mb-1">Weight</label>
                                        <d-input-group size="sm" seamless class="mb-2">
                                            <d-input v-model="model.record.meta.weight" 
                                                    name="weight" 
                                                    required/>
                                            <d-input-group-text slot="append">kg.</d-input-group-text>
                                        </d-input-group>
                                    </validate>
                                </d-col>
                                <d-col md="6" sm="6" class="col">
                                    <validate>
                                        <label class="mb-1">Height</label>
                                        <d-input-group size="sm" seamless class="mb-2">
                                            <d-input v-model="model.record.meta.height" 
                                                    name="height" 
                                                    required/>
                                            <d-input-group-text slot="append">ft. / inch.</d-input-group-text>
                                        </d-input-group>
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
                                <d-button>Save</d-button>
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
                options     : {
                    elementary  : [
                        {key: 1, label: 1},
                        {key: 2, label: 2},
                        {key: 3, label: 3},
                        {key: 4, label: 4},
                        {key: 5, label: 5},
                        {key: 6, label: 6},
                    ],
                    highSchool  : [
                        {key: 7, label: 7},
                        {key: 8, label: 8},
                        {key: 9, label: 9},
                        {key: 10, label: 10},
                    ],
                    seniorHigh  : [
                        {key: 11, label: 11},
                        {key: 12, label: 12},
                    ]  
                },
                model       : {
                    record    : {
                        email       : null,
                        type        : this.$route.params.type,
                        meta        : {
                            number      : null,
                            fname       : null,
                            mname       : null,
                            lname       : null,
                            address     : null,
                            birthday    : null,
                            position    : null,
                            weight      : null,
                            height      : null,
                            bp          : null,
                            level       : {
                                selected    : this.$route.params.type == 'employee' ? 'employee' : 'elementary',
                                options     : [
                                    {key: 'elementary', label: 'Elementary'},
                                    {key: 'high-school', label: 'High School'},
                                    {key: 'senior-high', label: 'Senior High'},
                                    {key: 'college', label: 'College'},
                                ]
                            },
                            stage       : this.$route.params.type == 'employee' ? null : 1,
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
        watch       : {
            'model.record.meta.level.selected'  : function(v, ov) {
                if( v == 'elementary' ) {
                    this.model.record.meta.stage = 1
                } else if( v == 'high-school' ) {
                    this.model.record.meta.stage = 7
                } else if( v == 'senior-high' ) {
                    this.model.record.meta.stage = 11
                } else {
                    this.model.record.meta.stage = null
                }
            },
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
            save() {
                Vue.axios({
                    url     : '/user/register',
                    method  : 'POST',
                    params  : this.model.record,
                }).then((response) => {
                    this.$refs.alerts.add('New '+ this.$route.params.type +' added.', 'messages')
                    this.$router.push({
                        name    : 'portal.records.medical',
                        params  : {
                            id      : response.data.id
                        }
                    })
                })
            }
        },
        mounted() {
            this.get()
        }
    }
</script>

