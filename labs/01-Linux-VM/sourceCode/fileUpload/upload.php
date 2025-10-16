<?php
function checkMimeType($filePath) { 
    $type = mime_content_type($filePath);
    return ($type != "image/gif" && $type != "image/png" && $type != "image/jpeg");
}

function CheckFileNameValidExtentino($fileName) { 
    return (!preg_match('/jpg|jpeg|png|gif/i', $fileName));
}  

$uploadDirectory = "./Uploads/";
$fileName = $_FILES["uploadedFile"]["name"];
$destination = $uploadDirectory . $fileName;


if (CheckFileNameValidExtentino($fileName)) { 
    echo "Only images are allowed to be uploaded (weak regex)";
    die();
}


if (checkMimeType($_FILES["uploadedFile"]["tmp_name"])) { 
    echo "Invalid Mimetype";
    die(); 
}


if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $destination)) {
    echo "File uploaded successfully to " . $destination;
} else {
    echo "Upload failed.";
}
?>
