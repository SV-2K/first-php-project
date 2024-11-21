<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php require '../dbConnect.php' ?>
    <link href="../styles.css" rel="stylesheet">
</head>
<body>
<?php
session_start();
global $pdo;

if (isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $name = $_POST['name'];
    $dean_full_name = $_POST['dean_full_name'];
    $room_number = $_POST['room_number'];
    $building_number = $_POST['building_number'];
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("UPDATE faculties SET name = :name, dean_full_name = :dean_full_name, room_number = :room_number, building_number = :building_number, phone = :phone WHERE id = :id");
    $stmt->execute([
        'id' => $id,
        'name' => $name,
        'dean_full_name' => $dean_full_name,
        'room_number' => $room_number,
        'building_number' => $building_number,
        'phone' => $phone,
    ]);

    $_SESSION['message'] = "Запись с ID $id успешно обновлена.";
    header("Location: faculties.php"); // Перезагрузка страницы
    exit();
}

if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    $stmt = $pdo->prepare('SELECT name FROM departments WHERE faculty_id = :id');
    $stmt->execute(['id' => $deleteId]);

    if ($stmt->fetchAll() === array()) {
        $stmt = $pdo->prepare('DELETE FROM faculties WHERE id = :id');
        $stmt->execute(['id' => $deleteId]);
        $_SESSION['message'] = "Строка с ID $deleteId удалена.";
    } else {
        $_SESSION['message'] = "У факультета остались привязанные кафедры, сначала удалите их!";
    }

    header("Location: faculties.php"); // Перенаправляем на ту же страницу
    exit();
}


#хз как это работает, но нужно оно для того, чтобы алерт не выводился каждый раз при обновлении страницы
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Удаляем сообщение после вывода
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
    <input type="hidden" name="update_recording" id="update_recording">
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
            <td><a href="#editForm<?= $row['id'] ?>">Изменить</a></td>
        </tr>
    <?php endwhile;?>
</table>
<?php if (!empty($message)):?>
    <script>
        window.onload = function () {
            alert("<?= $message ?>") //выводит сообщение после загрузки всего остального
        }
    </script>
<?php
endif;

#Создание модальных окон для редактирования
$stmt->execute();
while($row = $stmt->fetch()):
?>
    <div id="editForm<?= $row['id'] ?>" class="modal">
        <div class="modal-content">
            <a href="#" class="close">&times;</a>
            <h2>Редактирование факультета</h2>
            <form method="post">
                <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
                <label>
                    Название факультета:
                    <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>">
                </label>
                <br>
                <label>
                    ФИО декана:
                    <input type="text" name="dean_full_name" value="<?= htmlspecialchars($row['dean_full_name']) ?>">
                </label>
                <br>
                <label>
                    Номер кабинета:
                    <input type="text" name="room_number" value="<?= htmlspecialchars($row['room_number']) ?>">
                </label>
                <br>
                <label>
                    Номер корпуса:
                    <input type="text" name="building_number" value="<?= htmlspecialchars($row['building_number']) ?>">
                </label>
                <br>
                <label>
                    Телефон:
                    <input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>">
                </label>
                <br>
                <button type="submit">Сохранить</button>
            </form>
        </div>
    </div>
<?php endwhile;?>
</body>
</html>