<?php require_once('config.php');

error_reporting(E_ALL ^ E_NOTICE);

$url=$_SERVER["HTTP_REFERER"];
$arr = parse_url($url);
$id = substr($arr['query'],strripos($arr['query'],"=")+1);
$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
$username=$_COOKIE['Username'];
if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}
//查找uid
$sql = "SELECT UID FROM traveluser WHERE UserName = '$username'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
$UID=$row['UID'];

$sql = "INSERT INTO travelimagefavor(UID,ImageID) VALUES ($UID,$id)";

echo "<script>window.history.go(-1);</script>";


