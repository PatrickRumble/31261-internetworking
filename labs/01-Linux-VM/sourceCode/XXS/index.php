<!DOCTYPE html>
<html>
<head>
    <title>Internetworking Project Exercise 2 - Cross-Site Scripting (XSS)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Internetworking Project Exercise 2</h1>
        <h2>Cross-Site Scripting (XSS)</h2>
        <img src="images/UTS.png" alt="UTS Logo" class="uts-logo">
    </header>

    <main>
        <div class="upload-box">
            <form action="comment.php" method="get">
                <label for="comment">Enter your comment:</label><br>
                <input type="text" name="comment" id="comment" placeholder="Write something..."><br><br>
                <input type="submit" value="Post Comment" name="submit">
            </form>
        </div>

        <div class="upload-box" style="margin-top: 30px;">
            <h3>Recent Comment:</h3>
            <?php
            if (isset($_GET['comment'])) {
                echo "<p>" . $_GET['comment'] . "</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>
