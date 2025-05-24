<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="keywords" content="Photos, Viewer">
        <meta name="description" content="Photo Gallery">
        <meta name="author" content="Anon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../favicon.png">
        <title>Openchan /b/</title>
        <script defer src="../userstylesb.js"></script>
        <link rel="stylesheet" href="../styles/base.css">
        <?php
            $db = "database.html";
            $bn = "b";
        ?>
    </head>
    <body>
    <div id="nav">&nbsp;
        <span class='left'>
            <?php include '../nav.php';
            ?>
        </span>
        <span class="right">
        <select name="cars" id="userstyleselecter" style="margin-top: -5px;" onchange="userstyle();">
            <option value="Light">Light</option>
            <option value="Dark">Dark</option>
            <option value="Yotsuba">Yotsuba</option>
            <option value="Yotsuba B">Yotsuba B</option>
        </select>
        </span>
        </div>

        <div id="head">


            <h1>☆ Random ☆</h1>
                

            <form role="form" action="index.php" method="post" enctype="multipart/form-data">
                    <table>
                    <tr>
                    <td class="top">
                    Label
                    </td>
                    <td class="top">
                    Input
                    </td>
                    </tr>
                    <tr>
                    <td>
                    Name*
                    </td>
                    <td>
                    <input type = "text" class = "form-control" 
                    name="sendername" placeholder = "Name"
                    value="Anonymous" readonly required>
                    </td>
                    </tr>

                    <tr>
                    <td>
                    Post Content*
                    </td>
                    <td>
                    <textarea type='text' class = "form-control" 
                    name = "content" placeholder = "Content" 
                    required></textarea>
                    </td>
                    </tr>

                    <tr>
                    <td>
                    Image Upload
                    </td>
                    <td>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    </td>
                    </tr>

                    <tr>
                    <td>
                    Submit
                    </td>
                    <td>
                    <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
                    name = "submit">Submit</button>
                    <?php 
                    if (isset($_get['token']) && $_GET['token'] === "manage420") {
                        echo '<button class = "btn btn-lg btn-primary btn-block" type = "submit" 
                        name = "submitasmod"><rt>Mod Submit</rt></button>';
                    }
                    ?>
                    </td>
                    </tr>
                    </table>
                </form>


<?php
// Check if image file is a actual image or fake image
$target_file = null;
$uploadOk = 0;
if(isset($_POST["submit"])) {
    file_put_contents('postcount.txt',file_get_contents('postcount.txt') + 1);
    file_put_contents('../overchan/postcount.txt',file_get_contents('../overchan/postcount.txt') + 1);

    date_default_timezone_set("America/Chicago");
    file_put_contents('lastupdated.txt',date("m-d-y"));


    if (empty($_FILES['fileToUpload']['name'])) {
        $uploadOk = 0;
    } else {
    $target_dir = "data/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }



// Check file size
//if ($_FILES["fileToUpload"]["size"] > 500000) {
//  echo "Sorry, your file is too large.";
// $uploadOk = 0;
//}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}
    }
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    file_put_contents($db,'<table id="' . file_get_contents('postcount.txt') . '"><tr><td class="top"><span class="left"><b>Anonymous <pt>#'. file_get_contents('postcount.txt') . '</pt></span><span class="right"> ' . date("m-d-y") . ' ' . date("h:i a") . ' </span></b></td></tr><tr><td>' . htmlentities($_POST['content']) . '</td></tr></table>' . file_get_contents($db));
    
    // Overchan database
    file_put_contents('../overchan/database.html','<table><tr><td class="top"><span class="left"><a class="highlight overchanlink" href="../' . $bn . '">/' . $bn . '/</a> <b>Anonymous  <pt>#'. file_get_contents('postcount.txt') . '</pt></span><span class="right"> ' . date("m-d-y") . ' ' . date("h:i a") . ' </span></b></td></tr><tr><td>' . htmlentities($_POST['content']) . '</td></tr></table>' . file_get_contents('../overchan/database.html'));
    
    echo "<script>
    window.location.href = '" , htmlspecialchars($_SERVER['PHP_SELF']) , "'
    </script>";
    echo "<br><br>It seems like you have javascript disabled, which prevented a redirect.<br><br>Please <a href='board.php'>click here</a> to visit the board.";// if everything is ok, try to upload file
} else {
  if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "Sorry, there was an error uploading your file.";
  } else {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    file_put_contents($db,'<table id="' . file_get_contents('postcount.txt') . '"><tr><td class="top"><span class="left"><b>Anonymous  <pt>#'. file_get_contents('postcount.txt') . '</pt></span><span class="right"> ' . date("m-d-y") . ' ' . date("h:i a") . ' </span></b></td></tr><tr><td><img src="data/'  . basename($_FILES["fileToUpload"]["name"]) . '"><br><br>' . htmlentities($_POST['content']) . '</td></tr></table>' . file_get_contents($db));
    
    // Overchan databse
    file_put_contents('../overchan/database.html','<table><tr><td class="top"><span class="left"><a class="highlight overchanlink" href="../' . $bn . '">/' . $bn . '/</a> <b>Anonymous  <pt>#'. file_get_contents('postcount.txt') . '</pt> </span><span class="right"> ' . date("m-d-y") . ' ' . date("h:i a") . ' </span></b></td></tr><tr><td><img src="../' . $bn . '/data' . '/' . basename($_FILES["fileToUpload"]["name"]) . '"><br><br>' . htmlentities($_POST['content']) . '</td></tr></table>' . file_get_contents('../overchan/database.html'));

    echo "<script>
    window.location.href = '" , htmlspecialchars($_SERVER['PHP_SELF']) , "'
    </script>";
    echo "<br><br>It seems like you have javascript disabled, which prevented a redirect.<br><br>Please <a href='board.php'>click here</a> to visit the board.";  }
}  
    }
?>

<?php
                        echo file_get_contents($db);
                    ?>

        </div>

        <div id="content">
        </div>
        <div id="footer"></div>
        <div id="pageparam"><?php
            if (isset($_get['token'])) {
            echo $_GET['token'];
            }
        ?></div>
        
    </body>
</html>
