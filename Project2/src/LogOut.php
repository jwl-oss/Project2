<?php
require_once('config.php');
session_start();
function loginOUt(){
    unset($_SESSION['UserName']);
}
loginOUt();;
echo "<script>window.location.href='Home.php'</script>";
?>

