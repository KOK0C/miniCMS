<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 25.09.2017
 * Time: 16:13
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/session_function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/validation_function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
//    Регистрация нового пользователя
    if ($errorSignUp = validate_signup()) {
//        Проверка формы на ошибки, если есть ошибки отправляем заполнять заного
        $_SESSION['errors'] = $errorSignUp;
        redirect_to(DOMEN . '/public/login/?signup');
    } else {
//        После добавления пользователя в базу данных направляем его на вход
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $name = mb_ucfirst(mb_strtolower($_POST['name']));
        $surname = mb_ucfirst(mb_strtolower($_POST['surname']));
        try {
            $sql = 'INSERT INTO users (user_email, hash_password, `name`, surname)
                VALUES (:email, :password, :first_name, :surname)';
            $addUser = $pdo->prepare($sql);
            $addUser->execute(['email' => $_POST['email'], 'password' => $hashed_password,
                'first_name' => $name, 'surname' => $surname]);
            $_SESSION['message'] = 'Поздравляем с регистрацией ' . mb_ucfirst(mb_strtolower($_POST['name'])) . ', теперь можете войти';
            redirect_to(DOMEN . 'public/login/?signin');
        } catch (PDOException $e) {
            $error = 'Неудалось добавить нового пользователя в базу данных: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['signin']) || isset($_GET['signup']))) {
//    Направление пользователя на регистрацию либо на вход
    if (isset($_GET['signin'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/login/signin.phtml';
    } elseif (isset($_GET['signup'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/login/signup.phtml';
    } else {
        redirect_to(DOMEN . 'public/');
    }
} else {
    redirect_to(DOMEN . 'public/');
}
