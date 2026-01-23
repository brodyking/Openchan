<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

include "auth.php";

if (!isSignedIn()) {
    die();
}

if (isset($_POST["author"]) && isset($_POST["body"]) && isset($_POST["title"]) && isset($_POST["board"])) {

    $db = null;
    $content = null;
    $imgpath = null;

    // If an image was uploaded
    if (isset($_FILES["img"])) {
        $file = $_FILES['img'];

        // 1. Setup destination
        $upload_directory = 'database/img/';
        $file_name = time() . '_' . basename($file['name']);
        $target_path = $upload_directory . $file_name;

        // Verify integrity of file
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);
        $allowed_types = [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp",
        ];

        if (!in_array($mime_type, $allowed_types)) {
            echo json_encode(array("error" => true, "errormessage" => "Invalid file type. Allowed  formats are jpeg, png, gif, and webp."));
            die();
        }
        // 2. Validate for errors
        if ($file['error'] === UPLOAD_ERR_OK) {
            // 3. Move the file from temp storage to your folder
            if (move_uploaded_file($file['tmp_name'], "../" . $target_path)) {
                $imgpath = $target_path;
            } else {
                echo json_encode(array("error" => true, "errormessage" => "Image upload failed"));
                die();
            }
        } else {
            echo json_encode(array("error" => true, "errormessage" => "Image upload failed"));
            die();
        }
    }

    try {
        // 1. USE A SINGLE CONNECTION FOR BOTH OPERATIONS
        $db = new PDO("sqlite:" . "../database/main.db");
        // Optional: Set PDO to throw exceptions on error, which simplifies error handling
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. BEGIN A TRANSACTION
        $db->beginTransaction();

        // --- Creating the post ---

        $sql = "INSERT INTO posts (author,body,date,img,role) VALUES (:authorInput,:bodyInput,:dateInput,:imgInput,:roleInput);";
        $statement = $db->prepare($sql);

        $statement->bindValue(":authorInput", $_POST["author"], PDO::PARAM_STR);
        $statement->bindValue(":bodyInput", $_POST["body"], PDO::PARAM_STR);
        $statement->bindValue(":dateInput", date("m/d/Y"), PDO::PARAM_STR);
        $statement->bindValue(":imgInput", $imgpath, PDO::PARAM_STR);
        $statement->bindValue(":roleInput", "admin", PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure due to ATTR_ERRMODE

        // Get the last insert ID using the *connection*
        $post_id = $db->lastInsertId();
        $content = json_encode([(int)$post_id]);


        // --- Creating the thread ---

        $sql = "INSERT INTO threads (title,author,content,date) VALUES (:titleInput,:authorInput,:contentInput, :dateInput)";
        $statement = $db->prepare($sql);

        $statement->bindValue(":titleInput", $_POST["title"], PDO::PARAM_STR);
        $statement->bindValue(":authorInput", $_POST["author"], PDO::PARAM_STR);
        $statement->bindValue(":contentInput", $content, PDO::PARAM_STR);
        $statement->bindValue(":dateInput", date("m/d/Y"), PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure

        $thread_id = $db->lastInsertId();

        // --- Get board ---

        $sql = "SELECT * FROM boards WHERE title=:boardInput";
        $statement = $db->prepare($sql);

        $statement->bindValue(":boardInput", $_POST["board"], PDO::PARAM_STR);

        $statement->execute();

        $r = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$r) {
            die();
        }

        $threads = json_decode($r["threads"]);

        array_push($threads, (int)$thread_id);

        $threads = json_encode($threads);

        // --- Add thread to board ---

        $sql = "UPDATE boards SET threads = :threadsInput WHERE title=:boardInput";
        $statement = $db->prepare($sql);

        $statement->bindValue(":threadsInput", $threads, PDO::PARAM_STR);
        $statement->bindValue(":boardInput", $_POST["board"], PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure


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
    echo json_encode(array("error" => true, "errormessage" => "Missing Params"));
}
