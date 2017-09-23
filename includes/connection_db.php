<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.09.2017
 * Time: 18:21
 */

try {
    //Подключение к БД
    $pdo = new PDO('mysql:host=localhost;dbname=joke_db', 'joke_user', '78932145');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('SET NAMES "utf8"');
} catch (PDOException $e) {
    $error = 'Ошибка при подключении к базе данных: ' . $e->getMessage();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/public/error.phtml';
    exit();
}