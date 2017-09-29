<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.09.2017
 * Time: 11:38
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/session_function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/validation_function.php';

if ($_SESSION['type'] !== 'admin') {
//    Только для админов
    redirect_to(DOMEN . 'public/');
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['addcategory']) || isset($_POST['updatecategory']))) {
//    Добавление категории
    if (isset($_POST['addcategory'])) {
//        Проверка формы на ошибки
        if ($errorAddCategory = validate_Category()) {
            $_SESSION['errors'] = $errorAddCategory;
            redirect_to(DOMEN . 'public/admin/categories/?addcategory');
        } else {
//            Добавление категории
            try {
                $sql = 'INSERT INTO categories (category_name) VALUE (:category_name)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['category_name' => $_POST['category']]);
            } catch (PDOException $e) {
                $error = 'Неудалось добавить категорию в бд: ' . $e->getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
                exit();
            }
            $_SESSION['message'] = 'Новая категоря создана!';
            redirect_to(DOMEN . '/public');
        }
    } elseif (isset($_POST['updatecategory'])) {
//        Редактирование категории
        if ($errorUpdateCategory = validate_Category()) {
//            Проверка формы на ошибки
            $_SESSION['errors'] = $errorUpdateCategory;
            redirect_to(DOMEN . 'public/admin/categories/?updatecategory=' . $_SESSION['category_update']);
        } else {
            try {
                $sql = 'UPDATE categories SET category_name = :category_name WHERE category_id = ' . $_SESSION['category_update'];
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['category_name' => $_POST['category']]);
            } catch (PDOException $e) {
                    $error = 'Неудалось обновить категорию в бд: ' . $e->getMessage();
                    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
                    exit();
            }
            $_SESSION['category_update'] = '';
            $_SESSION['message'] = 'Категория обновлена';
            redirect_to(DOMEN . 'public/');
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['addcategory']) || isset($_GET['updatecategory'])
                                                  || isset($_GET['deletecategory']))) {
    if (isset($_GET['addcategory'])) {
        $header = 'Добавление категории';
        $label = 'Укажите название категории:';
        $updateValue = '';
        $operation = 'addcategory';
        $nameOperation = 'Добавить категорию';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/categories/category.phtml';
    } elseif (isset($_GET['updatecategory'])) {
        try {
            // Выбор id существующих категорий для проверки
            $sql = 'SELECT category_id FROM categories';
            $totalCategories = $pdo->query($sql);
            $totalCategories = $totalCategories->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $error = 'Ошибка при подсчете категорий в бд: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        if (in_array($_GET['updatecategory'], $totalCategories)) {
//            Проверка корректного id категории
            $_SESSION['category_update'] = $_GET['updatecategory'];
            try {
//                Извлечение необходимой категории
                $sql = 'SELECT category_name FROM categories WHERE category_id = ' . $_SESSION['category_update'];
                $selectCategory = $pdo->query($sql);
                $selectCategory = $selectCategory->fetch();
            } catch (PDOException $e) {
                $error = 'Ошибка при извлечении категории из бд: ' . $e->getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
                exit();
            }
            $header = 'Редактирование категории';
            $label = 'Укажите название категории:';
            $updateValue = $selectCategory['category_name'];
            $operation = 'updatecategory';
            $nameOperation = 'Редактировать категорию';
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/categories/category.phtml';
        } else {
            $_SESSION['errors'] = 'Вы пытаетесь редактировать не существующюю категорию';
            redirect_to(DOMEN . 'public/');
        }
    } elseif (isset($_GET['deletecategory'])) {
//        Удаление категории
        try {
            // Выбор id существующих категорий для проверки
            $sql = 'SELECT category_id FROM categories';
            $totalCategories = $pdo->query($sql);
            $totalCategories = $totalCategories->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $error = 'Ошибка при подсчете категорий в бд: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        if (in_array($_GET['deletecategory'], $totalCategories)) {
//            Проверка корректного id категории
            try {
//                Удаление связи категории с шутками
                $sql = 'DELETE FROM joke_category WHERE category_id = ' . $_GET['deletecategory'];
                $stmt = $pdo->exec($sql);
            } catch (PDOException $e) {
                $error = 'Ошибка при удалении связи категории с шутками из бд: ' . $e->getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
                exit();
            }
            try {
//                Удаление категории
                $sql = 'DELETE FROM categories WHERE category_id = ' . $_GET['deletecategory'];
                $stmt = $pdo->exec($sql);
            } catch (PDOException $e) {
                $error = 'Ошибка при удалении категоии из бд: ' . $e->getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
                exit();
            }
            $_SESSION['message'] = 'Категория успешно удалена';
            redirect_to(DOMEN . 'public/');
        } else {
            $_SESSION['errors'] = 'Вы пытаетесь удалить не существующюю категорию';
            redirect_to(DOMEN . 'public/');
        }
    }
} else {
    redirect_to(DOMEN . 'public/');
}