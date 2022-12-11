<?php

function addQueryToURL($query, $queryValue) {
    $url = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url_parts = parse_url($url);
    if (isset($url_parts['query'])) {
        parse_str($url_parts['query'], $params);
    } else {
        $params = array();
    }

    $params[$query] = $queryValue;

    $url_parts['query'] = http_build_query($params);

    return $url_parts['query'];
}

// Function to clean possibly dangerous input
function test_input($data): string {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}