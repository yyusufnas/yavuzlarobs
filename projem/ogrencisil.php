<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

$student_id = $_GET['id']; 

// foreign key kısıtlamalarını  devre dışı bırakma
$db->exec("SET foreign_key_checks = 0");

// Öğrenciyi veritabanından silme sorgusu
$sql_delete_student = "DELETE FROM t_users WHERE id = :student_id";
$stmt_delete_student = $db->prepare($sql_delete_student);
$stmt_delete_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);

// Öğrenciyi sınıf öğrencileri tablosundan silme 
$sql_delete_class_student = "DELETE FROM t_classes_students WHERE student_id = :student_id";
$stmt_delete_class_student = $db->prepare($sql_delete_class_student);
$stmt_delete_class_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);

// Öğrencinin sınavlarını silme sorgusu
$sql_delete_exams = "DELETE FROM t_exams WHERE student_id = :student_id";
$stmt_delete_exams = $db->prepare($sql_delete_exams);
$stmt_delete_exams->bindParam(':student_id', $student_id, PDO::PARAM_INT);


$delete_student_success = false;

try {
    $db->beginTransaction();

    if ($stmt_delete_student->execute() && $stmt_delete_class_student->execute() && $stmt_delete_exams->execute()) {
        $db->commit();
        $delete_student_success = true;
    } else {
        $db->rollBack();
    }
} catch (Exception $e) {
    $db->rollBack();
    echo "Öğrenciyi silerken bir hata oluştu: " . $e->getMessage();
}


$db->exec("SET foreign_key_checks = 1");

if ($delete_student_success) {
    echo "Öğrenci başarıyla silindi";
    header("Location: ögrenciler.php");
} 
?>
