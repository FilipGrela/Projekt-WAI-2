<?php
require_once __DIR__ . '/../models/ImageModel.php';
class GalleryController
{
    public function __construct()
    {
        $imageModel = new ImageModel();
        if (!isset($_SESSION['user_id'])) {
            (new Router)->redirect('/login');
        }
    }
    public function index() {

        $imageModel = $this->imageModel ?? new ImageModel();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $images = $imageModel->getPaginatedImages($currentPage, 8);
        $paginationLinks = $imageModel->getpaginationLinks(
            $images['pagination']['currentPage'],
            $images['pagination']['perPage'],
            $images['pagination']['totalPages']
        );
        $image_upload_message = '';

        if (!isset($_SESSION['user_id'])) {
            (new Router)->redirect("Location: /login");
            exit;
        }
        include_once __DIR__ . '/../views/gallery.php';
    }

    public function upload() {
        unset($_SESSION['image_upload_message']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'])) {
            $title = htmlspecialchars($_POST['image_title'] ?? '');
            $author = htmlspecialchars($_POST['image_author'] ?? '');
            $watermark_text = htmlspecialchars($_POST['watermark_text'] ?? '');
            $imageModel = new ImageModel();

            try {
                // Pass file details and metadata to the model
                $_SESSION['image_upload_message'] = $imageModel->upload($_FILES['file'], $watermark_text);

                // Redirect to the gallery page after successful upload
                header("Location: /gallery");
                exit;
            } catch (Exception $e) {

                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        } else {

            echo "<p>Error: Invalid request or no file uploaded.</p>";
        }
    }

    public function update_favourite_images(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $imageModel = $this->imageModel ?? new ImageModel();
            if (isset($_POST['images']) && is_array($_POST['images'])) {

                $selectedImages = $_POST['images'];
            } else {
                echo 'Nie wybrano żadnych zdjęć.';
                return;
            }


            if (isset($_GET['fav']) && $_GET['fav'] == 'true') {
                $imageModel->removeFavouriteImages($selectedImages);
            }elseif (!isset($_GET['fav']) || (isset($_GET['fav']) && $_GET['fav'] == 'true')){
                $imageModel->addFavouriteImages($selectedImages);
            }
        }

        (new Router)->redirect('/gallery', '#gallery');
    }

}