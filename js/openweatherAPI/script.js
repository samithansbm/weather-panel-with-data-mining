//import {initFirestore, firestore} from '../firebase/script.js';

//-------------------------------------------Remove-------------------

var firebaseConfig = {
    apiKey: "AIzaSyAbWnP6YfEdPsDSgmNZJE6pa86BOH6mkEM",
    authDomain: "addproadb.firebaseapp.com",
    databaseURL: "https://addproadb.firebaseio.com",
    projectId: "addproadb",
    storageBucket: "addproadb.appspot.com",
    messagingSenderId: "965456196408",
    appId: "1:965456196408:web:7efaf182ec8d6b079d07cd",
    measurementId: "G-BWNRQ0R9XW"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
firestore = firebase.firestore();

//Creating Collection
const refWeather = firestore.collection('weather_report');

//-----------------------[Additional Solution]--------------------------//

// var imported = document.createElement('script');
// imported.src = './sample.js';
// document.body.appendChild(imported);


//--------------------------------------------------------------------
// Buttons
const btn_man = document.getElementById('manual');

const we_notification = document.querySelector('.notification');
const we_icon = document.querySelector('.icon');
const we_title = document.querySelector('#location');
const we_desc = document.querySelector('#desc');
const temp_value = document.querySelector('.temp');
const windflow = document.querySelector('#wind');
const cdate = document.querySelector('#currentDate');
// const temp_unit = document.querySelector('');

var count = 0;
var full_date;
var add_data = false;

if(btn_man!=undefined){
    btn_man.addEventListener('click', ()=>{add_data = true; console.log("BTN CLICKED"); getLocationGraph(6.5854, 79.9607);})
}


const weather_model = {
    temperature: {
        value: 10,
        unit: "celcius"
    },
    windflow: "NILL",
    title: "NILL",
    description: "NILL",
    notification: "NILL",
    icon: "NILL"
    
}

function displayWeather(){
    we_notification.innerHTML = `${weather_model.notification}`;
    we_icon.innerHTML = `<img src="images/icon/source/${weather_model.icon}.png"/>`;
    we_title.innerHTML = `${weather_model.title}`;
    we_desc.innerHTML = `${weather_model.description}`;
    temp_value.innerHTML = `${weather_model.temperature.value}<sup>o</sup><span>C<span>`;
    windflow.innerHTML = `${weather_model.windflow}km/h`;
    cdate.innerHTML = full_date;
}

function displaySubWeather(num){
    var temp = document.getElementById(`temp${num}`);
    var loc = document.getElementById(`loc${num}`);
    var icon = document.getElementById(`icon${num}`);

    temp.innerHTML = `${weather_model.temperature.value}<sup>o</sup><span>C<span>`;
    loc.innerHTML = `${weather_model.title}`;
    icon.innerHTML = `<img src="images/icon/source/${weather_model.icon}.png" height=50/>`;
}

function celciusToFaren(temp){
    let faren = (temp * 9/5) + 32;
    return faren; 

}

function errorHandle(error){
    we_notification.style.display = "block";
    we_notification.innerHTML = "Error Has Occured!" + error;

}

temp_value.addEventListener('click', function(){

    if(weather_model.temperature.unit==="undefined") return;
    if(weather_model.temperature.unit==="celcius"){
        weather_model.temperature.unit==="farenhite";
        let faren = celciusToFaren(weather_model.temperature.value);
        faren = Math.floor(faren);
        temp_value.innerHTML = `${faren}<sup>o</sup><span>F<span>`;
        
    } else {
        temp_value.innerHTML = `${weather_model.temperature.value} ⁰<span>C<span>`;
        weather_model.temperature.unit==="celcius";
    }

});

function getLocation(location){
    let lat = location.coords.latitude;
    let lng = location.coords.longitude;

    getWeather(lat,lng);
}

function getLocationGraph(lat, lng, city){
    
    if(city == undefined){
        getWeather(lat, lng);
    } else {
        console.log("City "+ city);
        getWeather(lat, lng, city);
    }
    
}

//API KEY [355aba119673f675afe86bb6f650e684]

//Open Weather API

const kelvin = 273;
const apiKey = "355aba119673f675afe86bb6f650e684";

function getWeather(lat, lng, city){
    let api = `http://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lng}&appid=${apiKey}`;
    console.log(api);

    fetch(api).then(function(response){
        let impo = response.json();
        return impo;
    }).then(function(data){
        console.log(data);
        weather_model.title = data.name;
        weather_model.description = data.weather[0].description;
        weather_model.icon = data.weather[0].icon;
        weather_model.temperature.value = Math.floor(data.main.temp - kelvin);
        weather_model.notification = "24hrs";
        weather_model.windflow = data.wind.speed;

        

        //Get Full Date 
        var date = new Date();
        var dd = date.getDate(); if(dd<10){dd = '0'+dd}
        var mm = date.getMonth() + 1; if(mm<10){mm = '0'+mm}
        var yyyy = date.getFullYear(); 

        var ss = date.getSeconds();
        var mo = date.getMinutes();
        var hh = date.getHours();

        full_date = `${yyyy}-${mm}-${dd}`;
        var date2 = new Date(`${yyyy}-${mm}-${dd}T${hh}:${mo}:${ss}`);

        if(add_data){
            //Enter Data to Firestore
            console.log("Weather data record entered!");
            refWeather.add({
                date: firebase.firestore.FieldValue.serverTimestamp(),
                location: data.name,
                brief: data.weather[0].main,
                description: data.weather[0].description,
                temperature: Math.floor(data.main.temp - kelvin),
                temperature_min: Math.floor(data.main.temp_min - kelvin),
                temperature_max: Math.floor(data.main.temp_max - kelvin),
                humidity: data.main.humidity,
                pressure: data.main.pressure, 
                wind: data.wind.speed
                
            });
        }
        
        

    }).then(function(){
        if(city==undefined){
            displayWeather();
            getLocationGraph(6.9271, 79.8612, "Colombo");
            getLocationGraph(7.0840, 80.0098, "Gampaha");
            getLocationGraph(6.0535, 80.2210, "Galle");
            getLocationGraph(7.2906, 80.6337, "Kandy");
            getLocationGraph(8.5874, 81.2152, "Trincomalee");
            getLocationGraph(9.6615, 80.0255, "Jaffna");
        } else {
            displaySubWeather(++count);
        }
        
    });
}

function getWeatherData(){
    const weatherTable = document.getElementById('weather_table');
    var count = 0;
    refWeather.orderBy("date", "desc").get().then((snaps)=>{
        snaps.forEach((row)=>{
            // console.log("data", row.data());
            if(row.data().date != null){
                count++;
                let table_row = document.createElement('tr');
                weatherTable.appendChild(table_row);

                //setting Rows
                let counter = document.createElement('td');
                let row1 = document.createElement('td');
                let row2 = document.createElement('td');
                let row3 = document.createElement('td');
                let row4 = document.createElement('td');
                let row5 = document.createElement('td');
                let row6 = document.createElement('td');
                let row7 = document.createElement('td');
                let row8 = document.createElement('td');

                counter.innerHTML = count;
                row1.innerHTML = row.data().date.toDate();
                row2.innerHTML = row.data().brief;
                row3.innerHTML = row.data().description;
                row4.innerHTML = row.data().temperature;
                row5.innerHTML = row.data().pressure;
                row6.innerHTML = row.data().humidity;
                row7.innerHTML = row.data().wind;
                // row8.innerHTML = ;

                table_row.appendChild(counter);
                table_row.appendChild(row1);
                table_row.appendChild(row2);
                table_row.appendChild(row3);
                table_row.appendChild(row4);
                table_row.appendChild(row5);
                table_row.appendChild(row6);
                table_row.appendChild(row7);
            }
            
        });
    });
}

function launchChart(cat, type){
    //Category should pass as a parameter - Temperature / Wind / Humidity
    //Category of chart type should pass


    //Setting up chart
    Plotly.plot('chart', [{
        y:[getRandom()],
        type: 'bar'
    }]);

    setInterval(function(){
        Plotly.extendTraces('chart',{y:[[getRandom()]]}, [0]);
    }, 200);
}

function getTemperature(locate) {
              
    var temp, press, wind, dated;
    var count = 0;
    
    firestore.collection('weather_report').where('location','==', locate).orderBy("date", "desc").get().then(function(querySnapshot){
      querySnapshot.forEach(function(doc) {
        if(doc.data().date){
            temp = doc.data().temperature;
            temp_min = doc.data().temperature_min;
            temp_max = doc.data().temperature_max;
            press = doc.data().pressure;
            wind = doc.data().wind;
            dated = doc.data().date.toDate();

            
            var t = parseInt(temp);
            var t_min = parseInt(temp_min);
            var t_max = parseInt(temp_max);
            var p = parseInt(press);
            var w = parseInt(wind);

            count++;
            arrx.push(dated);
            arry1.push(t);
            arry2.push(t_min);
            arry3.push(t_max);
            arry4.push(p);
            arry5.push(w);
        }
        
    });
  }).catch(function(error){
    console.log("Error getting documents: ", error);
  });
}