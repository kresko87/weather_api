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
            <a  v-for="item in placesList"  v-on:click="placeItemClicked(item.id)" href="#" class="placeItem" :item_id="item.id" >
                <div class="placeItemName">{{item.place.name}}</div>
                <a href="#2" class="placeItemButtons">X</a>
            </a>
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

            placeItemClicked(id){
                $('.placeItem').removeClass('selected');
                $('.placeItem[item_id="'+id+'"]').addClass('selected');
                this.$emit('cityClicked', id);
            },

            addButtonClicked(callback){
                let data = $('#placesForm').serialize();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:'addPlace?'+data,
                    type:'GET',
                    dataType:'json',

                    success:function(data){
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