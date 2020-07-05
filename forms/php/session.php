<?php

    //---------------------------[CAUTION]----------------------------------//
    //Stil in Prototype mode and data derived using GET Method
    //Security level is unstable

    //IDETIFIED SOLUTIONS: Turn to POST Method Curl PHP
    //----------------------------------------------------------------------// 

    session_start();

    $error = "";
    echo "session step";

    // if(!isset($_SESSION['username'])){
    //     header("location: ../login.html");
    //     exit();
    // }

    $user = $_GET['username'];
    $role = $_GET['role'];

    //Session variables
    $_SESSION['Username'] = $user;
    $_SESSION['Role'] = $role;

    echo "Username is ".$user;

    header("Location:../../dashboard.php");




?>