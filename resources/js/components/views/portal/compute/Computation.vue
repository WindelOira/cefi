<template>
    <div>
        <page-header>Computation</page-header>
        <d-row class="form-row mb-3">
            <d-col md="2" cols="12">
                <div class="datepicker-trigger">
                    <d-input id="datepicker-trigger" 
                        :value="formatDates(range.start, range.end)" 
                        class="form-control-sm" 
                        placeholder="Select Dates" 
                        style="height: 36px;"></d-input>
                    <AirbnbStyleDatepicker
                        trigger-element-id="datepicker-trigger"
                        mode="range"
                        :fullscreen-mobile="true"
                        :date-one="range.start"
                        :date-two="range.end"
                        @date-one-selected="val => {range.start = val}"
                        @date-two-selected="val => {range.end = val}" 
                        @apply="filterRecords"
                    />
                </div>
            </d-col>
            <d-col md="1" cols="12">
                <d-form-select v-model="gender.selected" 
                                @input="filterRecords(date)" 
                                style="height: 36px;">
                    <option v-for="gender in gender.options" :key="gender.key" :value="gender.key">{{ gender.label }}</option>
                </d-form-select>
            </d-col>
            <d-col md="1" cols="12">
                <d-form-select v-model="level.selected" 
                                @input="filterRecords(date)" 
                                style="height: 36px;">
                    <option v-for="level in level.options" :key="level.key" :value="level.key">{{ level.label }}</option>
                </d-form-select>
            </d-col>
            <d-col md="1" offset-md="7" cols="12" class="font-weight-bold text-center text-uppercase">
                <h4 class="m-0">{{ new Date(date.year, date.month).toString('MMMM') }}</h4>
            </d-col>
        </d-row>
        <d-row>
            <d-col cols="12">
                <d-row v-for="data in datas" 
                        :key="data.id" 
                        no-gutters 
                        class="form-row">
                    <d-col>
                        <h5>{{ data.name }}</h5>
                        <d-tabs>
                            <d-tab v-for="(year, yIndex) in data.years"
                                    :key="year.year" 
                                    :title="year.year.toString()">
                                <div class="p-2">
                                    <d-tabs pills vertical>
                                        <d-tab v-for="(month, mIndex) in year.months" 
                                            :key="month.month" 
                                            :title="Date.parse(year.year +'-'+ month.month +'-'+ 1).toString('MMMM')" 
                                            :active="mIndex == 0 ? true : false">
                                            <d-row no-gutters class="form-row">
                                                <d-col v-for="(day, dIndex) in month.days" 
                                                    :key="day.day" 
                                                    class="font-weight-bold d-flex align-items-center justify-content-center small" 
                                                    style="height: 40.5px;">{{ day.day }}</d-col>
                                                <d-col cols="1" 
                                                        class="font-weight-bold d-flex align-items-center justify-content-center small" 
                                                        style="height: 40.5px;">TOTAL</d-col>
                                            </d-row>
                                            <d-row no-gutters class="form-row">
                                                <d-col v-for="(day, dIndex) in month.days" 
                                                        :key="day.day" 
                                                        class="d-flex align-items-center justify-content-center small" 
                                                        style="height: 40.5px;">
                                                    {{ day.records.length }}
                                                </d-col>
                                                <d-col cols="1" 
                                                        class="d-flex align-items-center justify-content-center small" 
                                                        style="height: 40.5px;">
                                                    {{ month.total }}
                                                </d-col>
                                            </d-row>
                                        </d-tab>
                                    </d-tabs>
                                </div>
                            </d-tab>
                        </d-tabs>
                    </d-col>
                </d-row>

                <d-row no-gutters class="form-row font-weight-bold">
                    <d-col cols="2" class="p-1">Health Complaint</d-col>
                    <d-col v-for="day in date.days" 
                            :key="day.key" 
                            class="p-1 font-weight-bold text-center small">{{ day.key }}</d-col>
                    <d-col cols="1" class="p-1 font-weight-bold text-center small">Total</d-col>
                </d-row>
                <d-row v-for="record in records" 
                        :key="record.category.id" 
                        no-gutters 
                        class="form-row">
                    <d-col cols="2" class="p-1 small">{{ record.category.name }}</d-col>
                    <d-col v-for="dt in record.dates" 
                            :key="dt.date" 
                            class="p-1 text-center small">
                        {{ dt.records.length }}
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
    import format from 'date-fns/format'
    import PageHeader from '@js/components/components/PageHeader.vue'
    import moment from 'moment'

    export default {
        components  : {
            'page-header'           : PageHeader,
        },
        data() {
            return {
                dates       : [],
                range       : {
                    format      : 'MMMM D, YYYY',
                    start       : moment().startOf('month').format('YYYY-MM-DD'),
                    end         : moment().endOf('month').format('YYYY-MM-DD'),
                },
                date        : {
                    month       : new Date().getMonth(),
                    year        : new Date().getFullYear(),
                    days        : [],
                },
                gender      : {
                    selected    : 'all',
                    options     : [
                        {key: 'all', label: 'Select-Sex'},
                        {key: 'm', label: 'Male'},
                        {key: 'f', label: 'Female'},
                    ],
                },
                level       : {
                    selected    : 'all',
                    options     : [
                        {key: 'all', label: 'Select-Level'},
                        {key: 'elementary', label: 'Elementary'},
                        {key: 'high-school', label: 'High School'},
                        {key: 'senior-high', label: 'Senior High'},
                        {key: 'college', label: 'College'},
                        {key: 'employee', label: 'Employee'},
                    ]
                },
                categories  : [],
                records     : [],
                datas       : [],
                total       : 0,
            }
        },
        methods     : {
            formatDates(start, end) {
                let formattedDates = ''

                if( start ) {
                    formattedDates = format(start, this.range.format)
                }

                if( end ) {
                    formattedDates += ' - ' + format(end, this.range.format)
                }

                return formattedDates
            },
            getCategories() {
                Vue.axios({
                    url     : '/categories/medical',
                    method  : 'GET',
                }).then((response) => {
                    var cIndex = -1

                    response.data.forEach((category) => {
                        if( 1 == category.complainable ) {
                            this.categories.push(category)
                        }
                    })
                })
            },
            getRecords() {
                Vue.axios({
                    url     : '/records/medical/'+ this.range.start +'/'+ this.range.end +'/'+ this.gender.selected +'/'+ this.level.selected, 
                    method  : 'GET',
                }).then((response) => {
                    var _this = this

                    _this.categories.forEach((category, cIndex) => {
                        var yIndex = -1,
                            yearStart = moment(_this.range.start).format('YYYY'),
                            yearEnd = moment(_this.range.end).format('YYYY')

                        _this.datas.push({
                            id          : category.id,
                            name        : category.name,
                            years       : []
                        })

                        for(var year = parseInt(yearStart); 
                            year <= parseInt(yearEnd); 
                            year++) {
                            yIndex++

                            _this.datas[cIndex].years.push({
                                year    : year,
                                months  : [],
                                total   : 0,
                            })

                            var mIndex = -1,
                                monthStart = parseInt(moment(_this.range.start).format('YYYY')) == year ? parseInt(moment(_this.range.start).format('MM')) : 1,
                                monthEnd = parseInt(moment(_this.range.end).format('YYYY')) == year ? parseInt(moment(_this.range.end).format('MM')) : 12
                            
                            for(var month = monthStart;
                                month <= monthEnd;
                                month++) {
                                mIndex++

                                _this.datas[cIndex].years[yIndex].months.push({
                                    month   : month,
                                    days    : [],
                                    total   : 0,
                                })

                                var dIndex = -1,
                                    dayStart = parseInt(moment(_this.range.start).format('MM')) == month ? parseInt(moment(_this.range.start).format('DD')) : 1,
                                    dayEnd = parseInt(moment(_this.range.end).format('MM')) == month ? parseInt(moment(_this.range.end).format('DD')) :  Date.getDaysInMonth(year, (month - 1))

                                for(var day = dayStart; day <= dayEnd; day++) {
                                    dIndex++

                                    _this.datas[cIndex].years[yIndex].months[mIndex].days.push({
                                        day         : day,
                                        date        : year +'-'+ month.toString().padStart(2, '0') +'-'+ day.toString().padStart(2, '0'),
                                        records     : []
                                    })

                                    response.data.forEach((record, recordIndex) => {
                                        var rDT = new Date(record.date)

                                        if( (0 <= record.data.chiefComplaint.indexOf(category.id.toString())) && 
                                            (record.date == _this.datas[cIndex].years[yIndex].months[mIndex].days[dIndex].date) ) {
                                            _this.total++

                                            _this.datas[cIndex].years[yIndex].months[mIndex].total++
                                            _this.datas[cIndex].years[yIndex].months[mIndex].days[dIndex].records.push(record)
                                        }
                                    })
                                }
                            }
                        }
                    })
                })
            },
            filterRecords() {
                this.datas = []
                this.records = []
                this.total = 0

                this.getRecords()
            }
        },
        mounted() {
            this.getCategories()
            this.getRecords()
        }
    }
</script>