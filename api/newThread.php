<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Get the raw POST data from the input stream
$jsonData = file_get_contents('php://input');

// Decode the JSON string into a PHP associative array
$data = json_decode($jsonData, true);

if (isset($data["author"]) && isset($data["body"]) && isset($data["title"])) {

    $db = null;
    $content = null;

    try {
        // 1. USE A SINGLE CONNECTION FOR BOTH OPERATIONS
        $db = new PDO("sqlite:" . "database/main.db");
        // Optional: Set PDO to throw exceptions on error, which simplifies error handling
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. BEGIN A TRANSACTION
        $db->beginTransaction();

        // --- Creating the post ---

        $sql = "INSERT INTO posts (author,body,date) VALUES (:authorInput,:bodyInput,:dateInput);";
        $statement = $db->prepare($sql);

        $statement->bindValue(":authorInput", $data["author"], PDO::PARAM_STR);
        $statement->bindValue(":bodyInput", $data["body"], PDO::PARAM_STR);
        $statement->bindValue(":dateInput", date("m/d/Y"), PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure due to ATTR_ERRMODE

        // Get the last insert ID using the *connection*
        $post_id = $db->lastInsertId();
        $content = json_encode([(int)$post_id]);


        // --- Creating the thread ---

        $sql = "INSERT INTO threads (title,author,content) VALUES (:titleInput,:authorInput,:contentInput);";
        $statement = $db->prepare($sql);

        $statement->bindValue(":titleInput", $data["title"], PDO::PARAM_STR);
        $statement->bindValue(":authorInput", $data["author"], PDO::PARAM_STR);
        $statement->bindValue(":contentInput", $content, PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure

        $thread_id = $db->lastInsertId();

        // 3. COMMIT THE TRANSACTION TO RELEASE THE WRITE LOCK
        $db->commit();

        echo json_encode(array("success" => "Thread Created", "threadid" => $thread_id));
    } catch (PDOException $e) {
        // 4. ROLLBACK ON ERROR
        if ($db && $db->inTransaction()) {
            $db->rollBack();
        }
        // You should log $e->getMessage() for debugging, but return a generic error to the user
        echo json_encode(array("error" => true, "errormessage" => "Unknown Error"));
    } finally {
        // 5. Close the connection
        $db = null;
        die();
    }
} else {
    echo json_encode(array("error" => true, "errormessage" => "Unknown Error"));
}
