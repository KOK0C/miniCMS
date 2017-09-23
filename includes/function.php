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