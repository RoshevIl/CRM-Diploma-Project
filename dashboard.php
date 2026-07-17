<?php require_once 'auth.php'; ?>

<!DOCTYPE html>

<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRM система</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>
<body>

<div id="loader">
    <div class="spinner"></div>
</div>

<nav>

<a href="clients.php">
    <i class="fa-solid fa-users"></i>
    Клиенты
</a>

<a href="orders.php">
    <i class="fa-solid fa-file-lines"></i>
    Заказы
</a>

<a href="analytics.php">
    <i class="fa-solid fa-chart-line"></i>
    Аналитика
</a>

<a href="profile.php">
    <i class="fa-solid fa-user"></i>
    Профиль
</a>

<a href="logout.php">
    <i class="fa-solid fa-right-from-bracket"></i>
    Выход
</a>

</nav>

<div class="container">

<h1>
    Добро пожаловать,
    <?= htmlspecialchars($_SESSION['user']['name']) ?>
</h1>

<script>
window.addEventListener('load', () => {
    document.body.classList.add('loaded');
});
</script>
