<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $db->exec("DELETE FROM t_lessons WHERE teacher_user_id = $id");

    
    $db->exec("DELETE FROM t_classes WHERE class_teacher_id = $id");

   
    $db->exec("DELETE FROM t_users WHERE id = $id");

    header("Location: sorumlular.php"); 
} 
?>
