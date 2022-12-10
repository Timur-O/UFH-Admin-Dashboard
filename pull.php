<?php

function verify_signature($body, $secret) {
    $headers = getallheaders();
    return hash_equals('sha256='.hash_hmac('sha256', $body, $secret), $headers['X-Hub-Signature-256']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Declare variables from config.php
     *
     * @var $github_secret_webhook string The secret to authenticate the webhook
     */
    include_once('config.php');

    $body = file_get_contents('php://input');

    if (verify_signature($body, $github_secret_webhook)) {
        echo "\n Verified Signature! \n";
        if (json_decode($body, true)['ref'] == 'refs/heads/main') {
            echo "Verified Branch! \n";
            echo shell_exec("sudo -u www-data git reset --hard && sudo -u www-data git pull 2>&1") . "\n";
        }
    }
}