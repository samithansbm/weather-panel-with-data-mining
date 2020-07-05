<?php 
	$_SESSION['Username'] = 0;
	session_start();

	if(isset($_SESSION['Username'])){
    $url=null;
    $level = 0;
    if($_SESSION['Role']=="Admin"){
      $level = 4;
    } else if($_SESSION['Role']=="Staff"){
      $level = 2;
    } else if($_SESSION['Role']=="Member"){
      $level = 1;
    }
    
  }
	


	if(isset($_GET['logout'])){
		$_SESSION = array();
		session_destroy();
	}
 ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Map Pannel | WeatherALPHA</title>

  <!--================================================= Site Icon ===================================================================-->
  <link rel="icon" type="image/png" href="images/icon/test.png">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


  <!--=================================FontAwesome Icons=============================================--> 
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/sb-custom.css" rel="stylesheet">

  <!-- The core Firebase JS SDK is always required and must be listed first -->
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-firestore.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-analytics.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.9.1/firebase-storage.js"></script>      
  <script src="https://www.gstatic.com/firebasejs/3.1.0/firebase-database.js"></script>

  <!--==========================================================JQuery=================================================================-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  

  <style>

    /* Water Level Slider */

.slider-container {
  height: 60vh;
  width: 12vw;
  background-color: #333333;
  display: flex;
  justify-content: center;
  justify-items: center;
  
}

.slider-container .st-slider {
  position: absolute;
  z-index: 3;
  top: 50%;
  height:20px;
  width: 300px;
  -webkit-appearance: none;
  transform: rotate(-90deg);
  border-radius: 15px;
  outline: none;
  margin: 0;
  background-image: linear-gradient(90deg, #232323  60%, #232323 40%);
  transition: .5s;
}

.slider-container .st-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width:30px;
  height: 30px;
  padding: 10px;
  border: 7px solid white;
  background-color: rgb(10, 123, 199);;
  outline: black;
  border-radius: 20px;
  cursor: pointer;
  box-shadow: 0px 0px 20px #1b1b1b;
  transition: .5s;
}

.slider-container .st-slider::-webkit-slider-thumb:hover {
  border: 7px solid rgb(10, 123, 199);
  background-color: rgb(0, 91, 151);
  transform: 
  scale(1.2);
}

.slider-container .st-slider::-webkit-slider-thumb:active {
  box-shadow: 0px 0px 10px rgb(54, 157, 226);
}

.span-back, .span-bar {
  position: absolute;
  left: 0px;
  right: 0px;
}

.span-back {
  z-index: 3;
  top:10px;
  bottom: 10px;
  
  background-color: rgba(0, 0, 0, 0.2);

}



.text {
  margin: 10px;
  display: inline-block;
  color: white;
  font-size: 25px;
  font-weight: 700;

}

/* Inforwindow Styles */

.info-container {
 border-radius: 7px;
 width: 25vw;
 background-color: #333333;
 color: white;
 display: flex;
 justify-content: center;
 justify-items: center;
 
}

.info-container .info-title {
 width: 100%;
 text-align: center;
 background-color: rgb(3, 105, 189);
 padding: 10px;
}

.info-container .info-title h1 {
 font-size: 25px;
 font-weight: 800;
}

.info-container .info-titl span {
 
 vertical-align: bottom;
}

.info-container .info-content {
 padding: 10px 20px;
 border: 2px solid rgb(3, 105, 189);
 
}

.info-container .info-content img{
 width: 100%;
 background-position: center;
 background-repeat: no-repeat;
 background-size: cover;
 
}

.info-container .info-content p {
 text-align: left;
}        

/* Emergency Button */
.emerg-circle {
  height: 20px;
  width: 20px;
  border-radius: 50px;
  border: 1px ridge #474747;
  background: radial-gradient(circle at 100%, rgb(97, 28, 28), rgb(136, 23, 23) 50%, rgb(168, 20, 20) 75%, rgb(116, 30, 30) 100%);
  /* rgb(218, 59, 59) */
  transition: .6s;
}

.emerg-circle:hover {
  background: radial-gradient(circle at 100%, rgb(185, 19, 19), rgb(184, 52, 52) 50%, rgb(168, 20, 20) 75%, rgb(116, 30, 30) 100%);
  box-shadow: 0px 0px 10px rgb(180, 53, 53) ;
}

/* Custom Toast Message */

#messenger {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  left: 50%;
  bottom: 30px;
  font-size: 17px;
}

#messenger.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

#mg-text {
  color: #fff;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;} 
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;} 
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}


.card-clip {
  color: #232323;
  float: right;
  position: static;
  width: 30%;
  height: 35px;
  background-color: #232323;
  color: white;
  padding: 5px;
  margin: 5px 15px;
  border-radius: 10px;
  text-align: center;
  
}



  </style>

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.html">Observation Panel</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0 custom-bar">
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger">9+</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <span class="badge badge-danger">7</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="emerg-circle" ></div>
          <!-- <i class="fas fa-user-circle fa-fw"></i> -->
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" id="ms-sect" href="#"></a>
          <a class="dropdown-item" href="#">MESSAGE 02</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="map.php">
          <i class="fas fa-map-marked-alt"></i>
          <span>Map</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="weather_report.php">
        <i class="fas fa-umbrella"></i>
          <span>Weather Pannel</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="dm_list.php">
        <i class="fas fa-house-damage"></i>
          <span>Disaster Management</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="announcement.php">
        <i class="fas fa-bullhorn"></i>
          <span>Announcement</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pages</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <h6 class="dropdown-header">Society:</h6>
          <a class="dropdown-item" href="articles.php">Articles</a>
          <a class="dropdown-item" href="404.html">Feedback</a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header">Other Pages:</h6>
          <a class="dropdown-item" href="404.html">Terms and Coditions</a>
          
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="mails.php">
        <i class="fas fa-mail-bulk"></i>
          <span>Mail Box</span></a>
      </li>
      
      <div class="user">
          
          <a class="nav-link" href="<?php if($level==4) { echo 'admin_panel.php';} else echo 'user_profile.php'?>" >
            <i class="fas fa-user-circle"></i><br>
            <span><?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?></span></a>
            <br>
            <small>[  <?php echo $_SESSION['Role']; ?>  ]</small><br>
            <p>Cooperate with us to do better!</p>
          <button data-toggle="modal" data-target="#logoutModal">LOGOUT</button>
          
      </div>
      
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Map</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>

        
        <!--Main Map panel-->
        <div class="cust-clip">
          <button onclick="viewMapPannel()"><i class="fas fa-arrows-alt"></i></button>
        </div>
        <!-- New Pannel Director -->
        <div class="cust-clip card-clip" >Latitude : <span id="mon-lat">[Number]</span></div>
        <div class="cust-clip card-clip" >Longitude : <span id="mon-lng">[Number]</span></div>
        <div class="cust-clip card-clip" >Elevation : <span id="mon-ele">[Number]</span></div>
        <div class="custom-panel" id="mappanel">
          
          <div style="position: sticky; z-index: 2; display: inline-block; width: 82vw;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header custom-head">
                    <h5 class="modal-title" id="exampleModalLabel">Option Menue</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="viewMapPannel()">
                    
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  
                  <div class="modal-footer custom-foot">
                    <a class="btn btn-light custom-btn" id="btn_update" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-arrows-alt"></i></div></li>
                        <li><div class="btn-icon">Scale</div></li>
                      </ol>
                 
                    </a>
                    <a class="btn btn-light custom-btn" id="btn_details" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-search-location"></i></div></li>
                        <li><div class="btn-icon">Details</div></li>
                      </ol>
                            
                    </a>
                    <a class="btn btn-light custom-btn" id="btn_batch" href="#" >
                      <ol>
                         <li><div class="btn-icon"><i class="fas fa-map-marker-alt"></i></div></li>
                         <li><div class="btn-icon">Trainer</div></li>
                       </ol>
                          
                    </a>
                    <a class="btn btn-light custom-btn" id="btn_draft" href="#" >
                      <ol>
                          <li><div class="btn-icon"><i class="fas fa-drafting-compass"></i></div></li>
                          <li><div class="btn-icon">Draft</div></li>
                        </ol>
                       
                      </a>
                      <a class="btn btn-light custom-btn" href="#" >
                        <ol>
                            <li><div class="btn-icon"><i class="fas fa-map-signs"></i></div></li>
                            <li><div class="btn-icon">Mode</div></li>
                        </ol>
                           
                      </a>
                  </div>
                </div>
              </div>
            </div>
          

        </div>

        <div class="custom-panel" id="listpanel">
          
          <div style="position: sticky; z-index: 2; display: inline-block; width: 80vw;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header custom-head">
                    <h5 class="modal-title" id="exampleModalLabel">Option Menue</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="viewMapPannel()">
                    
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  
                  <div class="modal-footer custom-foot">
                    <a class="btn btn-light custom-btn" id="btn_back" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-arrow-left"></i></div></li>
                        <li><div class="btn-icon">Back</div></li>
                      </ol>
                 
                    </a>
                    <a class="btn btn-light custom-btn" id="m_all" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-map-marked-alt"></i></div></li>
                        <li><div class="btn-icon">All</div></li>
                      </ol>
                      
                    </a>

                    <a class="btn btn-light custom-btn" id="m_user" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-map-marker-alt" style="color: red;"></i></div></li>
                        <li><div class="btn-icon">Pending</div></li>
                      </ol>
                            
                    </a>

                    <a class="btn btn-light custom-btn" id="m_confirm" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-map-marker-alt" style="color: rgb(13, 214, 13);"></i></div></li>
                        <li><div class="btn-icon">Confirmed</div></li>
                      </ol>
                            
                    </a>

                    <a class="btn btn-light custom-btn" id="m_station" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-map-marker-alt" style="color: blue;"></i></div></li>
                        <li><div class="btn-icon">Stations</div></li>
                      </ol>
                            
                    </a>

                    <a class="btn btn-light custom-btn" id="m_userloc" href="#" >
                      <ol>
                        <li><div class="btn-icon"><i class="fas fa-male"></i></div></li>
                        <li><div class="btn-icon">Users</div></li>
                      </ol>
                            
                    </a>
                    
                  </div>
                </div>
              </div>
            </div>
          

        </div>

        

        <!-- Map installation -->
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div class="card mb-3" id="map">

        
          
        <!-- Time settings -->
          <div class="card-footer small text-muted" >Updated yesterday at 11:59 PM</div>
        </div>

        <div class="custom-toast" id="messenger" style="background-color: rgb(170, 32, 32);"><p id="mg-text">This is the message</p></div>
        
        <!-- ====================================================|| DB Setting ||=================================================================-->
        <script src="js/firebase/init_fire.js"></script>
        <script src="js/firebase/announce_crud.js"></script>
        <!-- <script src="js/firebase/article_crud.js"></script> -->

        <script>

          // Init FireStre
          initFirestore();

          //--====================================================[FireStore Init]==================================================================--//

          //Database References | deploy Section | get Elevation of each section | 
          const refSection = firestore.collection('section'); 
          const refEncounter = firestore.collection('encounter');
          const refDMML = firestore.collection('disaster_identification_team');
          var storage = firebase.storage().ref();

          var role = ` <?php if(isset($_SESSION['Username'])) { echo $_SESSION['Role'];} ?>`;
          var drawPoly;
          var count_col = 0; //Varible to store all section count | (add Section) | deploy Section | get Elevation of each section | 
          var count_markers = 0; //Varible to store all markers count | (send Marker) | (update Marker) | get Elevation of each section | 
          var sect_elevation = []; //Varible array to store all section's elevation |  | 
          var sect_normal = [];
          var sect_warn = [];
          var sect_critical = [];
          var sect_collection = [];
          var sect_color = '#1abc9c';
          var drawPolyDB;
          var genVar = {};
          var genSect_collection = [];
          var message = '';
          var isMymap = false;
          var focused_user = "";

          // All Markers
          var marker_col = []; 
          var single_sect_contain = [];
          var ele_sect = [];


          //Memory Reducers
          var mem_mouseOver = 0;
          var h1 = 18;

          function promisedCount(data){
            count_col = data;
          }

          function promisedMarkersCount(){
            refEncounter.doc('count').get().then((num)=>{
              count_markers = num.data().count;
            });
          }

          promisedMarkersCount();

          

          
          function getSectionCount(){
            
            refSection.doc("River Banks").collection("collection_count").doc("count_sects").get().then((snaps)=>{
                setInterval(promisedCount(snaps.data().count), 8000);
            });
                
          }
         
          getSectionCount();

          

          function uploadImg(){
                const upload = document.getElementById('upload').files[0];
                var name = upload.name;
                
                var storage = firebase.storage().ref('resoures/uploads/'+name);

                var uploadTask = storage.put(upload);
                uploadTask.on('state_changed', function(snap){
                  var progress = (snap.bytesTranferred/snap.totalBytes);
                  console.log("Progress is"+progress);
                }, function(error){
                  console.log(error);
                }, function(){
                  // uploadTask.snap.ref.getDownloadURL().then(function(url){
                  //   console.log("URL is"+url);
                  // });
                });

                console.log("Upload Activity completed!");
                return name;
            }


          function sendMarker(){
              //All Inputs
              const category = document.querySelector('#cat');
              const description = document.querySelector('#desc');

              const lat = document.querySelector('#lat');
              const lng = document.querySelector('#lng');

              // pos = new GeoPoint(lat.value, lng.value);

              const loc =  document.querySelector('#pos');
              const user = document.querySelector('#user');
              const zone = document.querySelector('#zone');

              //Image Upload
              var filePath = uploadImg();
              console.log("Data:|"+category.value);
                refEncounter.add({
                  id: ++count_markers,
                  zone: zone.value,
                  category: category.value,
                  description: description.value,
                  user: user.value,
                  location: new firebase.firestore.GeoPoint(parseFloat(lat.value), parseFloat(lng.value)),
                  path: filePath,
                  accepted: false
                }).finally(()=>{alert("Section is Added!")});
                refEncounter.doc('count').update({
                  count: count_markers
                });
          }

          function updateMarkerData(id, col, val){
            refEncounter.where("id", "==", id).get().then((results)=>{
              results.forEach((row)=>{
                refEncounter.doc(row.id).update({
                  accepted: true
                }).then(()=>{
                  markerRemove();
                  markerShow();
                });
                
              });
            })
          }

          function deleteMarkerData(id){
            refEncounter.where("id", "==", id).get().then((results)=>{
              results.forEach((row)=>{
                refEncounter.doc(row.id).delete().then(()=>{
                  console.log("Marker Deletion Completed!");
                  markerRemove();
                  markerShow();
                }).catch((err)=>{
                  console.log("Error has occured! Error", err);
                })
              });
            })
          }

              
           
          //Varible to mark activation of each pannel button
          var train_state = 0;
          var list_state = 0;
          var help_state = false;
          var count = 0;

          


          var markersBatch = []; //Varible array to store multipple markers | (Add markers to section boader sections) | 
          var makersRemoveBatch = []; //Varible array to store multipple markers | (Removw markers to section boader sections) |
          var markerloc = {
            id: 0,
            name: "no-name",
            coords: 0
          }; //Varible to store data as Json

          const train_btn = document.getElementById('btn_batch');
          const list_btn = document.getElementById('btn_details');
          const back_btn = document.getElementById('btn_back');
          const draft_btn = document.getElementById('btn_draft');
          const update_btn = document.getElementById('btn_update')

          back_btn.addEventListener('click', ()=>{
            if(listPannel.style.display == "block"){
              listPannel.style.display = "none";
              mapPannel.style.display = "block";
            }
          });

          list_btn.addEventListener('click', ()=>{
            if(list_state==0){
              list_state = 1;
              listPannel.style.display = "block";
              mapPannel.style.display = "none";
              
            } 
            else {
              list_state = 0;
              listPannel.style.display = "none";
              mapPannel.style.display = "block";
              
            } 
          });

          train_btn.addEventListener('click', ()=>{
            if(train_state==0){
              train_state = 1;
              alert("Training Mode is On!");
            } 
            else {
              if(single_sect_contain!=null){
                if(confirm("Do you want to confirm this section?")){
                  instantSection();
                  single_sect_contain = [];
                } else {
                  single_sect_contain = [];
                }
              }
              train_state = 0;
              alert("Training Mode is Off!");
            } 
          });

          update_btn.addEventListener('click', ()=>{
            detectCryticalPolygons();
          })

          //Marker Buttons
          var userloc = true;
          var userm = true;
          var confi = true;
          var  station = true;

          const allmarker = document.getElementById('m_all');
          const usermarker = document.getElementById('m_user');
          const stationmarker = document.getElementById('m_station');
          const userlocmarker = document.getElementById('m_userloc');
          const confirmmarker = document.getElementById('m_confirm');

          allmarker.addEventListener('click', ()=>{
            if(userm){
              userloc = false;
              userm = false;
              confi = false;
              station = false;
              markerRemove();

              // JQuery Application 
              // $("#map").load(" #map");

            } else {
              userloc = true;
              userm = true;
              confi = true;
              station = true;
              markerShow();
            }
          });

          usermarker.addEventListener('click', ()=>{
            if(userm){
              userm = false;
              markerRemove();
              markerShow();
            } else {
              userm = true;
              markerRemove();
              markerShow();
            }
          });

          userlocmarker.addEventListener('click', ()=>{
            if(userloc){
              userloc = false;
              markerRemove();
              markerShow();
            } else {
              userloc = true;
              markerRemove();
              markerShow();
            }
          });

          confirmmarker.addEventListener('click', ()=>{
            if(userm){
              userm = false;
              markerRemove();
              markerShow();
            } else {
              userm = true;
              markerRemove();
              markerShow();
            }
          });

          stationmarker.addEventListener('click', ()=>{
            if(station){
              station = true;
              markerRemove();
              markerShow();
            } else {
              station = false;
              markerRemove();
              markerShow();
            }
          });

          //initializing a map using a function
          function myMap() {

      
            //------------------[Initialize a Map]----------------------------------------//
      
            //give some coords. as variable
            var myLoc = {lat: 6.5854, lng: 79.9607};
      
            var mapProp= {
                center:new google.maps.LatLng(myLoc),
                zoom:16,
                styles:[
            {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#263c3f'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#6b9a76'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#72716f'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#212a37'}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#e6dfdf'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#d3c8c8'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#1f2835'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#f3d19c'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#2f3948'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#066df5'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#dfe4eb'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#37383a'}]
            }
          ]
        };
            //creating map obejct using previously declared values (mapProp, myLoc)
            var map=new google.maps.Map(document.getElementById("map"),mapProp);


            //creating map obejct using previously declared values (mapProp, myLoc)
            var map=new google.maps.Map(document.getElementById("map"),mapProp);

      //create default bound for map[Not Working] 
      // var slbound = new google.maps.LatLngBounds(
      //   new google.maps.LatLng(8.774432,80.0120005),
      //   new google.maps.LatLng(7.481507,78.9197681));
      // var option = {bounds: slbound};

      //--------------------------------------------[Marker Information settings]-----------------------------------------------------//

      //main information draft created for Infowindow
      var mDraft = '<div id="Banner">'+'<div id="topB">'+'<h6><b>'+'Place 1 test 1'+'</b></h6><br><img src="test.jpg" alt="location" styles="position:absolute; float:right"/>'+'<p>'+'Test Location Test Location Test Location Test Location'+'</p>';

      var mDraft3 = '<div  id="form4"><form action="php/post_marker_ui.php" method="post"><table class="map1"><tr>'+
	            '<td><label>Name of the Location:</label></td>'+'<td><input type="text" name="txtLoc"></td></tr>'+
	            '<tr><td><a>Description:</a></td><td><textarea id="description" placeholder="Description" name = "txtme"></textarea></td></tr>'+
	            '<tr><td><input type="hidden" name="txtLati" value="'+parseFloat(45)+'"</td><td><input type="hidden" name="txtLngi" value="'+parseFloat(45)+'"</td></tr>'+
	            '<tr><td></td><td><input type="button" class="btn-warning" value="SUBMIT" name="submit2"/></td></tr></table></form></div>';

      //Water Level Meter
      var meterDraft = `<div class="slider-container">
                            <span class="span-back"></span><span class="span-fill"></span>
                            <span class="span-ruler"></span>
                            <input type="range" id="slider" class="st-slider" max="30" min="0" value="15" onclick="launchMeter()">
                            <span class="text" id= ele-river>00m</span>
                        </div>`;

      //Form Impowindow
      var formDraft  = `
            <div class="input-contain">
                    It's Your responsibility to submit correct information!
                    <div class="form-group">
                        <label>Zone</label>
                        <input type="text" id="zone" class="form-control">
                        <span></span>
                        <small class="text-danger" > Enter a valid Username</small>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" id="cat" class="form-control" >
                        <span></span>
                        <small class="text-danger" > Enter a valid Username</small>
                    </div>

                    <div class="form-group">
                        <label>Descriptiion</label>
                        <textarea id="desc" class="form-control"></textarea>
                        <span></span>
                        <small class="text-danger" > Password is Invalid</small>
                    </div>

                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" id="upload" class="form-control" >
                        <span></span>
                        
                    </div>

                    <input type="hidden" id="lat" value="">
                    <input type="hidden" id="lng" value="">
                    <input type="hidden" id="user" value="'<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>'">


                    <div class="form-group">
                        <button class="btn btn-success"  id="btn_markerSub">SUBMIT</button>
                       
                    </div>
                

            </div>
                        `;

      var mInfoDraft = `
            
                  <div class="info-container">
                      <div class="info-title">
                          <h1>[Title] </h1>
                          <small>[Category]</small>
                          <span>ICON</span>
                          
                      </div>
                      <div class="info-content">
                          <img src="./resources/sample.jpg" >
                          <p>
                              Unitec Institute of Technology is the largest institute of technology in Auckland, New Zealand. 16,844 students 
                              study programmes from certificate to
                          </p>
                      </div>
                  </div>
                      
      `;

      var a_loc = `
                  
      `;



      //create information window
      var inform = new google.maps.InfoWindow({
        content: mDraft
      });

     
      //-------------------------------------------[Pass location Latlng to the Form]-------------------------------------------------//
			var marker, maker;
			var dragableMarker = (function addDragableM(){
				//create Dragable marker
				marker = new google.maps.Marker({
					position: {lat: 6.5854, lng: 79.9607},
					map: map,
					title: 'Alert Area',
					content: "<h2>Give Your Location</h2>",
          icon:'images/icon/Geo Icon - choose.png',
					draggable: true,
					animation: google.maps.Animation.DROP


				});

      

				function toggleBounce() {
			        if (marker.getAnimation() !== null) {
			          marker.setAnimation(null);
			        } else {
			          marker.setAnimation(google.maps.Animation.BOUNCE);
			        }
			      }


				marker.addListener('click', toggleBounce);
        marker.addListener('click', () => {
            inform.open(map, marker);
        });

        //------------------------------------------------------[Help Mode]---------------------------------------------------------test---//
        window.init_help = function(fuser){
          // Do more with help state
          if(help_state==false){
            help_state= true;
            focused_user = fuser;
            message = "Community Helping Mode ON!";
            toastPopUp();
          } else {
            help_state= false;
            message = "Community Helping Mode OFF!";
            toastPopUp();
          }
          
        }


        //------------------------------------------------[Draggable Marker add]-------------------------------------------------------//
        //-----------------[Completed]-|82%|
        //-----------------|Single Marker Add|Firebase Deletion|Firebase counter effects handle|
        //-----------------[Tested]

				google.maps.event.addListener(marker,'dragend', function(){
				if(confirm("If you wanna Confirm this location press OK, otherwise press CANCEL.")){
						
          if(train_state==1){
            marker.setIcon("images/icon/Geo Icon - pending.png");
            marker.setAnimation(false);
            marker.setOptions({draggable: false});
            inform.setContent("<div id='Banner2'><strong>Your Location has been setted</strong><br><center><h6>Waiting for Confirm</h6><center></div>");
            inform.open(map,marker);
            
            console.log("Batch of markers can be inserted!");
            markerloc.id = ++count;
            markerloc.name = "Custom name";
            
            //Firebase Data Send
            refSection.doc("River Banks").collection("L"+count_col).doc(count.toString()).set({
                id: markerloc.id,
                name: markerloc.name,
                coords: new firebase.firestore.GeoPoint(marker.getPosition().lat(), marker.getPosition().lng())
            });
           
            if(confirm("Do you want to continue adding markers. Click OK! If you want to confirm this Section. Click CANCEL! ")){

              addDragableM();

            } else {
              if(confirm("Are you want to confirm this section?")){
                //Confirm inserted section settings
                console.log("Section is created!");
                // var ele = prompt("Confirm Elevation Difference", 7.6);
                ++count_col;
                refSection.doc("River Banks").collection("collection_count").doc("count_sects").set({
                  count: count_col
                });
                polygonAdd(count_col);
              } else {
                //Delete firebase record
              }
              
            } 
            
          } else {
            console.log("One marker can be entered");
            //Single Marker Adding implementation

            var user = ` <?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>`;
            var lat = this.getPosition().lat();
            var lng = this.getPosition().lng();
            var pos = this.getPosition();

            marker.setIcon("images/icon/Geo Icon - pending.png");
            marker.setAnimation(false);
            marker.setOptions({draggable: false});

            if(help_state){
              inform.setContent(`
            <div class="input-contain">
                      Submit correct information and Save lives!
                      <div class="form-group">
                          <label>Title</label>
                          <input type="text" id="zone" class="form-control">
                          <span></span>
                          
                      </div>

                      <div class="form-group">
                          <label>Descriptiion</label>
                          <textarea id="desc" class="form-control"></textarea>
                          <span></span>
                      </div>

                      <input type="hidden" id="lat" value="`+lat+`">
                      <input type="hidden" id="lng" value="`+lng+`">
                      <input type="hidden" id="lng" value="`+pos+`">
                      <input type="hidden" id="user" value="`+user+`">


                      <div class="form-group">
                          <button class="btn btn-success"  id="btnmarkerSub" onclick="sendMarker()">SUBMIT</button>
                        
                      </div>

                      
                  

              </div>
                        
              `);
            } else {
              inform.setContent(`
            <div class="input-contain">
                      It's Your responsibility to submit correct information!
                      <div class="form-group">
                          <label>Zone</label>
                          <input type="text" id="zone" class="form-control">
                          <span></span>
                          
                      </div>

                      <div class="form-group">
                          <label>Category</label>
                          <select id="cat" class="form-control" >
                            <option value="Encounter">Encounter / Emergency</option>
                            <option value="Condition">Weather Incident</option>
                            <option value="Building">Help Area</option>
                            <option value="Custom">Custom Area</option>
                          </select>
                          <span></span><br>
                          
                      </div>

                      <div class="form-group">
                          <label>Descriptiion</label>
                          <textarea id="desc" class="form-control"></textarea>
                          <span></span>
                          
                      </div>

                      <input type="hidden" id="lat" value="`+lat+`">
                      <input type="hidden" id="lng" value="`+lng+`">
                      <input type="hidden" id="lng" value="`+pos+`">
                      <input type="hidden" id="user" value="`+user+`">

                      <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" id="upload" name="file" class="form-control" accept="image/" >
                        <span></span>
                      </div>


                      <div class="form-group">
                          <button class="btn btn-success"  id="btnmarkerSub" onclick="sendMarker()">SUBMIT</button>
                        
                      </div>
              </div>
                        
              `);

            }

					  inform.open(map,marker);
          
            if(confirm("Do you want to add Another location. Click OK ")){
              addDragableM();
            } else {
              return;
            } 
          }
				} else {
					return;
				}
				

			  });

      })(); //Imediately invoke function


            // Function to remove markers 
            window.markerRemove = function (){
              marker_col.forEach(function(m){
                m.setMap(null);
              });
              
            }

            //Sub function use to create Makers
             function markerAdd(props){
              var maker = new google.maps.Marker({
                position: props.coords,
                map: map,
                draggable: props.isDrag,
                icon: props.icon
              });
              //console.log(maker.getPosition().lat());
              marker_col.push(maker);
              if(props.content){
                var inform = new google.maps.InfoWindow({
                  content: props.content
                });

                maker.addListener('click', function(){
                  inform.open(map,maker);
                });

              }else if(props.content==""){
                var inform = new google.maps.InfoWindow({
                  content: "Location A"
                });

                maker.addListener('click', function(){
                  inform.open(map,maker);
                });

              }
            }

            refDMML.doc('ML').get().then((values)=>{
              console.log("VALUE :" + values.data().slope);
              var slope_ml = values.data().slope;
              var inter_ml = values.data().intercept;
              markerAdd({coords:{lat: 6.677297671308204, lng:80.09391832282826}, icon:"images/icon/unnamed 2.png", 
              content:
                    `
                    <div class="info-container">
                            <div class="info-title">
                                <h1>Location A</h1>
                                <small>6.677297671308204<br>80.09391832282826</small><br><br>
                                <span>Elevation: 89 h</span><br>
                                <span></span><br>
                                <center><small>Water level difference</small><br>
                                        <h4><span id="ele-level">${h1}<span> <span style="font-size: 14px">m</span></h4>
                                <center>
                                <a class="btn btn-dark" onclick="getBElevation()">Feed</a>
                            </div>
                            <div class="info-content">
                                <h4>Elevation Test</h4>
                                <center>Slope: <span id="in_slope">${slope_ml}</span></center>
                                <center>Intercept: <span id="in_inter">${inter_ml}</span></center>
                                <p>
                                  This location is considered for Simulation purposes.
                                </p>
                            </div>
                      </div>
                `});

                window.getBElevation = function(){
                  var el; 
                  h1 = prompt("Enter your waterlevel elevation value of A.", 18);
                  el = (h1*slope_ml)+ parseInt(inter_ml) ;
                  console.log("Value : "+ el);
                }
            });


            

            


          //-----------------------------------------------[Pass Marker to the Map]------------------------------------------------------//

            function promisedImages(doc,gotURL, user, desc, cat, lat, lng, id, acc){
              //Froxy Url format
              var proxy_url = 'https://cors-anywhere.herokuapp.com/';
              
              var storage = firebase.storage().ref('resoures/uploads/'+doc).getDownloadURL().then(function(url){
                var xhr = new XMLHttpRequest();
                xhr.responseType = 'blob';
                xhr.onload = function(event) {
                  var blob = xhr.response;
                };
                xhr.open('GET', url);
                xhr.send();

                gotURL = url;
                
                //Category 
                // var ico = 'images/icon/Geo Icon - pending.png';
                if((acc==false)&&((role.trim()=="Admin")||(role.trim()=="Staff"))){ 
                  ico = 'images/icon/Geo Icon - warn.png'
                } else if(acc==true && cat=="Emergency"){
                  ico = 'images/icon/Geo Icon - encounter.png';
                } else if(acc==true && cat=="Building"){
                  ico ='images/icon/Geo Icon - outpost.png';
                } else if(acc==true && cat=="Condition"){
                  ico ='images/icon/Geo Icon - wether.png';
                } else if(acc==true && cat=="Custom"){
                  ico ='images/icon/Geo Icon - confirm.png';
                } 

                if(acc==false && role.trim()=="Member"){
                  ico = null;
                  return;
                }

                // Filter
                if((cat=="Emergency"|| cat=="Condition")&&(!userm)){
                  return;
                }
                if((cat=="Building")&&(!station)){
                  return;
                }
                if((cat=="Condition")&&(!station)){
                  return;
                }

                //Create Markers

                var hiddenTXT = '<?php echo $level ?>';
                // console.log("URL :", url);

                //If-Else to handle markers to display
                // if()

                if(((hiddenTXT==4)||(hiddenTXT==2))&&(!acc)){
                  
                  markerAdd({coords:{lat: lat, lng:lng}, icon:ico, content:
                  `
                  <div class="info-container">
                          <div class="info-title">
                              <h1>${cat} </h1>
                              <small>${lat}<br>${lng}</small><br><br>
                              <span>Uploaded by: <a href="./user_pannel.php?user=${user.trim()}">${user}</a></span><br>
                              <span><a href="./message_box.html?receiver=${user.trim()}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>">
                              <i class="fas fa-envelope-open" style='color:white'></i>
                              </a> 
                              <a href="./mail_box.html?receiver=${user.trim()}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>">
                              <i class="fas fa-comments"></i>
                              </a> 
                              </span><br>
                              <br>
                              <button class="btn btn-dark"onclick="updateMarkerData(${id}, 'accepted', 1)">Confirm</button>
                              <button class="btn btn-danger" onclick="deleteMarkerData(${id})">Decline</button>
                          </div>
                          <div class="info-content">
                              <img id="info-img" src="${url}" width="200" >
                              <p>
                                ${desc}
                              </p>
                          </div>
                    </div>
                  `});
                  
                } else {
                  markerAdd({coords:{lat: lat, lng:lng}, icon:ico, content:
                  `
                  <div class="info-container">
                          <div class="info-title">
                              <h1>${cat} </h1>
                              <small>${lat}<br>${lng}</small><br><br>
                              <span>Uploaded by: <a href="./user_pannel.php?user=${user.trim()}">${user}</a></span><br>
                              <span><a href="./message_box.html?receiver=${user.trim()}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>">
                              <i class="fas fa-envelope-open" style='color:white'></i>
                              </a> 
                              <a href="./mail_box.html?receiver=${user.trim()}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>">
                              <i class="fas fa-comments"></i>
                              </a> 
                              </span><br>
                              <br>
                          </div>
                          <div class="info-content">
                              <img id="info-img" src="${url}" width="200">
                              <p>
                                ${desc}
                              </p>
                          </div>
                    </div>
                  `});
                }
                

                //----------

              }).catch(function(error){
                console.log("404 Resource Not Found!");
              });
            }

            

            function promisedData(doc){
              var user_name = doc.user;
              var desc = doc.description;
              var cat = doc.category;

              if(doc.location!=null){
                var lat = doc.location.latitude;
                var lng = doc.location.longitude;
              }
              var url = doc.path;
              var id = doc.id;
              var acc = doc.accepted;

              //Get Uploaded Image
              var goturl;
              promisedImages(url, goturl, user_name, desc, cat, lat, lng, id, acc);
             
            }

            function promisedUserLocationData(doc){
              // Filter
              if((!userloc)){
                  return;
              }
              var user_name = doc.cust_name;
              var lat= doc.start_lat;
              var lng = doc.start_lng;
              
              markerAdd({coords:{lat: lat, lng:lng}, icon:'images/icon/marker_persond25px.png', isDrag: true, content:
              `
                    <div class="info-container">
                          <div class="info-title">
                              <h1>${user_name}</h1>
                              <small>${lat}<br>${lng}</small><br><br>
                              <span>Profile: <a href="./user_pannel.php?user=${user_name}">${user_name}</a></span><br>
                              <span><a href="./message_box.html?receiver=${user_name}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>">
                              <i class="fas fa-envelope-open" style='color:white'></i>
                              </a>
                              <a href="./mail_box.html?receiver=${user_name}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>">
                              <i class="fas fa-comments"></i>
                              </a> 
                              </span><br>
                              <br>
                              <a href="./message_box.html?receiver=${user_name}&sender=<?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>&type=alert" class="btn btn-danger">Alert</a>
                              <a href="#" id="help_userloc" onclick="init_help('${user_name}')" class="btn btn-primary">Share Location</a>
                          </div>
                          <div class="info-content">
                              <img id="info-img" src="#" width="200">
                              <p>
                                User Available
                              </p>
                              
                          </div>
                    </div>
              `
              });
              maker.addListener('position_changed', ()=>{
                
                for(var i = 0; i<sect_critical.length; i++ ){
                  console.log(maker.getPosition().lat());
                  var reelimg = google.maps.geometry.poly.containsLocation(maker.getPosition(), genSect_collection[sect_critical[i]])? 'images/icon/marker_persond25px.png':'images/icon/marker_persond25px.png';
                  maker.setIcon(reelimg); //--------------------------------------------------------------[User Marker Highlight] [FLAG]
                }
                
              });
              
            }

            window.markerShow = function(){
              var dbloc;
              console.log("Deploy Markers!");
              //Firestore References
              firestore.collection('encounter').get().then((snap)=>{
                snap.forEach((doc)=>{
                  dbloc = doc.data();
                  setInterval(promisedData(dbloc), 1000);
                  
                });
              });

              firestore.collection('user').get().then((snaps)=>{
                snaps.forEach((snap)=>{
                  firestore.collection('user').doc(snap.id).collection('user_location').get().then((latlng)=>{
                    latlng.forEach((impo)=>{
                      if(impo.data()){
                        dbloc = impo.data();
                        setTimeout(promisedUserLocationData(dbloc), 20000);
                      }
                      
                    });
                  });
                });
              });
              
            };

            markerShow();

            
          //----------------------------------------------[Pass Polygon sections to the Map]---------------------------------------------//


            function promisedPolygon(doc){
              let lat = doc.latitude;
              let lng = doc.longitude;
              
              let block = {lng:lng, lat:lat};
              markersBatch.push(block);
              
            }

            function promisedPolygonR(doc){ //------------------------[REPEATED]
              let lat = doc.latitude;
              let lng = doc.longitude;
              
              let block = {lng:lng, lat:lat};
              makersRemoveBatch.push(block);
              
            }


            function polygonAdd(c, sect_num){
              var length = c;
              
              // markerAdd([{coords:{lat: 6.5969992, lng: 79.96198}, icon:'"images/icon/Geo Icon 50px - connect.png"'}]);
              if(sect_num==undefined){
                var color_crit = '#FF0000';
                for(var i = 0; i < length; i++){
                  refSection.doc("River Banks").collection("L"+ i).orderBy("id").get().then((snap)=>{
                  snap.forEach((row)=>{
                    setInterval(promisedPolygon(row.data().coords), 9000);

                    
                  });

                  //with Generated Variables

                  genVar['drawSect'+ i] = new google.maps.Polygon({
                    paths: [markersBatch],
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: `${sect_color}`,
                    fillOpacity: 0.8

                  });

                  sect_collection.push(markersBatch);

                  genVar['drawSect'+ i].setMap(map);


                  genSect_collection.push(genVar['drawSect'+ i]);

                  
                  
                  
                  //Clearing Markers Array
                  markersBatch = [];
                  
                  

                });

                }
                
                

              } else  {
                  

                  genSect_collection[sect_num].setOptions({fillColor: sect_color});
                  // console.log(genSect_collection);

                  
                  //Clearing Markers Array
                  markersBatch = [];
                  // detectCryticalPolygons(sect_num);

                // });
              }
              
            }

            window.detectCryticalPolygons = function(){
              // Critical Section Detector
              
              for(var i = 0; i < genSect_collection.length; i++){
                  refSection.doc("River Banks").collection("L"+i).doc("critical").get().then((snaps)=>{
                    if(typeof snaps.data().isCritical!=undefined){
                      if(snaps.data().isCritical){
                        sect_color = '#e74c3c';
                        genSect_collection[6].setOptions({fillColor: sect_color});
                        polygonAdd(sect_collection, i);
                        console.log("Critical");
                      } else {
                        color_crit = '#1abc9c';
                      }
                      
                    }
                   
                    }).catch((ex)=>{console.log(ex)});
                }
                    
            }

            

            function detectInterception(polygon){
              maker.addListener('position_changed', ()=>{
              console.log("Marker Changed its position");
              var reelimg = google.maps.geometry.poly.containsLocation(marker.getPosition(), polygon)? 'icon/marker_persond25px.png' :
              'icon/marker_persond25px.png';
              marker.setIcon(reelimg);
              });
            }

            
            // setInterval(()=>{
            //   marker.setMap(null);
            //   markerShow();
            // }, 30000);

            polygonAdd(27);

          

            // --------------------------------------[Location Highlight using Polygon]-----------------------------------------------//

            var sampleLoc = [
              {lng: 80.72041321, lat: 5.96180487},
              {lng: 80.72041321, lat: 5.96152687}, 
              {lng: 80.7206955, lat: 5.96152687}, 
              {lng: 80.7206955, lat: 5.96124887}, 
              {lng: 80.7215271, lat: 5.96124887}, 
              {lng: 80.7215271, lat: 5.96097088}, 
              {lng: 80.72208405, lat: 5.96097088}, 
              {lng: 80.72208405, lat: 5.96069479}, 
              {lng: 80.72264099, lat: 5.96069479}, 
              {lng: 80.72264099, lat: 5.95958281}, 
              {lng: 80.72180176, lat: 5.95958281}, 
              {lng: 80.72180176, lat: 5.9598608}, 
              {lng: 80.72097015, lat: 5.9598608}, 
              {lng: 80.72097015, lat: 5.9601388}, 
              {lng: 80.72013855, lat:5.9601388}, 
              {lng: 80.72013855, lat: 5.96041679}, 
              {lng: 80.7195816, lat: 5.96041679}, 
              {lng: 80.7195816, lat: 5.96069479}, 
              {lng: 80.71930695, lat: 5.96069479}, 
              {lng: 80.71930695, lat:5.96152687}, 
              {lng: 80.7195816, lat: 5.96152687}, 
              {lng: 80.7195816, lat: 5.96180487}, 
              {lng: 80.72041321, lat: 5.96180487}

          ];



          var kalu_in = [
            {lng: 79.965377772, lat: 6.59085689},
            {lng: 79.967437709, lat: 6.59307373},
            {lng: 79.971815074, lat: 6.59200794},
            {lng: 79.972458804, lat: 6.59324426},
            {lng: 79.970828021, lat: 6.59520530},
            {lng: 79.971385920, lat: 6.59678266},
            {lng: 79.974003756, lat: 6.59674003},
            {lng: 79.975849116, lat: 6.59929789},
            {lng: 79.974733317, lat: 6.60151470},
            {lng: 79.969840968, lat: 6.60415780},
            {lng: 79.968338932, lat: 6.60415780},
            {lng: 79.967030013, lat: 6.60155733},
            {lng: 79.965012992, lat: 6.59850922},
            {lng: 79.962974513, lat: 6.59752870},
            {lng: 79.961601222, lat: 6.59633503},
            {lng: 79.961236441, lat: 6.59494951},
            {lng: 79.962480986, lat: 6.59350004}
          ]

          bounds = new google.maps.LatLngBounds();

          drawPoly = new google.maps.Polygon({
            paths: [kalu_in, sampleLoc],
            name: "drawPoly",
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35

          });

          drawPoly.setMap(map);
          
          

          if(role.trim()=="Admin"){
              google.maps.event.addListener(drawPoly, 'click', function(event) {
              var contentString = "name:" + this.name + "<br>" + event.latLng.toUrlValue(6);
              inform.setContent(meterDraft);
              inform.setPosition(event.latLng);
              inform.open(map);
              
            });
          }

          

          for (var i = 0; i < drawPoly.getPath().getLength(); i++) {
            bounds.extend(drawPoly.getPath().getAt(i));
          }

          // -------------------------------------------[Location Search settings]---------------------------------------------------//

			    // Create the search box and link it to the UI element.
	        var input = document.getElementById('pac-input');
	        var locatorM = new google.maps.places.SearchBox(input);
	        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	        // Bias the SearchBox results towards current map's viewport.
	        map.addListener('bounds_changed', function() {
	          locatorM.setBounds(map.getBounds());
	        });

	        var markers = [];
	        // Listen for the event fired when the user selects a prediction and retrieve
	        // more details for that place.
	        locatorM.addListener('places_changed', function() {
	          var places = locatorM.getPlaces();

	          if (places.length == 0) {
	            return;
	          }

	          // Clear out the old markers.
	          markers.forEach(function(marker) {
	            marker.setMap(null);
	          });
	          markers = [];

	          // For each place, get the icon, name and location.
	          var bounds = new google.maps.LatLngBounds();
	          places.forEach(function(place) {
	            if (!place.geometry) {
	              console.log("Returned place contains no geometry");
	              return;
	            }
	            var icon = {
	              url: place.icon,
	              size: new google.maps.Size(71, 71),
	              origin: new google.maps.Point(0, 0),
	              anchor: new google.maps.Point(17, 34),
	              scaledSize: new google.maps.Size(25, 25)
	            };

	            // Create a marker for each place.
	            markers.push(new google.maps.Marker({
	              map: map,
	              icon: icon,
	              title: place.name,
	              position: place.geometry.location
	            }));

	            if (place.geometry.viewport) {
	              // Only geocodes have viewport.
	              bounds.union(place.geometry.viewport);
	            } else {
	              bounds.extend(place.geometry.location);
	            }
	          });
	          map.fitBounds(bounds);
	        });


	        

          var service = new google.maps.places.PlacesService(map);

          service.getDetails({
            placeId: 'ChIJN1t_tDeuEmsRUsoyG83frY4'
          }, function(place, status){
            if(status=== google.maps.places.PlacesServiceStatus.OK){
              var marker2 = new google.maps.Marker({
                map: map,
                position: {lat: 7.8433, lng: 80.0032},
                draggable: true
              });
              google.maps.event.addListener(marker2,'click', function(){
                inform2.setContent('<div><strong>' + place.name +'<strong><br>'+'Place ID:'+place.placeId + '<br>' + place.formated_address + '</div>');
                inform2.open(map, this);
              });
            }
          });

          //----------------------------------------------------[Elevation API Settings]----------------------------------------------------//
          function elevationSettings(APID){
            // console.log(APID);
            var difference = parseFloat(APID); 
            var river_waterL = 15 + APID;
            
            sect_elevation.push(river_waterL);
            // console.log(sect_elevation);
          }

          function elevationGet(col){
            sect_elevation = [];
            for(var i = 0; i < col; i++){
              refSection.doc("River Banks").collection("L"+ i).doc("ele_difference").get().then((doc)=>{
                //console.log(doc.data());
                setInterval(elevationSettings(doc.data().difference), 12000);
              });
            }
            
          }

          
          
          //Infowindow Meter Launch
          window.launchMeter = function () {
            
            const slider = document.getElementById('slider');
            const river_level = document.getElementById('ele-river');

            elevationGet(count_col);
            river_level.innerHTML = slider.value+" m";
            
            slider.oninput = function(){
                river_level.innerHTML = this.value+" m";
            }

            slider.addEventListener('mousemove', function(){
              console.log("Meter launched");
                var x = slider.value;
                var color = `linear-gradient(90deg, #16afdd ${x*(100/30)}%, #232323 ${x*(100/30)}%)`;
                slider.style.background = color;

                //calculation
                if(mem_mouseOver==x){
                  return;
                } else {
                  mem_mouseOver = x;
                  for(var i = 0; i < count_col; i++){
                    if((sect_elevation[i]-(x))<0){
                      
                      // sect_color = '#e74c3c';

                      //Errase all Polygons
                      // if(i == 1){
                      //   drawPolyDB.setMap(null);
                      //   console.log("FULL");
                      // }
                      // drawPolyDB.setMap(null);

                      //++launch
                      // polygonAdd(count_col, i);
                      if(!sect_critical.includes(i)){
                        sect_critical.push(i);

                        let index = sect_normal.indexOf(i);
                        if (index > -1) {
                          sect_normal.splice(index, 1);
                        }
                      }
                      
                      console.log(i,"Action Triggered!");
                      message = "Warnigs are issued for "+sect_critical.length +" Sections due to water level rise to flood level.";
                      toastPopUp();

                    
                    } 
                    else {
                      // sect_color = "#1abc9c";
                      // polygonAdd(count_col, i);
                      if(!sect_normal.includes(i)){
                        sect_normal.push(i);
                        //Remove form Critical
                        let index = sect_critical.indexOf(i);
                        if (index > -1) {
                          sect_critical.splice(index, 1);
                        }
                      }
                    }
                  }
                }
                
                
            });

            slider.addEventListener('mouseout', function(){
              console.log("Critical Sections : ", sect_critical);
              for(var i = 0; i<sect_critical.length; i++){
                sect_color = '#e74c3c';
                polygonAdd(sect_collection, sect_critical[i]);

                // Section Permenant Effect Apply
                refSection.doc("River Banks").collection("L"+ sect_critical[i]).doc("critical").set({
                  isCritical: true
                });
              }
              console.log("Normal Sections : ", sect_normal);
              for(var i = 0; i<sect_normal.length; i++){
                sect_color = '#1abc9c';
                polygonAdd(sect_collection, sect_normal[i]);

                // Section Permenant Effect Apply
                refSection.doc("River Banks").collection("L"+ sect_normal[i]).doc("critical").set({
                  isCritical: false
                });
              }
            });
          };

          //-----------------[Completed]-
          //-----------------[Working]----------------------------------

          var countM = 0;
          google.maps.event.addListener(map, 'click', function(event){
            var user = ` <?php if(isset($_SESSION['Username'])) { echo $_SESSION['Username'];} else echo "NO USER"?>`;
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            
            countM += 1;
            var ele = 0;

            if(help_state){
              markerAdd({coords:event.latLng,icon:'images/icon/Geo Icon - choose.png', content: 
            `
            <div class="input-contain">
                      Submit correct information and Save lives!
                      <div class="form-group">
                          <label>Title</label>
                          <input type="text" id="title" class="form-control">
                          <span></span>
                          
                      </div>

                      <div class="form-group">
                          <label>Descriptiion</label>
                          <textarea id="desc" class="form-control"></textarea>
                          <span></span>
                      </div>

                      <input type="hidden" id="lat" value="`+lat+`">
                      <input type="hidden" id="lng" value="`+lng+`">
                      
                      <input type="hidden" id="user" value="`+user+`">


                      <div class="form-group">
                          <button class="btn btn-success" onclick="sendAlertswithLocation('${focused_user}')"  >SUBMIT</button>
                      </div>
              </div>
                        
              `
            
            });


            }else {

              markerAdd({coords:event.latLng,icon:'images/icon/Geo Icon - choose.png', content: 
            `
            <div class="input-contain">
                      It's Your responsibility to submit correct information!
                      <div class="form-group">
                          <label>Zone</label>
                          <input type="text" id="zone" class="form-control">
                          <span></span>
                          
                      </div>

                      

                      <div class="form-group">
                          <label>Category</label>
                          <select id="cat" class="form-control" >
                            <option value="Encounter">Encounter / Emergency</option>
                            <option value="Condition">Weather Incident</option>
                            <option value="Building">Help Area</option>
                            <option value="Custom">Custom Area</option>
                          </select>
                          <span></span><br>
                          
                      </div>

                      <div class="form-group">
                          <label>Descriptiion</label>
                          <textarea id="desc" class="form-control"></textarea>
                          <span></span>
                          
                      </div>

                      <input type="hidden" id="lat" value="`+lat+`">
                      <input type="hidden" id="lng" value="`+lng+`">
                     
                      <input type="hidden" id="user" value="`+user+`">

                      

                      <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" id="upload" name="file" class="form-control" accept="image/" >
                        <span></span>
                      </div>


                      <div class="form-group">
                          <button class="btn btn-success"  id="btnmarkerSub" onclick="sendMarker()">SUBMIT</button>
                        
                      </div>

                      
                  

              </div>
                        
              `
            
            });

            if(train_state == 1){
              single_sect_contain.push(event.latLng);
              
            }

              // Monitor Pan
              var elevator = 0;
              getElevation(event.latLng.lat(), event.latLng.lng(), elevator);
              var lat_in = document.getElementById('mon-lat');
              lat_in.innerHTML = event.latLng.lat();
              var lng_in = document.getElementById('mon-lng');
              lng_in.innerHTML = event.latLng.lng();
            }

            

          });

          window.instantSection = function(){
              for(var i = 0; i < single_sect_contain.length; i++){

                // Average Elevation Calculation


                //Firebase Data Send
                refSection.doc("River Banks").collection("L"+count_col).doc(i.toString()).set({
                    id: i,
                    name: "Instant Name",
                    coords: new firebase.firestore.GeoPoint(single_sect_contain[i].lat(), single_sect_contain[i].lng())
                });
                
              }
              ++count_col;
              refSection.doc("River Banks").collection("collection_count").doc("count_sects").set({
                count: count_col
              }).then(()=>{
                var rough_ele = prompt("Get Average elevation value", 7.5);
                refSection.doc("River Banks").collection("L"+(count_col-1)).doc("ele_difference").set({
                  difference: rough_ele
                });
                refSection.doc("River Banks").collection("collection_count").doc("count_sects").set({
                  count: count_col
                });
                polygonAdd(count_col);
              });
          }


        
          window.getElevation = function(lat, lng, elevation){

            //Elevation API Request url format
            var ele_url = `https://maps.googleapis.com/maps/api/elevation/json?locations=${lat},-${lng}&key=AIzaSyDAsJYZSQ92_NQAz9kiSpW1XpyuCxRl_uI`;
            //Froxy Url format
            var proxy_url = 'https://cors-anywhere.herokuapp.com/';

            //Fetching Response
            fetch(proxy_url + ele_url).then((response)=>{
              let result = response.json();
              return result;
            }).then((data)=>{
              elevation = data.results[0].elevation;
              
              // return elevation;
              var elevate = document.getElementById('mon-ele');
              elevate.innerHTML = elevation;
            })
          }


          // -----------------------------------------[Map Drawing Pannel]------------------------------------------------//
          var draw_pannel = false;
          
          var drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.MARKER,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.BOTTOM_CENTER,
            drawingModes: ['marker', 'circle', 'polygon', 'polyline', 'rectangle']
          },
          markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
          circleOptions: {
            fillColor: '#ffff00',
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 1
          }
        });
        
          
        draft_btn.addEventListener('click', ()=>{
            if(draw_pannel==false){
              drawingManager.setMap(map);
              draw_pannel = true;
            } else {
              drawingManager.setMap(null);
              draw_pannel = false;
            }
          });
          
          

          
              }
              
              //Map pannel controls
              var view = true;
              const mapPannel = document.querySelector('#mappanel');
              const listPannel = document.querySelector('#listpanel');
              const mapMini = document.querySelector('.cust-clip');
              mapMini.style.display = "none";
              listPannel.style.display = "none";
              function viewMapPannel(){
                if(view == true) {
                  view = false;
                  if(list_state == 1){
                    listPannel.style.display = "none";
                  } else {
                    mapPannel.style.display = "none";
                    listPannel.style.display = "none";
                  }
                  mapMini.style.display = "block";
                  
                } else {
                  view = true;
                  if(list_state == 1){
                    listPannel.style.display = "block";
                  } else {
                    mapPannel.style.display = "block";
                  }
                  
                  mapMini.style.display = "none";
                }

              }

              //Toast Messages Set Up
              function toastPopUp() {
                console.log("Popup Loaded");
                var x = document.getElementById("messenger");
                var m = document.getElementById("ms-sect");

                // Send Message to Firebase
                // sendAlerts("alert", message);
                sample();
                sendAlerts("alert", message);

                x.innerHTML = message;
                m.innerHTML = message;
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
              }

              // import { initFirestore } from 'js/firebase/script.js';


              // function addBatch(){
                
              // }
      
      
        </script>
      
        
        
        <!--Created API link with generted key-->
        <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAsJYZSQ92_NQAz9kiSpW1XpyuCxRl_uI&libraries=places&callback=myMap"></script>-->
        
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAsJYZSQ92_NQAz9kiSpW1XpyuCxRl_uI&libraries=places,drawing&language=si&region=SL&callback=myMap"></script>
        
        

        
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">

          <div class="copyright text-center my-auto">

              <!--display time-->


              <script>
              function startTime() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                document.getElementById('txt').innerHTML =
                h + ":" + m + ":" + s;
                var t = setTimeout(startTime, 500);
              }
              function checkTime(i) {
                if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
                return i;
              }
              </script>


              <body onload="startTime()">

              <div id="txt"></div>


            <span>Copyright © weather alpha</span>
          </div>
        </div>
      </footer>

    </div>


    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>



  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <!-- <script src="js/demo/chart-area-demo.js"></script> -->

</body>

</html>
