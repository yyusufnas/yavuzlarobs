<?php
session_start();
$db = new PDO("mysql:host=db;dbname=yavuzlar", "user", "user");
            if(isset($_POST["ekle"])) {
               
                
                    $selectedStudentID = $_POST['student'];
                    $selectedLessonID = $_POST['lesson'];
                    $examScore = $_POST['examScore'];
                    $examDate = date("Y-m-d H:i:s"); 

                    try {
                        $db = new PDO("mysql:host=localhost;dbname=yavuzlar", "user", "user");
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                       
                        $sql_class = "SELECT class_id FROM t_classes_students WHERE student_id = :student_id";
                        $stmt = $db->prepare($sql_class);
                        $stmt->bindParam(':student_id', $selectedStudentID);
                        $stmt->execute();
                        $row_class = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($row_class) {
                            $classID = $row_class['class_id'];

                            // Sınavı t_exams tablosuna ekledim
                            $sql_insert_exam = "INSERT INTO t_exams (student_id, lesson_id, class_id, exam_score, exam_date) 
                                                VALUES (:student_id, :lesson_id, :class_id, :exam_score, :exam_date)";
                            $stmt_insert_exam = $db->prepare($sql_insert_exam);
                            $stmt_insert_exam->bindParam(':student_id', $selectedStudentID);
                            $stmt_insert_exam->bindParam(':lesson_id', $selectedLessonID);
                            $stmt_insert_exam->bindParam(':class_id', $classID);
                            $stmt_insert_exam->bindParam(':exam_score', $examScore);
                            $stmt_insert_exam->bindParam(':exam_date', $examDate);
                            $stmt_insert_exam->execute();

                            echo "<p>Sınav sonucu başarıyla eklenmiştir.</p>";
                        } else {
                            echo "<p>Seçilen öğrenci bir sınıfa atanmamıştır.</p>";
                        }

                        $db = null;
                    } catch(PDOException $e) {
                        echo "Veritabanı hatası: " . $e->getMessage();
                    }
                
            }
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci ve Ders Seçimi</title>
</head>
<body>
    <div class="container p-5">
        <div class="card p-5">
           
            <form action="#" method="post">
                <div class="mb-3">
                    <h1>Öğrenci ve Ders Seçimi</h1>
                    <label for="studentSelect" class="form-label">Öğrenci Seçiniz</label>
                    <select class="form-select" id="studentSelect" name="student">
                        <?php
                           
                            try {
                                $db = new PDO("mysql:host=localhost;dbname=yavuzlar", "user", "user");
                                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $sql_students = "SELECT id, name FROM t_users WHERE role='Student'";
                                $result_students = $db->query($sql_students);

                                while ($row_student = $result_students->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row_student['id'] . '">' . $row_student['name'] . '</option>';
                                }

                                $db = null; 
                            } catch(PDOException $e) {
                                echo "Veritabanı hatası: " . $e->getMessage();
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="lessonSelect" class="form-label">Hangi Ders</label>
                    <select class="form-select" id="lessonSelect" name="lesson">
                        <?php
                          
                            try {
                                $db = new PDO("mysql:host=localhost;dbname=yavuzlar", "user", "user");
                                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $sql_lessons = "SELECT id, lesson_name FROM t_lessons";
                                $result_lessons = $db->query($sql_lessons);

                                while ($row_lesson = $result_lessons->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row_lesson['id'] . '">' . $row_lesson['lesson_name'] . '</option>';
                                }

                                $db = null; 
                            } catch(PDOException $e) {
                                echo "Veritabanı hatası: " . $e->getMessage();
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="examScore" class="form-label">Sınav Sonucu</label>
                    <input type="text" class="form-control" id="examScore" name="examScore">
                </div>
                <button type="submit" class="btn btn-primary" name="ekle">Ekle</button>
            </form>
        </div>
    </div>
</body>
</html> 