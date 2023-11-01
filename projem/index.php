<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<div class="container p-5">

<div class="card p-5">
    <p>Yazılım geliştiricisi: Yusuf NAS</p>
    <p>Yazılım geliştirme sürecinin bitim tarihi: 12.10.2023</p>
    <form action="login.php" method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Kullanıcı adı</label>
            <input type="text" class="form-control" id="exampleInputEmail1" name="username">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            <button type="submit" class="btn btn-primary" name="girisyap">giris yap</button>
        </div>
    </form>
</div>
</body>
</html>
