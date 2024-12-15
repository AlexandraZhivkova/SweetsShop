<?php

require_once('../functions.php');
require_once('../db.php');

if (!is_admin()) {
    $_SESSION['flash']['message']['type'] = 'warning';
    $_SESSION['flash']['message']['text'] = 'Нямате достъп до тази страница!';
    header('Location: ../index.php?page=home');
    exit;
}

$dessert_id = intval($_POST['id'] ?? 0);

if ($dessert_id == 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Невалиден продукт!';

    header('Location: ../index.php?page=desserts');
    exit;
}

$query = "DELETE FROM desserts WHERE id = :id";
$stmt = $pdo->prepare($query);
if ($stmt->execute([':id' => $dessert_id])) {
    $_SESSION['flash']['message']['type'] = 'success';
    $_SESSION['flash']['message']['text'] = 'Десертът е изтрит успешно!';
} else {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Възникна грешка при изтриването на десерта!';
}

header('Location: ../index.php?page=desserts');
exit;
