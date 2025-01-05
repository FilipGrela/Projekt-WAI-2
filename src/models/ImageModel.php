<?php
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);

const watermark_path = __DIR__ . '/../web/img/pg_logo.jpg';
const MAX_FILE_SIZE = 1*MB; // Maximum allowed file size in bytes

require_once __DIR__ . '/../core/Database.php';

class ImageModel {
    private $basePath;
    private $guestBasePath;
    private $publicPath;
    private $database;


    public function __construct() {
        $this->guestBasePath = __DIR__ . '/../web/images/guest/';
        $this->basePath = __DIR__ . '/../web/images/' . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest') . '/'; // Public-accessible path for images
        $this->publicPath = '/images/' . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest') . '/';
        $this->database = new Database();
    }

    function upload($file, $watermark_text, $private)
    {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'png'];

        if (!$this->isAllowedExtension($file_ext, $allowed_ext)) {
            return 'File extension not allowed';
        }

        if (!$this->isValidFileSize($file_size)) {
            return 'File size exceeds the limit, max file size is 1MB';
        }

        if (!$this->isValidFileError($file_error)) {
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

            $msg = "";
            if ($file_error === 1){
                $msg = 'File size exceeds the limit, max file size is 1MB';
            }else{
                $msg = $phpFileUploadErrors[$file_error];
            }
            return $msg;
        }

        $new_file_name = $this->generateUniqueFileName();

        $basePath = $this->basePath;
        if ($private != 1) {
            $basePath = $this->guestBasePath;
        }

        $file_original_destination = $basePath . $new_file_name . '.'.$file_ext;
        $file_watermarked_destination = $basePath . $new_file_name . '_watermark.'.$file_ext;
        $file_thumb_destination = $basePath . $new_file_name . '_thumb.' . $file_ext;


        try {
            $this->ensureFolderExists($basePath);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if (!move_uploaded_file($file_tmp, $file_original_destination)) {
            return 'File upload failed, move_uploaded_file error';
        }

        $this->createThumbnail(
            $file_original_destination,
            $file_thumb_destination,
            200,
            125
        );
        $this->addWatermark(
            $file_original_destination,
            $file_watermarked_destination,
            watermark_path,
            $watermark_text
        );

        $user_id = (isset($_SESSION['user_id']) and $private == 1) ? $_SESSION['user_id'] : 'guest';

        $this->database->add_image_to_db(
            $_POST['image_author'],
            $_POST['image_title'],
            $private,
            $new_file_name,
            $user_id
        );
    }


    private function ensureFolderExists($folderPath)
    {
        if (!is_dir($folderPath)) {
            if (!mkdir($folderPath, 0755, true)) {
                throw new Exception("Failed to create folder: " . $folderPath);
            }
        }
    }

    private function isAllowedExtension($extension, $allowed_extensions) {
        return in_array($extension, $allowed_extensions);
    }

    private function isValidFileSize($size) {
        return $size <= MAX_FILE_SIZE;
    }

    private function isValidFileError($error) {
        return $error === 0;
    }


    private function generateUniqueFileName() {
        return uniqid('', true);
    }


    private function addWatermark($source, $destination, $watermark_path, $watermark_text = '') {
        if (!file_exists($source) or !file_exists($watermark_path)) {
            throw new Exception('Source file does not exist: ' . $source);
        }
        list($w_width, $w_height) = getimagesize($watermark_path);

        $extension = pathinfo($source, PATHINFO_EXTENSION);
        if ($extension === 'jpg' or $extension === 'jpeg'){
            $source = imagecreatefromjpeg($source);
        }else if ($extension === 'png'){
            $source = imagecreatefrompng($source);
        }else{
            throw new Exception('Extension not allowed: ' . $extension);
        }
        $watermark_path = imagecreatefromjpeg($watermark_path);

        $watermark_scale = 2;
        $w_width = $w_width * $watermark_scale;
        $w_height = $w_height * $watermark_scale;

        $watermark_path = imagescale($watermark_path, $w_width, $w_height);

        $white = imagecolorallocate($watermark_path, 255, 255, 255);
        imagecolortransparent($watermark_path, $white);


        imagecopymerge($source, $watermark_path, 100, 100, 0, 0, $w_width, $w_height, 50);

        $font_path = __DIR__ . '/../web/fonts/Freedom-10eM.ttf';
        $black = imagecolorallocatealpha($watermark_path, 0, 0, 0, 50);
        imagettftext($source, 50, 0, 200, 550, $black, $font_path, $watermark_text);


        imagejpeg($source, $destination);
    }

    private function createThumbnail($source, $destination, $width, $height) {
        if (!file_exists($source)) {
            throw new Exception('Source file does not exist: ' . $source);
        }

        list($old_width, $old_height) = getimagesize($source);
        $extension = pathinfo($source, PATHINFO_EXTENSION);
        if ($extension === 'jpg' or $extension === 'jpeg'){
            $old_image = imagecreatefromjpeg($source);
        }else if ($extension === 'png'){
            $old_image = imagecreatefrompng($source);
        }else{
            throw new Exception('Extension not allowed: ' . $extension);
        }

        if ($old_image === false) {
            throw new Exception('Failed to load image from source: ' . $source);
        }
        $new_image = imagecreatetruecolor($width, $height);

        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $width, $height, $old_width, $old_height);

        imagejpeg($new_image, $destination);
    }

    /**
     * Fetch paginated images, metadata, and pagination links.
     *
     * @param int $page Current page number.
     * @param int $perPage Number of images per page.
     * @return array Returns an array with paginated images and pagination details.
     */
    public function getPaginatedImages($page = 1, $perPage = 8) {

        // Fetch thumbnails only
        $images = isset($_GET['fav']) && $_GET['fav'] ? $this->getFavouriteImagesThumb() : $this->getUserImagesThumb();
        // Total Images and Pagination Info
        $totalImages = count($images);
        $totalPages = (int) ceil($totalImages / $perPage);
        $currentPage = max(1, min($page, $totalPages));  // Ensure valid page number
        $currentPageImages = array_slice($images, ($currentPage - 1) * $perPage, $perPage);


        // Prepare structured data for returning images metadata
        $imageData = [];
        foreach ($currentPageImages as $imagePath) {
            $imagePath = explode('/images/', $imagePath)[1];
            $thumbnailPath = '/images/'.$imagePath; // Public path for the thumbnail
            $imagePathFull = str_replace("thumb", "watermark", $thumbnailPath);

            // Extract Image ID (everything before the underscore)
            $imageId = explode("_", rawurlencode(basename($imagePath)))[0];
            // Retrieve metadata from the database for the image ID
            $imageDb = $this->database->get_image_by_name($imageId);
            $imageData[] = [
                'id' => $imageId,
                'thumbnail' => $thumbnailPath,
                'full_image' => $imagePathFull,
                'title' => !empty($imageDb['title']) ? $imageDb['title'] : 'Unknown',
                'author' => !empty($imageDb['author']) ? $imageDb['author'] : 'Unknown',
                'private' => isset($imageDb['private']) ? $imageDb['private'] : 0
            ];
        }

        // Return the result
        return [
            'images' => $imageData,
            'pagination' => [
                'totalImages' => $totalImages,
                'totalPages' => $totalPages,
                'currentPage' => $currentPage,
                'perPage' => $perPage,
            ],
        ];
    }

    function getFavouriteImagesThumb(){
        $images = $this->getUserImagesThumb();
        $favourite_images = [];

        if (!isset($_SESSION['favourite_images'])) {
            return [];
        }

        foreach ($images as $image) {
            $image_id = explode("_", rawurlencode(basename($image)));
            if(in_array($image_id[0], $_SESSION['favourite_images'])){
                $favourite_images[] = $image;
            }
        }
        return $favourite_images;
    }

    function addFavouriteImages($selectedImages){
        if (!isset($_SESSION['favourite_images'])) {
            $_SESSION['favourite_images'] = [];
        }
        $_SESSION['favourite_images'] = array_merge($_SESSION['favourite_images'], $selectedImages);
    }


    function removeFavouriteImages($selectedImages)
    {
        if (!empty($_SESSION['favourite_images'])) {
            $_SESSION['favourite_images'] = array_diff($_SESSION['favourite_images'], $selectedImages);
        }
    }

    function getUserImagesThumb()
    {
        $thumbs = glob("{$this->basePath}*_thumb.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);
        
        if (isset($_SESSION['user_id'])) {
            $guestThumbs = glob("{$this->guestBasePath}*_thumb.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);
            $thumbs = array_merge($thumbs, $guestThumbs);
        }
        
        return $thumbs;
    }

    function getpaginationLinks($page = 1, $perPage = 8, $totalPages){
        $paginationLinks = '';
        if ($perPage > 1) {
            // Start building pagination HTML
            $paginationLinks .= '';

            // Previous Button
            if ($page > 1) {
                $prevPage = $page - 1;
                $paginationLinks .= "<a class='button' href='?page=$prevPage'>Previous</a> ";
            }

            // Numbered Page Links
            for ($p = 1; $p <= $totalPages; $p++) {
                $class = ($p === $page) ? 'active' : '';
                $paginationLinks .= "<a class='$class; button' href='?page=$p'>$p</a> ";
            }

            // Next Button
            if ($page < $totalPages) {
                $nextPage = $page + 1;
                $paginationLinks .= "<a class='button' href='?page=$nextPage'>Next</a> ";
            }

            // Close the pagination container
            $paginationLinks .= '';
        }
        return $paginationLinks;
    }
}
