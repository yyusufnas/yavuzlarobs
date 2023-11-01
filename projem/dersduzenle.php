<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lesson_name']) && isset($_POST['lesson_id'])) {
        $lesson_name = $_POST['lesson_name'];
        $lesson_id = $_POST['lesson_id'];

        
        $update_query = "UPDATE t_lessons SET lesson_name = :lesson_name WHERE id = :lesson_id";
        $stmt_update = $db->prepare($update_query);
        $stmt_update->bindParam(':lesson_name', $lesson_name);
        $stmt_update->bindParam(':lesson_id', $lesson_id);

        if ($stmt_update->execute()) {
            
            header("Location: dersler.php"); 
            exit();
        } else {
            echo "Ders güncelleme hatası.";
        }
    }

    if (isset($_POST['new_teacher_name']) && isset($_POST['lesson_id'])) {
        $new_teacher_name = $_POST['new_teacher_name'];
        $lesson_id = $_POST['lesson_id'];

        
        $get_teacher_id_query = "SELECT id FROM t_users WHERE name = :new_teacher_name AND role = 'teacher'";
        $stmt_get_teacher_id = $db->prepare($get_teacher_id_query);
        $stmt_get_teacher_id->bindParam(':new_teacher_name', $new_teacher_name);
        $stmt_get_teacher_id->execute();
        $teacher_id = $stmt_get_teacher_id->fetch(PDO::FETCH_COLUMN);

        if ($teacher_id) {
            
            $update_teacher_query = "UPDATE t_lessons SET teacher_user_id = :teacher_id WHERE id = :lesson_id";
            $stmt_update_teacher = $db->prepare($update_teacher_query);
            $stmt_update_teacher->bindParam(':teacher_id', $teacher_id);
            $stmt_update_teacher->bindParam(':lesson_id', $lesson_id);

            if ($stmt_update_teacher->execute()) {
                
                header("Location: dersler.php"); 
                exit();
            } else {
                echo "Sorumlu öğretmen güncelleme hatası.";
            }
        } else {
            echo "Yeni öğretmen bulunamadı.";
        }
    }
}

if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];

    
    $query = "SELECT * FROM t_lessons WHERE id = :lesson_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':lesson_id', $lesson_id);
    $stmt->execute();
    $lesson = $stmt->fetch(PDO::FETCH_ASSOC);

    
    $teacher_query = "SELECT name FROM t_users WHERE role = 'teacher'";
    $stmt_teacher = $db->prepare($teacher_query);
    $stmt_teacher->execute();
    $teachers = $stmt_teacher->fetchAll(PDO::FETCH_COLUMN);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Ders Düzenle</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <h1>Ders Düzenle</h1>
            <form action="dersduzenle.php" method="POST">
                <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                <div class="mb-3">
                    <label for="lesson_name" class="form-label">Ders Adı:</label>
                    <input type="text" class="form-control" id="lesson_name" name="lesson_name" value="<?= $lesson['lesson_name'] ?>">
                </div>
                <div class="mb-3">
                    <label for="new_teacher_name" class="form-label">Yeni Sorumlu Öğretmen Adı:</label>
                    <input type="text" class="form-control" id="new_teacher_name" name="new_teacher_name">
                </div>
                <button type="submit" class="btn btn-primary">Dersi Güncelle</button>
            </form>
        </div>
    </div>
</body>
</html>
