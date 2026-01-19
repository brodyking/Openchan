<?php
$accounts = [
    [
        "username" => "admin",
        "password" => "password",
        "type" => "admin"
    ]
];
function isCredCorrect($username, $password)
{
    global $accounts;
    foreach ($accounts as $account) {
        if ($account["username"] == $username and $account["password"] == $password) {
            return true;
        }
        return false;
    }
}
