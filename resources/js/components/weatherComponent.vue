<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-header">Places</div>

                    <div class="card-body">
                        <div><places-component v-on:cityClicked="placesCityClicked"></places-component></div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9 mt-lg-0 mt-3">
                <div class="card">
                    <div class="card-header">Chart</div>

                    <div class="card-body">
                        <graph-component :cityId="cityId"></graph-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import GraphComponent from "./GraphComponent";
    export default {
        components: {GraphComponent},
        mounted() {},
        data:function(){
            return {cityId:'0'}
        },
        name: "weatherComponent",
        methods:{
            placesCityClicked(cityId){
                this.cityId = cityId.toString();
            }
        }
    }
</script>

<style scoped>

</style>