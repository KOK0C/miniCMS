<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 27.09.2017
 * Time: 14:40
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/session_function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/validation_function.php';

if (empty($_SESSION['id'])) {
//    Не авторизованные пользователи - на главную
    redirect_to(DOMEN . 'public/');
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addjoke'])) {
//    Добавление шутки
    if ($errorAddJoke = validate_addJoke()) {
//        Если есть ошибку отправляем на повторное заполнение формы
        $_SESSION['errors'] = $errorAddJoke;
        redirect_to(DOMEN . 'public/admin/jokes/?addjoke');
    } else {
//        Если ошибок нету пытаемся добавить в бд
        try {
//            Записываем шутку
            $sql = 'INSERT INTO jokes (joke_text, author_id) VALUES (:joke_text, :user_id)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['joke_text' => $_POST['joke_text'], 'user_id' => $_SESSION['id']]);
        } catch (PDOException $e) {
            $error = 'Неудалось добавить шутку в базу данных: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        $jokeId = $pdo->lastInsertId();
        try {
//            Добавляем категории для шутки
            $sql = 'INSERT INTO joke_category (joke_id, category_id) VALUES (:joke_id, :category_id)';
            $stmt = $pdo->prepare($sql);
            foreach ($_POST['categories'] as $category) {
                $stmt->execute(['joke_id' => $jokeId, 'category_id' => $category]);
            }
        } catch (PDOException $e) {
            $error = 'Неудалось добавить категории для шутки: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
//        После добавления шутки возвращаемся на главную
        $_SESSION['message'] = 'Шутка успешно добавлена!';
        redirect_to(DOMEN . 'public/');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['addjoke'])) {
    try {
        $sql = 'SELECT * FROM categories ORDER BY category_name';
        $allCategories = $pdo->query($sql);
    } catch (PDOException $e) {
        $error = 'Неудалось извлеч категории из базы данных: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/jokes/jokes.phtml';
}
