<?php
// страница десерти
$desserts = [];

$query;
if (isset($_SESSION['user_id']) && $_SESSION['type'] == 1) {
    $user_id = $_SESSION['user_id'] ?? '';

    // правим заявка към базата данни
    $query = "SELECT * FROM orders 
              WHERE user_id LIKE :user_id";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    while ($row = $stmt->fetch()) {
        // $fav_query = "SELECT id FROM favorite_desserts_users 
        //         WHERE user_id = :user_id AND dessert_id = :dessert_id";
        // $fav_stmt = $pdo->prepare($fav_query);
        // $fav_params = [
        //     ':user_id' => $_SESSION['user_id'] ?? 0,
        //     ':dessert_id' => $row['id']
        // ];
        // $fav_stmt->execute($fav_params);
        // $row['is_favorite'] = $fav_stmt->fetch() ? 1 : 0;

        $desserts[] = $row;
    }
} else {
    $query = $query = "SELECT * FROM desserts";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $desserts[] = $row;
    }
}




if (!empty($search)) {
    setcookie('last_search', $search, time() + 3600, '/', 'localhost', false, false);
}

if (count($desserts) == 0) {
    echo '
            <div class="alert alert-warning" role="alert">
                Няма намерени поръчки.
            </div>
        ';
} else {
    echo ' <div class="d-flex flex-wrap justify-content-between">';
    foreach ($desserts as $dessert) {
        $fav_btn = $edit_delete_buttons = '';
        if (isset($_SESSION['user_name'])) {
            if ($dessert['is_favorite'] == '1') {
                $fav_btn = '
                        <div class="card-footer text-center">
                            <button class="btn btn-danger btn-sm remove-favorite" data-dessert="' . $dessert['id'] . '">Премахни от любими</button>
                        </div>
                    ';
            } else {
                $fav_btn = '
                        <div class="card-footer text-center">
                            <button class="btn btn-primary btn-sm add-favorite" data-dessert="' . $dessert['id'] . '">Добави в любими</button>
                        </div>
                    ';
            }
        }

        if (is_admin()) {
            $edit_delete_buttons = '
                    <div class="card-header d-flex flex-row justify-content-between">
                        <a href="?page=edit_dessert&id=' . $dessert['id'] . '" class="btn btn-sm btn-warning">Редактирай</a>
                        <form method="POST" action="./handlers/handle_delete_dessert.php">
                            <input type="hidden" name="id" value="' . $dessert['id'] . '">
                            <button type="submit" class="btn btn-sm btn-danger">Изтрий</button>
                        </form>
                    </div>
               ';
        }

        echo '
                <div class="card mb-4" style="width: 18rem;">
                    ' . $edit_delete_buttons . '
                    <img src="uploads/' . htmlspecialchars($dessert['image']) . '" class="card-img-top" alt="dessert Image">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($dessert['title']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($dessert['price']) . '</p>
                    </div>
                    ' . $fav_btn . '
                </div>
            ';
    }
    echo '</div>';
}
