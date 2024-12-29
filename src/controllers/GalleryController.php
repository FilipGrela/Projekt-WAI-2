<?php
include_once __DIR__ . '/../models/ImageModel.php';
class GalleryController
{
    public function index() {
        $imageModel = new ImageModel();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $images = $imageModel->getPaginatedImages($currentPage, 2);
        $paginationLinks = $imageModel->getpaginationLinks(
            $images['pagination']['currentPage'],
            $images['pagination']['perPage'],
            $images['pagination']['totalPages']
        );
        // Pass images to the view
        require_once __DIR__ . '/../views/gallery.php';
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'])) {
            $title = htmlspecialchars($_POST['image_title'] ?? '');
            $author = htmlspecialchars($_POST['image_author'] ?? '');
            $imageModel = new ImageModel();
//
            try {
                // Pass file details and metadata to the model
                $message = $imageModel->upload($_FILES['file'], $title, $author);

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