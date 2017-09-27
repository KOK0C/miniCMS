<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.09.2017
 * Time: 18:07
 */

function print_link($inactive, $text, $page, $pages) {
    if ($inactive && ($text === '<< Prev' || $text === 'Next >>')) {
        print '';
    } elseif ($inactive) {
        print "<span class='inactive'>$text</span>";
    } elseif ($pages != 0) {
        print "<span class='active'>".
            "<a href='?page=$page'>$text</a></span>";
    }
}

function indexed_links($page, $pages) {
    $separator = ' | ';
// Вывод ссылки "<<Prev"
    $prevPage = $page;
    print_link($page == 1,'<< Prev', max(1, --$prevPage), $pages);
// Вывод всех групп, кроме последней
    for ($i = 1; $i <= $pages; $i++) {
        print $separator;
        print_link($i == $page, $i, $i, $pages);
    }
// Вывод ссылки "Next>>"
    print $separator;
    $nextPage = $page;
    print_link($page == $pages, 'Next >>', min($pages, ++$nextPage), $pages);
}

function categories_for_joke($joke_id) {
//    Извлечение всех категорий для шутки
    global  $pdo;

    $categories = [];
    $sql = "SELECT category_name, categories.category_id FROM categories 
            INNER JOIN joke_category ON categories.category_id = joke_category.category_id 
            WHERE joke_category.joke_id = " . $joke_id;
    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        $categories[] = '<a href="?category=' . $row['category_id'] . '" class="category-page-link">' . $row['category_name'] . '</a>';
    }
    $output = implode(', ', $categories);
    return $output;
}

function redirect_to($new_location) {
//    Переадресация
    header('Location: ' . $new_location);
    exit();
}

function mb_ucfirst($string, $encoding = 'UTF-8')
    #Поскольку в PHP нету мультибайтового аналога ucfirst(), написал свой.
    #Переводит первый символ строки в верхний регистр
{
    return mb_strtoupper(mb_substr($string, 0, 1, $encoding)) .
        mb_substr($string, 1, mb_strlen($string, $encoding),$encoding);
}

function hello_block() {
//    Блок приветствия если пользователь не авторизован.
    $output = '<div class="hello-block">';
    $output .= '<p>';
    $output .= 'Привет, Гость! <br>';
    $output .= 'Желаешь <a href="' . DOMEN . 'public/login/?signin">войти</a><br>';
    $output .= 'или <a href="' . DOMEN . 'public/login/?signup">зарегистрироваться</a>?';
    $output .= '</p>';
    $output .= '</div>';
    return $output;
}