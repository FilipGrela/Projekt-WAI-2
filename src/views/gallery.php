<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria</title>
    <link rel="icon" href="img/travel.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section>
    <div id="gallery" class="round-corners">
        <form action="/login/logout" method="post" class="search-form">
            <button type="submit" class="button">Logout</button>
        </form>
        <form action="/gallery/upload" method="post" enctype="multipart/form-data" class="upload-form">
            <h2>Upload Your Image</h2>

            <label for="image_title" class="form-label">Title:</label>
            <input type="text" name="image_title" id="image_title" class="form-field" >

            <label for="image_author" class="form-label">Author:</label>
            <input type="text" name="image_author" id="image_author" class="form-field" >
            <label for="watermark_text" class="form-label">Watermark:</label>
            <input type="text" name="watermark_text" id="watermark_text" class="form-field" >
            <label for="file" class="form-label">Choose File:</label>
            <input type="file" name="file" id="file" class="form-field" >
            <button type="submit" class="button">Upload Image</button>
            <?php if (isset($_SESSION['image_upload_message']) && $_SESSION['image_upload_message']): ?>
                <br><p class="error_message"><?= $_SESSION['image_upload_message']; ?></p>
            <?php endif; ?>
        </form>
    </div>
    <h1 style="text-shadow: rgba(0, 0, 0, 0.5) 1px 0 10px;">Galeria</h1>
    <div class="gallery">
        <?php if (!empty($images)) : ?>
            <?php foreach ($images['images'] as $image) : ?>
                <div class='gallery-image'>
                    <a href='<?= $image['full_image']; ?>'>
                        <img class='gallery-image' src='<?= $image['thumbnail']; ?>' alt='image'>
                    </a>
                    <div class='gallery-image-details'>
                        <h3><?= $image['title']; ?></h3>
                        <p><?= $image['author']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No images found.</p>
        <?php endif; ?>
    </div>
    <div class="pagination">
        <?php echo isset($paginationLinks) ? $paginationLinks : ''; ?>
    </div>
</section>
</body>
</html>