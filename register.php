<?php
require_once 'config.php';
$error='';

if($_POST){
    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $pass=$_POST['password'];

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Некорректный email';
    elseif(strlen($pass)<5) $error='Пароль минимум 5 символов';
    else{
        $c=$conn->query("SELECT id FROM users WHERE email='$email'");
        if($c->num_rows) $error='Email уже зарегистрирован';
        else{
            $hash=password_hash($pass,PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (name,email,password) VALUES ('$name','$email','$hash')");
            header('Location: index.php'); exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Регистрация</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

<div id="loader"><div class="spinner"></div></div>

<form method="post" class="card auth-card">
<h2>Регистрация</h2>
<?php if($error) echo "<p class='err'>$error</p>"; ?>
<input name="name" placeholder="Имя" required>
<input name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Пароль" required>
<button>Создать аккаунт</button>
<p class="auth-link"><a href="index.php">Войти</a></p>
</form>

<script>
window.addEventListener('load',()=>document.body.classList.add('loaded'));
</script>
</body>
</html>
