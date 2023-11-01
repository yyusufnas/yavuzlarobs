<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");


$query_classes = "SELECT class_name FROM t_classes";
$stmt_classes = $db->prepare($query_classes);
$stmt_classes->execute();
$class_names = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);


$selected_class = "";

if (isset($_POST['filter'])) {
    $selected_class = $_POST['class_name'];
}


$query = "SELECT t_users.id, t_users.name, t_users.surname, t_classes.class_name, 
                 COUNT(t_exams.id) AS exam_count,
                 SUM(t_exams.exam_score) AS total_score
          FROM t_users
          JOIN t_classes_students ON t_users.id = t_classes_students.student_id
          JOIN t_classes ON t_classes_students.class_id = t_classes.id
          LEFT JOIN t_exams ON t_users.id = t_exams.student_id
          WHERE t_users.role = 'Student'";


if (!empty($selected_class)) {
    $query .= " AND t_classes.class_name = :class_name";// snf filtreleme yaptım
}

$query .= " GROUP BY t_users.id, t_users.name, t_users.surname, t_classes.class_name";


$stmt = $db->prepare($query);

if (!empty($selected_class)) {
    $stmt->bindParam(':class_name', $selected_class);
}

$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Öğrenci Listesi</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="#" method="post">
                <div class="mb-3">
                    <h1>Öğrenci Listesi</h1>
                    <label for="classFilter" class="form-label">Sınıf Seçin:</label>
                    <select class="form-select" id="classFilter" name="class_name">
                        <option value="">Tüm Sınıflar</option>
                        <?php foreach ($class_names as $class) : ?>
                            <option value="<?= $class['class_name'] ?>" <?= ($selected_class == $class['class_name']) ? 'selected' : '' ?>>
                                <?= $class['class_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="filter">Filtrele</button>
            </form>

            <table class="table mt-4">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Öğrenci adı</th>
                        <th scope="col">Soyadı</th>
                        <th scope="col">Sınıf</th>
                        <th scope="col">Sınav Sayısı</th>
                        <th scope="col">Genel Başarı Ortalaması</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $key => $student) : ?>
                        <tr>
                            <th scope="row"><?= $key + 1 ?></th>
                            <td><?= $student['name'] ?></td>
                            <td><?= $student['surname'] ?></td>
                            <td><?= $student['class_name'] ?></td>
                            <td><?= $student['exam_count'] ?></td>
                            <td><?= ($student['exam_count'] > 0) ? ($student['total_score'] / $student['exam_count']) : 0 ?></td>
                            
                            <td>
                            <a href="sinavgecmisi.php?id=<?= $student['id'] ?>" class="btn btn-primary">Ayrıntılı Görüntüle</a>
                            <a href="ogrenciekle.php?id=<?= $student['id'] ?>&mode=edit" class="btn btn-primary">Öğrenci Düzenle</a>

                            <a href="ogrencisil.php?id=<?= $student['id'] ?>" class="btn btn-danger">Öğrenciyi Sil</a>
                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
