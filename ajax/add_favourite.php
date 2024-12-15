<?php
require_once('../db.php');

$response = [
    'success' => true,
    'data' => [],
    'error' => ''
];

$dessert_id = intval($_POST['dessert_id'] ?? 0);

if ($dessert_id <= 0) {
    $response['success'] = false;
    $response['error'] = 'Невалиден десерт';
} else {
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO favourite_desserts_users (user_id, dessert_id) 
              VALUES (:user_id, :dessert_id)";
    $stmt = $pdo->prepare($query);
    $params = [
        ':user_id' => $user_id,
        ':dessert_id' => $dessert_id
    ];

    if (!$stmt->execute($params)) {
        $response['success'] = false;
        $response['error'] = 'Възникна грешка при добавяне в любими';
    }
}

echo json_encode($response);
exit;
