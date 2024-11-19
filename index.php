<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php require 'dbConnect.php'?>
</head>
<?php

function loadMenu()
{
    global $stmt, $selectedOption, $pdo;

    $stmt = $pdo->query('SELECT * FROM faculties');
    $selectedOption = isset($_POST['select']) ? $_POST['select'] : null;

    echo '<form method="post">
                <select name="select" onchange="this.form.submit()">
                    <option>--Выберите факультет--</option>';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . $row['name'] . '"';
        if ($selectedOption == $row['name']) echo 'selected';
        echo '>';
        print_r($row['name']);
        echo '</option>';
    }

    echo '    </select>
          </form>';
}

loadMenu();

if (isset($_POST['select'])) {


    $name = $_POST['select'];
    $stmt = $pdo->query("SELECT * FROM departments where faculty_id = (SELECT id FROM faculties WHERE name = \"$name\")");

    echo '<pre>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo '</pre>';
}
?>
<body>

</body>
</html>