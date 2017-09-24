<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.09.2017
 * Time: 18:07
 */

function print_link($inactive, $text, $page) {
    if ($inactive) {
        print "<span class='inactive'>$text</span>";
    } else {
        print "<span class='active'>".
            "<a href='?page=$page'>$text</a></span>";
    }
}

function indexed_links($page, $pages) {
    $separator = ' | ';
// Вывод ссылки "<<Prev"
    $prevPage = $page;
    print_link($page == 1,'<< Prev', max(1, --$prevPage));
// Вывод всех групп, кроме последней
    for ($i = 1; $i <= $pages; $i++) {
        print $separator;
        print_link($i == $page, $i, $i);
    }
// Вывод ссылки "Next>>"
    print $separator;
    $nextPage = $page;
    print_link($page == $pages, 'Next >>', min($pages, ++$nextPage));
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