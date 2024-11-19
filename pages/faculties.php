<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php require '../dbConnect.php' ?>
</head>
<body>
<header>
    <form method="post" action="faculties.php">
        <button type="submit">Факультеты</button>
    </form>
    <form method="post" action="departments.php">
        <button type="submit">Кафедры</button>
    </form>
    <form method="post" action="disciplines.php">
        <button type="submit">Дисциплины</button>
    </form>
    <form method="post" action="teachers.php">
        <button type="submit">Преподаватели</button>
    </form>
</header>
<?php
global $pdo;
$stmt = $pdo->query('SELECT * FROM faculties');

echo '<table>';
    echo '<tr>
            <th>Название факультета</th>
            <th>ФИО декана</th>
            <th>Номер кабинета</th>
            <th>Номер корпуса</th>
            <th>Телефон</th>';
while ($row = $stmt->fetch()) {
    echo '<tr>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['dean_full_name'] . '</td>';
        echo '<td>' . $row['room_number'] . '</td>';
        echo '<td>' . $row['building_number'] . '</td>';
        echo '<td>' . $row['phone'] . '</td>';
    echo '</tr>';
}

echo '</table>';

?>
</body>
</html>