
<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$db = null;

// Get the raw POST data from the input stream
$jsonData = file_get_contents('php://input');

// Decode the JSON string into a PHP associative array
$data = json_decode($jsonData, true);

$threads = null;

if (isset($data["id"])) {
    try {
        // 1. Establish a single database connection
        $db = new PDO("sqlite:" . "database/main.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode for reliable exception handling

        // 2. Prepare the SQL statement to select all columns and rows from the threads table
        $sql = "SELECT * FROM threads WHERE id=:idInput";

        $statement = $db->prepare($sql);
        $statement->bindValue(":idInput", $data["id"], PDO::PARAM_STR);

        // 3. Execute the statement
        $statement->execute();

        // 4. Fetch all results as an associative array
        $threads = $statement->fetchAll(PDO::FETCH_ASSOC);


        $author = $threads[0]["author"];
        $id = $threads[0]["id"];
        $title = $threads[0]["title"];
        $content = json_decode($threads[0]["content"]);

        $replies = [];

        foreach ($content as $post) {

            $sql = "SELECT * FROM posts WHERE id=:idInput";

            $statement = $db->prepare($sql);
            $statement->bindValue(":idInput", $data["id"], PDO::PARAM_STR);

            $statement->execute();

            $postinfo = $statement->fetch(PDO::FETCH_ASSOC);

            array_push($replies, $postinfo);
        }

        $output = array(
            "author" => $author,
            "id" => $id,
            "title" => $title,
            "replies" => $replies
        );

        echo json_encode($output);
        die();
    } catch (PDOException $e) {
        // Log the actual error for debugging (e.g., error_log($e->getMessage());)

        // Return a generic error message to the client
        echo json_encode(array("error" => true, "errormessage" => "Database Error: Unable to fetch threads." . $e->getMessage()));
    } finally {
        // 6. Close the connection
        $db = null;
    }
}
