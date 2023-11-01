<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar","user","user" );
//student sayısı icin sorgu
$sql = "SELECT COUNT(*) as veri_sayisi FROM t_users WHERE role='Student'";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$veriSayisi = $result['veri_sayisi'];

//teacher sayısı icin sorgu
$sql2 = "SELECT COUNT(*) as veri_sayisi2 FROM t_users WHERE role='Teacher'";
$stmt = $db->prepare($sql2);
$stmt->execute();
$result2 = $stmt->fetch(PDO::FETCH_ASSOC);
$veriSayisi2 = $result2['veri_sayisi2'];

//sınıf sayısı icin sorgu
$sql3 = "SELECT COUNT(*) as veri_sayisi3 FROM t_classes WHERE class_name IS NOT NULL AND class_name <> ''";
$stmt = $db->prepare($sql3);
$stmt->execute();
$result3 = $stmt->fetch(PDO::FETCH_ASSOC);
$veriSayisi3 = $result3['veri_sayisi3'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <header>
      <h1>SİBER VATAN YETKİNLİK MERKEZİ</h1>  
       </header>

       <ul>
       <li class="name"><a href="profile.php?id=<?php echo $_SESSION['id']; ?>">Hoşgeldin <?php echo $_SESSION['name']; ?></a></li>

       <li ><a href="cikisyap.php">Çıkış yap</a></li>
      </ul><?php
if ($_SESSION['role'] == "admin") {
    echo '
    <div class="sidenav" id="sidebarnav">
    <a href="#" class="closeBtn" onclick="closeNav()">&times;</a>

    <a href="ogrenciekle.php">Öğrenci Ekle</a>
    <a href="sinifekle.php">Sınıf ekle</a> 
    <a href="sorumluekle.php">Sorumlu ekle</a> 
    <a href="dersekle.php">Ders ekle</a> 
    <a href="sinavekle.php">Sınav ekle</a> 
    <a href="ögrenciler.php">Öğrenciler</a> 
    <a href="sorumlular.php">Sorumlular</a> 
    <a href="dersler.php">Dersler</a> 
    <a href="siniflar.php">Sınıflar</a> 
    <a href="sinavlar.php">Sınavlar</a> 
    </div> 
    ';
}
?>
<?php
if ($_SESSION['role'] == "admin") {
    echo '
    <div class="sidenav" id="sidebarnav">
    <a href="#" class="closeBtn" onclick="closeNav()">&times;</a>

    <a href="ogrenciekle.php">Öğrenci Ekle</a>
    <a href="sinifekle.php">Sınıf ekle</a> 
    <a href="sorumluekle.php">Sorumlu ekle</a> 
    <a href="dersekle.php">Ders ekle</a> 
    <a href="sinavekle.php">Sınav ekle</a> 
    <a href="ögrenciler.php">Öğrenciler</a> 
    <a href="sorumlular.php">Sorumlular</a> 
    <a href="dersler.php">Dersler</a> 
    <a href="siniflar.php">Sınıflar</a> 
    <a href="sinavlar.php">Sınavlar</a> 
    </div> 
    ';
}
?>
<?php
if ($_SESSION['role'] == "Teacher") {
    echo '
    <div class="sidenav" id="sidebarnav">
    <a href="#" class="closeBtn" onclick="closeNav()">&times;</a>

    <a href="ögrenciler.php">Öğrenciler</a> 
    <a href="siniflar.php">Sınıflar</a> 
    <a href="dersler.php">Dersler</a> 
    <a href="sinavlar.php">Sınavlar</a> 
    <a href="sinavekle.php">Sınav ekle</a> 
    
    <a href="dersekle.php">Ders ekle</a> 
    
    
    </div> 
    ';
}
?>
<?php
if ($_SESSION['role'] == "Student") {
    echo '
    <div class="sidenav" id="sidebarnav">
    <a href="#" class="closeBtn" onclick="closeNav()">&times;</a>

    <a href="ögrenciler.php">Öğrenciler</a> 
    
    <a href="sinavlar.php">Sınavlar</a> 
    </div> 
    ';
}
?>


       <div class="background-image"></div>
          <span class="open" onclick="openNav()">&#9776; open</span>
          <?php 
          if($_SESSION['role']=="admin"){
             echo '<main id="main"> 
             <div class="card" style="width: 18rem;"> 
                 <img src="foto/ogrenci.jfif" card-img-top" alt="...">
                 <div class="card-body">
                     <h5 class="card-title">Öğrenciler</h5>
                     <p class="card-text">
                     Toplam Öğrenci sayısı:'  . $veriSayisi . '
                     </p>
                     
                 </div>
             </div>
             <div class="card" style="width: 18rem;"> 
             <img src="foto/ogrenci.jfif" card-img-top" alt="...">
             <div class="card-body">
                 <h5 class="card-title">Sorumlular</h5>
                 <p class="card-text">Toplam Öğretmen sayısı:'  . $veriSayisi2 . '</p>
                 
             </div>
         </div>
         <div class="card" style="width: 18rem;"> 
                 <img src="foto/ogrenci.jfif" card-img-top" alt="...">
                 <div class="card-body">
                     <h5 class="card-title">Sınıflar</h5>
                     <p class="card-text">Toplam Sınıf sayısı:'  . $veriSayisi3 . '</p>
                     
                 </div>
             </div>
         </main>';
          }else if ($_SESSION['role'] == "Teacher") {
            
            $teacherId = $_SESSION['id'];
            $sql = "SELECT COUNT(*) as ogrenci_sayisi FROM t_classes_students 
                    WHERE class_id IN (SELECT class_id FROM t_classes WHERE class_teacher_id = :teacherId)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $ogrenciSayisi = $result['ogrenci_sayisi'];
        
            // Sınıfın sınav ortalamasını almak için SQL sorgusunu hazırladım
            $sql2 = "SELECT AVG(exam_score) as sinav_ortalama FROM t_exams 
                     WHERE student_id IN (SELECT student_id FROM t_classes_students 
                                          WHERE class_id IN (SELECT class_id FROM t_classes WHERE class_teacher_id = :teacherId))";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $sinavOrtalama = $result2['sinav_ortalama'];
        
            echo '<main id="main"> 
                     <div class="card" style="width: 18rem;"> 
                         <img src="foto/ogrenci.jfif" card-img-top" alt="...">
                         <div class="card-body">
                             <h5 class="card-title">Sınıfınızdaki Öğrenci sayısı</h5>
                             <p class="card-text">Sınıfınızdaki öğrenci sayısı: ' . $ogrenciSayisi . '</p>
                             <a href="#" class="btn btn-primary">Youtube kanalı icin tıklayınız</a>
                         </div>
                     </div>
                     <div class="card" style="width: 18rem;"> 
                         <img src="foto/ogrenci.jfif" card-img-top" alt="...">
                         <div class="card-body">
                             <h5 class="card-title">Sınıf Ortalaması</h5>
                             <p class="card-text">Sınıf ortalaması: ' . $sinavOrtalama . '</p>
                             <a href="#" class="btn btn-primary">Detaylar için tıklayınız</a>
                         </div>
                     </div>
                 </main>';
        }else if ($_SESSION['role'] == "Student") {
            $studentId = $_SESSION['id'];
            
            // Öğrencinin girdiği sınav sayısını almak için SQL sorgusu yaptım
            $sql = "SELECT COUNT(*) as sinav_sayisi FROM t_exams WHERE student_id = :studentId";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $sinavSayisi = $result['sinav_sayisi'];
            
            // Öğrencinin girdiği sınavların notlarının toplamını almak için SQL sorgusu yaptım
            $sql2 = "SELECT SUM(exam_score) as notlar_toplami FROM t_exams WHERE student_id = :studentId";
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $notlarToplami = $result2['notlar_toplami'];
            
            // Sınav notlarının ortalamasını hesapladm
            $sinavOrtalamasi = ($sinavSayisi > 0) ? $notlarToplami / $sinavSayisi : 0;
            echo '<main id="main"> 
             <div class="card" style="width: 18rem;"> 
                 <img src="foto/ogrenci.jfif" card-img-top" alt="...">
                 <div class="card-body">
                     <h5 class="card-title">Girdiği Sınav Sayısı</h5>
                     <p class="card-text">Toplam girdiği sınav sayısı: ' . $sinavSayisi . '</p>
                     <a href="#" class="btn btn-primary">Detaylar için tıklayınız</a>
                 </div>
             </div>
         </main>';

            echo '<main id="main"> 
                     <div class="card" style="width: 18rem;"> 
                         <img src="foto/ogrenci.jfif" card-img-top" alt="...">
                         <div class="card-body">
                             <h5 class="card-title">Genel Başarı Ortalaması</h5>
                             <p class="card-text">Sınavların genel ortalaması: ' . $sinavOrtalamasi . '</p>
                             <a href="#" class="btn btn-primary">Detaylar için tıklayınız</a>
                         </div>
                     </div>
                 </main>';
        }
                     ?>
<script src="main.js"></script>
</body>
</html>