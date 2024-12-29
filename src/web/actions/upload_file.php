<?php
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);

require_once __DIR__."/actions.php";

const destination = __DIR__ . '/../user_uploads/';
const watermark_path = __DIR__ . '/../img/pg_logo.jpg';
const MAX_FILE_SIZE = 1*MB; // Maximum allowed file size in bytes
const redirect_url = '/../home.php';

// Use output buffering to prevent warnings if any output is accidentally generated
ob_start();
saveFile();
function saveFile() {

    if (!isset($_FILES['file'])) {return;}
    $file = $_FILES['file'];

    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'png'];

    if (!isAllowedExtension($file_ext, $allowed_ext)) {
        showMessageAndRedirect('File extension not allowed');
        return;
    }

    if (!isValidFileSize($file_size)) {
        showMessageAndRedirect('File size exceeds the limit, max file size is 1MB');
        return;
    }

    if (!isValidFileError($file_error)) {
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        if ($file_error === 1){
            showMessageAndRedirect('File size exceeds the limit, max file size is 1MB');
        }else{
            showMessageAndRedirect($phpFileUploadErrors[$file_error]);
        }
        return;
    }

    $new_file_name = generateUniqueFileName();

    $file_original_destination = destination . $new_file_name . '.'.$file_ext;
    $file_watermarked_destination = destination . $new_file_name . '_watermark.'.$file_ext;
    $file_thumb_destination = destination . $new_file_name . '_thumb.' . $file_ext;

    if (!move_uploaded_file($file_tmp, $file_original_destination)) {
        showMessageAndRedirect('File upload failed, move_uploaded_file error');
        return;
    }

    createThumbnail(
        $file_original_destination,
        $file_thumb_destination,
        200,
        125
    );
    addWatermark(
        $file_original_destination,
        $file_watermarked_destination,
        watermark_path
    );

    $user_id = "uid";
    add_image_to_db(
        $_POST['image_author'],
        $_POST['image_title'],
        $new_file_name,
        $user_id
    );
}

// Functions
function isAllowedExtension($extension, $allowed_extensions) {
    return in_array($extension, $allowed_extensions);
}

function isValidFileSize($size) {
    return $size <= MAX_FILE_SIZE;
}

function isValidFileError($error) {
    return $error === 0;
}

function generateUniqueFileName() {
    return uniqid('', true);
}

// Unified helper to handle errors and redirection
function showMessageAndRedirect($message) {
    echo
    "<div class='error_container'>
            <div class='error_message'><p>".
    $message
    ."</p></div></div>";
}

function redirect($url) {
    clearOutputBuffer();
    header('Location: ' . $url);
    exit(); // Ensure the script halts after redirect
}

function clearOutputBuffer() {
    if (ob_get_length()) {
        ob_end_clean(); // Clear the buffer to prevent 'headers already sent' errors
    }
}

function addWatermark($source, $destination, $watermark_path) {
    if (!file_exists($source) or !file_exists($watermark_path)) {
        throw new Exception('Source file does not exist: ' . $source);
    }
    list($w_width, $w_height) = getimagesize($watermark_path);

    $source = imagecreatefromjpeg($source);
    $watermark_path = imagecreatefromjpeg($watermark_path);

    $watermark_scale = 2;
    $w_width = $w_width * $watermark_scale;
    $w_height = $w_height * $watermark_scale;

    $watermark_path = imagescale($watermark_path, $w_width, $w_height);

    $white = imagecolorallocate($watermark_path, 255, 255, 255);
    imagecolortransparent($watermark_path, $white);


    imagecopymerge($source, $watermark_path, 100, 100, 0, 0, $w_width, $w_height, 50);
    imagejpeg($source, $destination);
}

function createThumbnail($source, $destination, $width, $height) {
    if (!file_exists($source)) {
        throw new Exception('Source file does not exist: ' . $source);
    }

    list($old_width, $old_height) = getimagesize($source);
    $old_image = imagecreatefromjpeg($source);
    if ($old_image === false) {
        throw new Exception('Failed to load image from source: ' . $source);
    }
    $new_image = imagecreatetruecolor($width, $height);

    imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $width, $height, $old_width, $old_height);

    imagejpeg($new_image, $destination);
}