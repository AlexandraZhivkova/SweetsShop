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

    $query = "DELETE FROM favourite_desserts_users 
              WHERE user_id = :user_id AND dessert_id = :dessert_id";
    $stmt = $pdo->prepare($query);
    $params = [
        ':user_id' => $user_id,
        ':dessert_id' => $dessert_id
    ];

    if (!$stmt->execute($params)) {
        $response['success'] = false;
        $response['error'] = 'Възникна грешка при премахване от любими';
    }
}

echo json_encode($response);
exit;
