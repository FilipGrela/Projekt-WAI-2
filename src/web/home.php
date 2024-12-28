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
        <form action="" method="post" enctype="multipart/form-data" class="upload-form">
            <h2>Upload Your Image</h2>
            <label for="file" class="form-label">Choose File:</label>
            <input type="file" name="file" id="file" class="form-field">
            <button type="submit" name="SubmitButton" class="button">Upload Image</button>
        </form>
        <?php
        if(isset($_POST['SubmitButton'])){
            //check if form was submitted
            require'actions/upload_file.php';
        }
        ?>
        <h1>Galeria</h1>
        <div class="gallery">
            <?php require_once 'includes/image_gallery.php'; ?>
        </div>
        <div>
            <?php echo isset($paginationLinks) ? $paginationLinks : ''; ?>
        </div>
    </div>
</section>
</body>
</html>