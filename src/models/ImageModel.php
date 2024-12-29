<?php
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);

const destination = __DIR__ . '/../web/images/';
const watermark_path = __DIR__ . '/../web/img/pg_logo.jpg';
const MAX_FILE_SIZE = 1*MB; // Maximum allowed file size in bytes

//require_once  mongo model
require_once __DIR__ . '/../core/Database.php';

class ImageModel {
    private $basePath;
    private $publicPath;

    public function __construct() {
        $this->basePath = destination; // Path to the folder storing images
        $this->publicPath = '/images/';              // Public-accessible path for images
    }

    function upload($file, $title, $author, $watermark_text)
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

        $file_original_destination = destination . $new_file_name . '.'.$file_ext;
        $file_watermarked_destination = destination . $new_file_name . '_watermark.'.$file_ext;
        $file_thumb_destination = destination . $new_file_name . '_thumb.' . $file_ext;

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

        $user_id = "uid";

        //przeniesc do core/db
        (new Database)->add_image_to_db(
            $_POST['image_author'],
            $_POST['image_title'],
            $new_file_name,
            $user_id
        );
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


    private function addWatermark($source, $destination, $watermark_path, $watermark_text) {
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
        $old_image = imagecreatefromjpeg($source);
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
        $images = glob("{$this->basePath}*_thumb.{jpg,jpeg,gif,png,bmp,webp}", GLOB_BRACE);

        // Total Images and Pagination Info
        $totalImages = count($images);
        $totalPages = (int) ceil($totalImages / $perPage);
        $currentPage = max(1, min($page, $totalPages));  // Ensure valid page number
        $currentPageImages = array_slice($images, ($currentPage - 1) * $perPage, $perPage);

        // Prepare structured data for returning images metadata
        $imageData = [];
        foreach ($currentPageImages as $imagePath) {
            $thumbnailPath = $this->publicPath . rawurlencode(basename($imagePath)); // Public path for the thumbnail
            $imagePathFull = str_replace("thumb", "watermark", $thumbnailPath);      // Full-size (watermarked) image

            // Extract Image ID (everything before the underscore)
            $imageId = explode("_", rawurlencode(basename($imagePath)))[0];
            // Retrieve metadata from the database for the image ID
            $imageDb = (new Database())->get_image_by_name($imageId); // Assuming this function fetches the metadata (title, author)
            $imageData[] = [
                'id' => $imageId,
                'thumbnail' => $thumbnailPath,
                'full_image' => $imagePathFull,
                'title' => !empty($imageDb['title']) ? $imageDb['title'] : 'Unknown',
                'author' => !empty($imageDb['author']) ? $imageDb['author'] : 'Unknown',
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
