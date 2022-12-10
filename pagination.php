<?php

function displayPagination($page) {
    if (isset($_GET['page'])) {
        $pageNum = $_GET['page'];
        if ($pageNum > 2) {
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum - 1)) . '"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum - 2)) . '">' . ($pageNum - 2) . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum - 1)) . '">' . ($pageNum - 1) . '</a></li>';
            echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum)) . '">' . $pageNum . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum + 1)) . '">' . ($pageNum + 1) . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum + 2)) . '">' . ($pageNum + 2) . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', ($pageNum + 1)) . '"><i class="material-icons">chevron_right</i></a></li>';
        } else if ($pageNum == 2) {
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 1) . '"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 1) . '">1</a></li>';
            echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('page', 2) . '">2</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 3) . '">3</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 4) . '">4</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 5) . '">5</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 3) . '"><i class="material-icons">chevron_right</i></a></li>';
        } else {
            echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('page', 1) . '">1</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 2) . '">2</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 3) . '">3</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 4) . '">4</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 5) . '">5</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 2) . '"><i class="material-icons">chevron_right</i></a></li>';
        }
    } else {
        echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
        echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('page', 1) . '">1</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 2) . '">2</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 3) . '">3</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 4) . '">4</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 5) . '">5</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('page', 2) . '"><i class="material-icons">chevron_right</i></a></li>';
    }
}