<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 25.09.2017
 * Time: 20:00
 */

session_start();

function session_errors() {
    $output = '<div class="error">';
    $output .= $_SESSION['errors'];
    $output .= '</div>';
    $_SESSION['errors'] = '';
    return $output;
}

function session_message() {
    $output = '<div class="message">';
    $output .= $_SESSION['message'];
    $output .= '</div>';
    $_SESSION['message'] = '';
    return $output;
}

function hello_block_authorization() {
    $output = '<div class="hello-block">';
    $output .= '<p>';
    $output .= 'Привет, ' . $_SESSION['name'] . ' ' . $_SESSION['surname'] . '<br>';
    $output .= 'Мы рады Вас видеть, и надеемся<br>';
    $output .= 'Вы не желаете <a href="' . DOMEN . 'public/login/?logout">выходить</a>';
    $output .= '</p>';
    $output .= '</div>';
    return $output;
}

function addJokeLink() {
    $output = '<div class="addjoke">';
    $output .= '<a href="' . DOMEN . 'public/admin/jokes/?addjoke">+ Добавить шутку</a>';
    $output .= '</div>';
    return $output;
}