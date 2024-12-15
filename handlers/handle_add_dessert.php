<?php

require_once('../functions.php');
require_once('../db.php');

if (!is_admin()) {
    $_SESSION['flash']['message']['type'] = 'warning';
    $_SESSION['flash']['message']['text'] = 'Нямате достъп до тази страница!';
    header('Location: ../index.php?page=home');
    exit;
}

$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');

if (mb_strlen($name) == 0 || mb_strlen($price) == 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Моля попълнете всички полета!';

    header('Location: ../index.php?page=add_dessert');
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Моля изберете изображение!';

    header('Location: ../index.php?page=add_dessert');
    exit;
}

$new_filename = time() . '_' . $_FILES['image']['name'];
$upload_dir = '../uploads/';

// проверка дали директорията съществува
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

// качване на файла
if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Възникна грешка при качването на файла!';

    header('Location: ../index.php?page=add_dessert');
    exit;
} else {
    // запис в базата данни
    $query = "INSERT INTO desserts (name, price, image) 
              VALUES (:name, :price, :image)";
    $stmt = $pdo->prepare($query);
    $params = [
        ':name' => $name,
        ':price' => $price,
        ':image' => $new_filename
    ];
    if ($stmt->execute($params)) {
        $_SESSION['flash']['message']['type'] = 'success';
        $_SESSION['flash']['message']['text'] = 'Десертът е добавен успешно!';

        header('Location: ../index.php?page=desserts');
        exit;
    } else {
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = 'Възникна грешка при добавянето на десерта!';

        header('Location: ../index.php?page=add_dessert');
        exit;
    }
}
