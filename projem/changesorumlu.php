<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_POST['updatee'])) {
    $class_teacher_id = $_POST['class_teacher_id']; 
    $sorumluAdi = $_POST['sorumluadi']; 

    
    $sql_teacher_id = "SELECT id FROM t_users WHERE name = :teacher_name AND role = 'Teacher'";
    $stmt_teacher_id = $db->prepare($sql_teacher_id);
    $stmt_teacher_id->bindParam(':teacher_name', $sorumluAdi);
    $stmt_teacher_id->execute();
    $newTeacherId = $stmt_teacher_id->fetch(PDO::FETCH_ASSOC)['id'];

    
    $stmt = $db->prepare("UPDATE t_classes SET class_teacher_id = :newTeacherId WHERE class_teacher_id = :class_teacher_id");
    $stmt->bindParam(':newTeacherId', $newTeacherId);
    $stmt->bindParam(':class_teacher_id', $class_teacher_id);

    if ($stmt->execute()) {
        echo "Sorumlu başarıyla güncellendi!";
        header("Location:sinifekle.php");
    } else {
        print_r($stmt->errorInfo());
        echo "Sorumlu güncellenirken bir hata oluştu.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Şifre Değiştir</title>
</head>
<body>
<div class="container p-5">
        <div class="card p-5">
            <form action="changesorumlu.php" method="post">
                <div class="mb-3">
                <input type="hidden" name="class_teacher_id" value="<?= $_GET['class_teacher_id'] ?>"> 
                    <label for="exampleInputPassword1" class="form-label">Yeni Sorumlu adı</label>

                    <select class="form-select" aria-label="Default select example" name="sorumluadi">
                        <?php
                        $sql_sorumlu = "SELECT name FROM t_users WHERE role='Teacher'";
                        $result_sorumlu = $db->query($sql_sorumlu);

                        while ($row_sorumlu = $result_sorumlu->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row_sorumlu['name'] . '">' . $row_sorumlu['name'] . '</option>';
                        }
                        ?>
                    </select>
                   
                    <button type="submit" class="btn btn-primary" name="updatee">Güncelle</button>
            </form>
        </div>
    </div>
</body>
</html>
