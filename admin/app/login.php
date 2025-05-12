<?php 
session_start();
include_once("../../system/connection.php");

if($_SESSION['username']){
    echo 'User Already Loggdin <br>Logout <br> Continue';
    session_destroy();
    session_unset();
}else{
    if(isset($_POST['loginBtn'])){
        $username = $_POST['login'];
        $password = $_POST['password'];
        $user_role = $_POST['role'];
        $user_email = $_POST['email'];
        
    }else{
        $_SESSION['error'] = 'Sorry Usernem & Password Matched Successfull.';
        header('Location: ../dashboard/index.php');
    }
}



?>