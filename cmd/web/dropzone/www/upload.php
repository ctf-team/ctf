<?php
$mimetype = $_FILES['file']['type'];
$info = pathinfo($_FILES['file']['name']);
if($info['extension'] == "php") {
    http_response_code(403);
    exit("Sorry, files with the .php extension are not allowed.");
}

if(in_array($mimetype, array('image/jpeg', 'image/gif', 'image/png'))) {
    move_uploaded_file($_FILES['file']['tmp_name'], dirname(__FILE__).'/uploads/' . $_FILES['file']['name']);
    echo "Uploaded successfully!";
} else {
    http_response_code(403);
    echo "Sorry, the mime type '".$mimetype."' is not supported.";
}