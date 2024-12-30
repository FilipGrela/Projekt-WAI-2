<?php
include_once __DIR__ . '/../models/ImageModel.php';
class GalleryController
{
    public function index() {
        $imageModel = new ImageModel();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $images = $imageModel->getPaginatedImages($currentPage, 8);
        $paginationLinks = $imageModel->getpaginationLinks(
            $images['pagination']['currentPage'],
            $images['pagination']['perPage'],
            $images['pagination']['totalPages']
        );
        $image_upload_message = '';
        // Pass images to the view
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        require_once __DIR__ . '/../views/gallery.php';
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
                $_SESSION['image_upload_message'] = $imageModel->upload($_FILES['file'], $title, $author, $watermark_text);

                // Redirect to the gallery page after successful upload
                header("Location: /gallery");
                exit;
            } catch (Exception $e) {
                // Handle errors here (e.g., file upload failed)
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        } else {
            // If not a POST request or no file provided, return error
            echo "<p>Error: Invalid request or no file uploaded.</p>";
        }
    }
}