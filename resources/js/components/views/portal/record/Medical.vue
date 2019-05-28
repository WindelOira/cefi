<template>
    <div>
        <d-row>
            <d-col md="6">
                <page-header>Medical Record : {{ patient.meta.fname ? patient.meta.fname +' '+ patient.meta.mname +' '+ patient.meta.lname : patient.email }}</page-header>
            </d-col>
            <d-col md="6">
                <ul class="list-unstyled my-2 d-flex justify-content-md-end justify-content-center">
                    <li class="mr-2">
                        <d-link :to="{name: 'portal.records.edit', params: {type: patient.role, id: patient.id}}">Profile</d-link>
                    </li>
                    <li>
                        <d-link :to="{name: 'portal.records.dental', params: {id: patient.id}}">Dental Records</d-link>
                    </li>
                </ul>
            </d-col>
        </d-row>
        <alerts ref="alerts"></alerts>
        <d-row v-if="categories.length" class="form-row mb-4">
            <d-col v-for="category in categories" 
                    v-if="category.items.length" 
                    :key="category.id">
                <d-card class="card-small h-100">
                    <d-card-header>
                        <p class="mb-0 font-weight-bold text-transform-uppercase">{{ category.title }}</p>
                    </d-card-header>
                    <d-card-body>
                        <d-checkbox v-if="category.items.length" 
                                    v-for="item in category.items" 
                                    :key="item.key"
                                    v-model="model.record.info.data" 
                                    :value="item.key" 
                                    :checked="0 <= model.record.info.data.indexOf(item.key)" 
                                    :disabled="$auth.user().role != 'admin'" 
                                    name="records">{{ item.value }}</d-checkbox>
                    </d-card-body>
                </d-card>
            </d-col>
            <d-col cols="12">
                <d-button v-if="$auth.user().role == 'admin'" @click="model.record.info.id ? updateInfo() : addInfo()" class="mt-3 float-right">Save</d-button>
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12">
                <d-button v-if="$auth.user().role == 'admin'" @click="modal.state = 1" class="mb-3">Add New</d-button>
                <d-modal v-if="modal.state" @close="modal.state = 0">
                    <d-modal-header>
                        <d-modal-title>{{ modal.mode == 'add' ? 'Add New Record' : 'Update Record' }}</d-modal-title>
                    </d-modal-header>
                    <d-modal-body>
                        <vue-form :state="formState" @submit.prevent="modal.mode == 'add' ? addRecord() : updateRecord()">
                            <label for="record-date" class="mb-1 small">Date</label>
                            <datetime v-model="model.record.date" 
                                    input-id="record-date" 
                                    input-class="form-control form-control-sm" 
                                    class="form-group"></datetime>

                            <d-row class="form-row">
                                <d-col cols="12" md="6">
                                    <validate class="form-group">
                                        <label class="mb-1 small">Weight</label>
                                        <d-input v-model="model.record.data.weight" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col cols="12" md="6">
                                    <validate class="form-group">
                                        <label class="mb-1 small">Height</label>
                                        <d-input v-model="model.record.data.height" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col cols="12" md="6">
                                    <validate class="form-group">
                                        <label class="mb-1 small">BP</label>
                                        <d-input v-model="model.record.data.bp" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                                <d-col cols="12" md="6">
                                    <validate class="form-group">
                                        <label class="mb-1 small">Temp</label>
                                        <d-input v-model="model.record.data.temp" 
                                                class="form-control-sm"></d-input>
                                    </validate>
                                </d-col>
                            </d-row>

                            <validate class="form-group">
                                <label class="mb-1 small">Chief Complaint</label>
                                <tags-input v-model="model.record.data.chiefComplaint" 
                                            :existingTags="model.complains"
                                            element-id="complain" 
                                            placeholder="Add a complain" 
                                            :typeahead-activation-threshold="0" 
                                            typeahead 
                                            only-existing-tags></tags-input>
                            </validate>

                            <validate class="form-group">
                                <label class="mb-1 small">Intervention</label>
                                <tags-input v-model="model.record.data.intervention" 
                                            element-id="interventions" 
                                            placeholder="Add an intervention"></tags-input>
                            </validate>

                            <validate class="form-group">
                                <label class="mb-1 small">Meds Given</label>
                                <tags-input v-model="model.record.data.medsGiven" 
                                            element-id="meds" 
                                            placeholder="Add a medicine"></tags-input>
                            </validate>

                            <d-button>{{ modal.mode == 'add' ? 'Save' : 'Update' }}</d-button>
                        </vue-form>
                    </d-modal-body>
                </d-modal>

                <data-tables :data="table.data">
                    <el-table-column label="Date">
                        <template slot-scope="scope">
                            {{ scope.row.date }}
                            <ul v-if="$auth.user().role == 'admin'" class="list-unstyled m-0 d-flex">
                                <li v-for="button in recordsActions(scope.row)" :key="button.name">
                                    <d-link :class="button.class" 
                                            @click.native="button.handler(scope.row)" 
                                            class="mr-2">{{ button.name }}</d-link>
                                </li>
                            </ul>
                        </template>
                    </el-table-column>
                    <el-table-column prop="height" label="Height"></el-table-column>
                    <el-table-column prop="weight" label="Weight"></el-table-column>
                    <el-table-column prop="bp" label="BP"></el-table-column>
                    <el-table-column prop="temp" label="Temp"></el-table-column>
                    <el-table-column prop="chiefComplaint" label="Chief Complaint">
                        <template slot-scope="scope">
                            <d-badge v-for="(complain, index) in model.complains" 
                                    v-if="0 <= scope.row.chiefComplaint.indexOf(index)" 
                                    :key="index" 
                                    theme="light" 
                                    class="m-1">{{ complain }}</d-badge>
                        </template>
                    </el-table-column>
                    <el-table-column label="Intervention">
                        <template slot-scope="scope">
                            <d-badge v-for="intervention in scope.row.intervention" 
                                    :key="intervention" 
                                    theme="light" 
                                    class="m-1">{{ intervention }}</d-badge>
                        </template>
                    </el-table-column>
                    <el-table-column label="Meds Given">
                        <template slot-scope="scope">
                            <d-badge v-for="med in scope.row.medsGiven" 
                                    :key="med" 
                                    theme="light" 
                                    class="m-1">{{ med }}</d-badge>
                        </template>
                    </el-table-column>
                </data-tables>
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12" class="d-flex">
                <d-button v-if="$auth.user().role == 'admin'" 
                        theme="light" 
                        @click.native="$router.push({name: 'portal.records', params: {type: patient.role}})" 
                        class="ml-auto d-inline-flex align-items-center">
                    <vue-material-icon name="keyboard_backspace" size="20"></vue-material-icon>
                    <span class="ml-2">Return</span>
                </d-button>
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
                patient     : {
                    id	        : null,
                    email	    : null,
                    role	    : null,
                    meta        : {
                        number	    : null,
                        fname	    : null,
                        mname	    : null,
                        lname	    : null,
                        address	    : null,
                        birthday	: null,
                        position	: null,
                        weight	    : null,
                        height	    : null,
                        bp	        : null,
                        level	    : {
                            selected    : 'elementary',
                            options     : [
                                {key: 'elementary', label: 'Elementary'},
                                {key: 'high-school', label: 'High School'},
                                {key: 'senior-high', label: 'Senior High'},
                                {key: 'college', label: 'College'},
                            ]
                        },
                        stage	    : null,
                        gender	    : {
                            selected    : 'm',
                            options     : [
                                {key: 'm', label: 'Male'},
                                {key: 'f', label: 'Female'},
                            ]
                        },
                        guardian    : {
                            name	    : null,
                            phone	    : null,
                        },
                    },
                },
                categories  : [],
                modal       : {
                    mode        : 'add',
                    state       : 0,
                },
                formState   : {},
                model       : {
                    complains   : {},
                    record      : {
                        id              : null,
                        date            : null,
                        data            : {
                            weight          : null,
                            height          : null,
                            bp              : null,
                            temp            : null,
                            chiefComplaint  : [],
                            intervention    : [],
                            medsGiven       : [],
                        },
                        info            : {
                            length          : 0,
                            data            : [],
                        },
                    },
                },
                table       : {
                    activeIndex   : null,
                    data        : [],
                }
            }
        },
        watch       : {
            'modal.state'   : function(val, oldVal) {
                if( ! val ) {
                    this.modal.mode = 'add'
                    this.table.activeIndex = null
                    this.model.record.id = null 
                    this.model.record.date = null
                    this.model.record.data = {
                        weight          : null,
                        height          : null,
                        bp              : null,
                        temp            : null,
                        chiefComplaint  : [],
                        intervention    : [],
                        medsGiven       : [],
                    }
                }
            }
        },
        methods     : {
            get() {
                Vue.axios({
                    url     : '/user/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                    method  : 'GET',
                }).then((response) => {
                    this.patient = response.data
                })
            },
            getCategories() {
                var categories = []

                Vue.axios({
                    url     : '/categories/medical',
                    method  : 'GET',
                }).then((response) => {
                    if( 0 < response.data.length )
                        response.data.forEach(category => {
                            if( ! category.parent && category.parent == null ) {
                                categories[category.id] = {
                                    id      : category.id,
                                    title   : category.name,
                                    items   : [],
                                }
                            } else {
                                if( typeof categories[category.parent] != 'undefined' ) {
                                    categories[category.parent].items.push({
                                        key     : category.id,
                                        value   : category.name
                                    })
                                }
                            }

                            if( 1 == category.complainable ) {
                                this.model.complains[category.id] = category.name
                            }
                        })

                        categories.forEach(category => {
                            this.categories.push(category)
                        })
                })
            },
            getInfos() {
                Vue.axios({
                    url     : '/records/info/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                    method  : 'GET',
                }).then((response) => {
                    response.data.forEach((record) => {
                        this.model.record.info.id = record.id
                        this.model.record.info.data = record.data
                    })
                })
            },
            addInfo() {
                Vue.axios({
                    url     : '/record',
                    method  : 'POST',
                    params  : {
                        user_id     : this.$route.params.id, 
                        type        : 'info',
                        date        : null,
                        data        : this.model.record.info.data,
                    },
                }).then((response) => {
                    this.$refs.alerts.add('Record updated.', 'messages')
                })
            },
            updateInfo() {
                Vue.axios({
                    url     : '/record/'+ this.model.record.info.id,
                    method  : 'PUT',
                    params  : {
                        data        : this.model.record.info.data,
                    },
                }).then((response) => {
                    this.$refs.alerts.add('Record updated.', 'messages')
                })
            },
            getRecord() {
                Vue.axios({
                    url     : '/records/medical/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                    method  : 'GET',
                }).then((response) => {
                    response.data.forEach((record) => {
                        record.data.id = record.id
                        record.data.date = record.date

                        this.table.data.push(record.data)
                    })
                })
            },
            addRecord() {
                Vue.axios({
                    url     : '/record',
                    method  : 'POST',
                    params  : {
                        user_id     : this.$route.params.id, 
                        type        : 'medical',
                        date        : this.model.record.date,
                        data        : this.model.record.data,
                    },
                }).then((response) => {
                    var data = response.data.data
                    data.id = response.data.id
                    data.date = response.data.date

                    this.table.data.push(data)

                    this.modal.state = 0

                    this.$refs.alerts.add('New record added.', 'messages')
                })
            },
            updateRecord() {
                Vue.axios({
                    url     : '/record/'+ this.model.record.id,
                    method  : 'PUT',
                    params  : {
                        user_id     : this.$route.params.id, 
                        type        : 'medical',
                        date        : this.model.record.date,
                        data        : this.model.record.data,
                    },
                }).then((response) => {
                    this.table.data[this.table.activeIndex].date = response.data.date
                    this.table.data[this.table.activeIndex].height = response.data.data.height
                    this.table.data[this.table.activeIndex].weight = response.data.data.weight
                    this.table.data[this.table.activeIndex].bp = response.data.data.bp
                    this.table.data[this.table.activeIndex].temp = response.data.data.temp
                    this.table.data[this.table.activeIndex].chiefComplaint = response.data.data.chiefComplaint
                    this.table.data[this.table.activeIndex].intervention = response.data.data.intervention
                    this.table.data[this.table.activeIndex].medsGiven = response.data.data.medsGiven

                    this.table.activeIndex = null
                    
                    this.$refs.alerts.add('Record updated', 'messages')

                    this.modal.state = 0
                })
            },
            recordsActions() {
                return [{
                    name    : 'Edit',
                    class   : 'text-primary',
                    handler : row => {
                        Vue.axios({
                            url     : '/record/'+ row.id,
                            method  : 'GET',
                        }).then((response) => {
                            this.model.record.id = response.data.id,
                            this.model.record.date = response.data.date,
                            this.model.record.data = {
                                weight          : response.data.data.weight,
                                height          : response.data.data.height,
                                bp              : response.data.data.bp,
                                temp            : response.data.data.temp,
                                chiefComplaint  : response.data.data.chiefComplaint,
                                intervention    : response.data.data.intervention,
                                medsGiven       : response.data.data.medsGiven,
                            }

                            this.modal.mode = 'edit'
                            this.modal.state = 1

                            this.table.activeIndex = this.table.data.indexOf(row)
                        })
                    }
                },{
                    name    : 'Delete',
                    class   : 'text-danger',
                    handler : row => {
                        Vue.axios({
                            method      : 'DELETE',
                            url         : 'record/'+ row.id
                        }).then((data) => {
                            this.table.data.splice(this.table.data.indexOf(row), 1)
                            this.loading = false
                            this.$refs.alerts.add('Record deleted', 'alerts')
                        })
                    }
                }]
            }
        },
        mounted() {
            this.get()
            this.getCategories()
            this.getInfos()
            this.getRecord()
        }
    }
</script>

