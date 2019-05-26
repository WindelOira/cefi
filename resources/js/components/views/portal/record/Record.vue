<template>
    <div>
        <page-header v-if="$auth.user().role == 'admin'">{{ $route.params.type }}'s Record</page-header>
        <page-header v-else>Update Profile</page-header>
        <alerts ref="alerts"></alerts>
        <d-row>
            <d-col cols="12" md="6" offset-md="3" sm="8" offset-sm="2">
                <d-card class="user-details mt-5">
                    <d-card-body class="pt-0">
                        <vue-form :state="formState" @submit.prevent="update" enctype="multipart/form-data">
                            <div class="bg-white user-details__avatar mx-auto mb-4" :style="avatar.show ? 'background-image: url('+ avatar.preview +');' : ''">
                                <label class="d-flex align-items-center justify-content-center">
                                    <input ref="avatar" 
                                        type="file" 
                                        accept="image/*" 
                                        @change="uploadAvatar"/>
                                    <vue-material-icon name="photo" size="20"></vue-material-icon>
                                </label>
                            </div>
                            <d-row class="form-row">
                                <d-col>
                                    <validate class="form-group">
                                        <label class="mb-1 text-capitalize">{{ $route.params.type ? $route.params.type : $auth.user().role }} Number</label>
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
                                    <d-btn @click.prevent="togglePass = ! togglePass" 
                                            size="sm" class="mb-2">{{ togglePass ? 'Cancel' : 'Change Password' }}</d-btn>
                                    <validate v-if="togglePass" class="form-group">
                                        <label class="mb-1">Password</label>
                                        <d-input v-model="model.record.password" 
                                                type="password" 
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
                            <d-row v-if="$auth.user().role == 'admin'" class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Birthday</label>
                                        <d-input v-model="model.record.meta.birthday" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">Sex</label>
                                        <d-form-select v-model="model.record.meta.gender.selected" style="height: 34.2px;">
                                            <option v-for="gender in model.record.meta.gender.options" :key="gender.key" :value="gender.key">{{ gender.label }}</option>
                                        </d-form-select>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$auth.user().role == 'admin' && $route.params.type == 'employee'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Position</label>
                                        <d-input v-model="model.record.meta.position" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row class="form-row" v-if="$auth.user().role == 'admin' && $route.params.type == 'student'">
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
                            <d-row class="form-row" v-if="$auth.user().role == 'admin' && $route.params.type == 'student' && model.record.meta.level.selected == 'elementary'">
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
                            <d-row class="form-row" v-if="$auth.user().role == 'admin' && $route.params.type == 'student' && model.record.meta.level.selected == 'high-school'">
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
                            <d-row class="form-row" v-if="$auth.user().role == 'admin' && $route.params.type == 'student' && model.record.meta.level.selected == 'senior-high'">
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
                            <d-row class="form-row" v-if="$auth.user().role == 'admin' && $route.params.type == 'student' && model.record.meta.level.selected == 'college'">
                                <d-col cols="12">
                                    <validate class="form-group">
                                        <label class="mb-1">Course</label>
                                        <d-input v-model="model.record.meta.stage" class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row v-if="$auth.user().role == 'admin'" class="form-row">
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
                            <d-row v-if="$auth.user().role == 'admin'" class="form-row">
                                <d-col md="6" sm="6" class="col">
                                    <validate class="form-group">
                                        <label class="mb-1">BP</label>
                                        <d-input v-model="model.record.meta.bp" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>
                            <d-row v-if="$auth.user().role == 'admin'" class="form-row">
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
                                        @click.native="$router.push({name: 'portal.records.medical', params: {id: model.record.id}})" 
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
                togglePass  : false,
                formState   : {},
                avatar      : {
                    show        : false,
                    preview     : '',
                },
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
                        avatar      : null,
                        email       : null,
                        password    : null,
                        meta        : {
                            number      : null,
                            avatar      : 0,
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
        methods     : {
            uploadAvatar() {
                this.model.record.avatar = this.$refs.avatar.files[0]

                let reader  = new FileReader()

                reader.addEventListener("load", function () {
                    this.avatar.show = true
                    this.avatar.preview = reader.result
                }.bind(this), false)
              
                if( this.model.record.avatar ){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.model.record.avatar.name ) ) {
                        reader.readAsDataURL( this.model.record.avatar )
                    }
                }
            },
            get() {
                Vue.axios({
                    method  : 'GET',
                    url     : 'user/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                }).then((response) => {
                    this.model.record = response.data

                    if( response.data.meta.avatar ) {
                        this.avatar.show = true
                        this.avatar.preview = '/storage/avatars/'+ response.data.meta.avatar
                    } 
                })
            },
            update() {
                let formData = new FormData
                formData.append('method', 'PUT')
                formData.append('user', this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id)
                formData.append('avatar', this.model.record.avatar)
                formData.append('data', JSON.stringify(this.model.record))

                Vue.axios.post('/user', formData).then((response) => {
                    this.$refs.alerts.add('Record updated.', 'messages')

                    this.togglePass = false
                    this.model.record.password = null
                })
            }
        },
        mounted() {
            this.get()
        }
    }
</script>

