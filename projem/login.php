<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    include "dbconnect.php";

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        header("Location: index.php?error=User Name boş olamaz");
        exit();
    } else if (empty($password)) {
        header("Location: index.php?error=Password boş olamaz");
        exit();
    } else {
        $query = $db->prepare("SELECT * FROM t_users WHERE username = ?");
        $query->execute(array($username));
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: home.php");
            exit();
        } else {
            echo "Kullanıcı adı veya şifre yanlış";
            header("Location: index.php?");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}

?>
