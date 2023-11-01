<?php 
$db = new PDO("mysql:host=db;dbname=yavuzlar","user","user" );

$user=$_GET['id'];
$db->exec("DELETE FROM t_users WHERE id=$user");
header("Location:ogrenciekle.php");



?>