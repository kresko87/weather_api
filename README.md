# Weather application
simple weather application allows you to select cities, for which you wish to track temperature. App than retrieves temperature data and forecasts for next 12 hours and saves them in database. You can than check weather history and differences between forecast and real temperatures. 

## Installation

Download content in your web folder. 
<br>Run commands inside project folder: 
```
composer install
``` 
and 
```
npm install
```
copy ```.env.example``` to ```.env``` and change database configuration + OPEN_WEATHER_API_KEY
<br>
Run code below for database migration: 
```
php artisan migrate
```

You can run laravel task scheduler by adding these lines in your CRON file:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Usage

You can now open application in browser and register. After registration you will be redirected to weather panel.<br>
You can add cities inside Slovenia. When you click them, you can check statistic data for specified dates. You can also check 
if the forecast was the same as real temperature.  

## Structure
### Frontend
Main content is made of 4 vue components: 
- weatherComponent.vue   
    - PlacesComponent.vue
    - GraphComponent.vue
        - ChartComponent.vue

PlacesCompoonent.vue and GraphComponent.vue communicate through parent component. (Places sends Graph city_id, when city is clicked). 

### Backen
Interfaces are located in app\Interfaces<br>
Controllers are in app\Http\Controllers<br>
Models are in app\Models<br>
Main logic is located in <b>app\Services</b><br><br>
         
## Sample data
When you have added some cities in your database, you can also run command below, to insert sample data for these cities for july 2020. 
```
php artisan temperatures:fillRandomData
``` 