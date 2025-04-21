<?php

if (isset($_POST["postTitle"]) && isset($_POST["postAuthor"]) && isset($_POST["postBody"])) {

    $postTitle = $_POST["postTitle"];
    $postAuthor = $_POST["postAuthor"];
    $postBody = $_POST["postBody"];
    $postLocation = "";

    $threads = file_get_contents("data/threads.json");
    $threads = json_decode($threads, true);

    // Check if post name is taken
    if (isset($threads[$postTitle])) {
        Header("Location: index.php?error=postnametaken");
    }

    foreach (str_split($postTitle) as $char) {
        if ($char != " ") {
            $postLocation = $postLocation . $char;
        } else {
            $postLocation = $postLocation . "+";
        }
    }

    $postData = ["title" => $postTitle, "author" => $postAuthor, "body" => $postBody, "location" => $postLocation];

    $threads[$postTitle] = $postData;

    $threads = json_encode($threads, JSON_PRETTY_PRINT);

    file_put_contents("data/threads.json", $threads);




    $post = file_get_contents("../elements/post.html");
    $post = str_replace("{postTitle}", $postTitle, $post);
    $post = str_replace("{postBody}", $postBody, $post);

    $thread = file_get_contents("../elements/thread.html");
    $thread = str_replace("{postTitle}", $postTitle, $thread);
    $thread = str_replace("{nav}", file_get_contents("../elements/nav.php"), $thread);
    $thread = str_replace("{postBody}", $post, $thread);

    file_put_contents("threads/{$postLocation}.html", $thread);

}

?>