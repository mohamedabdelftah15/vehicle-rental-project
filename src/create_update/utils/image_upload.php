<?php
$target_file = "../images/" . $_FILES["vehicle_image"]["name"];
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["vehicle_image"]["tmp_name"]);
if($check !== false) {
    $uploadOk = 1;
} else {
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" and $imageFileType != "png" and $imageFileType != "jpeg" and $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["vehicle_image"]["tmp_name"], $target_file)) {
        echo "The file ". $_FILES["vehicle_image"]["name"] . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>