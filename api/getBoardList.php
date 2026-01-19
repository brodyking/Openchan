
<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$db = null;

if (isset($_POST["board"])) {
    try {
        // 1. Establish a single database connection
        $db = new PDO("sqlite:" . "database/main.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode for reliable exception handling

        // 2. Prepare the SQL statement to select all columns and rows from the threads table
        $sql = "SELECT * FROM boards WHERE title=:boardInput";

        $statement = $db->prepare($sql);

        $statement->bindValue(":boardInput", $_POST["board"], PDO::PARAM_STR);

        // 3. Execute the statement
        $statement->execute();

        // 4. Fetch all results as an associative array
        $threads = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$threads) {
            die();
        }

        $threads = json_decode($threads["threads"]);

        $threadsout = array();


        foreach ($threads as $thread) {
            $sql = "SELECT id, title, author, content, date FROM threads WHERE id=:idInput";
            $statement = $db->prepare($sql);
            $statement->bindValue(":idInput", (int)$thread, PDO::PARAM_INT);
            $statement->execute();
            $r = $statement->fetch(PDO::FETCH_ASSOC);
            array_push($threadsout, $r);
        }
        echo json_encode(array_reverse($threadsout));
    } catch (PDOException $e) {
        // Log the actual error for debugging (e.g., error_log($e->getMessage());)

        // Return a generic error message to the client
        echo json_encode(array("error" => true, "errormessage" => "Failed to fatch threads" . $e->getMessage()));
    } finally {
        // 6. Close the connection
        $db = null;
        die();
    }
} else {
    echo json_encode(array("error" => true, "errormessage" => "Missing Params"));
}
