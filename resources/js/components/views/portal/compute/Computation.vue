<template>
    <div>
        <page-header>Computation</page-header>
        <d-row class="form-row mb-3">
            <d-col md="2" cols="12">
                <month-picker-input @input="filterRecords" 
                                    :default-month="date.month + 1" 
                                    :default-year="date.year"
                                    no-default 
                                    show-year></month-picker-input>
            </d-col>
            <d-col md="2" cols="12">
                <d-form-select v-model="gender.selected" 
                                @input="filterRecords(date)" 
                                style="height: 36px;">
                    <option v-for="gender in gender.options" :key="gender.key" :value="gender.key">{{ gender.label }}</option>
                </d-form-select>
            </d-col>
            <d-col md="1" offset-md="7" cols="12" class="font-weight-bold text-center text-uppercase">
                {{ new Date(date.year, date.month).toString('MMMM') }}
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12">
                <d-row no-gutters class="form-row font-weight-bold">
                    <d-col cols="2" class="p-1">Health Complaint</d-col>
                    <d-col v-for="day in date.days" 
                            :key="day.key" 
                            class="p-1 text-center">{{ day.key }}</d-col>
                    <d-col cols="1" class="p-1 text-center">Total</d-col>
                </d-row>
                <d-row v-for="record in records" 
                        :key="record.category.id" 
                        no-gutters 
                        class="form-row">
                    <d-col cols="2" class="p-1">{{ record.category.name }}</d-col>
                    <d-col v-for="date in record.dates" 
                            :key="date.date" 
                            class="p-1 text-center">
                        {{ date.records.length }}
                    </d-col>
                    <d-col cols="1" class="p-1 font-weight-bold text-center">{{ record.total }}</d-col>
                </d-row>
                <d-row no-gutters class="form-row">
                    <d-col md="1" offset-md="11" cols="12" class="p-1 font-weight-bold text-center">{{ total }}</d-col>
                </d-row>
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
                date        : {
                    month       : new Date().getMonth(),
                    year        : new Date().getFullYear(),
                    days        : [],
                },
                gender      : {
                    selected    : null,
                    options     : [
                        {key: null, label: 'Select-Gender'},
                        {key: 'm', label: 'Male'},
                        {key: 'f', label: 'Female'},
                    ],
                },
                categories  : [],
                records     : [],
                total       : 0,
            }
        },
        methods     : {
            setCalendar() {
                var days = Date.getDaysInMonth(this.date.year, this.date.month)

                for(var day = 1; day <= days; day++) {
                    var date = new Date()
                    date.setYear(this.date.year)
                    date.setMonth(this.date.month)
                    date.setDate(day)

                    this.date.days.push({
                        key     : day,
                        date    : date.toString('yyyy-MM-d')
                    })
                }
            },
            getCategories() {
                Vue.axios({
                    url     : '/categories/medical',
                    method  : 'GET',
                }).then((response) => {
                    response.data.forEach((category) => {
                        if( 1 == category.complainable ) {
                            this.categories.push(category)
                        }
                    })
                })
            },
            getRecords() {
                Vue.axios({
                    url     : '/records/medical/'+ this.date.year +'/'+ this.date.month +'/'+ this.gender.selected, 
                    method  : 'GET',
                }).then((response) => {
                    if( response.data.length ) {

                        this.categories.forEach((category, cIndex) => {
                            var total = 0

                            this.records.push({
                                category    : {
                                    id          : category.id,
                                    name        : category.name,
                                },
                                dates       : [],
                                total       : 0
                            })

                            this.date.days.forEach((day, dIndex) => {
                                this.records[cIndex].dates.push({
                                    date        : day.date,
                                    records     : [],
                                })
                                response.data.filter((record, recordIndex) => {
                                    var rDT = new Date(record.date)

                                    if( (rDT.getFullYear() == this.date.year && rDT.getMonth() == this.date.month) &&
                                        (0 <= record.data.chiefComplaint.indexOf(category.id.toString())) && 
                                        (record.date == day.date) ) {
                                        total++
                                        this.total++

                                        return this.records[cIndex].dates[dIndex].records.push(record)
                                    }
                                })
                            })

                            this.records[cIndex].total = total
                        })
                    }
                })
            },
            filterRecords(date) {
                this.date = {
                    month       : ! isNaN(date.monthIndex) ? (date.monthIndex - 1) : this.date.month,
                    year        : date.year,
                    days        : [],
                }
                this.categories = []
                this.records = []
                this.total = 0

                this.setCalendar()
                this.getCategories()
                this.getRecords()
            }
        },
        mounted() {
            this.setCalendar()
            this.getCategories()
            this.getRecords()
        }
    }
</script>