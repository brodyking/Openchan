
<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

$db = null;

try {
    // 1. Establish a single database connection
    $db = new PDO("sqlite:" . "database/main.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode for reliable exception handling

    // 2. Prepare the SQL statement to select all columns and rows from the threads table
    $sql = "SELECT id, title, author, content, date, role FROM threads ORDER BY id DESC;";

    $statement = $db->prepare($sql);

    // 3. Execute the statement
    $statement->execute();

    // 4. Fetch all results as an associative array
    $threads = $statement->fetchAll(PDO::FETCH_ASSOC);

    // 5. Check if any threads were found
    if (empty($threads)) {
        // Return an empty array if no threads are found (a successful response)
        echo json_encode([]);
    } else {
        // Return the list of threads as a JSON array
        echo json_encode($threads);
    }
} catch (PDOException $e) {
    // Log the actual error for debugging (e.g., error_log($e->getMessage());)

    // Return a generic error message to the client
    echo json_encode(array("error" => true, "errormessage" => "Failed to fatch threads"));
} finally {
    // 6. Close the connection
    $db = null;
    die();
}
