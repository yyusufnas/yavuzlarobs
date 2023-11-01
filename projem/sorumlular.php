<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

$query = "SELECT t_users.name AS sorumlu_adi, t_users.surname AS sorumlu_soyadi, IFNULL(t_classes.class_name, 'Sınıf Yok') AS sorumlu_sinifi, t_users.id AS sorumlu_id
          FROM t_users
          LEFT JOIN t_classes ON t_users.id = t_classes.class_teacher_id
          WHERE t_users.role = 'Teacher'";

$selected_class = "";
$selected_lesson = "";

if (isset($_POST['filter'])) {
    $selected_class = $_POST['class_name'];
    $selected_lesson = $_POST['lesson_name'];
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($selected_class)) {
        $query .= " AND t_classes.class_name = :class_name";
    }
    if (!empty($selected_lesson)) {
        $query .= " AND t_lessons.lesson_name = :lesson_name";
    }
}

$stmt = $db->prepare($query);

if (!empty($selected_class)) {
    $stmt->bindParam(':class_name', $selected_class);
}
if (!empty($selected_lesson)) {
    $stmt->bindParam(':lesson_name', $selected_lesson);
}

$stmt->execute();
$sorumlular = $stmt->fetchAll(PDO::FETCH_ASSOC);


$query_classes = "SELECT class_name FROM t_classes";
$stmt_classes = $db->prepare($query_classes);
$stmt_classes->execute();
$class_names = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

$query_lessons = "SELECT lesson_name FROM t_lessons";
$stmt_lessons = $db->prepare($query_lessons);
$stmt_lessons->execute();
$lesson_names = $stmt_lessons->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Sorumlu Listesi</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <h1>Sorumlu Listesi</h1>
            <form action="#" method="post">
                <div class="mb-3">
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
                <div class="mb-3">
                    <label for="lessonFilter" class="form-label">Ders Seçin:</label>
                    <select class="form-select" id="lessonFilter" name="lesson_name">
                        <option value="">Tüm Dersler</option>
                        <?php foreach ($lesson_names as $lesson) : ?>
                            <option value="<?= $lesson['lesson_name'] ?>" <?= ($selected_lesson == $lesson['lesson_name']) ? 'selected' : '' ?>>
                                <?= $lesson['lesson_name'] ?>
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
                        <th scope="col">Adı</th>
                        <th scope="col">Soyadı</th>
                        <th scope="col">Sınıfı</th>
                        <th scope="col">İslemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sorumlular as $key => $sorumlu) : ?>
                        <tr>
                            <th scope="row"><?= $key + 1 ?></th>
                            <td><?= $sorumlu['sorumlu_adi'] ?></td>
                            <td><?= $sorumlu['sorumlu_soyadi'] ?></td>
                            <td><?= $sorumlu['sorumlu_sinifi'] ?></td>
                            <td>
                                <a href="sorumluekle.php?id=<?= $sorumlu['sorumlu_id'] ?>&mode=edit" class="btn btn-primary">Sorumlu Düzenle</a>
                                <a href="sorumlusil.php?id=<?= $sorumlu['sorumlu_id'] ?>" class="btn btn-danger" ">Sorumlu Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>