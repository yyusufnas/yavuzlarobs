<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

$adi = "";
$soyadi = "";
$kullaniciadi = "";
$sifre = "";
$selected_class = "";

if (isset($_GET['mode']) && $_GET['mode'] === 'edit') {
    if (isset($_GET['id'])) {
        $student_id = $_GET['id'];

        $query = "SELECT name, surname, username FROM t_users WHERE id = :student_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $student_info = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student_info) {
            $adi = $student_info['name'];
            $soyadi = $student_info['surname'];
            $kullaniciadi = $student_info['username'];
            
        }
    }

    if (isset($_POST["duzenle"])) { // Düzenle butonuna basıldığında
        // Öğrenci bilgilerini güncelledikq
        $adi = trim($_POST["adi"]);
        $soyadi = trim($_POST["soyadi"]);
        $kullaniciadi = trim($_POST["kullaniciadi"]);
        $sifre = trim($_POST["sifre"]);
        
        
        if (!empty($sifre)) {
            $hash_sifre = password_hash($sifre, PASSWORD_ARGON2ID);
        }
        
        $sql_update_student = "UPDATE t_users SET name = :adi, surname = :soyadi, username = :kullaniciadi";
        
        
        if (!empty($sifre)) {
            $sql_update_student .= ", password = :sifre";
        }
        
        $sql_update_student .= " WHERE id = :student_id";
        
        $stmt_update_student = $db->prepare($sql_update_student);
        $stmt_update_student->bindParam(':adi', $adi, PDO::PARAM_STR);
        $stmt_update_student->bindParam(':soyadi', $soyadi, PDO::PARAM_STR);
        $stmt_update_student->bindParam(':kullaniciadi', $kullaniciadi, PDO::PARAM_STR);
        
        
        if (!empty($sifre)) {
            $stmt_update_student->bindParam(':sifre', $hash_sifre, PDO::PARAM_STR);
        }
        
        $stmt_update_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        
        if ($stmt_update_student->execute()) {
            echo "Öğrenci başarıyla güncellendi";
        } else {
            echo "Öğrenci güncellenirken bir hata oluştu";
        }
    }
}

if (isset($_POST["ekle"])) {
    $adi = trim($_POST["adi"]);
    $soyadi = trim($_POST["soyadi"]);
    $role = "Student";
    $kullaniciadi = trim($_POST["kullaniciadi"]);
    $sifre = trim($_POST["sifre"]);
    $selected_class = $_POST["class"];

    
    if (!empty($sifre)) {
        $hash_sifre = password_hash($sifre, PASSWORD_ARGON2ID);
    }

    // Eğer düzenleme modundaysa  öğrenciyi güncelledm
    if (isset($_GET['mode']) && $_GET['mode'] === 'edit') {
        if (isset($_GET['id'])) {
            $student_id = $_GET['id'];
            
            $sql_update_student = "UPDATE t_users SET name = :adi, surname = :soyadi, username = :kullaniciadi";
            
            
            if (!empty($sifre)) {
                $sql_update_student .= ", password = :sifre";
            }
            
            $sql_update_student .= " WHERE id = :student_id";
            
            $stmt_update_student = $db->prepare($sql_update_student);
            $stmt_update_student->bindParam(':adi', $adi, PDO::PARAM_STR);
            $stmt_update_student->bindParam(':soyadi', $soyadi, PDO::PARAM_STR);
            $stmt_update_student->bindParam(':kullaniciadi', $kullaniciadi, PDO::PARAM_STR);
            
            
            if (!empty($sifre)) {
                $stmt_update_student->bindParam(':sifre', $hash_sifre, PDO::PARAM_STR);
            }
            
            $stmt_update_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            
            if ($stmt_update_student->execute()) {
                echo "Öğrenci başarıyla güncellendi";
            } 
        }
    } else {
        // Eğer ekleme modundaysa, yeni bir öğrenci ekledim
        $sql_check_username = "SELECT COUNT(*) FROM t_users WHERE username = :kullaniciadi";
        $stmt_check_username = $db->prepare($sql_check_username);
        $stmt_check_username->bindParam(':kullaniciadi', $kullaniciadi, PDO::PARAM_STR);
        $stmt_check_username->execute();
        $username_count = $stmt_check_username->fetchColumn();

        if ($username_count > 0) {
            echo "Bu kullanıcı adı zaten kullanılıyor!";
        } else {
            $sql_class_id = "SELECT id FROM t_classes WHERE class_name = :class_name";
            $stmt_class_id = $db->prepare($sql_class_id);
            $stmt_class_id->bindParam(':class_name', $selected_class, PDO::PARAM_STR);
            $stmt_class_id->execute();
            $class_id_result = $stmt_class_id->fetch(PDO::FETCH_ASSOC);

            if (!$class_id_result) {
                echo "Sınıf bulunamadı";
            } else {
                $class_id = $class_id_result['id'];

                $sql_insert_student = "INSERT INTO t_users (name, surname, username, password, role) 
                               VALUES (:adi, :soyadi, :kullaniciadi, :sifre, :role)";
                $stmt_insert_student = $db->prepare($sql_insert_student);
                $stmt_insert_student->bindParam(':adi', $adi, PDO::PARAM_STR);
                $stmt_insert_student->bindParam(':soyadi', $soyadi, PDO::PARAM_STR);
                $stmt_insert_student->bindParam(':kullaniciadi', $kullaniciadi, PDO::PARAM_STR);
                $stmt_insert_student->bindParam(':sifre', $hash_sifre, PDO::PARAM_STR); // Hash'lenmiş şifreyi kullanın
                $stmt_insert_student->bindParam(':role', $role, PDO::PARAM_STR);
                
                if ($stmt_insert_student->execute()) {
                    echo "Öğrenci başarıyla eklendi";

                    $student_id = $db->lastInsertId();

                    $sql_insert_class_student = "INSERT INTO t_classes_students (class_id, student_id)
                                       VALUES (:class_id, :student_id)";
                    $stmt_insert_class_student = $db->prepare($sql_insert_class_student);
                    $stmt_insert_class_student->bindParam(':class_id', $class_id, PDO::PARAM_INT);
                    $stmt_insert_class_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                    $stmt_insert_class_student->execute();
                } else {
                    echo "Öğrenci eklenirken bir hata oluştu";
                }
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
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Öğrenci Ekle</title>
</head>
<body>

<div class="container p-5">
    <div class="card p-5">
        <form action="ogrenciekle.php<?php echo isset($_GET['mode']) && $_GET['mode'] === 'edit' ? '?mode=edit&id=' . $_GET['id'] : ''; ?>" method="post">
            <h1><?php echo isset($_GET['mode']) && $_GET['mode'] === 'edit' ? 'ÖĞRENCİ DÜZENLE' : 'ÖĞRENCİ EKLE'; ?></h1>
            <div class="mb-3">
                <label for="adi" class="form-label">Adı</label>
                <input type="text" class="form-control" id="adi" name="adi" value="<?php echo $adi; ?>">
            </div>
            <div class="mb-3">
                <label for="soyadi" class="form-label">Soyadı</label>
                <input type="text" class="form-control" id="soyadi" name="soyadi" value="<?php echo $soyadi; ?>">
            </div>
            <div class="mb-3">
                <label for="kullaniciadi" class="form-label">Kullanıcı adı</label>
                <input type="text" class="form-control" id="kullaniciadi" name="kullaniciadi" value="<?php echo $kullaniciadi; ?>">
            </div>
            <div class="mb-3">
                <label for="sifre" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="sifre" name="sifre" value="<?php echo $sifre; ?>">
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Sınıfı</label>
                <select class="form-select" aria-label="Default select example" id="class" name="class">
                    <?php
                    $class_query = $db->query("SELECT class_name FROM t_classes")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($class_query as $class_option) {
                        $selected = $class_option['class_name'] === $selected_class ? 'selected' : '';
                        echo '<option value="' . $class_option['class_name'] . '" ' . $selected . '>' . $class_option['class_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="<?php echo isset($_GET['mode']) && $_GET['mode'] === 'edit' ? 'duzenle' : 'ekle'; ?>">
                <?php echo isset($_GET['mode']) && $_GET['mode'] === 'edit' ? 'Düzenle' : 'Ekle'; ?>
            </button>
        </form>
    </div>
</div>

</body>
</html>
