<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");


$lessons = array();

$sql_lessons = "SELECT l.lesson_name, u.name AS teacher_name, u.id AS teacher_id FROM t_lessons l
                JOIN t_users u ON l.teacher_user_id = u.id";
$result_lessons = $db->query($sql_lessons);

while ($row = $result_lessons->fetch(PDO::FETCH_ASSOC)) {
    $lessons[] = $row;
}

if (isset($_POST['add'])) {
    $dersadi = trim($_POST["dersadi"]);
    $snfsorumluadi = $_POST["snfsorumluadi"];
    
    
    $sql_teacher_id = "SELECT id FROM t_users WHERE name = :snfsorumluadi";
    $stmt_teacher_id = $db->prepare($sql_teacher_id);
    $stmt_teacher_id->bindParam(':snfsorumluadi', $snfsorumluadi, PDO::PARAM_STR);
    $stmt_teacher_id->execute();
    
    $teacher_id_result = $stmt_teacher_id->fetch(PDO::FETCH_ASSOC);
    
    if (empty($dersadi) || empty($snfsorumluadi)) {
        echo "Ders veya sorumlu adı boş bırakılamaz";
    } elseif (!$teacher_id_result) {
        echo "Öğretmen bulunamadı";
    } else {
        $teacher_id = $teacher_id_result['id'];
        $kontrol = $db->query("SELECT * FROM t_lessons WHERE lesson_name = '$dersadi'")->fetch(PDO::FETCH_ASSOC);

        if ($kontrol) {
            echo "Bu ders adı zaten eklenmiş";
        } else {
            $sql = "INSERT INTO t_lessons (lesson_name, teacher_user_id) VALUES (:dersadi, :teacher_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':dersadi', $dersadi, PDO::PARAM_STR);
            $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Ders başarıyla eklendi";
                header("Location: dersekle.php");
            } else {
                echo "Ders eklenirken bir hata oluştu";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

<div class="container p-5">

    <div class="card p-5">
        <form action="dersekle.php" method="post">
            <div class="mb-3">
                <h1>DERS EKLE</h1>
                <label for="exampleInputEmail1" class="form-label">Ders Adı</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="dersadi">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Sorumlu adı</label>
                <select class="form-select" aria-label="Default select example" name="snfsorumluadi">
                    <?php
                    $sql_sorumlu = "SELECT name FROM t_users WHERE role='Teacher'";
                    $result_sorumlu = $db->query($sql_sorumlu);

                    while ($row_sorumlu = $result_sorumlu->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $row_sorumlu['name'] . '">' . $row_sorumlu['name'] . '</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary" name="add">Ekle</button>
            </div>
        </form>

        
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Dersler</th>
                <th scope="col">Sorumlular</th>
                
            </tr>
            </thead>
            <tbody>
            <?php
            
            $row_number = 1;

            // Dizi içindeki her veriyi tabloya ekledim
            foreach ($lessons as $lesson) {
                echo '<tr>';
                echo '<th scope="row">' . $row_number . '</th>';
                echo '<td>' . $lesson['lesson_name'] . '</td>';
                echo '<td>' . $lesson['teacher_name'] . '</td>';

                
               

                echo '</tr>';

                $row_number++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
