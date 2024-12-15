<?php

require_once('./functions.php');
require_once('./db.php');

$page = $_GET['page'] ?? 'home';

$flash = [];
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

$admin_pages = ['add_dessert', 'edit_dessert'];

if (!is_admin() && in_array($page, $admin_pages)) {
    $_SESSION['flash']['message']['type'] = 'warning';
    $_SESSION['flash']['message']['text'] = 'Нямате достъп до тази страница!';

    header('Location: ./index.php?page=home');
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сладкарница</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <script>
        $(function() {
            // добавяне в любими
            $(document).on('click', '.add-favourite', function() {
                let btn = $(this);
                let dessertId = btn.data('dessert');

                $.ajax({
                    url: './ajax/add_favourite.php',
                    method: 'POST',
                    data: {
                        dessert_id: dessertId
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        console.log(res);

                        if (res.success) {
                            Swal.fire({
                                title: 'Продуктът беше добавен в любими',
                                icon: 'success',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 8000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                },
                                showCloseButton: true,
                            });
                            let removeBtn = $(`<button class="btn btn-danger btn-sm remove-favourite" data-dessert="${dessertId}">Премахни от любими</button>`);
                            btn.replaceWith(removeBtn);
                        } else {
                            Swal.fire({
                                title: 'Възникна грешка: ' + res.error,
                                icon: 'error',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 8000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                },
                                showCloseButton: true,
                            });
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });

            // премахване от любими
            $(document).on('click', '.remove-favourite', function() {
                let btn = $(this);
                let dessertId = btn.data('dessert');

                $.ajax({
                    url: './ajax/remove_favourite.php',
                    method: 'POST',
                    data: {
                        dessert_id: dessertId
                    },
                    success: function(response) {
                        let res = JSON.parse(response);

                        if (res.success) {
                            Swal.fire({
                                title: 'Продуктът беше премахнат от любими',
                                icon: 'success',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 8000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                },
                                showCloseButton: true,
                            });
                            let addBtn = $(`<button class="btn btn-primary btn-sm add-favourite" data-dessert="${dessertId}">Добави в любими</button>`);
                            btn.replaceWith(addBtn);
                        } else {
                            Swal.fire({
                                title: 'Възникна грешка: ' + res.error,
                                icon: 'error',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 8000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                },
                                showCloseButton: true,
                            });
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">Сладкарница</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'home' ? 'active' : ''); ?>" aria-current="page" href="?page=home">Начало</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'desserts' ? 'active' : ''); ?>" href="?page=desserts">Каталог</a>
                        </li>
                        <?php
                        if (isset($_SESSION['user_name'])) {
                            if (is_admin()) {
                                echo '
                                    <li class="nav-item">
                                        <a class="nav-link ' . ($page == 'add_dessert' ? 'active' : '') . '" href="?page=add_dessert">Добави десерт</a>
                                    </li>
                                ';
                            } else {
                                echo '
                                    <li class="nav-item">
                                        <a class="nav-link ' . ($page == 'contacts' ? 'active' : '') . '" href="?page=contacts">Контакти</a>
                                    </li>
                                ';
                            }
                        } else {
                            echo '
                                <li class="nav-item">
                                    <a class="nav-link ' . ($page == 'contacts' ? 'active' : '') . '" href="?page=contacts">Контакти</a>
                                </li>
                            ';
                        }
                        ?>
                    </ul>
                    <div class="d-flex flex-row gap-3">
                        <?php

                        if (isset($_SESSION['user_name'])) {
                            echo '<span class="text-dark align-content-center">Здравейте, ' . htmlspecialchars($_SESSION['user_name']) . '</span>';
                            echo '
                                    <form method="POST" action="./handlers/handle_logout.php" class="m-0">
                                        <button type="submit" class="btn btn-dark btn-outline-light  align-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                            </svg>
                                        </button>
                                    </form>
                                ';
                        } else {
                            echo '<a href="?page=login" class="btn  btn-dark btn-outline-light">Вход</a>';
                            echo '<a href="?page=register" class="btn btn-dark btn-outline-light">Регистрация</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4" style="min-height:80vh;">
        <?php
        if (isset($flash['message'])) {
            $type_icons = [
                'success' => 'success',
                'danger' => 'error',
                'warning' => 'warning',
            ];

            echo '
                    <script>
                        Swal.fire({
                            title: \'' . $flash['message']['text'] . '\',
                            icon: \'' . $type_icons[$flash['message']['type']] . '\',
                            toast: true,
                            position: \'top\',
                            showConfirmButton: false,
                            timer: 8000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            },
                            showCloseButton: true,
                        });
                    </script>
                ';
        }

        if (file_exists('pages/' . $page . '.php')) {
            require_once('pages/' . $page . '.php');
        } else {
            require_once('pages/not_found.php');
        }
        ?>
    </main>
    <footer class="bg-white text-center py-5 mt-auto">
        <div class="container">
            <span class="text-dark">© 2024 All rights reserved</span>
        </div>
    </footer>
</body>

</html>