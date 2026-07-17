<?php require_once 'dashboard.php'; ?>

<h2>Профиль пользователя</h2>

<div class="card profile-card">

<div class="profile-header">

<div class="profile-avatar">
<i class="fa-solid fa-user"></i>
</div>

<div>

<h3>
<?= htmlspecialchars($_SESSION['user']['name']) ?>
</h3>

<p class="profile-role">
Пользователь CRM-системы
</p>

</div>

</div>

<hr>

<div class="profile-info">

<p>

<i class="fa-solid fa-user"></i>

<strong>Имя:</strong>

<?= htmlspecialchars($_SESSION['user']['name']) ?>

</p>

<p>

<i class="fa-solid fa-envelope"></i>

<strong>Email:</strong>

<?= htmlspecialchars($_SESSION['user']['email']) ?>

</p>

<p>

<i class="fa-solid fa-calendar-days"></i>

<strong>Дата регистрации:</strong>

<?= htmlspecialchars($_SESSION['user']['created_at']) ?>

</p>

</div>

</div>

</div>
</body>
</html>
