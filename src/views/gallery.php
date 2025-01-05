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
    <div  class="round-corners gallery-container">
        <div class="header">
            <form action="/login/logout" method="post" class="search-form">
                <button type="submit" class="button">
                    <?php if (isset($_SESSION['login'])) : ?>Wyloguj <?php else: ?>Zaloguj<?php endif; ?>
                </button>
            </form>
            
            <div class="user-info">
                <p>Zalogowano jako:</p>
                <?php if (isset($_SESSION['login']) && $_SESSION['login']) : ?>
                    <p><b><?= htmlspecialchars($_SESSION['login']); ?></b></p>
                <?php else: ?>
                    <p><b>Gość</b></p>
                <?php endif; ?>
            </div>
        </div>
            
        <form action="/gallery/upload" method="post" enctype="multipart/form-data" class="upload-form">
            <h2>Upload Your Image</h2>

            <label for="image_title" class="form-label">Title:</label>
            <input type="text" name="image_title" id="image_title" class="form-field">

            <label for="image_author" class="form-label">Author:</label>
            <input type="text" name="image_author" id="image_author" class="form-field"
                   value="<?php if (isset($_SESSION['login']) && $_SESSION['login']) : ?><?= htmlspecialchars($_SESSION['login']); ?><?php endif; ?>">

            <label for="watermark_text" class="form-label">Watermark:</label>
            <input type="text" name="watermark_text" id="watermark_text" class="form-field" required>

            <label for="file" class="form-label">Choose File:</label>
            <input type="file" name="file" id="file" class="form-field" required>

            <?php if (isset($_SESSION['login'])): ?>
                <label for="private" class="form-label">Private:</label>
                <input type="checkbox" name="private" id="private" value="1">
                <br>
            <?php endif; ?>


            <button type="submit" class="button">Upload Image</button>
            <?php if (isset($_SESSION['image_upload_message']) && $_SESSION['image_upload_message']): ?>
                <br><p class="error_message"><?= $_SESSION['image_upload_message']; ?></p>
            <?php endif; ?>
        </form>
    </div>
    <h1 style="text-shadow: rgba(0, 0, 0, 0.5) 1px 0 10px;"><?= isset($_GET['fav']) ? 'Polubione' : 'Galeria'; ?></h1>

    <a href="<?= isset($_GET['fav']) ? '/gallery#gallery' : '?fav=true#gallery'; ?>"
       class="button"><?= isset($_GET['fav']) ? 'Galeria' : 'Polubione'; ?></a>
    <div id="gallery" class="gallery">
        <form id="fav-photos" action="/gallery/add_image_to_favorites<?= isset($_GET['fav']) ? '?fav='.$_GET['fav'] : ''; ?>" method="post">
        <?php if (!empty($images)) : ?>
            <?php foreach ($images['images'] as $image) : ?>
                <div class="gallery-image">
                    <a href='<?= $image['full_image']; ?>'>
                        <img class='gallery-image' src='<?= $image['thumbnail']; ?>' alt='image'>
                    </a>
                    <label for="checkbox-<?= $image['id']; ?>"></label>

                    <?php if (!isset($_GET['fav'])): ?>
                    <input type="checkbox" class="image-checkbox" name="images[]" value="<?= $image['id']; ?>" id="checkbox-<?= $image['id']; ?>"
                        <?= isset($_SESSION['favourite_images']) && in_array($image['id'], $_SESSION['favourite_images']) ? 'checked' : ''; ?>>
                    <?php else : ?>
                        <input type="checkbox" class="image-checkbox" name="images[]" value="<?= $image['id']; ?>" id="checkbox-<?= $image['id']; ?>">
                    <?php endif; ?>

                    <div class='gallery-image-details'>
                        <h3><?= $image['title']; ?></h3>
                        <p><?= $image['author']; ?></p>

                        <?php if (isset($_SESSION['login']) && $_SESSION['login']) : ?>
                            <p><?= $image['private'] == 1 ? 'Zdjęcie prywatne' : 'Zdjęcie publiczne'; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </form>
        <?php else : ?>
            <p>No images found.</p>
        <?php endif; ?>
    </div>
    
    <hr style="margin-left: 10%; width: 80%;"/>

    <div class="favourites">
    <?php if (!isset($_GET['fav'])): ?>
        <p>Dodaj zdjęcia do polubionych</p>
        <button form="fav-photos" type="submit" class="button">Dodaj</button>
    <?php else : ?>
        <p>Usuń zdjęcia z polubionych</p>
        <button form="fav-photos" type="submit" class="button">Usuń</button>
    <?php endif; ?>
    </div>

    <div class="pagination">
        <?php echo isset($paginationLinks) ? $paginationLinks : ''; ?>
    </div>
</section>
</body>
</html>