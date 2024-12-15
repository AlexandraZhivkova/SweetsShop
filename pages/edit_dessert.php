<?php
// добавяне/редакция на продукт
$dessert_id = intval($_GET['id'] ?? 0);

if ($dessert_id > 0) {
    $query = "SELECT * FROM desserts 
              WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $dessert_id]);
    $dessert = $stmt->fetch();
}
?>

<form class="border rounded p-4 w-50 mx-auto" method="POST" action="./handlers/handle_edit_dessert.php" enctype="multipart/form-data">
    <h3 class="text-center">Редактирай продукт</h3>
    <div class="mb-3">
        <label for="name" class="form-label">Наименование:</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $dessert['name'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Цена:</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $dessert['price'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Изображение:</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
    </div>

    <div class="mb-3">
        <img class="img-fluid" src="uploads/<?php echo $dessert['image'] ?>" alt="<?php echo $dessert['name'] ?>">
    </div>
    <input type="hidden" name="id" value="<?php echo $dessert['id'] ?>">
    <button type="submit" class="btn btn-success mx-auto">Редактирай</button>
</form>