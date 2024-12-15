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
$dessert_id = intval($_POST['id'] ?? 0);

if (mb_strlen($name) == 0 || mb_strlen($price) == 0 || $dessert_id <= 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Моля попълнете всички полета!';

    header('Location: ../index.php?page=edit_dessert&id=' . $dessert_id);
    exit;
}

// проверка дали има нова снимка
$img_uploaded = false;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
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

        header('Location: ../index.php?page=edit_dessert&id=' . $dessert_id);
        exit;
    } else {
        $img_uploaded = true;
    }
}

$query = '';
if ($img_uploaded) {
    $query = "
        UPDATE desserts
        SET name = :name, price = :price, image = :image
        WHERE id = :id
    ";
} else {
    $query = "
        UPDATE desserts
        SET name = :name, price = :price
        WHERE id = :id
    ";
}

$stmt = $pdo->prepare($query);
$params = [
    ':name' => $name,
    ':price' => $price,
    ':id' => $dessert_id
];
if ($img_uploaded) {
    $params[':image'] = $new_filename;
}

if ($stmt->execute($params)) {
    $_SESSION['flash']['message']['type'] = 'success';
    $_SESSION['flash']['message']['text'] = 'Продуктът е редактиран успешно!';
} else {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Възникна грешка при редакцията на продукта!';
}

header('Location: ../index.php?page=edit_dessert&id=' . $dessert_id);
exit;
