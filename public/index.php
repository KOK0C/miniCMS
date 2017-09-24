<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.09.2017
 * Time: 13:56
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/function.php';

try {
    // Подсчет количества категорий в базе данных
    $totalCategory = $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn(0);
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества категорий в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['category'])) {
//    Категория по умолчанию
    if (! is_numeric($_GET['category']) or $_GET['category'] <= 0 or $_GET['category'] > $totalCategory) {
        $category = 'all';
    } else {
        $category = $_GET['category'];
        $category = ceil($category);
    }
} else {
    $category = 'all';
}

try {
//    Извлечение категорий
    $sql = "SELECT * FROM categories ORDER BY category_name";
    $resultCategory = $pdo->query($sql);
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении категорий: ' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}

$per_page = 10; // количество выводимых записей на страницу

try {
    // Подсчет количества шуток в базе данных
    if ($category == 'all') {
        $totalJokes = $pdo->query('SELECT COUNT(*) FROM jokes')->fetchColumn(0);
    } else {
        $totalJokes = $pdo->query("SELECT COUNT(*) FROM jokes 
                                            INNER JOIN joke_category ON jokes.joke_id = joke_category.joke_id 
                                            WHERE joke_category.category_id = $category")->fetchColumn(0);
    }
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества шуток в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}

$pages = ceil($totalJokes / $per_page); //Количество необходимых страниц
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['page'])) {
//    Страница по умолчанию
    if (! is_numeric($_GET['page']) or $_GET['page'] <= 0 or $_GET['page'] > $pages) {
        $page = 1;
    } else {
        $page = $_GET['page'];
        $page = ceil($page);
    }
} else {
    $page = 1;
}
$offset = ($page - 1) * 10;

try {
    //Извлечение шуток
    $sql = "SELECT * FROM jokes ";
    $sql .= "INNER JOIN joke_category ON jokes.joke_id = joke_category.joke_id ";
    $sql .= "INNER JOIN categories ON joke_category.category_id = categories.category_id ";
    if ($category != 'all') {
        $sql .= "WHERE joke_category.category_id = $category ";
    }
    $sql .= "ORDER BY jokes.joke_id DESC ";
    $sql .= "LIMIT $per_page OFFSET $offset";
    $resultJoke = $pdo->query($sql);
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении шуток: ' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/public/jokes.phtml';
