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
    if ($errorAddJoke = validate_Joke()) {
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
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatejoke'])) {
//    Редактирование шутки
    if ($errorAddJoke = validate_Joke()) {
//        Если есть ошибку отправляем на повторное заполнение формы
        $_SESSION['errors'] = $errorAddJoke;
        redirect_to(DOMEN . 'public/admin/jokes/?updatejoke=' . $_SESSION['updatejoke']);
    } else {
//        Если нет ошибок редактируем шутку в бд
        try {
//            Сначала удаляем старые категории шутку
            $sql = 'DELETE FROM joke_category WHERE joke_id = ' . $_SESSION['updatejoke'];
            $stmt = $pdo->exec($sql);
        } catch (PDOException $e) {
            $error = 'Неудалось удалить категории для шутки: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        try {
//            Обновляем текст шутки
            $sql = 'UPDATE jokes SET joke_text = :joke_text, joke_date = CURRENT_TIMESTAMP WHERE joke_id = :joke_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['joke_text' => $_POST['joke_text'], 'joke_id' => $_SESSION['updatejoke']]);
        } catch (PDOException $e) {
            $error = 'Неудалось обновить текст для шутки: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        try {
//            Добавляем новые категории для шутки
            $sql = 'INSERT INTO joke_category (joke_id, category_id) VALUES (:joke_id, :category_id)';
            $stmt = $pdo->prepare($sql);
            foreach ($_POST['categories'] as $category) {
                $stmt->execute(['joke_id' => $_SESSION['updatejoke'], 'category_id' => $category]);
            }
        } catch (PDOException $e) {
            $error = 'Неудалось добавить категории для шутки: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
//        После редактирования шутки, отправляемся на главную
        $_SESSION['message'] = 'Шутка успешно обновлена!';
        redirect_to(DOMEN . 'public/');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['addjoke']) || isset($_GET['updatejoke']))) {
    try {
//        Выбор категорий для заполнения формы
        $sql = 'SELECT * FROM categories ORDER BY category_name';
        $allCategories = $pdo->query($sql);
    } catch (PDOException $e) {
        $error = 'Неудалось извлеч категории из базы данных: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
    if (isset($_GET['addjoke'])) {
//        Форма добавления шутки
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/jokes/addjokes.phtml';
    } elseif (isset($_GET['updatejoke'])) {
        try {
            // Выбор id существующих шуток для проверки
            $sql = 'SELECT joke_id FROM jokes';
            if ($_SESSION['type'] === 'user') {
                $sql .=  ' WHERE author_id = ' . $_SESSION['id'];
            }
            $totalJoke = $pdo->query($sql);
            $totalJoke = $totalJoke->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $error = 'Ошибка при подсчете количества шуток автора в бд: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        if (in_array($_GET['updatejoke'], $totalJoke)) {
//            После проверки достаем текс и категории выбраной шутки для редактирования
            $_SESSION['updatejoke'] = $_GET['updatejoke'];
            try {
                $sql = "SELECT * FROM jokes 
                        INNER JOIN joke_category ON jokes.joke_id = joke_category.joke_id
                        WHERE jokes.joke_id = " . $_SESSION['updatejoke'];
                $selectJoke = $pdo->query($sql);
                while ($row = $selectJoke->fetch()) {
                    $selectJokeText = $row['joke_text'];
                    $selectCategory[] = $row['category_id'];
                }
            } catch (PDOException $e) {
                $error = 'Ошибка при извлечении редактируемой шутки из бд: ' . $e->getMessage();
                include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
                exit();
            }
//            Форма для редактирования
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/jokes/updatejoke.phtml';
        } else {
            $_SESSION['errors'] = 'Вы пытаетесь редактрировать не существующюю шутку';
            redirect_to(DOMEN . 'public/');
        }
    }
}