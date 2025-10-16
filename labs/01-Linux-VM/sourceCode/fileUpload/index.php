<!DOCTYPE html>
<html>
<head>
    <title>Internetworking Project Exercise 1 - File Upload Attacks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Internetworking Project Exercise 1</h1>
        <h2>File Upload Attacks</h2>
        <img src="images/UTS.png" alt="UTS Logo" class="uts-logo">

    </header>

    <main>
        <div class="upload-box">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="uploadedFile">Select image to upload:</label><br>
                <input type="file" name="uploadedFile" id="uploadedFile"><br><br>
                <input type="submit" value="Upload Image" name="submit">
            </form>
            <p class="note">Note: Only images are allowed (jpg, png, gif)</p>
        </div>
    </main>
</body>
</html>
