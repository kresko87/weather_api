<template>
    <div>
        <form id="placesForm">
            <div class="input-group input-group-sm mb-3">
                <input type="text" name="cityName" class="form-control" placeholder="City name" aria-describedby="buttonAddCity">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" id="buttonAddCity" >Add</button>
                </div>
            </div>
        </form>
        <div id="placesList">
            <div  v-for="item in placesList"  v-on:click="placeItemClicked(item.id_place, $event)" href="#" class="placeItem" :item_id="item.id_place" >
                <div class="placeItemName">{{item.place.name}}</div>
                <a href="#" class="placeItemButtons">X</a>
            </div>
        </div>
    </div>
</template>




<script>
    export default {
        mounted() {
            let that = this;
            this.getPlacesListFromDatabase(function(data){
                that.placesList = data;
            });
            $('#placesForm').submit(function(e){
                e.preventDefault();
                that.addButtonClicked(function(addedCity){
                    if(addedCity!=null && addedCity.city!=null) {
                        that.getPlacesListFromDatabase(function (places) {
                            that.placesList = places;
                        });
                    }
                });
            })
        },
        data:function(){
            return {placesList:[]}
        },
        methods: {
            getPlacesListFromDatabase(callback){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:'allPlaces',
                    type:'POST',
                    dataType:'json',
                    success:function(data){
                        callback(data);
                    }
                });
            },

            placeItemClicked(id, event){
                if(event.target.className=='placeItemButtons'){
                    let r = confirm('You are about to remove city '+
                        $('.placeItem[item_id="'+id+'"] > .placeItemName').html()+
                        ' from your places list. Pres OK to delete or cancel to discard.');
                    if(r===true){
                        let that = this;
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: 'removePlaceAndReturnAllCitiesFromUser',
                            type: 'POST',
                            dataType: 'json',
                            data:{
                                cityId:id
                            },
                            success: function (data) {
                                console.log(data);
                                that.placesList = data;
                                $('.placeItem').removeClass('selected');
                                that.$emit('cityClicked', 0);
                            }
                        });
                    }
                }else{
                    $('.placeItem').removeClass('selected');
                    $('.placeItem[item_id="'+id+'"]').addClass('selected');
                    this.$emit('cityClicked', id);
                }
            },

            addButtonClicked(callback){
                let data = $('#placesForm').serialize();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:'addPlace?'+data,
                    type:'GET',
                    dataType:'json',

                    success:function(data){
                        $('#placesForm input[name="cityName"]').val('');
                        callback(data);
                    }
                });
            }
        }
    }
</script>

<style scoped>
.placeItem {
    padding:5px;
    border-bottom: 1px solid lightgray;
    display: flex;
    flex-direction: row;
}
.placeItem:hover {
    background-color: #F7F7F7;
    cursor:pointer;
}
.placeItem.selected {
    background-color: #EFEFEF;
}
.placeItemName{
    flex: auto;
}
.placeItemButtons{
    width:15px;
    text-align: right;
}
</style>