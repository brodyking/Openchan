<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

include "auth.php";

if (!isSignedIn()) {
    die();
}

if (isset($_POST["postId"])) {

    $db = null;
    $content = null;
    $imgpath = null;

    try {
        // 1. USE A SINGLE CONNECTION FOR BOTH OPERATIONS
        $db = new PDO("sqlite:" . "../database/main.db");
        // Optional: Set PDO to throw exceptions on error, which simplifies error handling
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. BEGIN A TRANSACTION
        $db->beginTransaction();


        // --- Replace the value ---

        $sql = "UPDATE posts SET body = :contentInput, img = null WHERE id=:idInput";

        $statement = $db->prepare($sql);

        $statement->bindValue(":idInput", $_POST["postId"], PDO::PARAM_INT);
        $statement->bindValue(":contentInput", "[ Post deleted by admin ]", PDO::PARAM_STR);

        $statement->execute();

        // 3. COMMIT THE TRANSACTION TO RELEASE THE WRITE LOCK
        $db->commit();
    } catch (PDOException $e) {
        // 4. ROLLBACK ON ERROR
        if ($db && $db->inTransaction()) {
            $db->rollBack();
        }
        // You should log $e->getMessage() for debugging, but return a generic error to the user
        echo json_encode(array("error" => true, "errormessage" => "Unknown Error" . $e));
    } finally {
        // 5. Close the connection
        $db = null;
        die();
    }
} else {
    echo json_encode(array("error" => true, "errormessage" => "Missing Params"));
}
