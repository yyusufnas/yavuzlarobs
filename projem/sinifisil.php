<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_GET['id'])) {
    $classId = $_GET['id'];

    
    $sql_delete_exams = "DELETE FROM t_exams WHERE class_id = :class_id";
    $stmt_delete_exams = $db->prepare($sql_delete_exams);
    $stmt_delete_exams->bindParam(':class_id', $classId, PDO::PARAM_INT);

    
    $sql_delete_students = "DELETE FROM t_classes_students WHERE class_id = :class_id";
    $stmt_delete_students = $db->prepare($sql_delete_students);
    $stmt_delete_students->bindParam(':class_id', $classId, PDO::PARAM_INT);
    
    
    $sql_delete_class = "DELETE FROM t_classes WHERE id = :class_id";
    $stmt_delete_class = $db->prepare($sql_delete_class);
    $stmt_delete_class->bindParam(':class_id', $classId, PDO::PARAM_INT);

    if ($stmt_delete_exams->execute() && $stmt_delete_students->execute() && $stmt_delete_class->execute()) {
        echo "Sınıf başarıyla silindi.";
    } 
} 

header("Location: siniflar.php");
?>
