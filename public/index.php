<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.09.2017
 * Time: 13:56
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/session_function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/function.php';

try {
//    Подсчет авторов
    $totalAuthor = $pdo->query('SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1');
    $row = $totalAuthor->fetch();
    $totalAuthor = $row['user_id'];
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества пользователей в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

try {
    // Подсчет количества категорий в базе данных
    $totalCategory = $pdo->query('SELECT category_id FROM categories ORDER BY category_id DESC LIMIT 1');
    $row = $totalCategory->fetch();
    $totalCategory = $row['category_id'];
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества категорий в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['author'])) {
//    Указание автора, если не получаеться выбираем все категории
    if (! is_numeric($_GET['author']) or $_GET['author'] <= 0 or $_GET['author'] > $totalAuthor) {
        $category = 'all';
    } else {
        $author = $_GET['author'];
        $author = ceil($author);
    }
} else {
    $category = 'all';
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
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

try {
    // Подсчет количества шуток в базе данных
    if (isset($author) && is_numeric($author)) {
        $totalJokes = $pdo->query("SELECT COUNT(*) FROM jokes
                                             WHERE author_id = $author")->fetchColumn(0);
    } elseif ($category == 'all') {
        $totalJokes = $pdo->query('SELECT COUNT(*) FROM jokes')->fetchColumn(0);
    } else {
        $totalJokes = $pdo->query("SELECT COUNT(*) FROM jokes 
                                             INNER JOIN joke_category ON jokes.joke_id = joke_category.joke_id 
                                             WHERE joke_category.category_id = $category")->fetchColumn(0);
    }
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества шуток в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

$pages = ceil($totalJokes / PER_PAGE); //Количество необходимых страниц
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
    $sql = "SELECT * FROM jokes 
            INNER JOIN joke_category ON jokes.joke_id = joke_category.joke_id 
            INNER JOIN categories ON joke_category.category_id = categories.category_id 
            INNER JOIN users ON jokes.author_id = users.user_id ";
    if (isset($author) && is_numeric($author)) {
        $sql .= "WHERE jokes.author_id = $author ";
    } elseif ($category != 'all') {
        $sql .= "WHERE joke_category.category_id = $category ";
    }
    $sql .= "ORDER BY jokes.joke_id DESC 
             LIMIT " . PER_PAGE . " OFFSET $offset";
    $resultJoke = $pdo->query($sql);
} catch (PDOException $e) {
    $error = 'Ошибка при извлечении шуток: ' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/jokes.phtml';