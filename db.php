<?php

try {
    $host = '127.0.0.1';
    $db   = 'sweets_shop';
    $user = 'sweetsshop_admin';
    $pass = 'Sweets_2024';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // как да се показват грешките
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // как да се връщат данните от заявката
        PDO::ATTR_EMULATE_PREPARES   => false, // как да се подготвят заявките преди изпълняване
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Caught PDO exception: ',  $e->getMessage(), "\n";
    exit;
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    exit;
}

?>