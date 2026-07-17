<?php

require_once 'dashboard.php';

$uid = $_SESSION['user']['id'];

if(isset($_POST['add'])){

    $conn->query("
        INSERT INTO orders
        (
            user_id,
            client_id,
            description,
            amount,
            status
        )
        VALUES
        (
            $uid,
            $_POST[client],
            '$_POST[desc]',
            $_POST[amount],
            'Новый'
        )
    ");

    header("Location: orders.php");
    exit;
}

if(isset($_GET['done'])){

    $conn->query("
        UPDATE orders
        SET status='Завершён'
        WHERE id=$_GET[done]
        AND user_id=$uid
    ");

    header("Location: orders.php?page=" . ($_GET['page'] ?? 1));
    exit;
}

if(isset($_GET['work'])){

    $conn->query("
        UPDATE orders
        SET status='В работе'
        WHERE id=$_GET[work]
        AND user_id=$uid
    ");

    header("Location: orders.php?page=" . ($_GET['page'] ?? 1));
    exit;
}

$clients = $conn->query("
SELECT *
FROM clients
WHERE user_id=$uid
");

$limit = 5;

$page = isset($_GET['page'])
? (int)$_GET['page']
: 1;

if($page < 1){
    $page = 1;
}

$offset = ($page - 1) * $limit;

$total = $conn->query("
SELECT COUNT(*) c
FROM orders
WHERE user_id=$uid
")->fetch_assoc();

$totalOrders = $total['c'];

$pages = ceil($totalOrders / $limit);

$orders = $conn->query("
SELECT
o.*,
c.name
FROM orders o
JOIN clients c
ON o.client_id = c.id
WHERE o.user_id=$uid
ORDER BY o.id DESC
LIMIT $limit OFFSET $offset
");

?>

<h2>Заказы</h2>

<form method="post" class="card">

<input
type="hidden"
name="add"
value="1"

>

<select name="client">

<?php while($c = $clients->fetch_assoc()): ?>

<option value="<?= $c['id'] ?>">
<?= htmlspecialchars($c['name']) ?>
</option>

<?php endwhile; ?>

</select>

<textarea
name="desc"
placeholder="Описание заказа"
required
></textarea>

<input
name="amount"
placeholder="Стоимость"
required

>

<button>
Добавить заказ
</button>

</form>

<ul class="orders">

<?php while($o = $orders->fetch_assoc()): ?>

<li class="<?= $o['status'] == 'Завершён' ? 'done' : '' ?>">

<b><?= htmlspecialchars($o['name']) ?></b>

<br><br>

<?= htmlspecialchars($o['description']) ?>

<br><br>

<strong><?= $o['amount'] ?> ₽</strong>

<span class="separator">|</span>

<i><?= htmlspecialchars($o['status']) ?></i>

<br><br>

<?php if($o['status'] != 'Завершён'): ?>

<a
href="?done=<?= $o['id'] ?>&page=<?= $page ?>"
class="status-btn success-btn"

>

<i class="fa-solid fa-check"></i>

Завершить

</a>

<?php else: ?>

<a
href="?work=<?= $o['id'] ?>&page=<?= $page ?>"
class="status-btn warning-btn"

>

<i class="fa-solid fa-rotate-left"></i>

Вернуть в работу

</a>

<?php endif; ?>

</li>

<?php endwhile; ?>

</ul>

<?php if($pages > 1): ?>

<div class="pagination">

<?php for($i = 1; $i <= $pages; $i++): ?>

<a
href="?page=<?= $i ?>"
class="<?= $page == $i ? 'active' : '' ?>"

>

<?= $i ?>

</a>

<?php endfor; ?>

</div>

<?php endif; ?>

</div>
</body>
</html>
