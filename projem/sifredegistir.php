<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    $sql = "SELECT password FROM t_users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_info) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'];

            // Şifreyi Argon2 ile şifreledim
            $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2I);

            $updateSql = "UPDATE t_users SET password = :new_password WHERE id = :id";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->bindParam(':new_password', $hashedPassword);
            $updateStmt->bindParam(':id', $userID, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                echo "Şifre başarıyla değiştirildi.";
                header("Location: home.php");
            } else {
                echo "Şifre değiştirme sırasında bir hata oluştu.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Şifre Değiştirme</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Şifre Değiştirme</h1>
                <form method="POST">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Yeni Şifre</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Şifre Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
