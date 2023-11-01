<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['exam_id']) && isset($_POST['new_exam_score'])) {
        $exam_id = $_POST['exam_id'];
        $new_exam_score = $_POST['new_exam_score'];

        // Sınav notunu güncellmek için SQL sorgusu yaptm
        $update_query = "UPDATE t_exams SET exam_score = :new_exam_score WHERE id = :exam_id";
        $stmt_update = $db->prepare($update_query);
        $stmt_update->bindParam(':new_exam_score', $new_exam_score);
        $stmt_update->bindParam(':exam_id', $exam_id);

        if ($stmt_update->execute()) {
            
            header("Location: sinavlar.php"); 
            exit();
        }
    }
}

if (isset($_GET['id'])) {
    $exam_id = $_GET['id'];

    
    $query = "SELECT * FROM t_exams WHERE id = :exam_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->execute();
    $exam = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7Rxnatzjc</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <h1>Sınav Düzenle</h1>
            <form action="sinavduzenle.php?id=<?= $exam_id ?>" method="POST">
                <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                <div class="mb-3">
                    <label for="exam_score" class="form-label">Yeni Sınav Notu:</label>
                    <input type="number" class="form-control" id="exam_score" name="new_exam_score" value="<?= $exam['exam_score'] ?>">
                </div>
                <button type="submit" class="btn btn-primary">Sınav Notunu Güncelle</button>
            </form>
        </div>
    </div>
</body>
</html>
