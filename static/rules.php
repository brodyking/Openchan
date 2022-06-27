<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="keywords" content="Photos, Viewer">
        <meta name="description" content="Photo Gallery">
        <meta name="author" content="Anon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="favicon.ico">
        <title>Openchan Rules</title>
        <script defer src="../userstylesb.js"></script>
        <link rel="stylesheet" href="../styles/base.css">
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

            <h1>Rules</h1> 

            <table>
                <tr>
                    <td class="top">
                        Global Rules
                    </td>
                </tr>
                <tr>
                    <td>
                        <ol style="padding-left: 15px;margin: 0px;">
                            <li>Do not post in or view any boards if you are under 13 years of age.</li>
                            <li>No <rt>NSFW</rt> content anywhere on this site.</li>
                            <li>If you break the rules we will delete your post. Do it repeatedly and you'll be banned.</li>
                            <li>Always argue in good faith and avoid using personal attacks.</li>
                            <li>Do not spam or advertise unrelated content.</li>
                            <li>Do not post child pornography or questionable 2D/3DCG/3DPG sexual depictions of children. This includes "child models".</li>
                            <li>Do not upload malicious archives.</li>
                        </ol>
                    </td>
                </tr>
            </table>


        </div>

        <div id="content">
        </div>
        <div id="footer"></div>
        <div id="pageparam"></div>
        
    </body>
</html>