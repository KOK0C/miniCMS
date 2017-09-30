<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 29.09.2017
 * Time: 14:29
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/session_function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/connection_db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/function.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/validation_function.php';

if (empty($_SESSION['id'])) {
//    Неавторизованые пользователи остаються на главной
    redirect_to(DOMEN . 'public/');
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
//    Редактирование пользователя
    if ($errorsEditUser = validate_editUser()) {
//        Проверка формы на ошибки
        $_SESSION['errors'] = $errorsEditUser;
        redirect_to(DOMEN . 'public/admin/users/?useredit=' . $_SESSION['id']);
    } else {
        $name = mb_ucfirst(mb_strtolower(trim($_POST['name'])));
        $surname = mb_ucfirst(mb_strtolower(trim($_POST['surname'])));
        try {
//            Обновление пользовательских данных
            $sql = 'UPDATE users SET user_email = :email, `name` = :first_name, surname = :surname 
                    WHERE user_id = ' . $_SESSION['id'];
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $_POST['email'], 'first_name' => $name, 'surname' => $surname]);
        } catch (PDOException $e) {
            $error = 'Неудалось обновить пользовательские данные в базе данных: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['message'] = 'Пользовательскте данные обновлены';
        redirect_to(DOMEN . '/public/admin/users/?user=' . $_SESSION['id']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_pass_user'])) {
//    Изменение пароля пользователя
    if ($errorsEditPass = validate_editUserPass()) {
//        Проверка формы на ошибки
        $_SESSION['errors'] = $errorsEditPass;
        redirect_to(DOMEN . 'public/admin/users/?userpassedit=' . $_SESSION['id']);
    } else {
        $hash_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        try {
            $sql = 'UPDATE users SET hash_password = :pass WHERE user_id = ' . $_SESSION['id'];
            $sql = $pdo->prepare($sql);
            $sql->execute(['pass' => $hash_pass]);
        } catch (PDOException $e) {
            $error = 'Неудалось обновить пароль в базе данных: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        $_SESSION['message'] = 'Пароль успешно изменен';
        redirect_to(DOMEN . 'public/admin/users/?user=' . $_SESSION['id']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['user']) && $_GET['user'] === $_SESSION['id'])) {
//    Личный кабинет
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/users/users.phtml';
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['useredit']) && $_GET['useredit'] === $_SESSION['id'])){
//    Смена пользовательских данных
    $header = 'Редактирование данных';
    $type = 'text';
    $nameOne = 'name';
    $firstInput = 'Укажите имя:';
    $firstValue = htmlentities($_SESSION['name']);
    $nameTwo = 'surname';
    $secondInput = 'Укажите фамилию:';
    $secondValue = htmlentities($_SESSION['surname']);
    $nameThree = 'email';
    $thirdInput = 'Введите эмейл:';
    $thirdValue = htmlentities($_SESSION['email']);
    $submitName = 'edit_user';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/users/useredit.phtml';
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['userpassedit']) && $_GET['userpassedit'] === $_SESSION['id'])) {
//    Смена пароля
    $header = 'Изменение пароля';
    $type = 'password';
    $nameOne = 'old_password';
    $firstInput = 'Введите старый пароль:';
    $nameTwo = 'new_password';
    $secondInput = 'Введите новый пароль:';
    $nameThree = 'check_new_password';
    $thirdInput = 'Повторите новый пароль:';
    $submitName = 'edit_pass_user';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/users/useredit.phtml';
} elseif ($_SESSION['type'] === 'admin' && $_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['alluser']) ||
                                                                                   isset($_GET['us_ed']))) {
//    Страница редактирования всех пользователей для админа
    try {
//        Извлечение всех пользователей из бд
        $sql = 'SELECT * FROM users INNER JOIN users_type ON users.user_type = users_type.user_type_id 
                ORDER BY user_type DESC';
        $allUsers = $pdo->query($sql);
    } catch (PDOException $e) {
        $error = 'Ошибка при извлечении пользователей из базы данных: ' . $e->getMessage();
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
        exit();
    }
    if (isset($_GET['us_ed'])) {
        try {
//            Статусы пользователя
            $sql = 'SELECT * FROM users_type';
            $allUserType = $pdo->query($sql);
        } catch (PDOException $e) {
            $error = 'Не удалось извлеч саисок статусов для пользователя: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        try {
//            Айдишники для проверки GET
            $sql = 'SELECT user_id FROM users';
            $stmt = $pdo->query($sql);
            $allUsersId = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $error = 'Не удалось извлеч айдишники авторов из бд: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        if (in_array($_GET['us_ed'], $allUsersId)) {
            $_SESSION['admin_edit_user_id'] = $_GET['us_ed'];
            $edit = true;
        } else {
            $edit = false;
        }
    } else {
        $edit = false;
    }
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/admin/users/allusers.phtml';
} elseif ($_SESSION['type'] === 'admin' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_edit_user'])) {
//    Редактирование пользователей админом
    if ($adminEditUserError = validate_adminEditUser()) {
//        Проверка формы на ошибки
        $_SESSION['errors'] = $adminEditUserError;
        redirect_to(DOMEN . 'public/admin/users/?alluser');
    } else {
//        Обновление пользователя
        $name = mb_ucfirst(mb_strtolower(trim($_POST['user_name'])));
        $surname = mb_ucfirst(mb_strtolower(trim($_POST['user_surname'])));
        try {
            $sql = 'UPDATE users SET `name` = :first_name, surname = :surname, user_email = :email, user_type = :type_id
                    WHERE user_id = ' . $_SESSION['admin_edit_user_id'];
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['first_name' => $name, 'surname' => $surname,
                            'email' => $_POST['user_email'], 'type_id' => $_POST['user_type']]);
        } catch (PDOException $e) {
            $error = 'Не удалось обновить данные об авторе: ' . $e->getMessage();
            include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/layouts/error.phtml';
            exit();
        }
        $_SESSION['admin_edit_user_id'] = '';
        $_SESSION['message'] = 'Информация о пользователе успешно обновлена';
        redirect_to(DOMEN . 'public/admin/users/?alluser');
    }
}
else {
    $_SESSION['errors'] = 'Зайти в личный кабинет не получилось';
    redirect_to(DOMEN . 'public/');
}