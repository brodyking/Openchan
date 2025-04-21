<!DOCTYPE html>
<html>

<head>
    <title>/b/ - Openchan</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body data-bs-theme="dark">
    <?php include "../elements/nav.php"; ?>
    <main>

        <div class="card">
            <h5 class="card-header">New Thread</h5>
            <div class="card-body">
                <form method="POST" action="rebuild.php">
                    <div class="mb-3">
                        <p class="mb-2">Thread Name *</p>
                        <input type="text" name="postTitle" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2">Username *</p>
                        <input type="text" name="postAuthor" value="Anonymous" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2">Post Content *</p>
                        <textarea type="text" name="postBody" value="Anonymous" class="form-control"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">Threads</h5>
            <div class="card-body">
                <ul class="list-group">
                    <?php

                    $threads = json_decode(file_get_contents("data/threads.json"), true);

                    foreach ($threads as $thread) {
                        $threadTitle = $thread["title"];
                        $threadLocation = $thread["location"];
                        echo "<li class='list-group-item'><a href='threads/{$threadLocation}.html'>{$threadTitle}</a></li>";
                    }

                    ?>
                </ul>
            </div>
        </div>
    </main>
</body>

</html>