<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];

    // İlgili dersin sınav kayıtlarını t_exams tablosundan sildim
    $sql_delete_exams = "DELETE FROM t_exams WHERE lesson_id = :lesson_id";
    $stmt_delete_exams = $db->prepare($sql_delete_exams);
    $stmt_delete_exams->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);

    if ($stmt_delete_exams->execute()) {
       
        $sql_delete_lesson = "DELETE FROM t_lessons WHERE id = :lesson_id";
        $stmt_delete_lesson = $db->prepare($sql_delete_lesson);
        $stmt_delete_lesson->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);

        if ($stmt_delete_lesson->execute()) {
            echo "Ders ve dersin sınav kayıtları başarıyla silindi.";
            header("Location: dersler.php");
        } else {
            echo "Ders silinirken bir hata oluştu.";
        }
    } else {
        echo "Dersin sınav kayıtları silinirken bir hata oluştu.";
    }
}
?>
