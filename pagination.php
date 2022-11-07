<?php

function displayPagination($page) {
    if (isset($_GET['pagePending'])) {
        $pageNum = $_GET['pagePending'];
        if ($pageNum > 2) {
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum - 1)) . '"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum - 2)) . '">' . ($pageNum - 2) . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum - 1)) . '">' . ($pageNum - 1) . '</a></li>';
            echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum)) . '">' . $pageNum . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum + 1)) . '">' . ($pageNum + 1) . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum + 2)) . '">' . ($pageNum + 2) . '</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', ($pageNum + 1)) . '"><i class="material-icons">chevron_right</i></a></li>';
        } else if ($pageNum == 2) {
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 1) . '"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 1) . '">1</a></li>';
            echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('pagePending', 2) . '">2</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 3) . '">3</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 4) . '">4</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 5) . '">5</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 3) . '"><i class="material-icons">chevron_right</i></a></li>';
        } else {
            echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
            echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('pagePending', 1) . '">1</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 2) . '">2</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 3) . '">3</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 4) . '">4</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 5) . '">5</a></li>';
            echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 2) . '"><i class="material-icons">chevron_right</i></a></li>';
        }
    } else {
        echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
        echo '<li class="active"><a href="' . $page . '?' . addQueryToURL('pagePending', 1) . '">1</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 2) . '">2</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 3) . '">3</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 4) . '">4</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 5) . '">5</a></li>';
        echo '<li class="waves-effect"><a href="' . $page . '?' . addQueryToURL('pagePending', 2) . '"><i class="material-icons">chevron_right</i></a></li>';
    }
}