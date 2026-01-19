<?php
if (!isset($username)) {
    die();
}
?>
<h1>Openchan API + Sys</h1>

<!-- Account Information Post -->
<table style="width: 100%; max-width: 600px; margin:auto;margin-bottom:20px;">
    <thead>
        <tr>
            <td>Your Information</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                You are logged in as <b><?php echo $username ?></b><br>
                <a href="?signout">Signout</a>
            </td>
        </tr>
    </tbody>
</table>

<h2 style="text-align: center; border-bottom: 1px solid lab(100 0 0 / 0.1); max-width: 600px; margin: auto; padding-bottom: 5px; margin-bottom: 20px;">Actions</h2>

<!-- Delete Thread -->
<table style="width: 100%;max-width: 600px; margin:auto;margin-bottom:20px;">
    <thead>
        <tr>
            <td>Delete Thread</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                This will delete the thread, but will keep each individual post attached.
            </td>
        </tr>
        <tr>
            <td>
                <form method="POST">
                    <input type="number" placeholder="Post ID" name="postId">
                    <button type="submit" class="btn" name="submit">Delete</button>
                </form>
            </td>
        </tr>
    </tbody>
</table>

<!-- Delete Post -->
<table style="width: 100%;max-width: 600px; margin:auto;margin-bottom:20px;">
    <thead>
        <tr>
            <td>Delete Post</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                This feature blanks out all images and text in a post
            </td>
        </tr>
        <tr>
            <td>
                <form method="POST">
                    <input type="number" placeholder="Post ID" name="postId">
                    <button type="submit" class="btn" name="submit">Delete</button>
                </form>
            </td>
        </tr>
    </tbody>
</table>


<!-- Admin Reply -->
<table style="width: 100%;max-width: 600px; margin:auto;margin-bottom:20px;">
    <thead>
        <tr>
            <td>Admin Reply</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                This allows you to reply as an admin.
            </td>
        </tr>
        <tr>
            <td>
                <form role="form" id="newReplyForm">
                    <table>
                        <thead>
                            <tr>
                                <td class="top">
                                    Label
                                </td>
                                <td class="top">
                                    Input
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Thread #
                                </td>
                                <td>
                                    <input type="number" name="title" placeholder="0" required id="newReplyId">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Name*
                                </td>
                                <td>
                                    <input type="text" name="author" placeholder="author" value="Admin" required id="newReplyAuthor">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Post Content*
                                </td>
                                <td>
                                    <textarea type="text" cols="50" rows="8" name="content" placeholder="Content" required id="newReplyContent"></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Image Upload
                                </td>
                                <td>
                                    <input type="file" name="fileToUpload" id="newThreadImage">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Submit
                                </td>
                                <td>
                                    <button type="submit" class="btn" id="newReplySubmit" name="submit">Submit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </td>
        </tr>
    </tbody>
</table>


<!-- Admin Post -->
<table style="width: 100%;max-width: 600px; margin:auto;margin-bottom:20px;">
    <thead>
        <tr>
            <td>Admin Post</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                This allows you to post a thread as admin.
            </td>
        </tr>
        <tr>
            <td>

                <form role="form" id="newThreadForm">
                    <table>
                        <thead>
                            <tr>
                                <td class="top">
                                    Label
                                </td>
                                <td class="top">
                                    Input
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Thread Title*
                                </td>
                                <td>
                                    <input type="text" name="title" placeholder="Title" required id="newThreadTitle">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Board*
                                </td>
                                <td>
                                    <select name="board" id="newThreadBoard" required>
                                        <option value="">Select a board</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Name*
                                </td>
                                <td>
                                    <input type="text" name="author" placeholder="author" value="Admin" required id="newThreadAuthor">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Post Content*
                                </td>
                                <td>
                                    <textarea type="text" cols="50" rows="8" name="content" placeholder="Content" required
                                        id="newThreadContent"></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Image Upload
                                </td>
                                <td>
                                    <input type="file" name="fileToUpload" id="newThreadImage">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Submit
                                </td>
                                <td>
                                    <button type="submit" class="btn" id="newThreadSubmit" name="submit">Submit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </td>
        </tr>
    </tbody>
</table>