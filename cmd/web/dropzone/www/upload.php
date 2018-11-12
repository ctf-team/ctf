<?php
$mimetype = mime_content_type($_FILES['file']['tmp_name']);
if(in_array($mimetype, array('image/jpeg', 'image/gif', 'image/png'))) {
    move_uploaded_file($_FILES['file']['tmp_name'], dirname(__FILE__).'/uploads/' . $_FILES['file']['name']);
    echo "Uploaded successfully!";
} else {
    http_response_code(403);
    echo "Please upload a real image";
}