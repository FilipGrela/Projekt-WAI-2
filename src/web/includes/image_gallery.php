<?php
$path = '/user_uploads/';
$dir = __DIR__ . '/../' . $path;
$images = glob("$dir*.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);

foreach ($images as $i) {
    echo "<img alt='' src='$path" . rawurlencode(basename($i)) . "' class='gallery-image'>";
}
?>