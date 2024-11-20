<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php require '../dbConnect.php' ?>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        tr {
            cursor: pointer;
        }
        tr.selected {
            background-color: #ffcccc;
        }
    </style>
</head>
<body>
<?php
global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    $stmt = $pdo->prepare('SELECT name FROM departments WHERE faculty_id = :id');
    $stmt->execute(['id' => $deleteId]);

    #Проверка на то, остались ли у факультета привязанные кафедры
    if ($stmt->fetchAll() === array()) {
        $stmt = $pdo->prepare('DELETE FROM faculties WHERE id = :id');
        $stmt->execute(['id' => $deleteId]);
        $message = "Строка с ID $deleteId удалена.";
    } else {
        $stmt = $pdo->prepare('SELECT name FROM departments WHERE faculty_id = :id');
        $stmt->execute(['id' => $deleteId]);
        $message = "У факультета остались привязанные кафедры, сначала удалите их!";
    }


}

$stmt = $pdo->query('SELECT * FROM faculties');
?>
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
<form method="post">
    <input type="hidden" name="delete_id" id="delete_id"> <!--Невидимое поле ввода для передачи информации об id записи-->
    <button type="submit" disabled id="delete_button">
        Удалить выбранную строку
    </button>
</form>
<table>
    <tr>
        <th>Название факультета</th>
        <th>ФИО декана</th>
        <th>Номер кабинета</th>
        <th>Номер корпуса</th>
        <th>Телефон</th>
    </tr>
    <?php while ($row = $stmt->fetch()): ?>
        <tr onclick="document.getElementById('delete_id').value='<?= $row['id'] ?>'; document.getElementById('delete_button').disabled=false;">
            <td><?= $row['name'] ?></td>
            <td><?= $row['dean_full_name'] ?></td>
            <td><?= $row['room_number'] ?></td>
            <td><?= $row['building_number'] ?></td>
            <td><?= $row['phone'] ?></td>
        </tr>
    <?php endwhile;?>
</table>
<?php if (!empty($message)):?>
    <script>
        window.onload = function () {
            alert("<?= $message ?>") //выводит сообщение после загрузки всего остального
        }
    </script>
<?php endif ?>
</body>
</html>