<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

// Sınıf adlarını çekmek için Sql sorgusu yaptm
$query_class_names = "SELECT DISTINCT class_name FROM t_classes";
$stmt_class_names = $db->prepare($query_class_names);
$stmt_class_names->execute();
$class_names = $stmt_class_names->fetchAll(PDO::FETCH_COLUMN);


$filter_class_name = isset($_POST['class_name']) ? $_POST['class_name'] : "";

// Sınavlar için SQL sorgusu yaptım
$query_exams = "SELECT t_exams.id, t_exams.exam_date, t_classes.class_name, t_users.name AS student_name, t_users.surname AS student_surname, t_lessons.lesson_name, t_exams.exam_score
               FROM t_exams
               LEFT JOIN t_classes ON t_exams.class_id = t_classes.id
               LEFT JOIN t_users ON t_exams.student_id = t_users.id
               LEFT JOIN t_lessons ON t_exams.lesson_id = t_lessons.id";

if (!empty($filter_class_name)) {
    $query_exams .= " WHERE t_classes.class_name = :class_name";
}

$stmt_exams = $db->prepare($query_exams);

if (!empty($filter_class_name)) {
    $stmt_exams->bindParam(':class_name', $filter_class_name);
}

$stmt_exams->execute();
$exams = $stmt_exams->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Sınavlar</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <h1>Sınavlar</h1>
            <form action="#" method="post">
                <div class="mb-3">
                    <label for="classFilter" class="form-label">Sınıf Adı Filtresi:</label>
                    <select class="form-select" id="classFilter" name="class_name">
                        <option value="">Tüm Sınıflar</option>
                        <?php foreach ($class_names as $class_name) : ?>
                            <option value="<?= $class_name ?>" <?= ($filter_class_name == $class_name) ? 'selected' : '' ?>>
                                <?= $class_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="filter">Filtrele</button>
            </form>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th scope="col">Sınav Tarihi</th>
                        <th scope="col">Sınıf Adı</th>
                        <th scope="col">Öğrenci Adı</th>
                        <th scope="col">Öğrenci Soyadı</th>
                        <th scope="col">Ders Adı</th>
                        <th scope="col">Ders Ortalaması</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exams as $exam) : ?>
                        <tr>
                            <td><?= $exam['exam_date'] ?></td>
                            <td><?= $exam['class_name'] ?></td>
                            <td><?= $exam['student_name'] ?></td>
                            <td><?= $exam['student_surname'] ?></td>
                            <td><?= $exam['lesson_name'] ?></td>
                            <td><?= $exam['exam_score'] ?></td>
                            <td>
                                <a href="sinavduzenle.php?id=<?= $exam['id'] ?>" class="btn btn-primary">Düzenle</a>
                                <a href="sinavsil.php?id=<?= $exam['id'] ?>" class="btn btn-danger">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
