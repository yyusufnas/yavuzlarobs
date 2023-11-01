<?php
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

if (isset($_POST["update"])) {
    $id = $_POST['id'];
    $password = $_POST['password'];

    $db->exec("UPDATE t_users SET password='$password' WHERE id=$id");

    header("Location: ogrenciekle.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Şifre Değiştir</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="changepasswd.php" method="post">
                <div class="mb-3">
                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                    <label class="form-label">Yeni Şifre</label>
                    <input type="text" class="form-control" id="input" name="password">
                    <button type="submit" class="btn btn-primary" name="update">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
