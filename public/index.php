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
    $totalAuthor = $pdo->query('SELECT user_id FROM users');
    $totalAuthor = $totalAuthor->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества пользователей в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

try {
    // Подсчет количества категорий в базе данных
    $totalCategory = $pdo->query('SELECT category_id FROM categories');
    $totalCategory = $totalCategory->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = 'Ошибка при подсчете количества категорий в бд' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['author'])) {
//    Указание автора, если не получаеться выбираем все категории
    if (in_array($_GET['author'], $totalAuthor)) {
        $author = $_GET['author'];
    } else {
        $category = 'all';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['category'])) {
//    Категория по умолчанию
    if (in_array($_GET['category'], $totalCategory)) {
        $category = $_GET['category'];
    } else {
        $category = 'all';
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
    if (isset($author)) {
        $totalJokes = $pdo->query("SELECT COUNT(*) FROM jokes
                                             WHERE author_id = $author")->fetchColumn(0);
    } elseif ($category === 'all') {
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
    if (filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT,
        ['option' => ['min_range' => 0,
                      'max_range' => $pages]])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
} else {
    $page = 1;
}
$offset = ($page - 1) * 10;

try {
    //Извлечение шуток
    $sql = "SELECT * FROM jokes 
            INNER JOIN users ON jokes.author_id = users.user_id ";
    if (isset($author)) {
        $sql .= "WHERE jokes.author_id = $author ";
    } elseif ($category != 'all') {
        $sql .= "INNER JOIN joke_category ON jokes.joke_id = joke_category.joke_id
                 INNER JOIN categories ON joke_category.category_id = categories.category_id
                 WHERE joke_category.category_id = $category ";
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