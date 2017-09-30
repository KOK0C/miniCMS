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
    try {
        $sql = 'SELECT user_email FROM users WHERE user_email = :email';
        $findEmail = $pdo->prepare($sql);
        $findEmail->execute(['email' => $_POST['email']]);
    } catch (PDOException $e) {
        $error = 'Неудалось обратиться к бд за эмейлом при проверке формы: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }

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
    if ($_POST['password'] !== $_POST['retry_password']) {
        $errors[] = 'Пароли должны совпадать!';
    }
    if ($errors) {
        $error = '<span>При регистрации обнаружены ошибки:</span><br><ul><li>';
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
    try {
        $sql = 'SELECT * FROM users INNER JOIN users_type ON users.user_type = users_type.user_type_id
            WHERE user_email = :email';
        $findUser = $pdo->prepare($sql);
        $findUser->execute(['email' => $_POST['email']]);
    } catch (PDOException $e) {
        $error = 'Неудалось обратиться к бд за данными при проверке формы: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
    $row = $findUser->fetch();
    if ((! $row) or ! (password_verify($_POST['password'], $row['hash_password']))) {
        $errors[] = 'Неверный эмейл или пароль';
    }
    if ($errors) {
        $error = '<span>Во время входа обнаружены ошибки:</span><br><ul><li>';
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

function validate_Joke(){
    $errors = [];
    if (mb_strlen(trim($_POST['joke_text'])) == 0) {
        $errors[] = 'Напишите шутку';
    }
    if (empty($_POST['categories'])) {
        $errors[] = 'Выберите категорию для шутки';
    }
    if ($errors) {
        $error = '<span>При добавлении шутки обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
        return false;
    }
}

function validate_Category() {
    $errors = [];
    if (mb_strlen(trim($_POST['category'])) == 0) {
        $errors[] = 'Поле не должно быть пустым';
    }
    if (is_numeric($_POST['category'])) {
        $errors[] = 'Категория не может быть числом';
    }
    if ($errors) {
        $error = '<span>При работе с категорией обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
        return false;
    }
}

function validate_editUser() {
    global $pdo;

    $errors = [];
    if (mb_strlen(trim($_POST['email'])) == 0  or mb_strlen(trim($_POST['name'])) == 0 or
        mb_strlen(trim($_POST['surname'])) == 0) {
        $errors[] = 'Все поля должны быть заполнены';
    }
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors[] = 'Укажите корректный эмейл';
    }
    try {
        $sql = 'SELECT user_email FROM users WHERE user_email = :email';
        $findEmail = $pdo->prepare($sql);
        $findEmail->execute(['email' => $_POST['email']]);
    } catch (PDOException $e) {
        $error = 'Неудалось обратиться к бд за эмейлом при проверке формы: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
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
    if ($errors) {
        $error = '<span>При редактировании пользовательских данных обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
        return false;
    }
}

function validate_editUserPass() {
    global $pdo;

    $errors = [];
    if (mb_strlen(trim($_POST['old_password'])) == 0  or mb_strlen(trim($_POST['new_password'])) == 0 or
        mb_strlen(trim($_POST['check_new_password'])) == 0) {
        $errors[] = 'Все поля должны быть заполнены';
    }
    try {
        $sql = 'SELECT hash_password FROM users WHERE user_id = :id';
        $findUser = $pdo->prepare($sql);
        $findUser->execute(['id' => $_SESSION['id']]);
    } catch (PDOException $e) {
        $error = 'Неудалось обратиться к бд за паролем при проверке формы: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
    $hash_password = $findUser->fetch();
    $hash_password = $hash_password['hash_password'];
    if (! (password_verify($_POST['old_password'], $hash_password))) {
        $errors[] = 'Неверный пароль';
    }
    if ($_POST['new_password'] !== $_POST['check_new_password']) {
        $errors[] = 'Пароли не совпадают';
    }
    if ($errors) {
        $error = '<span>При смене пароля обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
        return false;
    }
}

function validate_adminEditUser() {
    global $pdo;

    $errors = [];
    if (mb_strlen(trim($_POST['user_email'])) == 0  or mb_strlen(trim($_POST['user_name'])) == 0 or
        mb_strlen(trim($_POST['user_surname'])) == 0) {
        $errors[] = 'Все поля должны быть заполнены';
    }
    $email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors[] = 'Укажите корректный эмейл';
    }
    try {
        $sql = 'SELECT user_type_id FROM users_type';
        $stmt = $pdo->query($sql);
        $allUserTypeId = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $error = 'Неудалось обратиться к бд за id статусов проверке формы: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
    if (! in_array($_POST['user_type'], $allUserTypeId)) {
        $errors[] = 'Выбран не существующий статус';
    }
    if (mb_strlen(trim($_POST['user_name'])) > 30) {
        $errors[] = 'Имя не должно быть больше 30 символов';
    }
    if (mb_strlen(trim($_POST['user_surname'])) > 30) {
        $errors[] = 'Фамилия не должна быть больше 30 символов';
    }
    if (mb_strlen(trim($_POST['user_name'])) < 2 or mb_strlen(trim($_POST['user_surname'])) < 2) {
        $errors[] = 'Имя и фамилия должны быть больше 2 символов';
    }
    if ($errors) {
        $error = '<span>При редактировании пользователя обнаружены ошибки:</span><br><ul><li>';
        $error .= implode('</li><li>', $errors);
        $error .= '</li></ul>';
        return $error;
    } else {
        return false;
    }
}