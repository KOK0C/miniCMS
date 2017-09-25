<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 25.09.2017
 * Time: 18:13
 */

function validate_signup() {
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
    } else {
        $error = '';
    }
    return $error;
}