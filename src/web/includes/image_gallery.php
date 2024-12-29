<?php
include_once __DIR__ . '/../actions/actions.php';
$path = '/user_uploads/';
$dir = __DIR__ . '/../' . $path;
$images = glob("$dir*_thumb.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 8;
$totalImages = count($images);
$totalPages = (int)ceil($totalImages / $perPage);
$currentPageImages = array_slice($images, ($page - 1) * $perPage, $perPage);

foreach ($currentPageImages as $i) {
    $path_thumb = $path . rawurlencode(basename($i));
    $path_img = str_replace("thumb", "watermark", $path_thumb);


    $image_id = explode("_", rawurlencode(basename($i)))[0];
    $image_db = get_image_by_name($image_id);

    $title = !empty($image_db['title']) ? $image_db['title'] : 'Unknown';
    $author = !empty($image_db['author']) ? $image_db['author'] : 'Unknown';

    echo "<div class='gallery-image'>
        <a href='$path_img'>
            <img class='gallery-image' src='$path_thumb' alt='image'>
        </a>
        <div class='gallery-image-details'>
            <h3>$title</h3>
            <p>$author</p>
        </div>
    </div>";
}

if (!$images) {
    echo "No images found";
    exit;
}

$paginationLinks = '';
if ($totalPages > 1) {
    // Start building pagination HTML
    $paginationLinks .= '<div class="pagination">';

    // Previous Button
    if ($page > 1) {
        $prevPage = $page - 1;
        $paginationLinks .= "<a class='prev' href='?page=$prevPage'>Previous</a> ";
    }

    // Numbered Page Links
    for ($p = 1; $p <= $totalPages; $p++) {
        $class = ($p === $page) ? 'active' : '';
        $paginationLinks .= "<a class='$class' href='?page=$p'>$p</a> ";
    }

    // Next Button
    if ($page < $totalPages) {
        $nextPage = $page + 1;
        $paginationLinks .= "<a class='next' href='?page=$nextPage'>Next</a> ";
    }

    // Close the pagination container
    $paginationLinks .= '</div>';
}

// Pass the $paginationLinks variable back for use in home.php
$GLOBALS['paginationLinks'] = $paginationLinks;
?>