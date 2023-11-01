<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

// Sınıf listesini çekmek için SQL sorgusu
$query_classes = "SELECT DISTINCT t_classes.id, t_classes.class_name, t_users.name AS sorumlu_adi,
                            (SELECT COUNT(*) FROM t_classes_students WHERE t_classes_students.class_id = t_classes.id) AS ogrenci_sayisi,
                            AVG(t_exams.exam_score) AS sinif_basari_ortalamasi
                  FROM t_classes
                  LEFT JOIN t_users ON t_classes.class_teacher_id = t_users.id
                  LEFT JOIN t_exams ON t_classes.id = t_exams.class_id
                  GROUP BY t_classes.id";

$stmt_classes = $db->prepare($query_classes);
$stmt_classes->execute();
$siniflar = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

$selected_teacher = isset($_POST['teacher_name']) ? $_POST['teacher_name'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Sınıf Listesi</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="#" method="post">
                <div class="mb-3">
                    <h1>Sınıf Listesi</h1>
                    
                    <label for="teacherFilter" class="form-label mt-3">Sorumlu Öğretmen Seçin:</label>
                    <select class="form-select" id="teacherFilter" name="teacher_name">
                        <option value="">Tüm Öğretmenler</option>
                        <?php foreach ($siniflar as $sinif) : ?>
                            <option value="<?= $sinif['sorumlu_adi'] ?>" <?= ($selected_teacher == $sinif['sorumlu_adi']) ? 'selected' : '' ?>>
                                <?= $sinif['sorumlu_adi'] ?>
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
                        <th scope="col">Sınıf Adı</th>
                        <th scope="col">Sorumlu Öğretmen</th>
                        <th scope="col">Öğrenci Sayısı</th>
                        <th scope="col">Sınıf Başarı Ortalaması</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($siniflar as $key => $sinif) : ?>
                        <?php
                        // Sorumlu adına göre filtreleme yap
                        if ($selected_teacher == "" || $selected_teacher == $sinif['sorumlu_adi']) :
                        ?>
                            <tr>
                                <th scope="row"><?= $key + 1 ?></th>
                                <td><?= $sinif['class_name'] ?></td>
                                <td><?= $sinif['sorumlu_adi'] ?></td>
                                <td><?= $sinif['ogrenci_sayisi'] ?></td>
                                <td><?= ($sinif['sinif_basari_ortalamasi'] != null) ? number_format($sinif['sinif_basari_ortalamasi'], 2) : 0 ?></td>
                                <td>
                                    <a href="sinifekle.php?id=<?= $sinif['id'] ?>&mode=edit" class="btn btn-primary">Sınıfı Düzenle</a>
                                    <a href="sinifisil.php?id=<?= $sinif['id'] ?>" class="btn btn-danger">Sınıfı Sil</a>
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
