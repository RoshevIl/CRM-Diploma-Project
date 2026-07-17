<?php

require_once 'dashboard.php';

$uid = $_SESSION['user']['id'];

$totalClients = $conn->query("
SELECT COUNT(*) c
FROM clients
WHERE user_id=$uid
")->fetch_assoc();

$totalOrders = $conn->query("
SELECT COUNT(*) o
FROM orders
WHERE user_id=$uid
")->fetch_assoc();

$doneOrders = $conn->query("
SELECT
COUNT(*) d,
SUM(amount) s
FROM orders
WHERE user_id=$uid
AND status='Завершён'
")->fetch_assoc();

$activeOrders = $conn->query("
SELECT COUNT(*) a
FROM orders
WHERE user_id=$uid
AND status!='Завершён'
")->fetch_assoc();

$avgCheck = $conn->query("
SELECT AVG(amount) avg_sum
FROM orders
WHERE user_id=$uid
AND status='Завершён'
")->fetch_assoc();

$chartQuery = $conn->query("
SELECT
DATE_FORMAT(created_at,'%Y-%m') month,
SUM(amount) income
FROM orders
WHERE user_id=$uid
AND status='Завершён'
GROUP BY month
ORDER BY month
");

$labels = [];
$income = [];

while($row = $chartQuery->fetch_assoc()){

$labels[] = $row['month'];
$income[] = $row['income'];

}

$completePercent = 0;

if($totalOrders['o'] > 0){

$completePercent = round(
($doneOrders['d'] / $totalOrders['o']) * 100
);

}
?>

<h2>Аналитика</h2>

<div class="stats">

<div>
Клиенты
<br>
<b><?= $totalClients['c'] ?></b>
</div>

<div>
Всего заказов
<br>
<b><?= $totalOrders['o'] ?></b>
</div>

<div>
Завершённые
<br>
<b><?= $doneOrders['d'] ?></b>
</div>

<div>
Активные
<br>
<b><?= $activeOrders['a'] ?></b>
</div>

<div>
Доход
<br>
<b><?= number_format($doneOrders['s'] ?? 0,0,' ',' ') ?> ₽</b>
</div>

<div>
Средний чек
<br>
<b><?= round($avgCheck['avg_sum'] ?? 0) ?> ₽</b>
</div>

<div>
Успешность
<br>
<b><?= $completePercent ?>%</b>
</div>

</div>

<hr style="margin:30px 0;">

<h2>График доходов по месяцам</h2>

<div class="card">

<canvas id="incomeChart"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx =
document.getElementById('incomeChart');

new Chart(ctx,{

type:'line',

data:{

labels:
<?= json_encode($labels) ?>,

datasets:[{

label:'Доход',

data:
<?= json_encode($income) ?>,

fill:true,

tension:0.3,

borderWidth:3

}]

},

options:{

responsive:true,

plugins:{
legend:{
display:true
}
},

scales:{
y:{
beginAtZero:true
}
}

}

});

</script>

</div>
</body>
</html>
