

<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$db = null;

try {
    // 1. Establish a single database connection
    $db = new PDO("sqlite:" . "database/main.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode for reliable exception handling

    // 2. Prepare the SQL statement to select all columns and rows from the threads table
    $sql = "SELECT id FROM posts ORDER BY id DESC;";

    $statement = $db->prepare($sql);

    // 3. Execute the statement
    $statement->execute();

    // 4. Fetch all results as an associative array
    $postids = $statement->fetchAll(PDO::FETCH_ASSOC);

    $replies = [];

    foreach ($postids as $postid) {
        $sql = "SELECT * FROM posts WHERE id=:idInput";
        $statement = $db->prepare($sql);
        $statement->bindValue(":idInput", (int)$postid["id"], PDO::PARAM_INT);

        $statement->execute();

        $postinfo = $statement->fetch(PDO::FETCH_ASSOC);

        array_push($replies, $postinfo);
    }

    echo json_encode($replies);
} catch (PDOException $e) {
    // Log the actual error for debugging (e.g., error_log($e->getMessage());)

    // Return a generic error message to the client
    echo json_encode(array("error" => true, "errormessage" => "Failed to fatch threads"));
} finally {
    // 6. Close the connection
    $db = null;
    die();
}
