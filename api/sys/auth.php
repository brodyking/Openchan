<?php

include "accounts.php";

function isLoginAttempt()
{
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        if (isCredCorrect($_POST["username"], $_POST["password"])) {
            setcookie("sysUsername", $_POST["username"], time() + (86400 * 30), "/");
            setcookie("sysPassword", $_POST["password"], time() + (86400 * 30), "/");
            return true;
        }
    }
    return false;
}

function isSignedIn()
{
    if (isset($_COOKIE["sysUsername"]) && isset($_COOKIE["sysPassword"])) {
        if (isCredCorrect($_COOKIE["sysUsername"], $_COOKIE["sysPassword"])) {
            return true;
        }
    }
    return false;
}

function isSignoutAttempt()
{
    if (isset($_GET["signout"])) {
        setcookie("sysUsername", "", time() + (86400 * 30), "/");
        setcookie("sysPassword", "", time() + (86400 * 30), "/");
        return true;
    }
    return false;
}

function getCurrentUsername()
{
    if (isset($_POST["username"])) {
        return $_POST["username"];
    } else {
        return $_COOKIE["sysUsername"];
    }
}
