<?php

require_once 'dashboard.php';

$uid = $_SESSION['user']['id'];

if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $conn->query("
        DELETE FROM clients
        WHERE id=$id
        AND user_id=$uid
    ");

    header("Location: clients.php");
    exit;
}

if(isset($_POST['update'])){

    $id = (int)$_POST['id'];

    $conn->query("
        UPDATE clients
        SET
        name='$_POST[name]',
        organization='$_POST[org]',
        phone='$_POST[phone]',
        email='$_POST[email]'
        WHERE id=$id
        AND user_id=$uid
    ");

    header("Location: clients.php");
    exit;
}

if(isset($_POST['add'])){

    $conn->query("
        INSERT INTO clients
        (
            user_id,
            name,
            organization,
            phone,
            email
        )
        VALUES
        (
            $uid,
            '$_POST[name]',
            '$_POST[org]',
            '$_POST[phone]',
            '$_POST[email]'
        )
    ");

    header("Location: clients.php");
    exit;
}

$search = $_GET['search'] ?? '';

$sql = "
SELECT *
FROM clients
WHERE user_id=$uid
";

if($search != ''){

    $sql .= "
    AND (
        name LIKE '%$search%'
        OR organization LIKE '%$search%'
        OR phone LIKE '%$search%'
        OR email LIKE '%$search%'
    )
    ";
}

$sql .= " ORDER BY id DESC";

$res = $conn->query($sql);

$editClient = null;

if(isset($_GET['edit'])){

    $id = (int)$_GET['edit'];

    $editClient = $conn->query("
        SELECT *
        FROM clients
        WHERE id=$id
        AND user_id=$uid
    ")->fetch_assoc();
}

?>

<h2>Клиенты</h2>

<form method="get" class="card">

<input
    type="text"
    name="search"
    placeholder="Поиск клиента"
    value="<?= htmlspecialchars($search) ?>"
>

<button type="submit">
    Найти
</button>

</form>

<?php if($editClient): ?>

<form method="post" class="card">

<h3>Редактирование клиента</h3>

<input
    type="hidden"
    name="update"
    value="1"
>

<input
    type="hidden"
    name="id"
    value="<?= $editClient['id'] ?>"
>

<input
    name="name"
    value="<?= $editClient['name'] ?>"
    required
>

<input
    name="org"
    value="<?= $editClient['organization'] ?>"
>

<input
    name="phone"
    value="<?= $editClient['phone'] ?>"
>

<input
    name="email"
    value="<?= $editClient['email'] ?>"
>

<button>
    Сохранить изменения
</button>

</form>

<?php endif; ?>

<form method="post" class="card">

<input
    type="hidden"
    name="add"
    value="1"
>

<input
    name="name"
    placeholder="Имя клиента"
    required
>

<input
    name="org"
    placeholder="Организация"
>

<input
    name="phone"
    placeholder="Телефон"
>

<input
    name="email"
    placeholder="Email"
>

<button>
    Добавить клиента
</button>

</form>

<table>

<tr>
    <th>Имя</th>
    <th>Организация</th>
    <th>Телефон</th>
    <th>Email</th>
    <th>Действия</th>
</tr>

<?php while($c = $res->fetch_assoc()): ?>

<tr>

<td><?= htmlspecialchars($c['name']) ?></td>

<td><?= htmlspecialchars($c['organization']) ?></td>

<td><?= htmlspecialchars($c['phone']) ?></td>

<td><?= htmlspecialchars($c['email']) ?></td>

<td>

    <a
        href="?edit=<?= $c['id'] ?>"
        class="action-btn edit-btn"
        title="Редактировать"
    >
        <i class="fa-solid fa-pen"></i>
    </a>

    <a
        href="?delete=<?= $c['id'] ?>"
        class="action-btn delete-btn"
        title="Удалить"
        onclick="return confirm('Удалить клиента?')"
    >
        <i class="fa-solid fa-trash"></i>
    </a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>
</body>
</html>
