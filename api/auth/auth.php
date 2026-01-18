<?php
$accounts = [["admin", "password"]];
function isCredCorrect($username, $password)
{
    global $accounts;
    foreach ($accounts as $account) {
        if ($account[0] == $username and $account[1] == $password) {
            return true;
        }
        return false;
    }
}
