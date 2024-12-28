<?php
$path = '/user_uploads/';
$dir = __DIR__ . '/../' . $path;
$images = glob("$dir*_thumb.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);

foreach ($images as $i) {
    $path_thumb = $path.rawurlencode(basename($i));
    $path_img = str_replace("_thumb", "", $path_thumb);
    echo "<a href='$path_img'><img class='gallery-image' src='$path_thumb' alt='image'></a>";
}
?>