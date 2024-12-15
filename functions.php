<?php

function debug($data, $exit = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    if ($exit) {
        exit;
    }
}

function is_admin() {
    return isset($_SESSION['type']) && $_SESSION['type'] == '2';
}

?>