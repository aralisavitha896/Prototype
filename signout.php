<?php
include("includes/config.php");
session_start();
log_activity("User signout");
unset($_SESSION['username']);
unset($_SESSION['user_type']);
header("location:index.php");

?>