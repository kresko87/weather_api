<template>
    <div>
        <div class="graphSettings">
            <form class="form-inline" id="contentForm">
                <div class="graphSetting input-group input-group-sm mb-3 mr-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">From</span>
                    </div>
                    <input type="text" class="datePickerCustom form-control" id="datePickerFrom" >
                </div>

                <div class="graphSetting input-group input-group-sm mb-3 mr-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
                    </div>
                    <input type="text" class="datePickerCustom form-control" id="datePickerTo" >
                </div>

                <div class="graphSetting btn-group btn-group-sm btn-group-toggle mb-3 mr-3" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="optionsType" value="2" checked> Chart
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="optionsType" value="3" > Table
                    </label>
                </div>

                <div class="graphSetting btn-group btn-group-sm btn-group-toggle mb-3" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input type="radio" name="optionsWithForecast" value="1" checked> Current data
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="optionsWithForecast" value="2"> Compare with forecast
                    </label>
                </div>
            </form>
        </div>

        <div class="mt-3">
            <div v-if="display==1" id="displayContentNone">Please chose city from the left and enter dates to display data</div>
            <div v-if="display==2" id="displayContentGraph">
                <chart-component :chart-data="datacollection" :options="datacollection.options"></chart-component>
            </div>
            <div v-if="display==3" id="displayContentTable">
                <b-table striped hover :items="data" :fields="fields"></b-table>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "graphComponent",
        mounted() {
            let that = this;
            const flatpickr = require("flatpickr");
            flatpickr("#datePickerFrom", {
                enableTime: true,
                dateFormat: "Y-m-d H:00:00",
                onChange: function(selectedDates, dateStr, instance) {
                    that.displayData();
                },
            });
            flatpickr("#datePickerTo", {
                enableTime: true,
                dateFormat: "Y-m-d H:00:00",
                onChange: function(selectedDates, dateStr, instance) {
                    that.displayData();
                },
            });
            $('#contentForm').submit(function(e) {
                e.preventDefault();
            });
            $('#contentForm input[type="radio"][name="optionsType"]').change(function(){
                that.displaySwitch();
            });
            $('#contentForm input[type="radio"][name="optionsWithForecast"]').change(function(){
                that.tableFieldsSwitch();
                that.redrawChart();
            });
            that.datacollection = {
                labels: [],
                datasets: [],
                options:that.chartOptions
            }

        },
        props:{
            cityId: String
        },
        data:function(){
            return {
                display:1,
                data:[],
                tableFields:[
                    {
                        key: 'id',
                        label: 'Id',
                        sortable: true,
                    },
                    {
                        key: 'datetime_hours',
                        label: 'Time',
                        sortable: true,
                    },
                    {
                        key: 'temperature',
                        label: 'Temperature (°C)',
                        sortable: true,
                    },
                ],
                datacollection: null,
                fields:this.tableFields,
                chartOptions:{
                    responsive: true,
                    maintainAspectRatio: false
                },
                datasetChartBasic:{
                    label: 'Temperature',
                    backgroundColor: '#66ff99',
                    borderColor: '#66ff99',
                    borderWidth: 1,
                    data: []
                },
                datasetChartForecast:{
                    label: 'Predicted temperature',
                    backgroundColor: '#ff9999',
                    borderColor: '#ff9999',
                    borderWidth: 1,
                    data: []
                },
                chartLabls:[]
            }
        },
        watch:{
            'cityId':function(){
                this.displayData()
            }
        },

        methods:{
            displayData(){
                this.displaySwitch();
                if(this.display!=1){
                    let that = this;
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url:'getTemperatures',
                        type:'POST',
                        dataType:'json',
                        data:{
                            cityId:this.cityId,
                            dateFrom:$('#datePickerFrom').val(),
                            dateTo:$('#datePickerTo').val()
                        },
                        success:function(data){
                            let labels = [];
                            that.datasetChartBasic.data = [];
                            that.datasetChartForecast.data = [];
                            for(var i=0;i<data.length;i++){
                                data[i].datetime_hours = data[i].datetime.substring(0,16);
                                labels.push(data[i].datetime_hours);
                                that.datasetChartBasic.data.push(data[i].temperature);
                                that.datasetChartForecast.data.push(data[i].temperature_predicted_in_past);
                            }
                            that.chartLabls = labels;
                            that.data = data;
                            that.tableFieldsSwitch();
                            that.redrawChart();
                        }
                    });
                }
            },
            getRandomInt () {
                return Math.floor(Math.random() * (50 - 5 + 1)) + 5
            },
            tableFieldsSwitch(){
                let fields = [...this.tableFields];
                if($('#contentForm input[type="radio"][name="optionsWithForecast"]:checked').val()==2){
                    fields.push({
                        key: 'temperature_predicted_in_past',
                        label: 'Temperature predicted 12h ago (°C)',
                        sortable: true,
                    },)
                }
                this.fields = fields;
            },
            displaySwitch(){
                if(this.cityId==0 || $('#datePickerFrom').val()=='' || $('#datePickerTo').val()==''){
                    this.display = 1;
                }else{
                    this.display = $('#contentForm input[type="radio"][name="optionsType"]:checked').val();
                }
            },
            redrawChart(){
                let datasets = [this.datasetChartBasic];
                if($('#contentForm input[type="radio"][name="optionsWithForecast"]:checked').val()==2){
                    datasets.push(this.datasetChartForecast);
                }
                this.datacollection = {
                    labels: this.chartLabls,
                    datasets: datasets,
                    options:this.chartOptions
                }
            }
        }
    }
</script>

<style scoped>
    .graphSettings{
        border-bottom: 1px solid lightgray;
    }
    .datePickerCustom{
        background-color:white;
    }




</style>