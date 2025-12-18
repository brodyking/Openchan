<?php

// Set the response header to indicate JSON content
header('Content-Type: application/json');

if (isset($_POST["author"]) && isset($_POST["body"]) && isset($_POST["title"])) {

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
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
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
        $db = new PDO("sqlite:" . "database/main.db");
        // Optional: Set PDO to throw exceptions on error, which simplifies error handling
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. BEGIN A TRANSACTION
        $db->beginTransaction();

        // --- Creating the post ---

        $sql = "INSERT INTO posts (author,body,date,img) VALUES (:authorInput,:bodyInput,:dateInput,:imgInput);";
        $statement = $db->prepare($sql);

        $statement->bindValue(":authorInput", $_POST["author"], PDO::PARAM_STR);
        $statement->bindValue(":bodyInput", $_POST["body"], PDO::PARAM_STR);
        $statement->bindValue(":dateInput", date("m/d/Y"), PDO::PARAM_STR);
        $statement->bindValue(":imgInput", $imgpath, PDO::PARAM_STR);

        $statement->execute(); // Throws exception on failure due to ATTR_ERRMODE

        // Get the last insert ID using the *connection*
        $post_id = $db->lastInsertId();
        $content = json_encode([(int)$post_id]);


        // --- Creating the thread ---

        $sql = "INSERT INTO threads (title,author,content) VALUES (:titleInput,:authorInput,:contentInput);";
        $statement = $db->prepare($sql);

        $statement->bindValue(":titleInput", $_POST["title"], PDO::PARAM_STR);
        $statement->bindValue(":authorInput", $_POST["author"], PDO::PARAM_STR);
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
    echo json_encode(array("error" => true, "errormessage" => "Missing Params"));
}
