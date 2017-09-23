<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.09.2017
 * Time: 13:56
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';

$per_page = 10; // количество выводимых записей на страницу

try {
    // Подсчет количества строк в базе данных
    $total = $pdo->query('SELECT COUNT(*) FROM jokes')->fetchColumn(0);
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества строк в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}

$pages = ceil($total / $per_page); //Количество необходимых страниц
$page = $_GET['page'] ?? 1;
if (! is_numeric($page) or $page <= 0) {
    $page = 1;
} else {
    $page = ceil($page);
}
$offset = ($page - 1) * 10;

try {
    //Извлечение шуток
    $sql = "SELECT * FROM jokes ORDER BY joke_id DESC LIMIT $per_page OFFSET $offset";
    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        $jokes [] = $row['joke_text'];
    }
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении шуток: ' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/public/output.phtml';
