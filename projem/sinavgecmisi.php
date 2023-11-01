<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");


$student_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($student_id) {
   
    $query_student = "SELECT t_users.name, t_users.surname, t_classes.class_name
                     FROM t_users
                     JOIN t_classes_students ON t_users.id = t_classes_students.student_id
                     JOIN t_classes ON t_classes_students.class_id = t_classes.id
                     WHERE t_users.id = :student_id";

    $stmt_student = $db->prepare($query_student);
    $stmt_student->bindParam(':student_id', $student_id);
    $stmt_student->execute();
    $student_info = $stmt_student->fetch(PDO::FETCH_ASSOC);

    // Öğrencinin sınav geçmişini sorguladım
    $query_exams = "SELECT t_lessons.lesson_name, t_exams.exam_date, t_exams.exam_score
                   FROM t_exams
                   JOIN t_lessons ON t_exams.lesson_id = t_lessons.id
                   WHERE t_exams.student_id = :student_id";

    $stmt_exams = $db->prepare($query_exams);
    $stmt_exams->bindParam(':student_id', $student_id);
    $stmt_exams->execute();
    $exam_history = $stmt_exams->fetchAll(PDO::FETCH_ASSOC);

    // Ders ortalamalarını hesapla
    $lesson_averages = array();
    foreach ($exam_history as $exam) {
        $lesson_name = $exam['lesson_name'];
        $exam_score = $exam['exam_score'];
        if (!isset($lesson_averages[$lesson_name])) {
            $lesson_averages[$lesson_name] = array('total' => 0, 'count' => 0);
        }
        $lesson_averages[$lesson_name]['total'] += $exam_score;
        $lesson_averages[$lesson_name]['count']++;
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Öğrenci Sınav Geçmişi</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <h1>Öğrenci Sınav Geçmişi</h1>
            <?php if ($student_info) : ?>
                <h3><?= $student_info['name'] . ' ' . $student_info['surname'] ?></h3>
                <p>Sınıf: <?= $student_info['class_name'] ?></p>
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th scope="col">Ders Adı</th>
                            <th scope="col">Sınav Tarihi</th>
                            <th scope="col">Sınav Skoru</th>
                            <th scope="col">Ders Ortalaması</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exam_history as $exam) : ?>
                            <tr>
                                <td><?= $exam['lesson_name'] ?></td>
                                <td><?= $exam['exam_date'] ?></td>
                                <td><?= $exam['exam_score'] ?></td>
                                <td>
                                    <?php
                                    $lesson_name = $exam['lesson_name'];
                                    $lesson_average = ($lesson_averages[$lesson_name]['count'] > 0) ? ($lesson_averages[$lesson_name]['total'] / $lesson_averages[$lesson_name]['count']) : 0;
                                    echo $lesson_average;
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Geçerli bir öğrenci seçilmedi.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
