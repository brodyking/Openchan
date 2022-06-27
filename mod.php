<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="keywords" content="Photos, Viewer">
        <meta name="description" content="Photo Gallery">
        <meta name="author" content="Anon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="favicon.png">
        <title>Openchan /b/</title>
        <script defer src="userstyles.js"></script>
        <link rel="stylesheet" href="styles/base.css">
    </head>
    <body>
    <div id="nav">&nbsp;
        <span class='left'>
            <?php include 'nav.php';
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


            <h1>Manage</h1>
                

            <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post" id="form">

            <table style='width: auto;'>
                    <tr>
                    <td class="top">
                    Login
                    </td>
                    </tr>
                    <tr>
                    <td>
                    <input type = "text" class = "form-control" 
            name = "username" placeholder = "username" 
            required><br>
                    <input type = "password" class = "form-control"
            name = "password" placeholder = "password" required><br>
                    <button style="width: 100%;" class = "btn btn-lg btn-primary btn-block" type = "submit" 
            name = "login">Login</button>
                    </td>
                    </tr>
                    </table>
            </form>
        </div>



<?php
                       // echo file_get_contents($db);
                    ?>

        </div>

        <div id="content">
        <div class="center">
        <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				
               if ($_POST['username'] == 'redditmod' && 
                  $_POST['password'] == 'admin') {
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = 'redditmod';
                  
                  echo "<script>document.getElementById('head').innerHTML = '<!-- removed bc your logged in -->';
                  </script>";

                  // mod for b
                  echo "<table><tr><td class='top'>";
                  echo "<b>Moderate /b/</b>";
                  echo "</td></tr><tr><td>";
                  echo '<form role="form" action="mod.php" method="post" enctype="multipart/form-data">';
                  echo "<textarea name='ideb' style='width: 500px;height: 300px;'>" . file_get_contents('b/database.html') . "</textarea>";
                  echo '<button class="btn btn-lg btn-primary btn-block" type="submit" name="submitb">Update Page</button>';
                  echo '</form>';
                  echo "</td></tr></table>";

                  // mod for overchan
                  echo "<table><tr><td class='top'>";
                  echo "<b>Moderate /overchan/</b>";
                  echo "</td></tr><tr><td>";
                  echo '<form role="form" action="mod.php" method="post" enctype="multipart/form-data">';
                  echo "<textarea name='ideo' style='width: 500px;height: 300px;'>" . file_get_contents('overchan/database.html') . "</textarea>";
                  echo '<button class="btn btn-lg btn-primary btn-block" type="submit" name="submito">Update Page</button>';
                  echo '</form>';
                  echo "</td></tr></table>";


               }else {
                  echo '<b><rt>Wrong username or password</rt></b><br><br>';
               }
            }

            if(isset($_POST['submitb'])) {
                file_put_contents('b/database.html',$_POST['ideb']);
              }

              if(isset($_POST['submito'])) {
                file_put_contents('overchan/database.html',$_POST['ideo']);
              }
         ?>
        </div>
        </div>
        <div id="footer"></div>
        <div id="pageparam"><?php
            if (isset($_get['token'])) {
            echo $_GET['token'];
            }
        ?></div>
        
    </body>
</html>