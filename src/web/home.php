<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Gallery</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section>
    <div id="gallery" class="round-corners">
        <form action="actions/upload_file.php" method="post" enctype="multipart/form-data" class="upload-form">
            <h2>Upload Your Image</h2>
            <label for="file" class="form-label">Choose File:</label>
            <input type="file" name="file" id="file" class="form-field">
            <button type="submit" class="button">Upload Image</button>
        </form>
        <h1>Galeria</h1>
        <div class="gallery">
            <?php include 'includes/image_gallery.php'; ?>
        </div>
    </div>
</section>
</body>
</html>