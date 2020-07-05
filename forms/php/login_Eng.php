<?php

    $error = "";
    echo "step 01";
    if(isset($_POST['btn_sub'])){

        $uname = $_POST['username'];
        $pass = $_POST['password'];

        $con = new mysqli('localhost', 'root', '', 'weather_alpha');
        $que = "SELECT * FROM weather_alpha WHERE Username = '$uname' AND Password = '$pass'";
        echo "step 02  '$uname'  '$pass' ";
        if($con->query($que)){

            ?> <script type="text/javascript">
				alert("Registration Complete");
				window.location.href = "http://localhost:82/assignment-WEB/Phase%2002/Login/login.php";
            </script><?php
            
            $error = "Login Successful!";

        } else {

            ?> <script type="text/javascript">
				alert("Registration Faiiled!");
				window.location.href = "http://localhost:82/assignment-WEB/Phase%2002/Login/login.php";
            </script><?php

            $error = "Login Failed!";
        }
    }



?>