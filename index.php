<?php
require_once 'config.php';
$error='';

if($_POST){
    $email=trim($_POST['email']);
    $pass=$_POST['password'];

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Некорректный email';
    else{
        $res=$conn->query("SELECT * FROM users WHERE email='$email'");
        if(!$res->num_rows) $error='Пользователь не найден';
        else{
            $u=$res->fetch_assoc();
            if(password_verify($pass,$u['password'])){
                $_SESSION['user']=$u;
                header('Location: dashboard.php'); exit;
            } else $error='Неверный пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Вход</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">

<div id="loader"><div class="spinner"></div></div>

<form method="post" class="card auth-card">
<h2>Вход в CRM</h2>
<?php if($error) echo "<p class='err'>$error</p>"; ?>
<input name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Пароль" required>
<button>Войти</button>
<p class="auth-link"><a href="register.php">Регистрация</a></p>
</form>

<script>
window.addEventListener('load',()=>document.body.classList.add('loaded'));
</script>
</body>
</html>
