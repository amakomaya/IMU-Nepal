<template>
    <div>
        <div class="col-sm-1">
            <select name="select_year" class="form-control" id="select-year" @change="handleYearChange">
                <option
                        v-for="year in years.slice().reverse()"
                        v-bind:value="year"
                        :selected="year== selectedYear?true : false"
                >
                    {{ year }}
                </option>
            </select>
        </div>
        <div class="col-sm-2">
            <select name="select_month" class="form-control" id="select-month">
                <option
                        v-for="(month, index) in months.slice()"
                        v-bind:value="index+1"
                        :selected="index+1== selectedMonth?true : false"
                >
                    {{ month }}
                </option>
            </select>
        </div>
    </div>
</template>
<script>
    import DataConverter from 'ad-bs-converter'

    export default {
        name: "SelectYearMonth",
        data() {
            return {
                years: [],
                currentYear : '',
                selectedYear : '',
                months : ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Mangh', 'Falgun', 'Chaitra'],
                currentMonth : '',
                selectedMonth : ''
            }
        },
        beforeMount(){
            this.getCurrentDateNepali()
        },
        methods: {
            getCurrentDateNepali() {
                var dateObject = new Date();
                var dateFormat = dateObject.getFullYear() + "/" + (dateObject.getMonth() + 1) + "/" + dateObject.getDate();
                let dateConverter = DataConverter.ad2bs(dateFormat);

                this.selectedMonth = dateConverter.en.month;
                // return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;
                this.currentYear = dateConverter.en.year;
                this.currentMonth = dateConverter.en.strMonth;
                // this.years = this.currentYear;
                var startingYear = this.currentYear - 5;
                while (startingYear <= this.currentYear) {
                    this.years.push(startingYear++);
                }
                var i = this.months.indexOf(this.currentMonth);
                this.months = this.months.filter(function (value, key, arr) {
                    return key <= i;
                });
                this.selectedYear = this.getUrlVars().select_year;
                // this.selectedMonth = this.getUrlVars().select_month;
                if (this.getUrlVars().select_month) {
                    this.selectedMonth = this.getUrlVars().select_month;
                }
            },

            handleYearChange(e){
                // console.log(e.target.value);
                if(this.currentYear == e.target.value) {
                    var i = this.months.indexOf(this.currentMonth);
                    this.months = this.months.filter(function(value, index, arr){
                        return index <= i;
                    });
                }else{
                    this.months = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Mangh', 'Falgun', 'Chaitra'];
                }

            },

            getUrlVars() {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
                    vars[key] = value;
                });
                return vars;
            }
        }
    }
</script>
