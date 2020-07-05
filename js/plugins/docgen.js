const content_area = document.getElementById('gen');


function getWeather(){
    var refReport = firestore.collection('weather_report');
    refReport.orderBy("date", "desc").limit(7).get().then((results)=>{
        results.forEach((row)=>{
            getWeatherToday(row.data().date.toDate(), row.data().location, "SD", row.data().brief, row.data().temperature, row.data().wind, "None");
        });
    });
}


function getWeatherToday(date, loc, issuedtime, brief, temp, windflow, more){
    var content =   `
                    <h3>WEATHER FORECAST FOR ${loc} on ${date}</h3>
                    <h5>Issued at ${issuedtime} on ${date} and valid for 24 hours</h5>
                    <p>${brief} will frequently occur in ${loc}. Average temperature will be ${temp}<sup>o</sup>C and can experience up to ${windflow}
                    km/h wind speed.
                    </p>
                    <p>As additional info: ${more}</p>
                    `;
    var item = document.createElement('li');
    var div = document.createElement('div');
    div.innerHTML = content; 
    item.appendChild(div);
    content_area.appendChild(item);
}





// WEATHER FORECAST FOR 16 JUNE 2020

// Issued at 12.00 noon on 15 June 2020

 

// Showers will occur at several places in Western, North-western, Sabaragamuwa and Central provinces and in Galle and Matara districts.

// Wind speed can be increased up to (40-50) kmph at times over the island, particularly in western slopes of the Central hills and Northern, North-central, North-western provinces and in Hambanthota district.