<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

$sinifAdi = ""; 
$sorumluAdi = ""; 

// Sınıf düzenleme modundamı kontrol ettim
if (isset($_GET['id']) && isset($_GET['mode']) && $_GET['mode'] === 'edit') {
    $classId = $_GET['id'];

    
    $sql_get_class_info = "SELECT class_name, class_teacher_id FROM t_classes WHERE id = :class_id";
    $stmt_get_class_info = $db->prepare($sql_get_class_info);
    $stmt_get_class_info->bindParam(':class_id', $classId, PDO::PARAM_INT);
    $stmt_get_class_info->execute();
    $classInfo = $stmt_get_class_info->fetch(PDO::FETCH_ASSOC);

    if ($classInfo) {
       
        $sinifAdi = $classInfo['class_name'];

        
        $teacherId = $classInfo['class_teacher_id'];
        $sql_get_teacher_name = "SELECT name FROM t_users WHERE id = :teacher_id";
        $stmt_get_teacher_name = $db->prepare($sql_get_teacher_name);
        $stmt_get_teacher_name->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
        $stmt_get_teacher_name->execute();
        $teacherName = $stmt_get_teacher_name->fetchColumn();

        $sorumluAdi = $teacherName;
    }
}

if (isset($_POST['eklee'])) {
    $sinifAdi = trim($_POST["sinifAdi"]);
    $sorumluAdi = $_POST["sorumluAdi"];

    
    $sql_check_class = "SELECT COUNT(*) AS class_count FROM t_classes WHERE class_name = :class_name";
    $stmt_check_class = $db->prepare($sql_check_class);
    $stmt_check_class->bindParam(':class_name', $sinifAdi);
    $stmt_check_class->execute();
    $class_count = $stmt_check_class->fetch(PDO::FETCH_ASSOC)['class_count'];

    if ($class_count > 0) {
        echo "Bu sınıfa zaten bir sorumlu atanmış.";
    } else {
        // Sınıf daha önce eklenmemişse, yeni sınıf ve sorumlu ekledim burada
        $sql_insert_sinif = "INSERT INTO t_classes (class_name, class_teacher_id) VALUES (:class_name, :class_teacher_id)";
        $stmt_sinif = $db->prepare($sql_insert_sinif);

        $sql_teacher_id = "SELECT id FROM t_users WHERE name = :teacher_name AND role = 'Teacher'";
        $stmt_teacher_id = $db->prepare($sql_teacher_id);
        $stmt_teacher_id->bindParam(':teacher_name', $sorumluAdi);
        $stmt_teacher_id->execute();

        $teacherId = $stmt_teacher_id->fetch(PDO::FETCH_ASSOC)['id'];

        $stmt_sinif->bindParam(':class_name', $sinifAdi);
        $stmt_sinif->bindParam(':class_teacher_id', $teacherId);

        if ($stmt_sinif->execute()) {
            echo "Sınıf başarıyla eklendi!";
        } else {
            print_r($stmt_sinif->errorInfo());
            echo "Sınıf eklenirken bir hata oluştu.";
        }
    }
}

$sql_list_classes = "SELECT t_classes.id, t_classes.class_name, t_users.name AS teacher_name, t_classes.class_teacher_id
FROM t_classes
INNER JOIN t_users ON t_classes.class_teacher_id = t_users.id";
$result_classes = $db->query($sql_list_classes);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Sınıf Ekle / Düzenle</title>
</head>

<body>

    <div class="container p-5">

        <div class="card p-5">
            <form action="sinifekle.php" method="post">
                <div class="mb-3">
                <h1>SINIF EKLE</h1>
                    <label for="exampleInputPassword1" class="form-label">Sınıfı</label>
                    <select class="form-select" aria-label="Default select example" name="sinifAdi">

                        <option value="Yavuzlar">Yavuzlar</option>
                        <option value="Zayotem">Zayotem</option>
                        <option value="Cuberium">Cuberium</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Sorumlu adı</label>
                    <select class="form-select" aria-label="Default select example" name="sorumluAdi">
                        <?php
                        $sql_sorumlu = "SELECT name FROM t_users WHERE role='Teacher'";
                        $result_sorumlu = $db->query($sql_sorumlu);

                        while ($row_sorumlu = $result_sorumlu->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($row_sorumlu['name'] === $sorumluAdi) ? "selected" : "";
                            echo '<option value="' . $row_sorumlu['name'] . '" ' . $selected . '>' . $row_sorumlu['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="eklee">EKLE / DÜZENLE</button>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Sınıf Adı</th>
                    <th scope="col">Sorumlu Adı</th>
                    <th scope="col">Sorumlu Değiştir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while ($row = $result_classes->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<th scope="row">' . $count . '</th>';
                    echo '<td>' . $row['class_name'] . '</td>';
                    echo '<td>' . $row['teacher_name'] . '</td>';
                    echo '<td><a href="changesorumlu.php?class_teacher_id=' . $row['class_teacher_id'] . '&teacher_name=' . $row['teacher_name'] . '">Sorumlu Değiştir</a></td>';
                    echo '</tr>';
                    $count++;
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
