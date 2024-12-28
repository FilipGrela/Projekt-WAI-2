<?php
$destination = __DIR__ . '/../user_uploads/';
const MAX_FILE_SIZE = 20000000; // Maximum allowed file size in bytes
$redirect_url = '/../home.php';

// Use output buffering to prevent warnings if any output is accidentally generated
ob_start();

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];

    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'png'];

    if (!isAllowedExtension($file_ext, $allowed_ext)) {
        showErrorAndRedirect('File extension not allowed', $redirect_url);
    }

    if (!isValidFileSize($file_size)) {
        showErrorAndRedirect('File size exceeds the limit', $redirect_url);
    }

    if (!isValidFileError($file_error)) {
        showErrorAndRedirect('An error occurred during file upload', $redirect_url);
    }

    $new_file_name = generateUniqueFileName();
    $file_destination = $destination . $new_file_name . '.'.$file_ext;
    $file_thumb_destination = $destination . $new_file_name . '_thumb.' . $file_ext;

    if (!move_uploaded_file($file_tmp, $file_destination)) {
        showErrorAndRedirect('File upload failed', $redirect_url);
    }

    createThumbnail(
        $file_destination,
        $file_thumb_destination,
        200,
        125
    );
    showMessageAndRedirect('File uploaded successfully', $redirect_url);
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
function showErrorAndRedirect($message, $url) {
    clearOutputBuffer();
    echo $message;
    redirect($url);
}

function showMessageAndRedirect($message, $url) {
    clearOutputBuffer();
    echo $message;
    redirect($url);
}

function redirect($url) {
    clearOutputBuffer();
//    header('Location: ' . $url);
//    exit(); // Ensure the script halts after redirect
}

function clearOutputBuffer() {
    if (ob_get_length()) {
        ob_end_clean(); // Clear the buffer to prevent 'headers already sent' errors
    }
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