<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

// Sınıf listesini çekmek için SQL sorgum
$query_classes = "SELECT DISTINCT class_name FROM t_classes";
$stmt_classes = $db->prepare($query_classes);
$stmt_classes->execute();
$class_names = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

// Ders listesini çekmek için SQL sorgum
$query_lessons = "SELECT DISTINCT t_lessons.id, t_lessons.lesson_name, t_users.name AS sorumlu_adi
                  FROM t_lessons
                  LEFT JOIN t_users ON t_lessons.teacher_user_id = t_users.id";

$stmt_lessons = $db->prepare($query_lessons);
$stmt_lessons->execute();
$dersler = $stmt_lessons->fetchAll(PDO::FETCH_ASSOC);

$selected_class = isset($_POST['class_name']) ? $_POST['class_name'] : "";
$selected_teacher = isset($_POST['teacher_name']) ? $_POST['teacher_name'] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Ders Listesi</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="#" method="post">
                <div class="mb-3">
                    <h1>Ders Listesi</h1>
                    
                    <label for="teacherFilter" class="form-label mt-3">Sorumlu Öğretmen Seçin:</label>
                    <select class="form-select" id="teacherFilter" name="teacher_name">
                        <option value="">Tüm Öğretmenler</option>
                        <?php foreach ($dersler as $ders) : ?>
                            <option value="<?= $ders['sorumlu_adi'] ?>" <?= ($selected_teacher == $ders['sorumlu_adi']) ? 'selected' : '' ?>>
                                <?= $ders['sorumlu_adi'] ?>
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
                        <th scope="col">Ders Adı</th>
                        <th scope="col">Sorumlu Öğretmen</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dersler as $key => $ders) : ?>
                        <?php
                        if ($selected_teacher == "" || $selected_teacher == $ders['sorumlu_adi']) :
                        ?>
                            <tr>
                                <th scope="row"><?= $key + 1 ?></th>
                                <td><?= $ders['lesson_name'] ?></td>
                                <td><?= $ders['sorumlu_adi'] ?></td>
                                <td>
                                    <a href="dersduzenle.php?id=<?= $ders['id'] ?>&mode=edit" class="btn btn-primary">Dersi Düzenle</a>
                                    <a href="derssil.php?id=<?= $ders['id'] ?>&mode=edit" class="btn btn-primary">Dersi Sil</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
