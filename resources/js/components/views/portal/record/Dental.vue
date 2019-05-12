<template>
    <div>
        <page-header>Dental Record</page-header>
        <alerts ref="alerts"></alerts>
        <d-row class="dental__teeth-chart">
            <d-col cols="12">
                <div class="teeth-chart--container">
                    <d-row class="mb-1 form-row teeth-chart teeth-chart--top">
                        <d-col v-for="top in model.record.data.teeth.top"
                                :key="top.key" 
                                class="text-center">
                            {{ top.key }}
                            <d-input v-model="top.selected" 
                                    type="number" 
                                    min="0" 
                                    max="7" 
                                    :disabled="$auth.user().role != 'admin'" 
                                    class="mt-2"></d-input>
                        </d-col>
                    </d-row>
                    <d-row class="mb-3 form-row teeth-chart teeth-chart--bottom">
                        <d-col v-for="bottom in model.record.data.teeth.bottom"
                                :key="bottom.key" 
                                class="text-center">
                            {{ bottom.key }}
                            <d-input v-model="bottom.selected" 
                                    type="number" 
                                    min="0" 
                                    max="7" 
                                    :disabled="$auth.user().role != 'admin'" 
                                    class="mt-2"></d-input>
                        </d-col>
                    </d-row>
                </div>
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12">
                <h5 class="text-muted d-block mb-3">Status</h5> 
                <d-row class="mb-4">
                    <d-col v-for="stat in status" 
                            :key="stat.key" 
                            cols="6" 
                            md="3" 
                            sm="4" 
                            class="mb-1">
                        <span class="badge badge-primary badge-pill">{{ stat.key }}</span>
                        {{ stat.label }}
                    </d-col>
                </d-row>
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12">
                <h5>Treatment Plan</h5>
                <d-checkbox v-model="model.record.data.treatment_plan.oral_prophylaxis" 
                            :disabled="$auth.user().role != 'admin'" >Oral Prophylaxis</d-checkbox>
                <d-checkbox v-model="model.record.data.treatment_plan.flouride_application" 
                            :disabled="$auth.user().role != 'admin'" >Application of Flouride</d-checkbox>
                <d-checkbox v-model="model.record.data.treatment_plan.dentures_construction" 
                            :disabled="$auth.user().role != 'admin'" >Construction of Dentures</d-checkbox>
                <d-row class="my-4">
                    <d-col sm="6" cols="12">
                        <d-card class="card-small">
                            <d-card-header>
                                <p class="mb-0 font-weight-bold">Restoration of #</p>
                            </d-card-header>
                            <d-card-body>
                                <d-row class="form-row">
                                    <d-col v-for="(restoration, index) in orderBy(model.record.data.teeth.top, 'key')" 
                                            :key="restoration.key" 
                                            md="1" 
                                            :offset-md="(index == 0 || index == 8) ? 2 : 0" 
                                            sm="2" 
                                            cols="12">
                                        <d-checkbox v-model="model.record.data.treatment_plan.restoration" 
                                            name="restoration" 
                                            :value="restoration.key" 
                                            :disabled="$auth.user().role != 'admin'" >{{ restoration.key }}</d-checkbox>
                                    </d-col>
                                    <d-col v-for="(restoration, index) in orderBy(model.record.data.teeth.bottom, 'key')" 
                                            :key="restoration.key" 
                                            md="1" 
                                            :offset-md="(index == 0 || index == 8) ? 2 : 0" 
                                            sm="2" 
                                            cols="12">
                                        <d-checkbox v-model="model.record.data.treatment_plan.restoration" 
                                            name="restoration" 
                                            :value="restoration.key" 
                                            :disabled="$auth.user().role != 'admin'" >{{ restoration.key }}</d-checkbox>
                                    </d-col>
                                    <d-col md="2" cols="12"></d-col>
                                </d-row>
                            </d-card-body>
                        </d-card>
                    </d-col>
                    <d-col sm="6" cols="12">
                        <d-card class="card-small">
                            <d-card-header>
                                <p class="mb-0 font-weight-bold">Extraction of #</p>
                            </d-card-header>
                            <d-card-body>
                                <d-row class="form-row">
                                    <d-col v-for="(extraction, index) in orderBy(model.record.data.teeth.top, 'key')" 
                                            :key="extraction.key" 
                                            md="1" 
                                            :offset-md="(index == 0 || index == 8) ? 2 : 0" 
                                            sm="2" 
                                            cols="12">
                                        <d-checkbox v-model="model.record.data.treatment_plan.extraction" 
                                                    name="extraction" 
                                                    :value="extraction.key" 
                                                    :disabled="$auth.user().role != 'admin'" >{{ extraction.key }}</d-checkbox>
                                    </d-col>
                                    <d-col v-for="(extraction, index) in orderBy(model.record.data.teeth.bottom, 'key')" 
                                            :key="extraction.key" 
                                            md="1" 
                                            :offset-md="(index == 0 || index == 8) ? 2 : 0" 
                                            sm="2" 
                                            cols="12">
                                        <d-checkbox v-model="model.record.data.treatment_plan.extraction" 
                                                    name="extraction" 
                                                    :value="extraction.key" 
                                                    :disabled="$auth.user().role != 'admin'" >{{ extraction.key }}</d-checkbox>
                                    </d-col>
                                </d-row>
                            </d-card-body>
                        </d-card>
                    </d-col>
                </d-row>
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12" class="d-flex">
                <d-button v-if="$auth.user().role == 'admin'" 
                        @click.prevent="model.record.hasRecord ? updateRecord() : addRecord()">Save</d-button>
                <d-button v-if="$auth.user().role == 'admin'" 
                        theme="light" 
                        @click="$router.push({name: 'portal.records.edit', params: {type: patient.type, id: $route.params.id}})" 
                        class="ml-auto d-inline-flex align-items-center">
                    <vue-material-icon name="keyboard_backspace" size="20"></vue-material-icon>
                    <span class="ml-2">Return</span>
                </d-button>
            </d-col>
        </d-row>
    </div>
</template>

<script>
    import Vue2Filters from 'vue2-filters'
    import PageHeader from '@js/components/components/PageHeader.vue'
    import Alerts from '@js/components/components/Alerts.vue'

    export default {
        components  : {
            'page-header'   : PageHeader,
            'alerts'        : Alerts,
        },
        mixins      : [
            Vue2Filters.mixin,
        ],
        data() {
            return {
                patient : {},
                model   : {
                    record  : {
                        hasRecord   : false,
                        id          : null,
                        date        : null,
                        data        : {
                            teeth       : {
                                top         : [
                                    {key: 18, selected: null},
                                    {key: 17, selected: null},
                                    {key: 16, selected: null},
                                    {key: 15, selected: null},
                                    {key: 14, selected: null},
                                    {key: 13, selected: null},
                                    {key: 12, selected: null},
                                    {key: 11, selected: null},
                                    {key: 21, selected: null},
                                    {key: 22, selected: null},
                                    {key: 23, selected: null},
                                    {key: 24, selected: null},
                                    {key: 25, selected: null},
                                    {key: 26, selected: null},
                                    {key: 27, selected: null},
                                    {key: 28, selected: null},
                                ],
                                bottom      : [
                                    {key: 48, selected: null},
                                    {key: 47, selected: null},
                                    {key: 46, selected: null},
                                    {key: 45, selected: null},
                                    {key: 44, selected: null},
                                    {key: 43, selected: null},
                                    {key: 42, selected: null},
                                    {key: 41, selected: null},
                                    {key: 31, selected: null},
                                    {key: 32, selected: null},
                                    {key: 33, selected: null},
                                    {key: 34, selected: null},
                                    {key: 35, selected: null},
                                    {key: 36, selected: null},
                                    {key: 37, selected: null},
                                    {key: 38, selected: null},
                                ]
                            },
                            treatment_plan  : {
                                oral_prophylaxis        : false,
                                flouride_application    : false,
                                dentures_construction   : false,
                                restoration             : [],
                                extraction              : [],
                            },
                        },
                    },
                },
                status  : [
                    {key: '-', label: 'Missing'},
                    {key: 0, label: 'Sound & Fully Erupted'},
                    {key: 1, label: 'Sound & Partially Erupted'},
                    {key: 2, label: 'Slightly Decayed'},
                    {key: 3, label: 'Extension Decayed'},
                    {key: 4, label: 'Filled & Restored'},
                    {key: 5, label: 'Malposition'},
                    {key: 6, label: 'Impacted'},
                    {key: 7, label: 'Supernumerary'},
                ],
                table   : {
                    data    : [],
                    titles  : [
                        {prop: 'date', label: 'Date',},
                        {prop: 'weight', label: 'Weight',},
                        {prop: 'height', label: 'Height',},
                        {prop: 'bp', label: 'BP',},
                        {prop: 'temp', label: 'Temp',},
                        {prop: 'chief-complaint', label: 'Chief Complaint',},
                        {prop: 'intervention', label: 'Intervention',},
                        {prop: 'meds-given', label: 'Meds Given',},
                    ]
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
            getRecord() {
                Vue.axios({
                    url     : '/records/dental/'+ (this.$auth.user().role != 'admin' ? this.$auth.user().id : this.$route.params.id),
                    method  : 'GET',
                }).then((response) => {
                    if( response.data.length )
                        this.model.record.hasRecord = true
                        response.data.forEach((record) => {
                            this.model.record.id = record.id
                            this.model.record.date = record.date
                            this.model.record.data = record.data
                        })
                })
            },
            addRecord() {
                Vue.axios({
                    url     : '/record',
                    method  : 'POST',
                    params  : {
                        user_id     : this.$route.params.id,
                        type        : 'dental',
                        date        : null,
                        data        : this.model.record.data,
                    }
                }).then((response) => {
                    this.$refs.alerts.add('Record updated', 'messages')
                })
            },
            updateRecord() {
                Vue.axios({
                    url     : '/record/'+ this.model.record.id,
                    method  : 'PUT',
                    params  : {
                        data        : this.model.record.data,
                    },
                }).then((response) => {
                    this.$refs.alerts.add('Record updated', 'messages')
                })
            },
        },
        mounted() {
            this.get()
            this.getRecord()
        }
    }
</script>