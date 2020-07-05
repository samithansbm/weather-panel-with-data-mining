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

  <title>Weather Pannel | WeatherALPHA</title>

  <!--================================================= Site Icon ===================================================================-->
  <link rel="icon" type="image/png" href="images/icon/test.png">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


  <!--===============================================FontAwesome Icons================================================================--> 
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!--==============================================Weather Plugin Fonts==============================================================--> 
  <link href="https://fonts.googleapis.com/css?family=Quicksand|Raleway&display=swap" rel="stylesheet">

  <!--============================================= Page level plugin CSS=============================================================-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/sb-custom.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/sb-weather.css" rel="stylesheet">

  <!-- The core Firebase JS SDK is always required and must be listed first -->
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-firestore.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-analytics.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.9.1/firebase-storage.js"></script>      
  <script src="https://www.gstatic.com/firebasejs/3.1.0/firebase-database.js"></script>

  <!--==========================================================JQuery=================================================================-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!--==========================================================Plotly.js==============================================================-->
  <script src="vendor/plotly.js/dist/plotly.min.js"></script>
  

  

  <style>

          .pannel {
                position: relative;
                width:90vh;
                height: 100%;
                
                display: flex;
                padding: 20px;
                font-family: 'Raleway', sans-serif;
                
            }

            .window {
                background-color: aquamarine;
                flex: 1;
                border-radius: 10px 10px;
                max-height: 65vh;
                display: flex;
                justify-content: center;
                justify-items: center;
                text-align: center;
                border: 1px ridge #fff;
                
                
            }

            


            .main {
			display: flex;
			
			width:100%;
			height: 100%;
			justify-content: center;
			align-items: center;

		} 

		.table-box {
			height: 600px;
			width:100%;
			margin-top: 10px;
			overflow-y: scroll;
			box-shadow: 0 10px 100px rgba(0,0,0,0.5);
			background: white;

		}

		table {
			width:100%;
		}

		table, th, td {
			border:1px solid black;
			border-collapse: collapse;
			
		}

		td:hover {
			background-color: #ff7979;
			color: #fff;
		}

		th {
			position: sticky;
			top: 0px;
			background-color: #4834d4;
			color:  #fff;
			box-sizing: border-box;
		}

		tr th:nth-child(1){
			background-color: #eb4d4b;
			width: 80px;
		}

		tr td:nth-child(1){
			background-color: #686de0;
			color: white;
			text-align: center;
			width: 80px;
		}

		tr th:nth-child(2){
			
			width: 330px;
		}

		tr th:nth-child(3), tr th:nth-child(4), tr th:nth-child(5), tr th:nth-child(6){
			
			width: 170px;
		}

		tr td:nth-child(7) {
			text-align: right;
		}

  </style>

</head>

<body id="page-top" onload="getLocationGraph(6.5854, 79.9607)">

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
          <!-- <i class="fas fa-user-circle fa-fw"></i> -->
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="#">Activity Log</a>
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
      <li class="nav-item">
        <a class="nav-link" href="map.php">
          <i class="fas fa-map-marked-alt"></i>
          <span>Map</span>
        </a>
      </li>
      <li class="nav-item active">
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
            <a href="weather_report.php">Weather</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>

        
        <!-- Single Location Weather Pannel -->
        <div >
          <div class="forecast-container">
            <div class="today forecast">
              <div class="forecast-header">
                <div class="day">Weekly Brief</div>
                <div class="date"><i class="far fa-calendar-minus" style="color: rgb(9, 230, 230); margin: 0 5px 0 10px;"></i><span id="currentDate">6 Oct</span></div>
              </div> 
              <div class="forecast-content">
                <div class="location" id="location">Colombo</div>
                <div class="degree">
                  <div class="num temp">23<sup>o</sup>C</div>
                  <div class="forecast-icon icon" style="width: 50px; margin-bottom: 52px;">
                    <img src="images/icon/source/unknown.png" alt="" width=90>
                  </div>	
                </div>
                <span><i class="fas fa-clock" style="color: rgb(9, 230, 230); margin: 0 5px 0 10px;"></i><span class="notification">24hrs</span></span>
                <span><i class="fas fa-wind" style="color: rgb(9, 230, 230); margin: 0 5px 0 10px;"></i><span id="wind">18km/h</span></span>
                <span><i class="fas fa-smog" style="color: rgb(9, 230, 230); margin: 0 5px 0 10px;"></i><span id="desc"></span></span>
              </div>
            </div>
            <div class="forecast">
              <div class="forecast-header">
                <div class="day">Tuesday</div>
              </div> 
              <div class="forecast-content">
                <div class="forecast-icon">
                  <!-- <img src="images/icons/icon-3.svg" alt="" width=48> -->
                </div>
                <div class="degree" id="temp1">23<sup>o</sup>C</div>
                <small>18<sup>o</sup></small>
              </div>
            </div>
            <div class="forecast">
              <div class="forecast-header">
                <div class="day">Wednesday</div>
              </div> 
              <div class="forecast-content">
                <div class="forecast-icon">
                  <!-- <img src="images/icons/icon-5.svg" alt="" width=48> -->
                </div>
                <div class="degree" id="temp2">23<sup>o</sup>C</div>
                <small>18<sup>o</sup></small>
              </div>
            </div>
            <div class="forecast">
              <div class="forecast-header">
                <div class="day">Thursday</div>
              </div> 
              <div class="forecast-content">
                <div class="forecast-icon">
                  <!-- <img src="images/icons/icon-7.svg" alt="" width=48> -->
                </div>
                <div class="degree" id="temp3">23<sup>o</sup>C</div>
                <small>18<sup>o</sup></small>
              </div>
            </div>
            <div class="forecast">
              <div class="forecast-header">
                <div class="day">Friday</div>
              </div> 
              <div class="forecast-content">
                <div class="forecast-icon">
                  <!-- <img src="images/icons/icon-12.svg" alt="" width=48> -->
                </div>
                <div class="degree" id="temp4">23<sup>o</sup>C</div>
                <small>18<sup>o</sup></small>
              </div>
            </div>
            <div class="forecast">
              <div class="forecast-header">
                <div class="day">Saturday</div>
              </div> 
              <div class="forecast-content">
                <div class="forecast-icon">
                  <!-- <img src="images/icons/icon-13.svg" alt="" width=48> -->
                </div>
                <div class="degree" id="temp5">23<sup>o</sup>C</div>
                <small>18<sup>o</sup></small>
              </div>
            </div>
            <div class="forecast">
              <div class="forecast-header">
                <div class="day">Sunday</div>
              </div> 
              <div class="forecast-content">
                <div class="forecast-icon">
                  <!-- <img src="images/icons/icon-14.svg" alt="" width=48> -->
                </div>
                <div class="degree" id="temp6">23<sup>o</sup>C</div>
                <small>18<sup>o</sup></small>
              </div>
            </div>
          </div>
        </div>
        <div>
          <a href="#" class="btn btn-dark" id="manual">Upload Manually</a>
          <a href="#" class="btn btn-primary">Upload Automatically</a>
        </div>
        
        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Physical Parameter Different</div>
          <div class="card-body flex-panel">
            <button class="btn btn-warning" onclick="chartTempLaunch()"><i class="fas fa-chart-line"></i><br>Temperature</button>
            <button class="btn btn-danger" onclick="chartPressLaunch()"><i class="fas fa-chart-line"></i><br>Pressure</button>
            <button class="btn btn-primary" onclick="chartWindLaunch()"><i class="fas fa-chart-line"></i><br>Wind Flow</button>
            <div class="pannel">
              <div class="window">
                <div id="chart"></div>
              </div>
            </div>

          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

        <!--Weather History-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Records History</div>
          <div class="card-body flex-panel" id="check"> 
            
           
		
	
              <div class="table-box">
                <table id="weather_table" cellpadding="10">
                  <tr><th>ID</th><th>Record Date/Time</th><th>Brief</th><th>Description</th><th>Temperature (C)</th><th>Pressure</th><th>Wind Speed</th><th>Humidity</th></tr>
                  
                  
                </table>
            
              </div>
              
            

          </div>
          
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

        <!-- Weather API Settings -->
        <script src="js/openweatherAPI/script.js" ></script>
        <script src="js/openweatherAPI/weather_week.js" ></script>

        <!-- Firestore Settings -->
        <script>
          
          

          
          //Get Weather Details history
          getWeatherData();

          //Get Weather Details for charts
          getTemperature("Kalutara"); 
          genTempWeek();

          var arrx = [], arry1 = [], arry2 = [], arry3 = [], arry4 = [], arry5 = [];

          function chartTempLaunch(){
            var config = {responsive: true};

            var set1 = {
              x: arrx,
              y: arry1,
              mode: 'lines+markers',
              name: 'Temperature'
            };

            var set2 = {
              x: arrx,
              y: arry2,
              mode: 'lines+markers',
              name: 'Minimum Temperature'
            };

            var data = [ set1, set2 ];

            var layout = {
              title:'Difference between Max, Min and normal Temperature values'
            };

            Plotly.newPlot('chart', data, layout, config);
          }

          function chartPressLaunch(){
            var config = {responsive: true};

            var set1 = {
              x: arrx,
              y: arry4,
              mode: 'lines+markers',
              name: 'Temperature'
            };

            var data = [set1];

            var layout = {
              title:'Pressure change over Time'
            };

            Plotly.newPlot('chart', data, layout, config);
          }

          function chartWindLaunch(){
            var config = {responsive: true};

            var set1 = {
              x: arrx,
              y: arry5,
              mode: 'lines+markers',
              name: 'Temperature'
            };

            var data = [set1];

            var layout = {
              title:'Wind Flow change'
            };

            Plotly.newPlot('chart', data, layout, config);
          }

          function getRandom(){
              return Math.random();
          }

          // Page Reloader
          $('body').ready(function(){
            refresh();
          });

          function refresh(){
            setTimeout(function(){
              $('body').fadeOut('slow').fadeIn('slow');
              refresh();
            }, 43000000);
          }

            

        </script>
          
        <!-- Time settings -->
          <div class="card-footer small text-muted" >Updated yesterday at 11:59 PM</div>
        </div>
        
        
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
