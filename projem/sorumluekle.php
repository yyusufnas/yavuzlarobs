<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");
$count = 1;

$adi = $soyadi = $kullaniciadi = $sifre = $role = "";
$editMode = isset($_GET['id']);

if (isset($_POST["ekle"])) {
    $adi = trim($_POST["adi"]);
    $soyadi = trim($_POST["soyadi"]);
    $kullaniciadi = trim($_POST["kullaniciadi"]);
    $sifre = trim($_POST["sifre"]);
    $role = "Teacher"; 

    if (empty($adi) || empty($soyadi) || empty($kullaniciadi) || empty($sifre)) {
        echo "Bilgilerde boşluk bırakılamaz";
    } else {
        if ($editMode) {
            $id = $_GET['id'];
            
            if (!empty($sifre)) {
                $hashedPassword = password_hash($sifre, PASSWORD_ARGON2ID);
                $db->exec("UPDATE t_users SET name = '$adi', surname = '$soyadi', username = '$kullaniciadi', password = '$hashedPassword' WHERE id = $id");
            } else {
                $db->exec("UPDATE t_users SET name = '$adi', surname = '$soyadi', username = '$kullaniciadi' WHERE id = $id");
            }
            echo "Öğretmen bilgileri güncellendi!";
        } else {
            $kontrol = $db->query("SELECT * FROM t_users WHERE username = '$kullaniciadi'")->fetchAll(PDO::FETCH_ASSOC);
            if ($kontrol != null) {
                echo "Bu kullanıcı adı kullanılmaktadır";
            } else {
               
                if (!empty($sifre)) {
                    $hashedPassword = password_hash($sifre, PASSWORD_ARGON2ID);
                    $db->exec("INSERT INTO t_users (name, surname, username, password, role) VALUES ('$adi', '$soyadi', '$kullaniciadi', '$hashedPassword', '$role')");
                } else {
                    $db->exec("INSERT INTO t_users (name, surname, username, password, role) VALUES ('$adi', '$soyadi', '$kullaniciadi', '', '$role')");
                }
                echo "Sorumlu başarıyla eklendi";            
            }
        }
    }
}

if ($editMode) {
    $id = $_GET['id'];
    $sorumlu = $db->query("SELECT * FROM t_users WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    $adi = $sorumlu['name'];
    $soyadi = $sorumlu['surname'];
    $kullaniciadi = $sorumlu['username'];
    
    $role = $sorumlu['role'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Öğretmen Ekle</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
            <form action="sorumluekle.php<?= $editMode ? "?id=$id" : "" ?>" method="post">
                <div class="mb-3">
                    <h1><?= $editMode ? "Öğretmen Düzenle" : "Öğretmen EKLE" ?></h1>
                    <label for="exampleInputEmail1" class="form-label">Adı</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="adi" value="<?= $adi ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Soyadı</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="soyadi" value="<?= $soyadi ?>">
                </div>
                <div class="mb-3">
                    <label for "exampleInputEmail1" class="form-label">Kullanıcı adı</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="kullaniciadi" value="<?= $kullaniciadi ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Şifre</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" name="sifre" value="<?= $sifre ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Rol</label>
                    <select class="form-select" aria-label="Default select example" name="role">
                        <option value="Teacher">Teacher</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="ekle"><?= $editMode ? "Düzenle" : "Ekle" ?></button>
            </form>
        </div>
    </div>
</body>
</html>
