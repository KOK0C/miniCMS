<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 25.09.2017
 * Time: 18:13
 */

function validate_signup() {
//    Валидация регистрации
    global $pdo;

    $errors = [];
    if (mb_strlen(trim($_POST['email'])) == 0 or mb_strlen(trim($_POST['password'])) == 0 or
        mb_strlen(trim($_POST['retry_password'])) == 0 or mb_strlen(trim($_POST['name'])) == 0 or
        mb_strlen(trim($_POST['surname'])) == 0) {
        $errors[] = 'Все поля должны быть заполнены';
    }
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors[] = 'Укажите корректный эмейл';
    }
    $sql = 'SELECT user_email FROM users WHERE user_email = :email';
    $findEmail = $pdo->prepare($sql);
    $findEmail->execute(['email' => $_POST['email']]);
    if ($findEmail->fetch()) {
        $errors[] = 'Такой эмейл уже зарегистрирован';
    }
    if (mb_strlen(trim($_POST['name'])) > 30) {
        $errors[] = 'Имя не должно быть больше 30 символов';
    }
    if (mb_strlen(trim($_POST['surname'])) > 30) {
        $errors[] = 'Фамилия не должна быть больше 30 символов';
    }
    if (mb_strlen(trim($_POST['name'])) < 2 or mb_strlen(trim($_POST['surname'])) < 2) {
        $errors[] = 'Имя и фамилия должны быть больше 2 символов';
    }
    if (! $_POST['password'] === $_POST['retry_password']) {
        $errors[] = 'Пароли должны совпадать!';
    }
    if ($errors) {
        $error = '<span>При заполнении формы обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
        return false;
    }
}

function validate_signin() {
//        Валидация входа
    global $pdo;

    $errors = [];
    if (mb_strlen(trim($_POST['email'])) == 0 or mb_strlen(trim($_POST['password'])) == 0) {
        $errors[] = 'Все поля должны быть заполнены';
    }
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors[] = 'Укажите корректный эмейл';
    }
    $sql = 'SELECT * FROM users INNER JOIN users_type ON users.user_type = users_type.user_type_id
            WHERE user_email = :email';
    $findUser = $pdo->prepare($sql);
    $findUser->execute(['email' => $_POST['email']]);
    $row = $findUser->fetch();
    if ((! $row) or ! (password_verify($_POST['password'], $row['hash_password']))) {
        $errors[] = 'Неверный эмейл или пароль';
    }
    if ($errors) {
        $error = '<span>При заполнении формы обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
//        Валидация пройдена - вход выполнен
        $_SESSION['id'] = $row['user_id'];
        $_SESSION['email'] = $row['user_email'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
        $_SESSION['type'] = $row['user_type_name'];
        return false;
    }
}