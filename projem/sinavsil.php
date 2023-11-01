<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_GET['id'])) {
    $exam_id = $_GET['id'];

    // Sınav silme işlemi
    $sql_delete_exam = "DELETE FROM t_exams WHERE id = :exam_id";
    $stmt_delete_exam = $db->prepare($sql_delete_exam);
    $stmt_delete_exam->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);

    if ($stmt_delete_exam->execute()) {
        echo "Sınav başarıyla silindi.";
        header("Location:sinavlar.php");
    } 
}



?>