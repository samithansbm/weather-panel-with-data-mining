var d1 = document.getElementById('d1');
var d2 = document.getElementById('d2');
var d3 = document.getElementById('d3');
var d4 = document.getElementById('d4');
var d5 = document.getElementById('d5');
var d6 = document.getElementById('d6');

var temp1 = document.getElementById('temp1');
var temp2 = document.getElementById('temp2');
var temp3 = document.getElementById('temp3');
var temp4 = document.getElementById('temp4');
var temp5 = document.getElementById('temp5');
var temp6 = document.getElementById('temp6');


function genTempWeek(){

    temp1.innerHTML = `${randomNum()}<sup>o</sup><span>C<span>`;
    temp2.innerHTML = `${randomNum()}<sup>o</sup><span>C<span>`;
    temp3.innerHTML = `${randomNum()}<sup>o</sup><span>C<span>`;
    temp4.innerHTML = `${randomNum()}<sup>o</sup><span>C<span>`;
    temp5.innerHTML = `${randomNum()}<sup>o</sup><span>C<span>`;
    temp6.innerHTML = `${randomNum()}<sup>o</sup><span>C<span>`;
}

function randomNum(){
    return Math.floor(Math.random()*32) + 26;
}
