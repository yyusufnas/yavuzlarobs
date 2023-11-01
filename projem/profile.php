
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Kullanıcı Profili</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Kullanıcı Profili</h1>
                <?php
                session_start();
                $db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");

                if (isset($_GET['id'])) {
                    $userID = $_GET['id'];

                    
                    $sql = "SELECT name, surname, username, password FROM t_users WHERE id = :id";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':id', $userID, PDO::PARAM_INT);
                    $stmt->execute();
                    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

                    
                    if ($user_info) {
                        echo "<h4>Ad: " . $user_info['name'] . "</h4>";
                        echo "<h4>Soyad: " . $user_info['surname'] . "</h4>";
                        echo "<h4>Kullanıcı Adı: " . $user_info['username'] . "</h4>";
                        
                        
                    } 
                } 
                ?>
                <a class="btn btn-primary mt-2" href="sifredegistir.php?id=<?php echo $userID; ?>">Şifre Değiştir</a>
            </div>
        </div>
    </div>
</body>
</html>
