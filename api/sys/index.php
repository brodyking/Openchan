<!DOCTYPE html>
<html>

<head>
    <title>Sys - Openchan</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/lib/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
</head>

<body>
    <nav id="nav"></nav>
    <main id="main">
        <?php

        require_once "auth.php";

        // If they are signed in, have had a successful login attempt, and are not trying to logout.
        if ((isSignedIn() || isLoginAttempt()) && !isSignoutAttempt()) {
            $username = getCurrentUsername();
            include "dash.php";
        } else {
            include "login.html";
        }

        ?>

    </main>
    <footer id="footer"></footer>
</body>

</html>