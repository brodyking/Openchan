
<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Get the raw POST data from the input stream
$jsonData = file_get_contents('php://input');

// Decode the JSON string into a PHP associative array
$data = json_decode($jsonData, true);

if (isset($data["author"]) && isset($data["body"]) && isset($data["threadId"])) {

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


        // --- Find the thread  ---

        $sql = "SELECT * FROM threads WHERE id=:idInput";
        $statement = $db->prepare($sql);

        $statement->bindValue(":idInput", $data["threadId"], PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure

        $r = $statement->fetch(PDO::FETCH_ASSOC);

        $replies = json_decode($r["content"]);

        array_push($replies, (int)$post_id);

        $replies = json_encode($replies);

        // --- Replace the value ---

        $sql = "UPDATE threads SET content = :repliesInput WHERE id=:idInput";

        $statement = $db->prepare($sql);

        $statement->bindValue(":idInput", $data["threadId"], PDO::PARAM_STR);
        $statement->bindValue(":repliesInput", $replies, PDO::PARAM_STR);

        $statement->execute();

        if ($statement) {
            echo json_encode(array("success" => "Reply Created", "threadid" => $data["threadId"]));
        } else {
            echo json_encode(array("error" => true, "errormessage" => "Unknown Error"));
        }

        // 3. COMMIT THE TRANSACTION TO RELEASE THE WRITE LOCK
        $db->commit();
    } catch (PDOException $e) {
        // 4. ROLLBACK ON ERROR
        if ($db && $db->inTransaction()) {
            $db->rollBack();
        }
        // You should log $e->getMessage() for debugging, but return a generic error to the user
        echo json_encode(array("error" => true, "errormessage" => "Unknown Error" . $e->getMessage()));
    } finally {
        // 5. Close the connection
        $db = null;
        die();
    }
} else {
    echo json_encode(array("error" => true, "errormessage" => "Missing Params"));
}
