<?php

require_once('../functions.php');
require_once('../db.php');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// проверяваме дали потребителят съществува
$query = "SELECT * FROM users 
          WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if ($user == null) {
    header('Location: ../index.php?page=login&error=Грешен имейл или парола');
    exit;
}

if (!password_verify($password, $user['password_hash'])) {
    header('Location: ../index.php?page=login&error=Грешен имейл или парола');
    exit;
}

session_start();
$_SESSION['user_name'] = $user['names'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_id'] = $user['id'];
$_SESSION['type'] = $user['type_id'];

// сетваме бисквитка
setcookie('user_email', $user['email'], time() + 3600, '/', 'localhost', false, true);

header('Location: ../index.php');
exit;
